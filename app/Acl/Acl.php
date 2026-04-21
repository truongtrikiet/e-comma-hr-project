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
    
    const ROLE_HR = 'HR';

    const ROLE_STUDENT = 'Student';

    const PERMISSION_ASSIGNEE = 'Assign Role';

    const PERMISSION_VIEW_MENU_DASHBOARD = 'View Dashboard Menu';

    const PERMISSION_VIEW_MENU_SUPER_ADMIN = 'View Super Admin Menu';

    const PERMISSION_VIEW_MENU_ADMIN = 'View Admin Menu';

    const PERMISSION_VIEW_MENU_STAFF = 'View Staff Menu';

    const PERMISSION_VIEW_MENU_TEACHER = 'View Teacher Menu';

    const PERMISSION_VIEW_MENU_HR = 'View HR Menu';

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

    const PERMISSION_SCHOOL_WORKING_CALENDAR_LIST = 'School Working Calendar List';

    const PERMISSION_SCHOOL_WORKING_CALENDAR_ADD = 'Add School Working Calendar';

    const PERMISSION_SCHOOL_WORKING_CALENDAR_EDIT = 'Edit School Working Calendar';

    const PERMISSION_SCHOOL_WORKING_CALENDAR_DELETE = 'Delete School Working Calendar';

    const PERMISSION_CONTRACT_SETTINGS_EDIT = 'Edit Contract Settings';

    const PERMISSION_CONTRACT_LIST = 'Contract List';

    const PERMISSION_CONTRACT_ADD = 'Add Contract';

    const PERMISSION_CONTRACT_EDIT = 'Edit Contract';

    const PERMISSION_CONTRACT_DELETE = 'Delete Contract';

    const PERMISSION_CONTRACT_DETAIL_PDF = 'Contract Detail PDF';
 
    const PERMISSION_CONTRACT_TYPE_LIST = 'Contract Type List';

    const PERMISSION_CONTRACT_TYPE_ADD = 'Add Contract Type';

    const PERMISSION_CONTRACT_TYPE_EDIT = 'Edit Contract Type';

    const PERMISSION_CONTRACT_TYPE_DELETE = 'Delete Contract Type';

    const PERMISSION_APPENDIX_CONTRACT_LIST = 'Appendix Contract List';

    const PERMISSION_APPENDIX_CONTRACT_ADD = 'Add Appendix Contract';

    const PERMISSION_APPENDIX_CONTRACT_EDIT = 'Edit Appendix Contract';

    const PERMISSION_APPENDIX_CONTRACT_DELETE = 'Delete Appendix Contract';

    const PERMISSION_CONTRACT_ATTRIBUTE_LIST = 'Contract Attribute List';

    const PERMISSION_CONTRACT_ATTRIBUTE_ADD = 'Add Contract Attribute';

    const PERMISSION_CONTRACT_ATTRIBUTE_EDIT = 'Edit Contract Attribute';

    const PERMISSION_CONTRACT_ATTRIBUTE_DELETE = 'Delete Contract Attribute';

    const PERMISSION_CANDIDATE_MANAGE = 'Manage Candidates';

    const PERMISSION_CANDIDATE_LIST = 'Candidate List';

    const PERMISSION_CANDIDATE_ADD = 'Add Candidate';

    const PERMISSION_CANDIDATE_EDIT = 'Edit Candidate';

    const PERMISSION_CANDIDATE_DELETE = 'Delete Candidate';

    const PERMISSION_SALARY_LIST = 'Salary List';

    const PERMISSION_SALARY_ADD = 'Add Salary';

    const PERMISSION_SALARY_EDIT = 'Edit Salary';

    const PERMISSION_SALARY_DELETE = 'Delete Salary';

    const PERMISSION_AI_PROFILE_LIST = 'AI Profile List';

    const PERMISSION_AI_PROFILE_ADD = 'Add AI Profile';

    const PERMISSION_AI_PROFILE_EDIT = 'Edit AI Profile';

    const PERMISSION_AI_PROFILE_DELETE = 'Delete AI Profile';

    const PERMISSION_CANDIDATE_SCREENING_LIST = 'Candidate Screening List';

    const PERMISSION_CANDIDATE_SCREENING_ADD = 'Add Candidate Screening';

    const PERMISSION_CANDIDATE_SCREENING_EDIT = 'Edit Candidate Screening';

    const PERMISSION_CANDIDATE_SCREENING_DELETE = 'Delete Candidate Screening';

    const PERMISSION_CANDIDATE_SCREENING_VIEW = 'View Candidate Screening';

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
