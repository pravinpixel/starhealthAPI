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
      $status = $request->input('status');
      $currentRouteName = request()->route()->getName();
      $perPage = 2;
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
          $query->where('employee_status', $currentRouteName);
      }
      if ($currentRouteName == 'register') {
       $title='Register';
     }elseif($currentRouteName == 'shortlist'){
      $title='Shortlisted';
     }else{
      $title='Final';
     }
      $employees = $query->orderBy('id','desc')->paginate($perPage);
      $currentPage = $employees->currentPage();
      $serialNumberStart = ($currentPage - 1) * $perPage + 1;
      return view('employee.index', [
          'employees' => $employees,
          'search' => $search,
          'title'=>$title,
          'serialNumberStart' => $serialNumberStart, 
      ]);
  }
  public function statusselect(Request $request)
  {
    $id=$request->id;
    $pagename=$request->pagename;
try{
    $employee=Employee::find($id);
    if($pagename == 'Register'){
      $employee->employee_status='shortlist';
      $employee->update();  
      return response()->json(['message' => 'Employee Selected Shortlist', 'data' => $employee]);
    }else{
      $employee->employee_status='final';
      $employee->update();  
    }
    return response()->json(['message' => 'Employee Selected Finallist', 'data' => $employee]);
}catch (\Exception $e) {
return response()->json(['status' => false, 'errors' => $e->getMessage()], 422);
}
  }
  
}
