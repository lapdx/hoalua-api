<?php

namespace App\Services;


use App\Models\HrmContract;
use Illuminate\Support\Facades\DB;

class ContractService extends BaseService {
    const MODEL = HrmContract::class;

    public function find($filter) {
        $query = parent::query($filter);
        if (array_key_exists("staff_status", $filter)) {
            $query->leftJoin("hrm_staff", "hrm_staff.id", "=", "hrm_contract.staff_id");
            $query->where("hrm_staff.status", "=", $filter["staff_status"]);
        }
        if (array_key_exists("next_time_increase_salary_from", $filter)) {
            $query->where("hrm_contract.next_time_increase_salary", ">=", $filter["next_time_increase_salary_from"]);
        }
        if (array_key_exists("next_time_increase_salary_to", $filter)) {
            $query->where("hrm_contract.next_time_increase_salary", "<=", $filter["next_time_increase_salary_to"]);
        }
        if (array_key_exists("not_increase_salary", $filter)) {
            $query->where("hrm_contract.not_increase_salary", "=", $filter["not_increase_salary"]);
        }
        if (array_key_exists("is_recent", $filter) && $filter["is_recent"]) {
            $query->whereRaw("(hrm_contract.staff_id,hrm_contract.id) in (select staff_id,max(id) from hrm_contract GROUP BY staff_id)");
        }
        return $query->get();
    }

}
