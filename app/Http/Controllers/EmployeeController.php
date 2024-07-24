<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use Carbon\Carbon;

class EmployeeController extends Controller
{
  public function registerlist(Request $request)
  {
    $search = $request->input('search');
    $designation = $request->input('designation');
    $state = $request->input('state');
    $department = $request->input('department');
    $city = $request->input('city');
    $currentRouteName = request()->route()->getName();
    $perPage = 10;
    $query = Employee::query();
    if ($search) {
      $query->where(function ($q) use ($search) {
        $q->where('employee_name', 'like', '%' . $search . '%')
          ->orWhere('employee_code', 'like', '%' . $search . '%')
          ->orWhere('email', 'like', '%' . $search . '%')
          ->orWhere('mobile_number', 'like', '%' . $search . '%')
          ->orWhere('designation', 'like', '%' . $search . '%');
      });
    }
    if ($currentRouteName) {
      $query->where('status','completed')->where('employee_status', $currentRouteName)->whereNotNull('profile_photo')->whereNotNull('passport_photo');
    }
    if ($designation) {
      $query->where('designation', $designation);
    }
    if ($state) {
      $query->where('state', $state);
    }
    if ($department) {
      $query->where('department', $department);
    }
    if ($city) {
      $query->where('city', $city);
    }

    if ($currentRouteName == 'register') {
      $title = 'Submitted';
      $employees = $query->orderBy('id', 'desc')->paginate($perPage);
    } elseif ($currentRouteName == 'shortlist') {
      $title = 'Shortlisted';
      $employees = $query->orderBy('status_change_time', 'desc')->paginate($perPage);
    } else {
      $title = 'Finalist';
      $employees = $query->orderBy('status_change_time', 'desc')->paginate($perPage);
    }
   
    $datas=Employee::where('status','completed')->where('employee_status',$currentRouteName)->whereNotNull('profile_photo')->whereNotNull('passport_photo')->get();
    $currentPage = $employees->currentPage();
    $serialNumberStart = ($currentPage - 1) * $perPage + 1;
   

    return view('employee.index', [
      'employees' => $employees,
      'datas'=>$datas,
      'search' => $search,
      'title' => $title,
      'serialNumberStart' => $serialNumberStart,
    ]);
  }
  public function statusselect(Request $request)
  {
      $ids = $request->id;
      $pagename = $request->pagename;
      $data=$request->submit_data ?? '';
      try {
          $shortlistedEmployees = [];
          $finalizedEmployees = [];
          $submittededEmployees=[];
          foreach ($ids as $id) {
              $employee = Employee::find($id);
              if (!$employee) {
                  continue;
              }
              if ($pagename == 'Submitted' || $pagename == 'Submitted Gallery') {
                  $employee->employee_status = 'shortlist';
                  $employee->status_change_time =now();
                  $employee->update();
                  $shortlistedEmployees[] = $employee;
              } 
              elseif($pagename == 'Finalist' || $pagename == 'Finalist Gallery'){
                $employee->employee_status = 'shortlist';
                $employee->status_change_time =now();
                $employee->update();
                $shortlistedEmployees[] = $employee;
              }
              elseif($data == 'move_to_submitted'){
                $employee->employee_status = 'register';
                $employee->update();
                $submittededEmployees[] = $employee;
              }
              else {
                  $employee->employee_status = 'final';
                  $employee->status_change_time =now();
                  $employee->update();
                  $finalizedEmployees[] = $employee;
              }
          }
          $responseMessages = [];
          if (!empty($shortlistedEmployees)) {
              $responseMessages[] = 'Selected Employee is Shortlist';
          }
          if (!empty($submittededEmployees)) {
            $responseMessages[] = 'Selected Employee is Submitted';
        }
          if (!empty($finalizedEmployees)) {
              $responseMessages[] = 'Selected Employee is Finalist';
          }
          return response()->json([
              'message' => $responseMessages,
              'shortlisted' => $shortlistedEmployees,
              'finalized' => $finalizedEmployees
          ]);
  
      } catch (\Exception $e) {
          return response()->json([
              'status' => false,
              'errors' => $e->getMessage()
          ], 422);
      }
  }
  public function view(Request $request)
  {
    $id = $request->id;
    try {
      $employee = Employee::find($id);
      return response()->json(['message' => 'Employee data', 'data' => $employee]);
    } catch (\Exception $e) {
      return response()->json(['status' => false, 'errors' => $e->getMessage()], 422);
    }
  }
  public function galleryview(Request $request)
  {
    $search = $request->input('search');
    $designation = $request->input('designation');
    $state = $request->input('state');
    $department = $request->input('department');
    $city = $request->input('city');
    $currentRouteName = request()->route()->getName();
    if ($currentRouteName == 'registernew') {
      $employee_status = 'register';
    } elseif ($currentRouteName == 'shortlistnew') {
      $employee_status = 'shortlist';
    } else {
      $employee_status = 'final';
    }
    $perPage = 24;
    $query = Employee::query();
    if ($search) {
      $query->where(function ($q) use ($search) {
        $q->where('employee_name', 'like', '%' . $search . '%')
          ->orWhere('employee_code', 'like', '%' . $search . '%')
          ->orWhere('email', 'like', '%' . $search . '%')
          ->orWhere('mobile_number', 'like', '%' . $search . '%')
          ->orWhere('designation', 'like', '%' . $search . '%');
      });
    }
      $query->where('status','completed')->where('employee_status', $employee_status)->whereNotNull('profile_photo')->whereNotNull('passport_photo');
    if ($designation) {
      $query->where('designation', $designation);
    }
    if ($state) {
      $query->where('state', $state);
    }
    if ($department) {
      $query->where('department', $department);
    }
    if ($city) {
      $query->where('city', $city);
    }
    if ($currentRouteName == 'registernew') {
      $title = 'Submitted Gallery';
      $employees = $query->orderBy('id', 'desc')->paginate($perPage);
    } elseif ($currentRouteName == 'shortlistnew') {
      $title = 'Shortlisted Gallery';
      $employees = $query->orderBy('status_change_time', 'desc')->paginate($perPage);
    } else {
      $title = 'Finalist Gallery';
      $employees = $query->orderBy('status_change_time', 'desc')->paginate($perPage);
    }
    $datas=Employee::where('status','completed')->where('employee_status', $employee_status)->whereNotNull('profile_photo')->whereNotNull('passport_photo')->get();
    $currentPage = $employees->currentPage();
    $serialNumberStart = ($currentPage - 1) * $perPage + 1;
    return view('employee.registernew', [
      'employees' => $employees,
      'search' => $search,
      'title' => $title,
      'datas'=>$datas,
      'serialNumberStart' => $serialNumberStart,
    ]);
  }
}
