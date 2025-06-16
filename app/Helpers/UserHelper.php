<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class UserHelper
{
    public static function name($guard = 'admin')
    {
        return Auth::guard($guard)->check()
            ? Auth::guard($guard)->user()->name
            : 'Guest';
    }

    public static function photo($guard = 'admin')
    {
        $user = Auth::guard($guard)->user();

        if (!$user || !$user->profile_photo) {
            return asset('assets/images/avatars/avatar-2.png');
        }

        return asset($user->profile_photo);
    }
}
