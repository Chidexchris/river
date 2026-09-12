<?php

namespace App\Lib;

use App\Models\User;
use App\Traits\Makeable;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Psr\SimpleCache\InvalidArgumentException;
use Random\RandomException;

class OTPManager
{
    use Makeable;

    private int $otpExpiry;
    private mixed $storage;
    private string $otp;
    private User $user;

    /**
     * OTPManager constructor.
     */
    public function __construct()
    {
        $this->otpExpiry = bs('otp_expiry');
        $this->storage   = Cache::store('file');
        $this->user      = auth('web')->user();
    }

    /**
     * Generate an OTP for a specific identifier (e.g., john@example.com).
     *
     * @param string $identifier
     * @return OTPManager
     * @throws RandomException
     */
    public function generateOTP(string $identifier): static
    {
        $this->otp           = $this->generateRandomOTP();
        $key                 = $this->getStorageKey($identifier);
        $expirationTimestamp = time() + $this->otpExpiry;

        Log::info("Generated OTP for the '$identifier'", ['otp' => $this->otp]);

        $this->storage->put($key, ['otp' => $this->otp, 'expires_at' => $expirationTimestamp], $this->otpExpiry);

        return $this;
    }

    /**
     * Send OTP via email or sms
     *
     * @param string $sendVia
     * @return void
     */
    public function sendOTP(string $sendVia): void
    {
        // Convert expiry time from seconds to minutes
        $expiryTimeInMinutes = $this->otpExpiry / 60;

        if ($expiryTimeInMinutes >= 60) {
            $expiryTimeInHours = $expiryTimeInMinutes / 60;
            $expiryTimeMessage = $expiryTimeInHours > 1 ? "$expiryTimeInHours hours" : "$expiryTimeInHours hour";
        } else {
            $expiryTimeMessage = $expiryTimeInMinutes > 1 ? "$expiryTimeInMinutes minutes" : "$expiryTimeInMinutes minute";
        }

        notify($this->user, 'OTP_FOR_TRANSACTION', [
            'otp'         => $this->otp,
            'expiry_time' => $expiryTimeMessage,
        ], [$sendVia]);
    }

    /**
     * Regenerate an OTP for a specific identifier.
     * If the current OTP is still valid, it won't generate a new one.
     *
     * @param string $identifier
     * @return OTPManager
     * @throws RandomException
     * @throws InvalidArgumentException
     * @throws Exception
     */
    public function regenerateOTP(string $identifier): static
    {
        $key        = $this->getStorageKey($identifier);
        $storedData = $this->storage->get($key);

        if ($storedData && array_key_exists('otp', $storedData)) {
            $remainingTime = $storedData['expires_at'] - time();

            if ($remainingTime > 0) {
                throw new Exception('Please try again after ' . $this->formatRemainingTime($remainingTime));
            }
        }

        $this->otp           = $this->generateRandomOTP();
        $expirationTimestamp = time() + $this->otpExpiry;

        Log::info("Regenerated OTP for the '$identifier'", ['otp' => $this->otp]);

        $this->storage->put($key, ['otp' => $this->otp, 'expires_at' => $expirationTimestamp], $this->otpExpiry);

        return $this;
    }

    /**
     * Validate an OTP for a specific identifier.
     *
     * @param string $identifier
     * @param string $otp
     * @return bool
     * @throws InvalidArgumentException
     */
    public function validateOTP(string $identifier, string $otp): bool
    {
        $key        = $this->getStorageKey($identifier);
        $storedData = $this->storage->get($key);

        if ($storedData && array_key_exists('otp', $storedData) && $storedData['otp'] === $otp) {
            $this->storage->forget($key);

            return true;
        }

        return false;
    }

    /**
     * Generate a random OTP of the specified length.
     *
     * @return string
     * @throws RandomException
     */
    private function generateRandomOTP(): string
    {
        return str_pad(random_int(0, pow(10, 6) - 1), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Get the storage key for an identifier.
     *
     * @param string $identifier
     * @return string
     */
    private function getStorageKey(string $identifier): string
    {
        return 'otp_' . md5($identifier);
    }

    /**
     * Format remaining time into a human-readable message.
     *
     * @param int $remainingSeconds
     * @return string
     */
    private function formatRemainingTime(int $remainingSeconds): string
    {
        if ($remainingSeconds < 60) {
            // Less than a minute
            return "$remainingSeconds second" . ($remainingSeconds > 1 ? 's.' : '.');
        } elseif ($remainingSeconds < 3600) {
            // Less than an hour
            $minutes = floor($remainingSeconds / 60);

            return "$minutes minute" . ($minutes > 1 ? 's.' : '.');
        } else {
            // More than or equal to an hour
            $hours = floor($remainingSeconds / 3600);

            return "$hours hour" . ($hours > 1 ? 's.' : '.');
        }
    }
}
