<?php

namespace App\Http\Controllers\Staff;

use App\Constants\ManageStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\AccountRequest;
use App\Models\AdminNotification;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AccountController extends Controller
{
    public function index()
    {
        $staff = auth('staff')->user();

        $staff->load('branches');

        $branches  = $staff->branches;
        $branch    = session()->has('branchId') ? $branches->find(session('branchId')) : $branches->first();
        $pageTitle = "Accounts Opened at the $branch->name";

        $accounts = User::where('branch_id', $branch->id)
            ->searchable(['account_number', 'firstname', 'lastname', 'username', 'email'])
            ->latest()
            ->paginate(getPaginate());

        if (isManager()) $accounts->load('staff');

        return view('staff.accounts.index', compact('staff', 'pageTitle', 'accounts'));
    }

    public function create()
    {
        $pageTitle = 'Create New Account';
        $staff     = auth('staff')->user();

        if (Gate::forUser($staff)->denies('createBankAccount', User::class)) abort(Response::HTTP_FORBIDDEN);

        $countries               = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $isReferralSystemEnabled = bs('referral_system');

        return view('staff.accounts.create', compact('pageTitle', 'countries', 'isReferralSystemEnabled'));
    }

    public function store(AccountRequest $request)
    {
        $validated = $request->validated();
        $staff     = auth('staff')->user();

        $staff->load('branches');

        $branch = $staff->branches->first();

        try {
            $validated['image'] = fileUploader($request->file('image'), getFilePath('userProfile'), getFileSize('userProfile'));
        } catch (Exception $exception) {
            $toast[] = ['error', "Image uploading process has failed. {$exception->getMessage()}"];

            return back()->with('toasts', $toast);
        }

        $settings = bs();

        if ($settings->referral_system && isset($validated['ref_by'])) {
            $referrerId = User::where('account_number', $validated['ref_by'])->pluck('id')->first();
        }

        $password = generatePassword();

        $user = $staff->users()->create([
            'branch_id'             => $branch->id,
            'account_number'        => generateAccountNumber(),
            'image'                 => $validated['image'],
            'firstname'             => $validated['firstname'],
            'lastname'              => $validated['lastname'],
            'username'              => $validated['username'],
            'email'                 => $validated['email'],
            'country_code'          => $validated['country_code'],
            'country_name'          => $validated['country_name'],
            'mobile'                => $validated['mobile'],
            'ref_by'                => $referrerId ?? 0,
            'referral_action_limit' => $settings->referral_commission_count,
            'password'              => Hash::make($password),
            'address'               => [
                'address' => $validated['address'],
                'city'    => $validated['city'],
                'state'   => $validated['state'],
                'zip'     => $validated['zip_code'],
            ],
            'kc'                    => !$settings->kc ? ManageStatus::VERIFIED : ManageStatus::UNVERIFIED,
            'ec'                    => ManageStatus::VERIFIED,
            'sc'                    => !$settings->sc ? ManageStatus::VERIFIED : ManageStatus::UNVERIFIED,
        ]);

        notify($user, 'ACCOUNT_OPENED', [
            'username'      => $user->username,
            'email_address' => $user->email,
            'password'      => $password,
            'url'           => route('user.login.form'),
        ], ['email']);

        AdminNotification::create([
            'user_id'   => $user->id,
            'title'     => "A new account has just opened form $branch->name!",
            'click_url' => urlPath('admin.user.details', $user->id)
        ]);

        $toast[] = ['success', 'New account has been opened successfully'];

        return to_route('staff.accounts.index')->with('toasts', $toast);
    }

    public function edit(string $account)
    {
        $pageTitle = 'Edit Account';
        $staff     = auth('staff')->user();
        $user      = User::with('referrer')->where('account_number', $account)->firstOrFail();

        if (Gate::forUser($staff)->denies('editBankAccount', $user)) abort(Response::HTTP_FORBIDDEN);

        $countries         = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $countryCode       = $user->country_code;
        $fullContactNumber = $user->mobile;

        foreach ($countries as $key => $country) {
            if ($countryCode == $key && str_starts_with($fullContactNumber, $country->dial_code)) {
                $user['contact_number'] = substr($fullContactNumber, strlen($country->dial_code));
                break;
            }
        }

        return view('staff.accounts.edit', compact('pageTitle', 'countries', 'user'));
    }

    public function update(AccountRequest $request, string $account)
    {
        $validated = $request->validated();
        $user      = User::where('account_number', $account)->firstOrFail();

        if ($request->hasFile('image')) {
            try {
                $validated['image'] = fileUploader(
                    $request->file('image'),
                    getFilePath('userProfile'),
                    getFileSize('userProfile'),
                    $user->image
                );
            } catch (Exception $exception) {
                $toast[] = ['error', "Image uploading process has failed. {$exception->getMessage()}"];

                return back()->with('toasts', $toast);
            }
        }

        $user->update([
            'image'        => $validated['image'] ?? $user->image,
            'firstname'    => $validated['firstname'],
            'lastname'     => $validated['lastname'],
            'username'     => $validated['username'],
            'email'        => $validated['email'],
            'country_code' => $validated['country_code'],
            'country_name' => $validated['country_name'],
            'mobile'       => $validated['mobile'],
            'address'      => [
                'address' => $validated['address'],
                'city'    => $validated['city'],
                'state'   => $validated['state'],
                'zip'     => $validated['zip_code'],
            ],
            'ec'           => $validated['email'] != $user->email ? ManageStatus::UNVERIFIED : $user->ec,
            'sc'           => $validated['mobile'] != $user->mobile ? ManageStatus::UNVERIFIED : $user->sc,
        ]);

        $toast[] = ['success', 'Account has been updated successfully'];

        return back()->with('toasts', $toast);
    }

    public function checkUnique(Request $request, ?int $id = 0)
    {
        $field = $request->input('field');
        $value = $request->input('value');

        $exists = false;

        if (in_array($field, ['username', 'email', 'mobile'])) {
            $exists = User::whereNot('id', $id)->where($field, $value)->exists();
        }

        return response()->json(['exists' => $exists]);
    }

    public function statement(string $account)
    {
        if (isManager()) abort(Response::HTTP_FORBIDDEN);

        $pageTitle = 'Account Statement';

        return view('staff.accounts.statement', compact('pageTitle', 'account'));
    }

    public function fetchStatement(Request $request, string $account)
    {
        $request->validate([
            'date' => 'required|string',
        ]);

        $pageTitle    = 'Account Statement';
        $transactions = $this->getFilteredTransactions($request, $account);

        return view('staff.accounts.statement', compact('transactions', 'account', 'pageTitle'));
    }

    public function exportStatement(Request $request, string $account)
    {
        $request->validate([
            'date' => 'required|string',
        ]);

        [$transactions, $user] = $this->getFilteredTransactions($request, $account, false);

        $contactElements = getSiteData('contact_us.element', false, null, true);
        $companyInfo     = [];

        foreach ($contactElements as $contactElement) {
            $companyInfo[$contactElement->data_info->heading] = $contactElement->data_info->data;
        }

        $dates          = explode(' - ', $request->input('date'));
        $formattedDates = array_map(fn($date) => showDateTime($date, 'F d, Y'), $dates);
        $creditTotal    = $transactions->where('trx_type', '+')->sum('amount');
        $debitTotal     = $transactions->where('trx_type', '-')->sum('amount');

        $pdf = Pdf::loadView('pdf.statement', compact('transactions', 'companyInfo', 'user', 'formattedDates', 'creditTotal', 'debitTotal'));

        $accountHolderName = str_replace(' ', '-', $user->fullname);
        $todayDate         = now()->format('Y-m-d');
        $fileName          = "Account-Statement_{$accountHolderName}_$todayDate.pdf";

        return $pdf->download($fileName);
    }

    private function getFilteredTransactions(Request $request, string $account, bool $paginate = true)
    {
        $staff = auth('staff')->user();
        $user  = User::where('account_number', $account)->firstOrFail();

        if (Gate::forUser($staff)->denies('fetchAccountStatement', $user)) abort(Response::HTTP_FORBIDDEN);

        $fromAmount = $request->query('from_amount');
        $toAmount   = $request->query('to_amount');

        $trxQuery = $user->transactions()
            ->filter(['trx_type'])
            ->dateFilter()
            ->when($fromAmount, function (Builder $query, $fromAmount) {
                $query->where('amount', '>=', $fromAmount);
            })
            ->when($toAmount, function (Builder $query, $toAmount) {
                $query->where('amount', '<=', $toAmount);
            })
            ->orderBy('created_at');

        return $paginate ? $trxQuery->paginate(getPaginate()) : [$trxQuery->get(), $user->load('branch:id,name')];
    }
}
