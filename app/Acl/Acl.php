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

    const PERMISSION_FURLOUGH_LIST = 'Furlough List';

    const PERMISSION_FURLOUGH_ADD = 'Add Furlough';

    const PERMISSION_FURLOUGH_EDIT = 'Edit Furlough';

    const PERMISSION_FURLOUGH_DELETE = 'Delete Furlough';

    const PERMISSION_FURLOUGH_TYPE_LIST = 'Furlough Type List';

    const PERMISSION_FURLOUGH_TYPE_ADD = 'Add Furlough Type';

    const PERMISSION_FURLOUGH_TYPE_EDIT = 'Edit Furlough Type';

    const PERMISSION_FURLOUGH_TYPE_DELETE = 'Delete Furlough Type';

    const PERMISSION_FURLOUGH_SHOW = 'Show Furlough';

    const PERMISSION_EMPLOYEE_TYPE_LIST = 'Employee Type List';

    const PERMISSION_EMPLOYEE_TYPE_ADD = 'Add Employee Type';

    const PERMISSION_EMPLOYEE_TYPE_EDIT = 'Edit Employee Type';

    const PERMISSION_EMPLOYEE_TYPE_DELETE = 'Delete Employee Type';

    const PERMISSION_HOLIDAY_SCHEDULE_LIST = 'Holiday Schedule List';

    const PERMISSION_HOLIDAY_SCHEDULE_ADD = 'Add Holiday Schedule';

    const PERMISSION_HOLIDAY_SCHEDULE_EDIT = 'Edit Holiday Schedule';

    const PERMISSION_HOLIDAY_SCHEDULE_DELETE = 'Delete Holiday Schedule';

    const PERMISSION_HOLIDAY_SCHEDULE_SHOW = 'Show Holiday Schedule';

    const PERMISSION_FURLOUGH_POLICY_TEMPLATE_LIST = 'Furlough Policy Template List';

    const PERMISSION_FURLOUGH_POLICY_TEMPLATE_ADD = 'Add Furlough Policy Template';

    const PERMISSION_FURLOUGH_POLICY_TEMPLATE_EDIT = 'Edit Furlough Policy Template';

    const PERMISSION_FURLOUGH_POLICY_TEMPLATE_DELETE = 'Delete Furlough Policy Template';

    const PERMISSION_FURLOUGH_POLICY_LIST = 'Furlough Policy List';

    const PERMISSION_FURLOUGH_POLICY_ADD = 'Add Furlough Policy';

    const PERMISSION_FURLOUGH_POLICY_EDIT = 'Edit Furlough Policy';

    const PERMISSION_FURLOUGH_POLICY_DELETE = 'Delete Furlough Policy';

    const PERMISSION_FURLOUGH_BALANCE_LIST = 'Furlough Balance List';

    const PERMISSION_FURLOUGH_BALANCE_ADD = 'Add Furlough Balance';

    const PERMISSION_FURLOUGH_BALANCE_EDIT = 'Edit Furlough Balance';

    const PERMISSION_FURLOUGH_BALANCE_DELETE = 'Delete Furlough Balance';

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
