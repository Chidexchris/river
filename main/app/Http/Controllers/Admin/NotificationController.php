<?php

namespace App\Http\Controllers\Admin;

use App\Notify\Sms;
use App\Constants\ManageStatus;
use App\Http\Controllers\Controller;
use App\Models\NotificationTemplate;

class NotificationController extends Controller
{
    function universal()
    {
        $this->authorize('viewUniversalTemplate', NotificationTemplate::class);

        $pageTitle = 'Universal Template for Notification';

        return view('admin.notification.universalTemplate', compact('pageTitle'));
    }

    function universalUpdate()
    {
        $this->authorize('updateUniversalTemplate', NotificationTemplate::class);

        $this->validate(request(), [
            'email_from'     => 'required|email|string|max:40',
            'sms_from'       => 'required|string|max:40',
            'email_template' => 'required',
            'sms_body'       => 'required',
        ]);

        $setting                 = bs();
        $setting->email_from     = request('email_from');
        $setting->email_template = request('email_template');
        $setting->sms_from       = request('sms_from');
        $setting->sms_body       = request('sms_body');
        $setting->save();

        $toast[] = ['success', 'Universal notification settings update successfully'];

        return back()->with('toasts', $toast);
    }

    function templates()
    {
        $this->authorize('viewAny', NotificationTemplate::class);

        $pageTitle = 'Notification Templates';
        $templates = NotificationTemplate::orderBy('name')->get();

        return view('admin.notification.templates', compact('pageTitle', 'templates'));
    }

    function templateEdit(int $id)
    {
        $template = NotificationTemplate::findOrFail($id);

        $this->authorize('edit', $template);

        $pageTitle = $template->name;

        return view('admin.notification.edit', compact('pageTitle', 'template'));
    }

    function templateUpdate($id)
    {
        $this->validate(request(), [
            'subject'    => 'required|string|max:255',
            'email_body' => 'required',
            'sms_body'   => 'required',
        ]);

        $template = NotificationTemplate::findOrFail($id);

        $this->authorize('update', $template);

        $template->subj         = request('subject');
        $template->email_body   = request('email_body');
        $template->email_status = request('email_status') ? ManageStatus::ACTIVE : ManageStatus::INACTIVE;
        $template->sms_body     = request('sms_body');
        $template->sms_status   = request('sms_status') ? ManageStatus::ACTIVE : ManageStatus::INACTIVE;
        $template->save();

        $toast[] = ['success', 'Notification template updated successfully'];

        return back()->with('toasts', $toast);
    }

    function email()
    {
        $this->authorize('viewEmailConfiguration', NotificationTemplate::class);

        $pageTitle = 'Email Notification Settings';

        return view('admin.notification.email', compact('pageTitle'));
    }

    function emailUpdate()
    {
        $this->authorize('updateEmailConfiguration', NotificationTemplate::class);

        $this->validate(request(), [
            'email_method' => 'required|in:php,smtp,sendgrid,mailjet',
            'host'         => 'required_if:email_method,smtp',
            'port'         => 'required_if:email_method,smtp',
            'username'     => 'required_if:email_method,smtp',
            'password'     => 'required_if:email_method,smtp',
            'enc'          => 'required_if:email_method,smtp',
            'appkey'       => 'required_if:email_method,sendgrid',
            'public_key'   => 'required_if:email_method,mailjet',
            'secret_key'   => 'required_if:email_method,mailjet',
        ], [
            'host.required_if'       => ':attribute is required for SMTP configuration',
            'port.required_if'       => ':attribute is required for SMTP configuration',
            'username.required_if'   => ':attribute is required for SMTP configuration',
            'password.required_if'   => ':attribute is required for SMTP configuration',
            'enc.required_if'        => ':attribute is required for SMTP configuration',
            'appkey.required_if'     => ':attribute is required for SendGrid configuration',
            'public_key.required_if' => ':attribute is required for Mailjet configuration',
            'secret_key.required_if' => ':attribute is required for Mailjet configuration',
        ]);

        $data = null;

        if (request('email_method') == 'php') {
            $data['name'] = 'php';
        } else if (request('email_method') == 'smtp') {
            request()->merge([
                'name' => 'smtp'
            ]);

            $data = request()->only('name', 'host', 'port', 'enc', 'username', 'password', 'driver');
        } else if (request('email_method') == 'sendgrid') {
            request()->merge([
                'name' => 'sendgrid'
            ]);

            $data = request()->only('name', 'appkey');
        } else if (request('email_method') == 'mailjet') {
            request()->merge([
                'name' => 'mailjet'
            ]);

            $data = request()->only('name', 'public_key', 'secret_key');
        }

        $setting              = bs();
        $setting->mail_config = $data;
        $setting->save();

        $toast[] = ['success', 'Email settings updated success'];

        return back()->with('toasts', $toast);
    }

