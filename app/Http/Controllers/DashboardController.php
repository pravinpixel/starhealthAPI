<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Employee;
use Carbon\Carbon;


class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $sub_mission = Employee::count();
        $final_list = Employee::where('employee_status','final')->where('status','completed')->count();
        $completed = Employee::where('status','completed')->whereNotNull('profile_photo')->whereNotNull('passport_photo')->count();
        $in_completed = Employee::whereIn('status',['basic','upload','summary'])->count();
        $shortlist = Employee::where('employee_status','shortlist')->where('status','completed')->whereNotNull('profile_photo')->whereNotNull('passport_photo')->count();
        $today_sub_mission = Employee::whereDate('created_at', Carbon::today())->count();
        $today_completed = Employee::whereDate('created_at', Carbon::today())->where('status','completed')->count();
        $today_final_list = Employee::whereDate('created_at', Carbon::today())->where('employee_status','final')->count();
        $today_shortlist = Employee::whereDate('created_at', Carbon::today())->where('employee_status','shortlist')->count();

         return view('dashboard',[
            'in_completed'=>$in_completed,
            'sub_mission'=>$sub_mission,
            'completed'=>$completed,
            'final_list'=> $final_list,
            'shortlist'=> $shortlist,
            'today_sub_mission'=> $today_sub_mission,
            'today_final_list'=> $today_final_list,
            'today_completed'=> $today_completed,
            'today_shortlist'=> $today_shortlist,
         ]);
    }

}
