<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use App\Models\Role;
class UserController extends Controller
{
    public function index(Request $request)
    {

      $status = $request->input('status');
      $search = $request->input('search');
  
      $query = User::query();
  
      if (isset($search)) {
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', '%' . $search . '%');
              
        });
    }
  
    if (isset($status) && $status !='all') {
      $query->where('status', $status);
  }
       $users = $query->orderBy('id','desc')->paginate(10);
       $roles = Role::all();
      return view('user.index', [
     'users' => $users,
     'roles' => $roles,
     'selectedStatus' => $status,
          'search' => $search,
     
      ]);
    }

    public function create(Request $request)
    { 
      $roles = Role::all();
      return view('user.action',['roles' => $roles]);
    }

    public function get(Request $request,$id)
    {
      $user=User::find($id);
      $roles = Role::all();
      return view('user.action',['user'=>$user,'roles' => $roles]);

    }

    public function save(Request $request)
    {
     $request->validate([
        'name' => 'required',
        'password' => 'required',
        'email' => ['required', 'email', 'unique:users', 'regex:/(.+)@(.+)\.(.+)/i'],
        'mobile_number' => 'required|numeric',
        'status' => 'required|boolean',
        'role_id'=>'required|exists:roles,id'
      ]);
   try{
     
      $user=new User;
      $user->name=$request->input('name');
      $user->mobile_number=$request->input('mobile_number');
      $user->password=bcrypt($request->input('password'));
      $user->email=$request->input('email');
      $user->status=$request->input('status');
      $user->role_id=$request->input('role_id');
      $user->save();    
      return $this->returnSuccess(
      [],
        'User Created successfully'
    );
    } catch (\Exception $e) {
        return $this->returnError($e->getMessage());
    }
  }
  public function update(Request $request)
  { $id=$request->id;
           $form_data = $request->validate([
            'name' => 'required',
            'mobile_number' => 'required|numeric',
            'email' => [
              'required',
              'email',
              'unique:users,email,' . $id,
              'regex:/(.+)@(.+)\.(.+)/i'
          ],
            'status' => 'required|boolean',
            'role_id' => 'required|exists:roles,id'
        ]);

    try {
        $user = User::findOrFail($id);
        $user->name =  $request->input('name');
        $user->mobile_number = $request->input('mobile_number');
        $user->email = $request->input('email');
        $user->status = $request->input('status');
        $user->role_id =$request->input('role_id');
        $user->update();
        return $this->returnSuccess(
          [],
            'User Updated successfully'
        );
        } catch (\Exception $e) {
            return $this->returnError($e->getMessage());
        }
  }
  
  public function delete(Request $request, $id)
  {
      try {
          User::find($id)->delete();
          return response()->json(['message' => 'User Deleted successfully']);
        }catch (\Exception $e) {
        return response()->json(['status' => false, 'errors' => $e->getMessage()], 422);
        }
    }
  

}
