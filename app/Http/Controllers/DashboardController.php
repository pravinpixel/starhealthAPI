<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Employee;
use Carbon\Carbon;


class DashboardController extends Controller
{
    public function index(Request $request)
    {
        
        $basic_null=Employee::whereNull('status')->count();
        $today_basic_null=Employee::whereDate('created_at', Carbon::today())->whereNull('status')->count();
        $basic=Employee::where('status','basic')->count();
        $no_upoloads=Employee::whereIn('status',['upload','completed','summary'])->whereNull('passport_photo')->whereNull('profile_photo')->count();
        $passportcount=Employee::whereIn('status',['upload','summary','completed'])
                        ->where(function($query){
                            $query->whereNotNull('passport_photo')
                            ->whereNull('profile_photo');
                        })->count();
                        $profilecount=Employee::whereIn('status',['upload','summary','completed'])
                        ->where(function($query){
                            $query->whereNotNull('profile_photo')
                            ->whereNull('passport_photo');
                        })->count();
                        $partial_upoloads = $passportcount + $profilecount;
        $today_basic=Employee::whereDate('created_at', Carbon::today())->where('status','basic')->count();
        $today_no_upoloads=Employee::whereDate('created_at', Carbon::today())->whereIn('status',['upload','summary','completed'])->whereNull('passport_photo')->whereNull('profile_photo')->count();
        $today_passportcount=Employee::whereDate('created_at', Carbon::today())->whereIn('status',['upload','summary','completed'])
                        ->where(function($query){
                            $query->whereNotNull('passport_photo')
                            ->whereNull('profile_photo');
                        })->count();
                        $today_profilecount=Employee::whereDate('created_at', Carbon::today())->whereIn('status',['upload','summary','completed'])
                        ->where(function($query){
                            $query->whereNotNull('profile_photo')
                            ->whereNull('passport_photo');
                        })->count();
                        $today_partial_upoloads = $today_passportcount + $today_profilecount;
        $sub_mission = Employee::count();
        $final_list = Employee::where('employee_status','final')->where('status','completed')->count();
        $completed = Employee::where('status','completed')->whereNotNull('profile_photo')->whereNotNull('passport_photo')->count();
        $summary = Employee::where('status','summary')->whereNotNull('profile_photo')->whereNotNull('passport_photo')->count();
        $today_summary = Employee::whereDate('created_at', Carbon::today())->where('status','summary')->whereNotNull('profile_photo')->whereNotNull('passport_photo')->count();
        $in_completed = Employee::whereIn('status',['basic','upload','summary'])->count();
        $shortlist = Employee::where('employee_status','shortlist')->where('status','completed')->whereNotNull('profile_photo')->whereNotNull('passport_photo')->count();
        $today_sub_mission = Employee::whereDate('created_at', Carbon::today())->count();
        $today_completed = Employee::whereDate('created_at', Carbon::today())->whereNotNull('profile_photo')->whereNotNull('passport_photo')->where('status','completed')->count();
        $today_final_list = Employee::whereDate('created_at', Carbon::today())->where('employee_status','final')->count();
        $today_shortlist = Employee::whereDate('created_at', Carbon::today())->where('employee_status','shortlist')->count();
        $upload_with_photos = Employee::where('status', 'upload')
        ->whereNotNull('passport_photo')
        ->whereNotNull('profile_photo')
        ->count();
        $today_upload_with_photos = Employee::whereDate('created_at', Carbon::today())->where('status', 'upload')
        ->whereNotNull('passport_photo')
        ->whereNotNull('profile_photo')
        ->count();
    $summary += $upload_with_photos ;
    $today_summary += $today_upload_with_photos;
         return view('dashboard',[
            'basic'=>$basic,
            'basic_null'=>$basic_null,
            'summary'=>$summary,
            'today_basic_null'=> $today_basic_null,
            'today_summary'=>$today_summary,
            'no_upoloads'=>$no_upoloads,
            'partial_upoloads'=>$partial_upoloads,
            'today_basic'=>$today_basic,
            'today_no_upoloads'=>$today_no_upoloads,
            'today_partial_upoloads'=>$today_partial_upoloads,
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
