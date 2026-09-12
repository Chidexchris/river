<?php

namespace App\Services;

use App\Constants\ManageStatus;
use App\Interfaces\HasInstallments;
use App\Lib\OTPManager;
use App\Models\Installment;
use App\Models\Setting;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Random\RandomException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

abstract class BaseService
{
    /**
     * Handles the whole OTP process (generation, sending, and session storage.).
     *
     * @param string $authorizationMode
     * @param User $user
     * @param array $transactionStateInformation
     * @param string $redirectRoute
     * @return void
     * @throws RandomException
     */
    public function processOTP(string $authorizationMode, User $user, array $transactionStateInformation, string $redirectRoute): void
    {
        $sendVia = $authorizationMode == ManageStatus::AUTHORIZATION_MODE_EMAIL ? 'email' : 'sms';

        OTPManager::make()->generateOTP($user->email)->sendOTP($sendVia);

        session()->put('transaction_state_information', [
            ...$transactionStateInformation,
            'send_via'       => $sendVia,
            'redirect_route' => $redirectRoute,
        ]);
    }

    /**
     * Validate the transaction state based on OTP requirements and the presence of a plan.
     *
     * @param Setting $settings
     * @param array $transactionStateInformation
     * @param string $key
     * @param string $context
     * @return void
     * @throws Exception
     */
    public function checkTransactionState(Setting $settings, array $transactionStateInformation, string $key, string $context): void
    {
        if ($settings->email_based_otp || $settings->sms_based_otp) {
            if (!($transactionStateInformation['otp_verified'] ?? false)) {
                throw new Exception("Authorization failed: OTP verification is required to $context.");
            }
        } else {
            if (!($transactionStateInformation[$key] ?? false)) {
                $formattedKey = str_replace('_', ' ', $key);

                throw new Exception("Transaction failed: No $formattedKey found!");
            }
        }
    }

    /**
     * Processes the form field data and prepares it for further use.
     *
     * @param mixed $formData
     * @param array $payload
     * @return array
     * @throws Exception
     */
    public function processFormFieldData(mixed $formData, array $payload): array
    {
        $formPayload = [];

        foreach ($formData as $formField) {
            if (array_key_exists($formField->label, $payload)) {
                if ($formField->type == 'file') {
                    $value = $this->moveFileToAssets($payload[$formField->label]);
                } else {
                    $value = $payload[$formField->label];
                }

                $formPayload[] = [
                    'name'  => $formField->name,
                    'type'  => $formField->type,
                    'value' => $value,
                ];
            }
        }

        return $formPayload;
    }

    /**
     * Move an uploaded file from temporary storage to the asset folder.
     *
     * @param string $tempFilePath
     * @return string
     * @throws Exception
     */
    private function moveFileToAssets(string $tempFilePath): string
    {
        $tempPath  = storage_path("app/$tempFilePath");
        $directory = date('Y/m/d');
        $finalPath = base_path("../assets/verify/$directory/");

        if (!file_exists($finalPath)) mkdir($finalPath, 0755, true);

        // Extract file name from the path
        $fileName    = basename($tempPath);
        $newFilePath = $finalPath . $fileName;

        if (rename($tempPath, $newFilePath)) {
            return str_replace('temp_uploads/', "$directory/", $tempFilePath);
        } else {
            throw new Exception('Failed to move the file!');
        }
    }

    /**
     * Add delay calculation to each installment in the paginator collection.
     *
     * @param LengthAwarePaginator $installments
     * @return LengthAwarePaginator
     */
    public function transformInstallment(LengthAwarePaginator $installments): LengthAwarePaginator
    {
        $installments->getCollection()->transform(function (Installment $installment) {
            if ($installment->given_at) {
                $installmentDate    = $installment->installment_date->startOfDay();
                $givenDate          = $installment->given_at->startOfDay();
                $installment->delay = (int)$installmentDate->diffInDays($givenDate);
            } else {
                $installment->delay = null;
            }

            return $installment;
        });

        return $installments;
    }

    /**
     * Get the next installment date for any model implementing HasInstallments.
     *
     * @param HasInstallments $model
     * @return string|null
     */
    public function getNextInstallmentDate(HasInstallments $model): ?string
    {
        $startDate = $model->getInstallmentStartDate();

        if (!$startDate) return null;

        $nextInstallmentDate = $startDate->copy()->addDays($model->getInstallmentInterval());
        $lastInstallmentDate = $startDate->copy()->addDays($model->getTotalInstallments() * $model->getInstallmentInterval());

        // Keep adding the installment interval until the date is in the future but not beyond the last installment date
        while ($nextInstallmentDate->isPast() && $nextInstallmentDate->lt($lastInstallmentDate)) {
            $nextInstallmentDate->addDays($model->getInstallmentInterval());
        }

        return $nextInstallmentDate->toDateString();
    }

    /**
     * Retrieve and download a file from a payload.
     *
     * @param array $detailsPayload
     * @param string $file
     * @return BinaryFileResponse
     * @throws Exception
     */
    public function downloadFileFromPayload(array $detailsPayload, string $file): BinaryFileResponse
    {
        $fileFound = collect($detailsPayload)
            ->where('type', '=', 'file')
            ->first(function ($item) use ($file) {
                return !is_null($item['value']) && decrypt($item['value']) == $file;
            });

        if (!$fileFound) throw new Exception('File not found!');

        $filePath = getFilePath('verify') . "/$file";

        if (!file_exists($filePath)) throw new Exception('File not found on the server!');

        return response()->download($filePath);
    }

    /**
     * Saves the installments for any model implementing HasInstallments.
     *
     * @param HasInstallments $model
     * @return void
     */
    public function saveInstallments(HasInstallments $model): void
    {
        $installments    = [];
        $installmentDate = $model->getInstallmentDate();

        for ($i = 0; $i < $model->getTotalInstallments(); $i++) {
            $installments[] = [
                'installment_date' => $installmentDate->toDateString(),
            ];

            $installmentDate->addDays($model->getInstallmentInterval());
        }

        $model->installments()->createMany($installments);
    }
}
