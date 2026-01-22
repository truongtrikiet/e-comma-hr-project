<?php

/**
 * File Acl.php
 *
 * @version 1.0
 */

namespace App\Acl;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

final class Acl
{
    const ROLE_SUPER_ADMIN = 'Super Admin';

    const ROLE_ADMIN = 'Admin';

    const ROLE_TEACHER = 'Teacher';

    const ROLE_STAFF = 'Staff';

    const ROLE_STUDENT = 'Student';

    const PERMISSION_ASSIGNEE = 'Assign Role';

    const PERMISSION_VIEW_MENU_DASHBOARD = 'View Dashboard Menu';

    const PERMISSION_VIEW_MENU_SUPER_ADMIN = 'View Super Admin Menu';

    const PERMISSION_VIEW_MENU_ADMIN = 'View Admin Menu';

    const PERMISSION_VIEW_MENU_STAFF = 'View Staff Menu';

    const PERMISSION_VIEW_MENU_TEACHER = 'View Teacher Menu';

    const PERMISSION_PERMISSION_MANAGE = 'Manage Permissions';

    const PERMISSION_VIEW_MENU_ACCOUNT = 'View Account Menu';

    const PERMISSION_ROLE_LIST = 'Role List';

    const PERMISSION_ROLE_ADD = 'Add Role';

    const PERMISSION_ROLE_EDIT = 'Edit Role';

    const PERMISSION_ROLE_DELETE = 'Delete Role';

    const PERMISSION_USER_MANAGE = 'Manage Users';

    const PERMISSION_USER_LIST = 'User List';

    const PERMISSION_USER_ADD = 'Add User';

    const PERMISSION_USER_EDIT = 'Edit User';

    const PERMISSION_USER_DELETE = 'Delete User';

    const PERMISSION_USER_VIEW = 'View User';

    const PERMISSION_SCHOOL_LIST = 'School List';

    const PERMISSION_SCHOOL_ADD = 'Add School';

    const PERMISSION_SCHOOL_EDIT = 'Edit School';

    const PERMISSION_SCHOOL_DELETE = 'Delete School';

    const PERMISSION_DEPARTMENT_LIST = 'Department List';

    const PERMISSION_DEPARTMENT_ADD = 'Add Department';

    const PERMISSION_DEPARTMENT_EDIT = 'Edit Department';

    const PERMISSION_DEPARTMENT_DELETE = 'Delete Department';

    const PERMISSION_SUBJECT_LIST = 'Subject List';

    const PERMISSION_SUBJECT_ADD = 'Add Subject';

    const PERMISSION_SUBJECT_EDIT = 'Edit Subject';

    const PERMISSION_SUBJECT_DELETE = 'Delete Subject';

    const PERMISSION_POSITION_LIST = 'Position List';

    const PERMISSION_POSITION_ADD = 'Add Position';

    const PERMISSION_POSITION_EDIT = 'Edit Position';

    const PERMISSION_POSITION_DELETE = 'Delete Position';

    /**
     * @param  array  $exclusives Exclude some permissions from the list
     */
    public static function permissions(array $exclusives = []): array
    {
        try {
            $class = new \ReflectionClass(__CLASS__);
            $constants = $class->getConstants();
            $permissions = Arr::where($constants, function ($value, $key) use ($exclusives) {
                return ! in_array($value, $exclusives) && Str::startsWith($key, 'PERMISSION_');
            });

            return array_values($permissions);
        } catch (\ReflectionException $exception) {
            return [];
        }
    }

    public static function menuPermissions(): array
    {
        try {
            $class = new \ReflectionClass(__CLASS__);
            $constants = $class->getConstants();
            $permissions = Arr::where($constants, function ($value, $key) {
                return Str::startsWith($key, 'PERMISSION_VIEW_MENU_');
            });

            return array_values($permissions);
        } catch (\ReflectionException $exception) {
            return [];
        }
    }

    public static function roles(): array
    {
        try {
            $class = new \ReflectionClass(__CLASS__);
            $constants = $class->getConstants();
            $roles = Arr::where($constants, function ($value, $key) {
                return Str::startsWith($key, 'ROLE_');
            });

            return array_values($roles);
        } catch (\ReflectionException $exception) {
            return [];
        }
    }
}
