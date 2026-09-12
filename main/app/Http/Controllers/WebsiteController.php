<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Contact;
use App\Models\DepositPensionSchemePlan;
use App\Models\FixedDepositSchemePlan;
use App\Models\Language;
use App\Models\LoanPlan;
use App\Models\SiteData;
use App\Constants\ManageStatus;
use App\Models\Subscriber;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;

class WebsiteController extends Controller
{
    public function home()
    {
        $pageTitle = 'Home';
        $dpsPlans  = DepositPensionSchemePlan::active()->get();
        $fdsPlans  = FixedDepositSchemePlan::active()->get();
        $loanPlans = LoanPlan::active()->get();

        return view("{$this->activeTheme}page.home", compact('pageTitle', 'dpsPlans', 'fdsPlans', 'loanPlans'));
    }

    public function storeSubscriber()
    {
        $validator = Validator::make(request()->all(), [
            'email' => 'required|email|max:40|unique:subscribers',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ]);
        }

        $subscriber        = new Subscriber();
        $subscriber->email = request('email');
        $subscriber->save();

        return response()->json([
            'success' => 'You have successfully subscribed into our system',
        ]);
    }

    public function aboutUs()
    {
        $pageTitle = 'About Us';

        return view("{$this->activeTheme}page.aboutUs", compact('pageTitle'));
    }

    public function branches()
    {
        $pageTitle = 'Our Branches';
        $branches  = Branch::active()->orderBy('name')->paginate(getPaginate());

        return view("{$this->activeTheme}page.branches", compact('pageTitle', 'branches'));
    }

    public function faq()
    {
        $pageTitle = 'FAQ';

        return view("{$this->activeTheme}page.faq", compact('pageTitle'));
    }

    public function contact()
    {
        $pageTitle       = 'Contact';
        $user            = auth('web')->user();
        $contactContent  = getSiteData('contact_us.content', true);
        $contactElements = getSiteData('contact_us.element', false, null, true);

        return view("{$this->activeTheme}page.contact", compact('pageTitle', 'user', 'contactContent', 'contactElements'));
    }

    public function contactStore()
    {
        $this->validate(request(), [
            'name'    => 'required|string|max:40',
            'email'   => 'required|string|max:40',
            'subject' => 'required|string|max:255',
            'message' => 'required',
        ]);

        $user         = auth('web')->user();
        $email        = $user ? $user->email : request('email');
        $contactCheck = Contact::where('email', $email)->where('status', ManageStatus::NO)->first();

        if ($contactCheck) {
            $toast[] = ['warning', 'There is an existing contact on our record, kindly wait for the admin\'s response'];

            return back()->with('toasts', $toast);
        }

        $contact          = new Contact();
        $contact->name    = $user ? $user->fullname : request('name');
        $contact->email   = $email;
        $contact->subject = request('subject');
        $contact->message = request('message');
        $contact->save();

        $toast[] = ['success', 'We have received your message, kindly wait for the admin\'s response'];

        return back()->with('toasts', $toast);
    }

    public function changeLanguage($lang = null)
    {
        $language = Language::where('code', $lang)->first();

        if (!$language) $lang = 'en';

        session()->put('lang', $lang);

        return back();
    }

    public function cookieAccept()
    {
        Cookie::queue('gdpr_cookie', bs('site_name'), 43200);
    }

    public function cookiePolicy()
    {
        $pageTitle = 'Cookie Policy';
        $cookie    = SiteData::where('data_key', 'cookie.data')->first();

        return view("{$this->activeTheme}page.cookie", compact('pageTitle', 'cookie'));
    }

    public function maintenance()
    {
        if (bs('site_maintenance') == ManageStatus::INACTIVE) return to_route('home');

        $maintenance = SiteData::where('data_key', 'maintenance.data')->first();
        $pageTitle   = $maintenance->data_info->heading;

        return view("{$this->activeTheme}page.maintenance", compact('pageTitle', 'maintenance'));
    }

    public function policyPages(int $id, string $slug)
    {
        $policy    = SiteData::where('id', $id)->where('data_key', 'policy_pages.element')->firstOrFail();
        $pageTitle = $policy->data_info->title;

        return view("{$this->activeTheme}page.policy", compact('policy', 'pageTitle'));
    }

    public function placeholderImage($size = null)
    {
        $imgWidth  = explode('x', $size)[0];
        $imgHeight = explode('x', $size)[1];
        $text      = $imgWidth . '×' . $imgHeight;
        $fontFile  = realpath('assets/font/RobotoMono-Regular.ttf');
        $fontSize  = round(($imgWidth - 50) / 8);

        if ($fontSize <= 9) $fontSize = 9;

        if ($imgHeight < 100 && $fontSize > 30) $fontSize = 30;

        $image     = imagecreatetruecolor($imgWidth, $imgHeight);
        $colorFill = imagecolorallocate($image, 100, 100, 100);
        $bgFill    = imagecolorallocate($image, 175, 175, 175);

        imagefill($image, 0, 0, $bgFill);

        $textBox    = imagettfbbox($fontSize, 0, $fontFile, $text);
        $textWidth  = abs($textBox[4] - $textBox[0]);
        $textHeight = abs($textBox[5] - $textBox[1]);
        $textX      = ($imgWidth - $textWidth) / 2;
        $textY      = ($imgHeight + $textHeight) / 2;

        header('Content-Type: image/jpeg');
        imagettftext($image, $fontSize, 0, $textX, $textY, $colorFill, $fontFile, $text);
        imagejpeg($image);
        imagedestroy($image);
    }
}
