<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;

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
      $query->where('employee_status', $currentRouteName)->whereNotNull('profile_photo')->whereNotNull('passport_photo');
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
    } elseif ($currentRouteName == 'shortlist') {
      $title = 'Shortlisted';
    } else {
      $title = 'Finalist';
    }
   
    $employees = $query->orderBy('id', 'desc')->paginate($perPage);
    $currentPage = $employees->currentPage();
    $serialNumberStart = ($currentPage - 1) * $perPage + 1;
    return view('employee.index', [
      'employees' => $employees,
      'search' => $search,
      'title' => $title,
      'serialNumberStart' => $serialNumberStart,
    ]);
  }
  public function statusselect(Request $request)
  {
      $ids = $request->id;  // Expecting an array of IDs
      $pagename = $request->pagename;
  
      try {
          // Initialize an array to store the results
          $shortlistedEmployees = [];
          $finalizedEmployees = [];
          
          foreach ($ids as $id) {
              $employee = Employee::find($id);
  
              if (!$employee) {
                  // Skip this iteration if employee is not found
                  continue;
              }
  
              if ($pagename == 'Submitted') {
                  $employee->employee_status = 'shortlist';
                  $employee->update();
                  $shortlistedEmployees[] = $employee;  // Collect updated employee data
              } else {
                  $employee->employee_status = 'final';
                  $employee->update();
                  $finalizedEmployees[] = $employee;  // Collect updated employee data
              }
          }
  
          // Prepare the response messages
          $responseMessages = [];
          if (!empty($shortlistedEmployees)) {
              $responseMessages[] = 'Selected Employee is Shortlist';
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

}
