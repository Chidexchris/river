<?php

namespace App\Http\Controllers\User;

use App\Models\Form;
use App\Lib\FormProcessor;
use App\Models\GatewayCurrency;
use App\Models\Transaction;
use App\Constants\ManageStatus;
use App\Lib\GoogleAuthenticator;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\DepositPlan;
use App\Rules\StrongPassword;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\File;

class UserController extends Controller
{
    public function home()
    {
        $pageTitle  = 'Dashboard';
        $user       = auth('web')->user();
        $kycContent = $user->kc == ManageStatus::UNVERIFIED || $user->kc == ManageStatus::PENDING
            ? getSiteData('kyc.content', true)
            : null;

        $referURL = route('user.register.form', ['referrer' => auth('web')->user()->username]);

        $user->load(['deposits', 'withdrawals']);

        // the deposit & withdrawal amounts of the user
        $depositAmount    = $user->deposits()->done()->sum('amount');
        $withdrawalAmount = $user->withdrawals()->done()->sum('amount');

        // count transactions
        $user->loadCount([
            'transactions'          => fn($query) => $query->whereDate('created_at', today()),
            'depositPensionSchemes' => fn($query) => $query->running(),
            'fixedDepositSchemes'   => fn($query) => $query->running(),
            'loans'                 => fn($query) => $query->running(),
        ]);

        $recentDeposits    = $user->deposits()->done()->latest()->limit(10)->get();
        $recentWithdrawals = $user->withdrawals()->done()->latest()->limit(10)->get();

        // Build the cash-flow chart from the authenticated user's completed
        // deposits and withdrawals, grouped into the last four calendar weeks.
        $cashFlowStart = now()->startOfWeek()->subWeeks(3);
        $depositsByWeek = $user->deposits()->done()
            ->where('created_at', '>=', $cashFlowStart)
            ->get(['amount', 'created_at'])
            ->groupBy(fn($deposit) => $deposit->created_at->startOfWeek()->toDateString())
            ->map(fn($deposits) => (float) $deposits->sum('amount'));
        $withdrawalsByWeek = $user->withdrawals()->done()
            ->where('created_at', '>=', $cashFlowStart)
            ->get(['amount', 'created_at'])
            ->groupBy(fn($withdrawal) => $withdrawal->created_at->startOfWeek()->toDateString())
            ->map(fn($withdrawals) => (float) $withdrawals->sum('amount'));

        $cashFlow = collect(range(0, 3))->map(function ($weekOffset) use ($cashFlowStart, $depositsByWeek, $withdrawalsByWeek) {
            $week = $cashFlowStart->copy()->addWeeks($weekOffset);
            $key  = $week->toDateString();

            return [
                'label'       => $week->format('M j'),
                'deposits'    => $depositsByWeek->get($key, 0),
                'withdrawals' => $withdrawalsByWeek->get($key, 0),
            ];
        });
        $cashFlowMax = max(1, $cashFlow->max('deposits'), $cashFlow->max('withdrawals'));

        return view("{$this->activeTheme}user.page.dashboard", compact('pageTitle', 'kycContent', 'user', 'depositAmount', 'withdrawalAmount', 'referURL', 'recentDeposits', 'recentWithdrawals', 'cashFlow', 'cashFlowMax'));
    }

    public function kycForm()
    {
        $pageTitle = 'Identification Form';
        $user      = auth('web')->user();

        if ($user->kc == ManageStatus::PENDING) {
            $toast[] = ['warning', 'Your identity verification is being processed'];

            return back()->with('toasts', $toast);
        }

        if ($user->kc == ManageStatus::VERIFIED) {
            $toast[] = ['success', 'Your identity verification is being succeed'];

            return back()->with('toasts', $toast);
        }

        return view("{$this->activeTheme}user.kyc.form", compact('pageTitle', 'user'));
    }

    public function kycSubmit()
    {
        $form           = Form::where('act', 'kyc')->first();
        $formData       = $form->form_data;
        $formProcessor  = new FormProcessor();
        $validationRule = $formProcessor->valueValidation($formData);

        request()->validate($validationRule);

        $userData       = $formProcessor->processFormData(request(), $formData);
        $user           = auth('web')->user();
        $user->kyc_data = $userData;
        $user->kc       = ManageStatus::PENDING;
        $user->save();

        $toast[] = ['success', 'Your identity verification information has been submitted'];

        return to_route('user.home')->with('toasts', $toast);
    }

    public function kycData()
    {
        $pageTitle = 'Identification Information';
        $user      = auth('web')->user();

        return view("{$this->activeTheme}user.kyc.info", compact('pageTitle', 'user'));
    }

