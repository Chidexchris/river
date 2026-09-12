<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteData;
use Exception;
use HTMLPurifier;
use Illuminate\Validation\Rules\File;
use Symfony\Component\HttpFoundation\Response;

class SiteController extends Controller
{
    function themes()
    {
        $this->authorize('viewThemeSettings', SiteData::class);

        $pageTitle  = 'Themes';
        $themePaths = array_filter(glob('main/resources/views/themes/*'), 'is_dir');
        $themes     = [];

        foreach ($themePaths as $key => $theme) {
            $arr                   = explode('/', $theme);
            $themeName             = end($arr);
            $themes[$key]['name']  = $themeName;
            $themes[$key]['image'] = asset($theme) . '/' . $themeName . '.jpg';
        }

        return view('admin.site.themes', compact('pageTitle', 'themes'));
    }

    function makeActive()
    {
        $this->authorize('updateThemeSettings', SiteData::class);

        $setting               = bs();
        $setting->active_theme = request('name');
        $setting->save();

        $toast[] = ['success', strtoupper(request('name')) . ' theme successfully activated'];

        return back()->with('toasts', $toast);
    }

    function sections($key)
    {
        $this->authorize('viewHomePageSections', SiteData::class);

        $pageSections = getPageSections();

        if (!$pageSections || !property_exists($pageSections, $key)) abort(Response::HTTP_NOT_FOUND);

        $section   = $pageSections->$key;
        $content   = SiteData::where('data_key', $key . '.content')->first();
        $elements  = SiteData::where('data_key', $key . '.element')->orderByDesc('id')->get();
        $pageTitle = $section->name;

        return view('admin.site.index', compact('section', 'content', 'elements', 'key', 'pageTitle'));
    }

    function content($key)
    {
        $this->authorize('updateHomePageSections', SiteData::class);

        $purifier          = new HTMLPurifier();
        $valInputs         = request()->except('_token', 'image_input', 'key', 'status', 'type', 'id');
        $inputContentValue = [];

        foreach ($valInputs as $keyName => $input) {
            if (gettype($input) == 'array') {
                $inputContentValue[$keyName] = $input;
                continue;
            }

            $inputContentValue[$keyName] = htmlspecialchars_decode($purifier->purify($input));
        }

        $type = request('type');

        if (!$type) abort(Response::HTTP_NOT_FOUND);

        $pageSections = getPageSections();
        $imgJson      = ($pageSections && property_exists($pageSections, $key) && isset($pageSections->$key->$type->images))
            ? $pageSections->$key->$type->images
            : null;

        $validationRule    = [];
        $validationMessage = [];

        foreach (request()->except('_token', 'video') as $inputField => $val) {
            if ($inputField == 'has_image' && $imgJson) {
                foreach ($imgJson as $imgValKey => $imgJsonVal) {
                    $validationRule['image_input.' . $imgValKey]               = ['nullable', 'image', File::types(['png', 'jpg', 'jpeg'])];
                    $validationMessage['image_input.' . $imgValKey . '.image'] = keyToTitle($imgValKey) . ' must be an image';
                    $validationMessage['image_input.' . $imgValKey . '.mimes'] = keyToTitle($imgValKey) . ' file type not supported';
                }

                continue;
            } elseif ($inputField == 'seo_image') {
                $validationRule['image_input'] = ['nullable', 'image', File::types(['png', 'jpg', 'jpeg'])];

                continue;
            }

            $validationRule[$inputField] = 'required';
        }

        request()->validate($validationRule, $validationMessage, ['image_input' => 'image']);

        if (request('id')) {
            $content = SiteData::findOrFail(request('id'));
        } else {
            $content = SiteData::where('data_key', $key . '.' . request('type'))->first();

            if (!$content || request('type') == 'element') {
                $content           = new SiteData();
                $content->data_key = $key . '.' . request('type');
                $content->save();
            }
        }

        if ($type == 'data') {
            $inputContentValue['image'] = ($content->data_info && isset($content->data_info->image)) ? $content->data_info->image : null;

            if (request()->hasFile('image_input')) {
                try {
                    $inputContentValue['image'] = fileUploader(request()->file('image_input'), getFilePath('seo'), getFileSize('seo'), $inputContentValue['image']);
                } catch (Exception) {
                    $toast[] = ['error', 'Image upload failed'];

                    return back()->with('toasts', $toast);
                }
            }
        } else {
            if ($imgJson) {
                foreach ($imgJson as $imgKey => $imgValue) {
                    $imgData = request()->image_input[$imgKey] ?? null;

                    if ($imgData && is_file($imgData)) {
                        try {
                            $oldImg = ($content->data_info && isset($content->data_info->$imgKey)) ? $content->data_info->$imgKey : null;

                            $inputContentValue[$imgKey] = $this->storeImage($imgJson, $type, $key, $imgData, $imgKey, $oldImg);
                        } catch (Exception) {
                            $toast[] = ['error', 'Image upload failed'];

                            return back()->with('toasts', $toast);
                        }
                    } else if (isset($content->data_info->$imgKey)) {
                        $inputContentValue[$imgKey] = $content->data_info->$imgKey;
                    }
                }
            }
        }

        $content->data_info = $inputContentValue;
        $content->save();

        $toast[] = ['success', 'Content successfully updated'];

        return back()->with('toasts', $toast);
    }

