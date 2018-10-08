<?php

namespace App\Services;


use App\Models\HrmVacation;
use Illuminate\Support\Facades\DB;

class VacationService extends BaseService {
    const MODEL = HrmVacation::class;
    public function find($filter) {
        $query = parent::query($filter);
        if (array_key_exists("from_time", $filter)) {
            $query->where("from_time", ">=", $filter["from_time"]);
        }
        if (array_key_exists("to_time", $filter)) {
            $query->where("to_time", "<=", $filter["to_time"]);
        }
        return $query->get();
    }

}
