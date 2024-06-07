<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Employee;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $sub_mission = Employee::count();
        $final_list = Employee::where('employee_status','final')->count();
        $completed = Employee::where('status','completed')->count();
        $in_completed = Employee::whereIn('status',['basic','upload','summary'])->count();
        $shortlist = Employee::where('employee_status','shortlist')->count();

         return view('dashboard',[
            'in_completed'=>$in_completed,
            'sub_mission'=>$sub_mission,
            'completed'=>$completed,
            'final_list'=> $final_list,
            'shortlist'=> $shortlist,

         ]);
    }

}
