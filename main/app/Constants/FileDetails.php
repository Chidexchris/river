<?php

namespace App\Constants;

class FileDetails
{
    function fileDetails(): array
    {
        return [
            'logoFavicon'  => [
                'path' => 'assets/universal/images/logoFavicon',
            ],
            'favicon'      => [
                'size' => '128x128',
            ],
            'seo'          => [
                'path' => 'assets/universal/images/seo',
                'size' => '1180x600',
            ],
            'adminProfile' => [
                'path' => 'assets/admin/images/profile',
                'size' => '200x200',
            ],
            'userProfile'  => [
                'path' => 'assets/user/images/profile',
                'size' => '350x350',
            ],
            'plugin'       => [
                'path' => 'assets/admin/images/plugin',
            ],
            'setting'      => [
                'path' => 'assets/admin/images/setting',
            ],
            'verify'       => [
                'path' => 'assets/verify',
            ],
            'staffProfile' => [
                'path' => 'assets/staff/images/profile',
                'size' => '200x200',
            ],
        ];
    }
}