    function element($key, $id = null)
    {
        $this->authorize('viewElementContent', SiteData::class);

        $pageSections = getPageSections();

        if (!$pageSections || !property_exists($pageSections, $key)) abort(Response::HTTP_NOT_FOUND);

        $section = $pageSections->$key;

        unset($section->element->modal);

        $pageTitle = $section->name . ' Items';

        if ($id) {
            $data = SiteData::findOrFail($id);

            return view('admin.site.element', compact('section', 'key', 'pageTitle', 'data'));
        }

        return view('admin.site.element', compact('section', 'key', 'pageTitle'));
    }

    function remove($id)
    {
        $siteData = SiteData::findOrFail($id);

        $this->authorize('removeElementContent', $siteData);

        $dataKeyParts = $siteData->data_key ? explode('.', $siteData->data_key) : [];
        $key          = $dataKeyParts[0] ?? null;
        $type         = $dataKeyParts[1] ?? null;

        if (in_array($type, ['element', 'content'])) {
            $path         = activeTheme(true) . 'images/site/' . $key;
            $pageSections = getPageSections();

            $imgJson = ($pageSections && isset($pageSections->$key) && isset($pageSections->$key->$type) && isset($pageSections->$key->$type->images))
                ? $pageSections->$key->$type->images
                : null;

            if ($imgJson && $siteData->data_info) {
                foreach ($imgJson as $imgKey => $imgValue) {
                    $imagePath = isset($siteData->data_info->$imgKey) ? $siteData->data_info->$imgKey : null;

                    if ($imagePath) {
                        fileManager()->removeFile($path . '/' . $imagePath);
                        fileManager()->removeFile($path . '/thumb_' . $imagePath);
                    }
                }
            }
        }

        $siteData->delete();

        $toast[] = ['success', 'Content successfully removed'];

        return back()->with('toasts', $toast);
    }

    protected function storeImage($imgJson, $type, $key, $image, $imgKey, $oldImage = null)
    {
        $path = activeTheme(true) . 'images/site/' . $key;

        if ($type == 'element' || $type == 'content') {
            $size  = $imgJson->$imgKey->size ?? null;
            $thumb = $imgJson->$imgKey->thumb ?? null;
        } else {
            $path  = getFilePath($key);
            $size  = getFileSize($key);
            $thumb = fileManager()->$key()->thumb ?? null;
        }

        return fileUploader($image, $path, $size, $oldImage, $thumb);
    }
}
