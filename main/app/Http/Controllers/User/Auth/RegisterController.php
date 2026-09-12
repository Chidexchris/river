<?php

namespace App\Http\Controllers\User\Auth;

use App\Constants\ManageStatus;
use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\User;
use App\Rules\StrongPassword;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    use RegistersUsers;

    function registerForm()
    {
        if (request()->filled('referrer')) {
            session()->put('refer_by', request('referrer'));
        } else {
            session()->forget('refer_by');
        }

        $pageTitle       = 'Register';
        $info            = json_decode(json_encode(getIpInfo()), true);
        // Ensure $info['code'] is an array before using implode
        $mobileCode      = is_array($info['code']) ? implode(',', $info['code']) : ($info['code'] ?? '');
        $countries       = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $registerContent = getSiteData('register.content', true);
        $policyPages     = getSiteData('policy_pages.element', false, null, true);

        return view("{$this->activeTheme}user.auth.register", compact('pageTitle', 'mobileCode', 'countries', 'registerContent', 'policyPages'));
    }

    protected function validator(array $data)
    {
        $setting = bs();
        $agree   = 'nullable';

        if ($setting->agree_policy) $agree = 'required';

        $countryData  = (array)json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $countryCodes = implode(',', array_keys($countryData));
        $mobileCodes  = implode(',', array_column($countryData, 'dial_code'));
        $countries    = implode(',', array_column($countryData, 'country'));

        return Validator::make($data, [
            'firstname'    => 'required|string|max:40',
            'lastname'     => 'required|string|max:40',
            'email'        => 'required|email|max:40|unique:users',
            'mobile'       => 'required|max:40|regex:/^([0-9]*)$/',
            'password'     => ['required', 'confirmed', new StrongPassword],
            'username'     => 'required|unique:users|min:6|max:40',
            'mobile_code'  => 'required|in:' . $mobileCodes,
            'country_code' => 'required|in:' . $countryCodes,
            'country'      => 'required|in:' . $countries,
            'agree'        => $agree,
        ]);
    }

    /**
     * Get the guard to be used during authentication.
     *
     */
    protected function guard()
    {
        return auth()->guard('web');
    }

    function register()
    {
        $this->validator(request()->all())->validate();

        request()->session()->regenerateToken();

        if (!verifyCaptcha()) {
            $toast[] = ['error', 'Invalid captcha provided'];

            return back()->with('toasts', $toast);
        }

        if (preg_match("/[^a-z0-9_]/", trim(request('username')))) {
            $toast[] = ['info', 'Usernames are limited to lowercase letters, numbers, and underscores'];
            $toast[] = ['error', 'Username must exclude special characters, spaces, and capital letters'];

            return back()->with('toasts', $toast)->withInput(request()->all());
        }

        $exist = User::where('mobile', request('mobile_code') . request('mobile'))->first();

        if ($exist) {
            $toast[] = ['error', 'The mobile number already exists in our records'];

            return back()->with('toasts', $toast)->withInput();
        }

        event(new Registered(
            $user = $this->create(request()->all())
        ));

        // notify admin
        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = $user->id;
        $adminNotification->title     = 'A new member has just registered!';
        $adminNotification->click_url = urlPath('admin.user.index');
        $adminNotification->save();

        $this->guard()->login($user);

        return $this->registered() ?: redirect($this->redirectPath());
    }

    protected function create(array $data)
    {
        $setting   = bs();
        $referUser = null;

        if (session()->has('refer_by')) {
            $referBy   = session('refer_by');
            $referUser = User::where('username', $referBy)->first();
        }

        // User Create
        $user                 = new User();
        $user->account_number = generateAccountNumber();
        $user->firstname      = $data['firstname'];
        $user->lastname       = $data['lastname'];
        $user->username       = $data['username'];
        $user->email          = strtolower($data['email']);
        $user->password       = Hash::make($data['password']);
        $user->ref_by         = $referUser ? $referUser->id : 0;
        $user->country_code   = $data['country_code'];
        $user->country_name   = $data['country'] ?? null;
        $user->mobile         = $data['mobile_code'] . $data['mobile'];
        $user->kc             = $setting->kc ? ManageStatus::UNVERIFIED : ManageStatus::VERIFIED;
        $user->ec             = $setting->ec ? ManageStatus::UNVERIFIED : ManageStatus::VERIFIED;
        $user->sc             = $setting->sc ? ManageStatus::UNVERIFIED : ManageStatus::VERIFIED;
        $user->ts             = ManageStatus::INACTIVE;
        $user->tc             = ManageStatus::VERIFIED;
        $user->save();

        return $user;
    }

    function checkUser()
    {
        $exist['data'] = false;
        $exist['type'] = null;

        if (request('email')) {
            $exist['data'] = User::where('email', request('email'))->exists();
            $exist['type'] = 'email';
        }

        if (request('mobile')) {
            $exist['data'] = User::where('mobile', request('mobile'))->exists();
            $exist['type'] = 'mobile';
        }

        if (request('username')) {
            $exist['data'] = User::where('username', request('username'))->exists();
            $exist['type'] = 'username';
        }

        return response($exist);
    }

    function registered()
    {
        return to_route('user.home');
    }
}
