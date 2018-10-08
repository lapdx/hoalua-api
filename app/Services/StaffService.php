<?php

namespace App\Services;


use App\Models\HrmStaff;
use Illuminate\Support\Facades\DB;

class StaffService extends BaseService {
    const MODEL = HrmStaff::class;
    public function find($filter) {
        $query = parent::query($filter);
        $query->with("departments", "managements");
        if (array_key_exists("ids", $filter)) {
            $query->whereIn("id", $filter["ids"]);
        }
        if (array_key_exists('status', $filter)) {
            $query->where('status', '=', $filter['status']);
        }
        if (array_key_exists('statuses', $filter)) {
            $query->whereIn('status', $filter['statuses']);
        }
        if (array_key_exists("birthday_month", $filter) && is_numeric($filter["birthday_month"])) {
            $query->whereRaw("month(birthday) = " . $filter["birthday_month"]);
        }
        if (array_key_exists("estimated_contract_time_from", $filter)) {
            $query->where("estimated_contract_time", ">=", $filter["estimated_contract_time_from"]);
        }
        if (array_key_exists("estimated_contract_time_to", $filter)) {
            $query->where("estimated_contract_time", "<=", $filter["estimated_contract_time_to"]);
        }
        return $query->get();
    }

    public static function getUserByStaffId($staffId, $staffStatus = NULL) {
        $query = DB::table("chi_user as u")->join("hrm_staff as staff", "staff.code", "=", "u.code");
        if ($staffStatus != NULL) {
            $query->where("staff.status", "=", $staffStatus);
        }
        return DB::table("chi_user as u")->join("hrm_staff as staff", "staff.code", "=", "u.code")
                        ->where("staff.id", "=", $staffId)->first(array("u.*"));
    }

    public static function listUsersByDepartmentId($departmentId, $staffStatus = NULL) {
        $query = DB::table("chi_user as u")->join("hrm_staff as staff", "staff.code", "=", "u.code");
        if ($staffStatus != NULL) {
            $query->where("staff.status", "=", $staffStatus);
        }
        return $query->join("hrm_staff_n_department as snd", "snd.staff_id", "=", "staff.id")
                        ->where("snd.department_id", "=", $departmentId)->get(array("u.*"));
    }

    public static function listAllUsers($staffStatus = NULL) {
        $query = DB::table("chi_user as u")->join("hrm_staff as staff", "staff.code", "=", "u.code");
        if ($staffStatus != NULL) {
            $query->where("staff.status", "=", $staffStatus);
        }
        return $query->get(array("u.*"));
    }

}