    public function profile()
    {
        $pageTitle = 'Profile Settings';
        $user      = auth('web')->user();

        return view("{$this->activeTheme}user.page.profile", compact('pageTitle', 'user'));
    }

    public function profileUpdate()
    {
        $this->validate(request(), [
            'firstname' => 'required|string',
            'lastname'  => 'required|string',
            'image'     => ['nullable', File::types(['png', 'jpg', 'jpeg'])],
        ], [
            'firstname.required' => 'First name field is required',
            'lastname.required'  => 'Last name field is required',
        ]);

        $user = auth('web')->user();

        if (request()->hasFile('image')) {
            try {
                $user->image = fileUploader(request()->file('image'), getFilePath('userProfile'), getFileSize('userProfile'), $user->image);
            } catch (Exception) {
                $toast[] = ['error', 'Image uploading process has failed'];

                return back()->with('toasts', $toast);
            }
        }

        $user->firstname = request('firstname');
        $user->lastname  = request('lastname');

        $user->address = [
            'state'   => request('state'),
            'zip'     => request('zip'),
            'city'    => request('city'),
            'address' => request('address'),
        ];

        $user->save();

        $toast[] = ['success', 'Your profile has updated'];

        return back()->with('toasts', $toast);
    }

    public function password()
    {
        $pageTitle             = 'Change Password';
        $changePasswordContent = getSiteData('change_password.content', true);
        $user                  = auth('web')->user();

        return view("{$this->activeTheme}user.page.password", compact('pageTitle', 'changePasswordContent', 'user'));
    }

    public function passwordChange()
    {
        $this->validate(request(), [
            'current_password' => 'required',
            'password'         => ['required', 'confirmed', new StrongPassword],
        ]);

        $user = auth('web')->user();

        if (!Hash::check(request('current_password'), $user->password)) {
            $toast[] = ['error', 'Current password mismatched!'];

            return back()->with('toasts', $toast);
        }

        $user->password = Hash::make(request('password'));
        $user->save();

        $toast[] = ['success', 'Your password has changed'];

        return back()->with('toasts', $toast);
    }

    public function show2faForm()
    {
        $ga        = new GoogleAuthenticator();
        $user      = auth('web')->user();
        $secret    = $ga->createSecret();
        $qrCodeUrl = $ga->getQRCodeGoogleUrl($user->username . '@' . bs('site_name'), $secret);
        $pageTitle = '2FA Settings';

        return view("{$this->activeTheme}user.page.twoFactor", compact('pageTitle', 'secret', 'qrCodeUrl', 'user'));
    }

    public function enable2fa()
    {
        $user = auth('web')->user();

        $this->validate(request(), [
            'key'    => 'required',
            'code'   => 'required|array|min:6',
            'code.*' => 'required|integer',
        ]);

        $verCode  = (int)(implode("", request('code')));
        $response = verifyG2fa($user, $verCode, request('key'));

        if ($response) {
            $user->tsc = request('key');
            $user->ts  = ManageStatus::YES;
            $user->save();

            $toast[] = ['success', 'Two factor authenticator successfully activated'];
        } else {
            $toast[] = ['error', 'Wrong verification code'];
        }

        return back()->with('toasts', $toast);
    }

    public function disable2fa()
    {
        $this->validate(request(), [
            'code'   => 'required|array|min:6',
            'code.*' => 'required|integer',
        ]);

        $verCode  = (int)(implode("", request('code')));
        $user     = auth('web')->user();
        $response = verifyG2fa($user, $verCode);

        if ($response) {
            $user->tsc = null;
            $user->ts  = ManageStatus::NO;
            $user->save();

            $toast[] = ['success', 'Two factor authenticator successfully deactivated'];
        } else {
            $toast[] = ['error', 'Wrong verification code'];
        }

        return back()->with('toasts', $toast);
    }

    public function deposit()
    {
        $pageTitle         = 'Deposit Money';
        $user              = auth('web')->user();

        $gatewayCurrencies = GatewayCurrency::whereHas('method', fn($gateway) => $gateway->active())
            ->with('method')
            ->orderby('method_code')
            ->get();

        // Fetch all deposit plans for the logged-in user
        $depositPlans =  DepositPlan::where('user_id', $user->id)->get();
        // $depositPlans = [];

        return view("{$this->activeTheme}user.deposit.index", compact(
            'pageTitle',
            'user',
            'gatewayCurrencies',
            'depositPlans'
        ));
    }