    function testEmail()
    {
        $this->authorize('sendTestEmail', NotificationTemplate::class);

        $this->validate(request(), [
            'email' => 'required|email',
        ]);

        $emailAddress = request('email');
        $setting      = bs();
        $config       = $setting->mail_config;
        $receiverName = explode('@', request()->email)[0];
        $subject      = strtoupper($config->name) . ' Configuration Success';
        $message      = 'Your email notification setting is configured successfully for ' . $setting->site_name;

        if ($setting->ea) {
            $user = [
                'username' => $emailAddress,
                'email'    => $emailAddress,
                'fullname' => $receiverName,
            ];

            notify($user, 'DEFAULT', [
                'subject' => $subject,
                'message' => $message,
            ], ['email']);
        } else {
            $toast[] = ['info', 'Please enable from basic settings'];
            $toast[] = ['error', 'Your email notification is disabled'];

            return back()->with('toasts', $toast);
        }

        if (session('mail_error')) {
            $toast[] = ['error', session('mail_error')];
        } else {
            $toast[] = ['success', "Email sent to $emailAddress successfully"];
        }

        return back()->with('toasts', $toast);
    }

    function sms()
    {
        $this->authorize('viewSmsConfiguration', NotificationTemplate::class);

        $pageTitle = 'SMS Notification Settings';

        return view('admin.notification.sms', compact('pageTitle'));
    }

    function smsUpdate()
    {
        $this->authorize('updateSmsConfiguration', NotificationTemplate::class);

        $this->validate(request(), [
            'sms_method'        => 'required|in:nexmo,twilio,custom',
            'nexmo_api_key'     => 'required_if:sms_method,nexmo',
            'nexmo_api_secret'  => 'required_if:sms_method,nexmo',
            'account_sid'       => 'required_if:sms_method,twilio',
            'auth_token'        => 'required_if:sms_method,twilio',
            'from'              => 'required_if:sms_method,twilio',
            'custom_api_method' => 'required_if:sms_method,custom|in:get,post',
            'custom_api_url'    => 'required_if:sms_method,custom',
        ]);

        $data = [
            'name'   => request('sms_method'),
            'nexmo'  => [
                'api_key'    => request('nexmo_api_key'),
                'api_secret' => request('nexmo_api_secret'),
            ],
            'twilio' => [
                'account_sid' => request('account_sid'),
                'auth_token'  => request('auth_token'),
                'from'        => request('from'),
            ],
            'custom' => [
                'method'  => request('custom_api_method'),
                'url'     => request('custom_api_url'),
                'headers' => [
                    'name'  => request('custom_header_name') ?? [],
                    'value' => request('custom_header_value') ?? [],
                ],
                'body'    => [
                    'name'  => request('custom_body_name') ?? [],
                    'value' => request('custom_body_value') ?? [],
                ],
            ],
        ];

        $setting             = bs();
        $setting->sms_config = $data;
        $setting->save();

        $toast[] = ['success', 'SMS settings updated success'];

        return back()->with('toasts', $toast);
    }

    function testSMS()
    {
        $this->authorize('sendTestSms', NotificationTemplate::class);

        $this->validate(request(), [
            'mobile' => 'required'
        ]);

        $setting = bs();

        if ($setting->sa) {
            $sendSms               = new Sms;
            $sendSms->mobile       = request('mobile');
            $sendSms->receiverName = ' ';
            $sendSms->message      = "Your sms notification setting is configured successfully for $setting->site_name";
            $sendSms->subject      = ' ';
            $sendSms->send();
        } else {
            $toast[] = ['error', 'Your sms notification is disabled'];
            $toast[] = ['info', 'Please enable from basic settings'];

            return back()->with('toasts', $toast);
        }

        if (session('sms_error')) {
            $toast[] = ['error', session('sms_error')];
        } else {
            $toast[] = ['success', 'SMS sent to ' . request('mobile') . ' successfully'];
        }

        return back()->with('toasts', $toast);
    }
}
