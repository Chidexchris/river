<?php

namespace App\Http\Controllers\Admin;

use App\Constants\ManageStatus;
use App\Http\Controllers\Controller;
use App\Lib\FormProcessor;
use App\Models\Form;
use App\Models\Plugin;
use App\Models\Setting;
use App\Models\SiteData;
use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Validation\Rules\File;
use Image;

class SettingController extends Controller
{
    public function basic()
    {
        $this->authorize('viewAny', Setting::class);

        $pageTitle   = 'Basic Setting';
        $timeRegions = json_decode(file_get_contents(resource_path('views/admin/partials/timeRegion.json')));

        return view('admin.settings.basic', compact('pageTitle', 'timeRegions'));
    }

    public function updateBasicPreferences()
    {
        $settings = bs();

        $this->authorize('updateBasicSettings', $settings);

        $this->validate(request(), [
            'site_name'              => 'required|string|max:40',
            'site_cur'               => 'required|string|max:40',
            'cur_sym'                => 'required|string|max:40',
            'primary_color'          => ['required', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'secondary_color'        => ['required', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'per_page_item'          => 'required|in:20,50,100',
            'fraction_digit'         => 'required|int|gte:0|max:9',
            'date_format'            => 'required|in:m-d-Y,d-m-Y,Y-m-d',
            'time_region'            => 'required',
            'account_number_prefix'  => 'required|string|max:10',
            'account_number_length'  => 'required|integer|gt:0',
            'referral_tree_level'    => 'required|integer|gt:0',
            'otp_expiry'             => 'required|integer|min:60',
            'idle_timeout'           => 'required|integer|min:60',
            'statement_download_fee' => 'required|numeric|gte:0',
        ]);

        $settings->update([
            'site_name'              => request('site_name'),
            'site_cur'               => request('site_cur'),
            'cur_sym'                => request('cur_sym'),
            'per_page_item'          => request('per_page_item'),
            'fraction_digit'         => request('fraction_digit'),
            'date_format'            => request('date_format'),
            'primary_color'          => str_replace('#', '', request('primary_color')),
            'secondary_color'        => str_replace('#', '', request('secondary_color')),
            'account_number_prefix'  => request('account_number_prefix'),
            'account_number_length'  => request('account_number_length'),
            'referral_tree_level'    => request('referral_tree_level'),
            'otp_expiry'             => request('otp_expiry'),
            'idle_timeout'           => request('idle_timeout'),
            'statement_download_fee' => request('statement_download_fee'),
        ]);

        $timeRegionFile = config_path('timeRegion.php');
        $setTimeRegion  = '<?php $timeRegion = ' . request('time_region') . ' ?>';

        file_put_contents($timeRegionFile, $setTimeRegion);

        $toast[] = ['success', 'Basic settings successfully updated'];

        return back()->with('toasts', $toast);
    }

    public function updateBankTransactionPreferences()
    {
        $settings = bs();

        $this->authorize('updateBankTransactionSettings', $settings);

        $this->validate(request(), [
            'per_transaction_min_amount'     => 'bail|required|numeric|gt:0',
            'per_transaction_max_amount'     => 'bail|required|numeric|gt:per_transaction_min_amount',
            'daily_transaction_max_amount'   => 'bail|required|numeric|gt:per_transaction_max_amount',
            'monthly_transaction_max_amount' => 'bail|required|numeric|gt:daily_transaction_max_amount',
            'fixed_charge'                   => 'bail|required|numeric|gte:0',
            'percentage_charge'              => 'bail|required|numeric|regex:/^\d+(\.\d{1,2})?$/|gte:0',
        ], [
            'per_transaction_max_amount.gt'     => 'The per transaction max amount must be greater than per transaction min amount.',
            'daily_transaction_max_amount.gt'   => 'The daily transaction max amount must be greater than per transaction max amount.',
            'monthly_transaction_max_amount.gt' => 'The monthly transaction max amount must be greater than daily transaction max amount.',
            'percentage_charge.regex'           => 'The percentage charge must take only two decimal places',
        ]);

        $settings->update([
            'per_transaction_min_amount'     => request('per_transaction_min_amount'),
            'per_transaction_max_amount'     => request('per_transaction_max_amount'),
            'daily_transaction_max_amount'   => request('daily_transaction_max_amount'),
            'monthly_transaction_max_amount' => request('monthly_transaction_max_amount'),
            'fixed_charge'                   => request('fixed_charge'),
            'percentage_charge'              => request('percentage_charge'),
        ]);

        $toast[] = ['success', 'Bank transaction settings successfully updated'];

        return back()->with('toasts', $toast);
    }

    public function updateSystemPreferences()
    {
        $settings = bs();

        $this->authorize('updateSystemSettings', $settings);

        $preferences = [
            'signup', 'enforce_ssl', 'agree_policy', 'strong_pass', 'kc', 'ec', 'ea',
            'sc', 'sa', 'language', 'open_account', 'deposit', 'withdraw', 'dps', 'fds',
            'loan', 'internal_bank_transfer', 'external_bank_transfer', 'wire_transfer',
            'sms_based_otp', 'email_based_otp', 'auto_logout'
        ];

        foreach ($preferences as $preference) {
            $settings->$preference = request($preference) ? ManageStatus::ACTIVE : ManageStatus::INACTIVE;
        }

        $settings->save();

        $toast[] = ['success', 'System settings successfully updated'];

        return back()->with('toasts', $toast);
    }

    public function updateLogoFavicon()
    {
        $this->authorize('updateLogoAndFavicon', Setting::class);

        $this->validate(request(), [
            'logo_light' => File::types('png'),
            'logo_dark'  => File::types('png'),
            'favicon'    => File::types('png'),
        ]);

        $path = getFilePath('logoFavicon');

        if (request()->hasFile('logo_light')) {
            try {
                if (!file_exists($path)) {
                    mkdir($path, 0755, true);
                }

                Image::make(request('logo_light'))->save($path . '/logo_light.png');
            } catch (Exception) {
                $toast[] = ['error', 'Unable to upload light logo'];

                return back()->with('toasts', $toast);
            }
        }

        if (request()->hasFile('logo_dark')) {
            try {
                if (!file_exists($path)) {
                    mkdir($path, 0755, true);
                }

                Image::make(request('logo_dark'))->save($path . '/logo_dark.png');
            } catch (Exception) {
                $toast[] = ['error', 'Unable to upload dark logo'];

                return back()->with('toasts', $toast);
            }
        }

        if (request()->hasFile('favicon')) {
            try {
                if (!file_exists($path)) {
                    mkdir($path, 0755, true);
                }

                $size = explode('x', getFileSize('favicon'));
                Image::make(request('favicon'))->resize($size[0], $size[1])->save($path . '/favicon.png');
            } catch (Exception) {
                $toast[] = ['error', 'Unable to upload the favicon'];

                return back()->with('toasts', $toast);
            }
        }

        $toast[] = ['success', 'Logo and favicon has been successfully updated.'];

        return back()->with('toasts', $toast);
    }

    public function plugin()
    {
        $this->authorize('viewAny', Plugin::class);

        $pageTitle = 'Plugin Settings';
        $plugins   = Plugin::orderBy('name')->get();

        return view('admin.settings.plugin', compact('pageTitle', 'plugins'));
    }

    public function updatePlugin(int $id)
    {
        $plugin = Plugin::findOrFail($id);

        $this->authorize('update', $plugin);

        $validationRule = [];

        foreach ($plugin->shortcode as $key => $val) {
            $validationRule = array_merge($validationRule, [$key => 'required']);
        }

        request()->validate($validationRule);

        $shortCode = json_decode(json_encode($plugin->shortcode), true);

        foreach ($shortCode as $key => $value) {
            $shortCode[$key]['value'] = request($key);
        }

        $plugin->shortcode = $shortCode;
        $plugin->status    = request('status') ? ManageStatus::ACTIVE : ManageStatus::INACTIVE;
        $plugin->save();

        $toast[] = ['success', "$plugin->name successfully updated."];

        return back()->with('toasts', $toast);
    }

    public function updatePluginStatus(int $id)
    {
        $this->authorize('changeStatus', Plugin::class);

        return Plugin::changeStatus($id);
    }

    public function seo()
    {
        $pageTitle = 'SEO Settings';
        $seo       = SiteData::where('data_key', 'seo.data')->first();

        $this->authorize('viewSeoSettings', $seo);

        if (!$seo) {
            $data_info           = '{"keywords":[],"description":"","social_title":"","social_description":"","image":null}';
            $data_info           = json_decode($data_info, true);
            $siteData            = new SiteData();
            $siteData->data_key  = 'seo.data';
            $siteData->data_info = $data_info;
            $siteData->save();
        }

        return view('admin.site.seo', compact('pageTitle', 'seo'));
    }

    public function cookie()
    {
        $pageTitle = 'Cookie Policy';
        $cookie    = SiteData::where('data_key', 'cookie.data')->first();

        $this->authorize('viewCookieSettings', $cookie);

        return view('admin.site.cookie', compact('pageTitle', 'cookie'));
    }

    public function updateCookie()
    {
        $this->validate(request(), [
            'short_details' => 'required',
            'details'       => 'required',
        ]);

        $cookie = SiteData::where('data_key', 'cookie.data')->first();

        $this->authorize('updateCookieSettings', $cookie);

        $cookie->data_info = [
            'short_details' => request('short_details'),
            'details'       => request('details'),
            'status'        => request('status') ? ManageStatus::ACTIVE : ManageStatus::INACTIVE,
        ];
        $cookie->save();

        $toast[] = ['success', 'Cookie policy updated successfully'];

        return back()->with('toasts', $toast);
    }

    public function maintenance()
    {
        $pageTitle   = 'Maintenance Mode';
        $maintenance = SiteData::where('data_key', 'maintenance.data')->first();

        $this->authorize('viewMaintenanceSettings', $maintenance);

        return view('admin.site.maintenance', compact('pageTitle', 'maintenance'));
    }

    public function updateMaintenance()
    {
        $this->validate(request(), [
            'heading' => 'required',
            'details' => 'required',
        ]);

        $setting                   = bs();
        $setting->site_maintenance = request('status') ? ManageStatus::ACTIVE : ManageStatus::INACTIVE;
        $setting->save();

        $maintenance = SiteData::where('data_key', 'maintenance.data')->first();

        $this->authorize('updateMaintenanceSettings', $maintenance);

        $maintenance->data_info = [
            'heading' => request('heading'),
            'details' => request('details'),
        ];
        $maintenance->save();

        $toast[] = ['success', 'Maintenance info updated successfully'];

        return back()->with('toasts', $toast);
    }

    public function kyc()
    {
        $pageTitle = 'Know Your Customer Settings';
        $form      = Form::where('act', 'kyc')->first();

        $this->authorize('viewAny', $form);

        $formHeading = 'KYC Form Data';

        return view('admin.settings.kyc', compact('pageTitle', 'form', 'formHeading'));
    }

    public function updateKYC()
    {
        $formProcessor       = new FormProcessor();
        $generatorValidation = $formProcessor->generatorValidation();

        $this->validate(request(), $generatorValidation['rules'], $generatorValidation['messages']);

        $form = Form::where('act', 'kyc')->first();

        $this->authorize('update', $form);

        $isUpdate = (bool)$form;

        try {
            $formProcessor->generate('kyc', $isUpdate);
        } catch (Exception $exception) {
            $toast[] = ['error', $exception->getMessage()];

            return back()->with('toasts', $toast);
        }

        $toast[] = ['success', 'KYC data has been successfully updated.'];

        return back()->with('toasts', $toast);
    }

    function cronjobIndex()
    {
        $settings = bs();

        $this->authorize('viewCronSettings', $settings);

        $pageTitle = 'Cronjob Setting';
        return view('admin.page.cronjob', compact('pageTitle'));
    }

    public function clearCache()
    {
        Artisan::call('optimize:clear');

        $toast[] = ['success', 'Cache has been successfully cleared.'];

        return back()->with('toasts', $toast);
    }
}