    public function createDepositPlan()
    {
        $pageTitle = 'Create Deposit Plan';
        $user = auth('web')->user();
        $depositPlans = DepositPlan::where('user_id', $user->id)->get();

        return view("{$this->activeTheme}user.deposit.create", compact(
            'pageTitle',
            'user',
            'depositPlans',
        ));
    }


    public function store(Request $request)
    {
        $request->validate([
            'plan_name'     => 'required|string|max:255',
            'daily_amount'  => 'required|numeric|min:0',
            'currency'      => 'required|string|max:10',
            'duration_days' => 'required|integer|min:1',
            'start_date'    => 'required|date',
        ]);

        DepositPlan::create([
            'user_id'       => auth('web')->id(), // manual link
            'plan_name'     => $request->plan_name,
            'daily_amount'  => $request->daily_amount,
            'currency'      => $request->currency,
            'duration_days' => $request->duration_days,
            'start_date'    => $request->start_date,
        ]);

        return back()->with('success', 'Deposit plan created successfully.');
    }

    /**
     * insert a new deposit with status pending for cash payment
     * so that admin can approve it manually
     */
    public function insert(Request $request){
        dd($request->all());
        $request->validate([
            'amount'   => 'required|numeric|min:1',
            'currency' => 'required|string|max:10',
            'deposit_plan_id' => 'nullable|exists:deposit_plans,id',
            'gateway' => 'required|in:cash', // only cash is allowed

        ]);

        $user = auth('web')->user();

        // Create a new deposit with status pending
        // $deposit = $user->deposits()->create([
        //     'amount'       => $request->amount,
        //     'currency'     => $request->currency,
        //     'deposit_plan_id' => $request->deposit_plan_id,
        //     'method_code'  => 'cash', // manual link
        //     'status'       => ManageStatus::PENDING,
        //     'trx'          => getTrx(),
        // ]);

        return back()->with('success', 'Cash deposit request submitted successfully. Please wait for admin approval.');
    }

    public function depositHistory()
    {
        $pageTitle = 'Deposit History';
        $user      = auth('web')->user();
        $deposits  = $user->deposits()
            ->with('gateway')
            ->searchable(['trx'])
            ->index()
            ->latest()
            ->paginate(getPaginate());

        return view("{$this->activeTheme}user.deposit.history", compact('pageTitle', 'deposits', 'user'));
    }

    public function transactions()
    {
        $pageTitle    = 'Transactions';
        $user         = auth('web')->user();
        $remarks      = Transaction::select('remark')->distinct()->orderBy('remark')->get('remark');
        $transactions = $user->transactions()
            ->searchable(['trx'])
            ->filter(['trx_type', 'remark'])
            ->latest()
            ->paginate(getPaginate());

        return view("{$this->activeTheme}user.page.transactions", compact('pageTitle', 'transactions', 'remarks', 'user'));
    }

    public function accountStatement()
    {
        $pageTitle = 'Account Statement';
        $user      = auth('web')->user();

        return view("{$this->activeTheme}user.page.statement", compact('pageTitle', 'user'));
    }

    public function fetchStatement(Request $request)
    {
        $request->validate([
            'date' => 'required|string',
        ]);

        $pageTitle    = 'Account Statement';
        $user         = auth('web')->user();
        $transactions = $this->getFilteredTransactions($request, $user);

        return view("{$this->activeTheme}user.page.statement", compact('pageTitle', 'user', 'transactions'));
    }

    public function exportStatement(Request $request)
    {
        $request->validate([
            'date' => 'required|string',
        ]);

        $user                 = auth('web')->user();
        $statementDownloadFee = bs('statement_download_fee');

        if ($statementDownloadFee > 0 && $user->balance < $statementDownloadFee) {
            return back()->with('toasts', [
                ['error', 'You do not have enough balance to download statement'],
            ]);
        }

        [$transactions, $user] = $this->getFilteredTransactions($request, $user, false);

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

    public function fileDownload()
    {
        $path = request('filePath');
        $file = fileManager()->$path()->path . '/' . request('fileName');

        return response()->download($file);
    }

    public function showReferralTree()
    {
        $pageTitle = 'Referred Users';
        $user      = auth('web')->user();
        $maxLevel  = bs('referral_tree_level');
        $relations = collect()->times($maxLevel, fn($i) => 'referrals' . str_repeat('.referrals', $i - 1))->toArray();

        $user->load(array_merge(['referrer'], $relations));

        return view("{$this->activeTheme}user.page.referral", compact('pageTitle', 'user', 'maxLevel'));
    }

    private function getFilteredTransactions(Request $request, User $user, bool $paginate = true)
    {
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
