<?php

namespace App\Http\Middleware;

use App\Models\HrmStaff;
use Closure;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->isMethod('get') && $request->is('api/hrm_vacation') && !$request->user->hasRole("hrm")) {
            // filter by staff_id and manager_id
            $staffIdFilters = [];
            $staffIdFilters[] = $request->user->staff->id;
            $staffs = HrmStaff::where('manager_id', '=', $request->user->staff->id)->get(['id']);
            foreach ($staffs as $staff) {
                $staffIdFilters[] = $staff->id;
            }
            $filters = $request->input('filters', '');
            $filters .= ',staff_id={';
            foreach ($staffIdFilters as $staffIdFilter) {
                $filters .= ($staffIdFilter . ';');
            }
            $filters .= '}';
            $request->merge(['filters' => $filters]);
        } else if ($request->isMethod('get') && $request->is('api/hrm_doc') && !$request->user->hasRole("hrm")) {
            // filter by department or department = -1
            $filters = $request->input('filters', '');
            $filters .= ',department_id={-1;';
            foreach ($request->user->staff->departments as $department) {
                $filters .= ($department->id . ';');
            }
            $filters .= '}';
            $request->merge(['filters' => $filters]);
        } else if ($request->isMethod('get') && $request->is('api/sa_message')) {
            // filter by received user
            $embeds = $request->input('embeds', '');
            $embeds .= ',messageUsers';
            $request->merge(['embeds' => $embeds]);
            $filters = $request->input('filters', '');
            $filters .= ',messageUsers.user_id=' . $request->user->id;
            $request->merge(['filters' => $filters]);
        }
        return $next($request);
    }
}
