<?php

namespace App\Policies;

use App\Models\Admin;

class LanguagePolicy
{
    public function viewAny(Admin $admin): bool
    {
        return $admin->can('view all languages');
    }

    public function viewKeywords(Admin $admin): bool
    {
        return $admin->can('view all keywords');
    }

    public function createOrUpdate(Admin $admin): bool
    {
        return $admin->can('create or update language');
    }

    public function changeStatus(Admin $admin): bool
    {
        return $admin->can('change language status');
    }

    public function delete(Admin $admin): bool
    {
        return $admin->can('delete language');
    }

    public function viewTranslatedKeywords(Admin $admin): bool
    {
        return $admin->can('view translated keywords');
    }

    public function importKeywords(Admin $admin): bool
    {
        return $admin->can('import keywords');
    }

    public function addKeyword(Admin $admin): bool
    {
        return $admin->can('add keyword');
    }

    public function updateKeyword(Admin $admin): bool
    {
        return $admin->can('update keyword');
    }

    public function deleteKeyword(Admin $admin): bool
    {
        return $admin->can('delete keyword');
    }
}
