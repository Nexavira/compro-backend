<?php

use Illuminate\Support\Facades\Auth;

if (! function_exists('can_access')) {
    function can_access(string $permissionCode): bool
    {
        /** @var User $user */
        $user = Auth::user();

        return $user?->can($permissionCode) ?? false;
    }
}