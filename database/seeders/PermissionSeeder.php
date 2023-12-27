<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::firstOrCreate(['name' => Permission::CAN_SEE_HOME_INSIGHTS]);
        Permission::firstOrCreate(['name' => Permission::CAN_SEE_MEMBER_JOBS]);
        Permission::firstOrCreate(['name' => Permission::CAN_CREATE_MEMBER_JOB]);
        Permission::firstOrCreate(['name' => Permission::CAN_EDIT_MEMBER_JOB]);
        Permission::firstOrCreate(['name' => Permission::CAN_DELETE_MEMBER_JOB]);
        Permission::firstOrCreate(['name' => Permission::CAN_SEE_MEMBER_PROMOTIONS]);
        Permission::firstOrCreate(['name' => Permission::CAN_CREATE_MEMBER_PROMOTION]);
        Permission::firstOrCreate(['name' => Permission::CAN_EDIT_MEMBER_PROMOTION]);
        Permission::firstOrCreate(['name' => Permission::CAN_DELETE_MEMBER_PROMOTION]);
        Permission::firstOrCreate(['name' => Permission::CAN_CREATE_MEMBER_PROMOTION_PENSION]);
        Permission::firstOrCreate(['name' => Permission::CAN_SEE_MEMBERS]);
        Permission::firstOrCreate(['name' => Permission::CAN_CREATE_MEMBER]);
        Permission::firstOrCreate(['name' => Permission::CAN_EDIT_MEMBER]);
        Permission::firstOrCreate(['name' => Permission::CAN_DELETE_MEMBER]);
        Permission::firstOrCreate(['name' => Permission::CAN_EXPORT_MEMBERS]);
        Permission::firstOrCreate(['name' => Permission::CAN_IMPORT_MEMBERS]);
        Permission::firstOrCreate(['name' => Permission::CAN_SEE_MEMBER_UNITS]);
        Permission::firstOrCreate(['name' => Permission::CAN_CREATE_MEMBER_UNIT]);
        Permission::firstOrCreate(['name' => Permission::CAN_EDIT_MEMBER_UNIT]);
        Permission::firstOrCreate(['name' => Permission::CAN_DELETE_MEMBER_UNIT]);
        Permission::firstOrCreate(['name' => Permission::CAN_CREATE_MEMBER_UNIT_JOB]);
        Permission::firstOrCreate(['name' => Permission::CAN_SEE_SAFE_ENTRIES]);
        Permission::firstOrCreate(['name' => Permission::CAN_CREATE_SAFE_ENTRY]);
        Permission::firstOrCreate(['name' => Permission::CAN_EDIT_SAFE_ENTRY]);
        Permission::firstOrCreate(['name' => Permission::CAN_DELETE_SAFE_ENTRY]);
        Permission::firstOrCreate(['name' => Permission::CAN_SEE_MEMBERS_WITH_LATE_PAYMENTS_REPORT]);
        Permission::firstOrCreate(['name' => Permission::CAN_EXPORT_MEMBERS_WITH_LATE_PAYMENTS_REPORT]);
        Permission::firstOrCreate(['name' => Permission::CAN_SEE_AGE_OF_HONORING_REPORT]);
        Permission::firstOrCreate(['name' => Permission::CAN_SEE_NCO_65_REPORTS]);
        Permission::firstOrCreate(['name' => Permission::CAN_SEE_IN_SERVICE_CO_MEMBERS_REPORT]);
        Permission::firstOrCreate(['name' => Permission::CAN_SEE_ON_PENSION_CO_MEMBERS_REPORT]);
        Permission::firstOrCreate(['name' => Permission::CAN_SEE_IN_SERVICE_NCO_MEMBERS_REPORT]);
        Permission::firstOrCreate(['name' => Permission::CAN_SEE_ON_PENSION_NCO_MEMBERS_REPORT]);
        Permission::firstOrCreate(['name' => Permission::CAN_SEE_MEMBERSHIPS]);
        Permission::firstOrCreate(['name' => Permission::CAN_CREATE_MEMBERSHIP]);
        Permission::firstOrCreate(['name' => Permission::CAN_EDIT_MEMBERSHIP]);
        Permission::firstOrCreate(['name' => Permission::CAN_DELETE_MEMBERSHIP]);
        Permission::firstOrCreate(['name' => Permission::CAN_UPLOAD_MEMBERSHIPS_SHEET]);
        Permission::firstOrCreate(['name' => Permission::CAN_SEE_MEMBER_WALLET_TRANSACTIONS]);
        Permission::firstOrCreate(['name' => Permission::CAN_SEE_AGE_FORMS]);
        Permission::firstOrCreate(['name' => Permission::CAN_CREATE_AGE_FORM]);
        Permission::firstOrCreate(['name' => Permission::CAN_EDIT_AGE_FORM]);
        Permission::firstOrCreate(['name' => Permission::CAN_DELETE_AGE_FORM]);
        Permission::firstOrCreate(['name' => Permission::CAN_SEE_DEATH_FORMS]);
        Permission::firstOrCreate(['name' => Permission::CAN_CREATE_DEATH_FORM]);
        Permission::firstOrCreate(['name' => Permission::CAN_EDIT_DEATH_FORM]);
        Permission::firstOrCreate(['name' => Permission::CAN_DELETE_DEATH_FORM]);
        Permission::firstOrCreate(['name' => Permission::CAN_SEE_DISABLED_FORMS]);
        Permission::firstOrCreate(['name' => Permission::CAN_CREATE_DISABLED_FORM]);
        Permission::firstOrCreate(['name' => Permission::CAN_EDIT_DISABLED_FORM]);
        Permission::firstOrCreate(['name' => Permission::CAN_DELETE_DISABLED_FORM]);
        Permission::firstOrCreate(['name' => Permission::CAN_SEE_FELLOWSHIP_GRANT_FORMS]);
        Permission::firstOrCreate(['name' => Permission::CAN_CREATE_FELLOWSHIP_GRANT_FORM]);
        Permission::firstOrCreate(['name' => Permission::CAN_EDIT_FELLOWSHIP_GRANT_FORM]);
        Permission::firstOrCreate(['name' => Permission::CAN_DELETE_FELLOWSHIP_GRANT_FORM]);
        Permission::firstOrCreate(['name' => Permission::CAN_SEE_MARRIAGE_FORMS]);
        Permission::firstOrCreate(['name' => Permission::CAN_CREATE_MARRIAGE_FORM]);
        Permission::firstOrCreate(['name' => Permission::CAN_EDIT_MARRIAGE_FORM]);
        Permission::firstOrCreate(['name' => Permission::CAN_DELETE_MARRIAGE_FORM]);
        Permission::firstOrCreate(['name' => Permission::CAN_SEE_PROJECT_CLOSURE_FORMS]);
        Permission::firstOrCreate(['name' => Permission::CAN_CREATE_PROJECT_CLOSURE_FORM]);
        Permission::firstOrCreate(['name' => Permission::CAN_EDIT_PROJECT_CLOSURE_FORM]);
        Permission::firstOrCreate(['name' => Permission::CAN_DELETE_PROJECT_CLOSURE_FORM]);
        Permission::firstOrCreate(['name' => Permission::CAN_SEE_RELATIVE_DEATH_FORMS]);
        Permission::firstOrCreate(['name' => Permission::CAN_CREATE_RELATIVE_DEATH_FORM]);
        Permission::firstOrCreate(['name' => Permission::CAN_EDIT_RELATIVE_DEATH_FORM]);
        Permission::firstOrCreate(['name' => Permission::CAN_DELETE_RELATIVE_DEATH_FORM]);
        Permission::firstOrCreate(['name' => Permission::CAN_SEE_REFUND_FORMS]);
        Permission::firstOrCreate(['name' => Permission::CAN_CREATE_REFUND_FORM]);
        Permission::firstOrCreate(['name' => Permission::CAN_EDIT_REFUND_FORM]);
        Permission::firstOrCreate(['name' => Permission::CAN_DELETE_REFUND_FORM]);
        Permission::firstOrCreate(['name' => Permission::CAN_ACCESS_SYSTEM_CORE_VALUES]);
        Permission::firstOrCreate(['name' => Permission::CAN_ACCESS_ACTIVITY_LOGS]);


    }
}
