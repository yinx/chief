<?php

namespace Chief\Authorization;

use Illuminate\Support\Collection;

class AuthorizationDefaults
{
    public static function roles(): Collection
    {
        return collect([

            // full access, even to application logic stuff
            'developer' => ['role', 'permission', 'user', 'page'],

            // Manages everything, including users
            'admin' => ['user', 'page'],

            // Writes and edits content
            'author' => ['page'],
        ]);
    }

    public static function permissions(): Collection
    {
        return collect([
            'view-permission',
            'create-permission',
            'update-permission',
            'delete-permission',

            'view-role',
            'create-role',
            'update-role',
            'delete-role',

            'view-user',
            'create-user',
            'update-user',
            'delete-user',

            'view-page',
            'create-page',
            'update-page',
            'delete-page',
        ]);
    }
}