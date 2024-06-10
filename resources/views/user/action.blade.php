@extends('layouts.index')

@section('title', 'User')
@section('style')
@parent
@endsection

@section('content')
<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
  <!--begin::Toolbar container-->
  <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
    <!--begin::Page title-->
    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
      <!--begin::Title-->
      <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">User</h1>
      <!--end::Title-->
      <!--begin::Breadcrumb-->
      <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
        <!--begin::Item-->
        <li class="breadcrumb-item text-muted">
          <a href="{{url('dashboard')}}" class="text-muted text-hover-primary">Home</a>
        </li>
        <!--end::Item-->
        <!--begin::Item-->
        <li class="breadcrumb-item">
          <span class="bullet bg-gray-400 w-5px h-2px"></span>
        </li>
        <li class="breadcrumb-item text-muted">User</li>
        <!--end::Item-->
      </ul>
      <!--end::Breadcrumb-->
    </div>
    <!--end::Page title-->
    <!--begin::Actions-->
    <div class="d-flex align-items-center gap-2 gap-lg-3">
      <!--begin::Filter menu-->
      <div class="m-0">
        <!--begin::Menu toggle-->
        <!--end::Menu 1-->
      </div>
      <!--end::Filter menu-->
    </div>
    <!--end::Actions-->
  </div>
  <!--end::Toolbar container-->
</div>
<!--end::Toolbar-->
<div class="card mb-5 mb-xl-8">
  <!--begin::Header-->
  <div class="card-header border-0 pt-5">
    <h3 class="card-title align-items-start flex-column">
      <span class="card-label fw-bold fs-3 mb-1">User </span>
    </h3>
    <div class="card-toolbar" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover" title="Click to add a user">
    </div>
  </div>
  <!--end::Header-->
  <!--begin::Body-->
  <div class="card-body py-3">
    <!--begin::Table container-->
    <form id="user_form">
      @csrf
      
    <div class="row mt-5">
        <div class="col-md-4">
          <div class="form-group"> 
              <label  class="required" for="name">Name</label>
            <input type="text" name="name"  placeholder="  Name" class=" form-control" value="{{ $user->name ?? old('name') }}"/> 
            <span  style="color: red" class="field-error" id="name-error"></span>
          </div>
        </div>
        <input type="hidden" name="id"  id="id"  value="{{ $user->id ?? '' }}" /> 
        <div class="col-md-4">
         <label  class="required" for="email">Email</label>
        <input type="email" name="email" placeholder=" Email" class="required form-control " value="{{ $user->email ?? old('email') }}" autocomplete="new-email" /> 
        <span  style="color: red" class="field-error" id="email-error"></span>
      </div>
        <div class="col-md-4">
           <label class="required" for="mobile_number">Mobile Number</label>
            <input type="number" name="mobile_number" placeholder=" Mobile Number" class="required form-control" value="{{ $user->mobile_number ?? old('mobile_number') }}" maxlength="14"/> 
            <span  style="color: red" class="field-error" id="mobile_number-error"></span>
        </div>
      </div>
      <div class="row mt-5">
        @if(!isset($user))
        <div class="col-md-4">
          <div class="form-group"> 
            <label  class="required" for="password">Password</label>
        <input type="password" name="password" placeholder="Password" id="password-field" class="required form-control bg-transparent @error('password') is-invalid @enderror" value="{{ old('password') }}" autocomplete="new-password" /> 
      <span toggle="#password-field" class="fa fa-fw fa-eye-slash field-icon toggle-password"></span>
      <span  style="color: red" class="field-error" id="password-error"></span>
          </div>
        </div>
        @endif
        <div class="col-md-4">
          <label  class="required" for="role">Role</label>
        <select data-control="select2" data-hide-search="false" name="role_id" class="form-control" value="{{ $user->role ?? old('role') }}">
            <option selected value="">Choose Role</option>
            @foreach($roles as $role)
            <option value="{{ $role->id }}" {{ isset($user) && $role->id == old('role_id', $user->role_id) ? 'selected' : '' }}>{{ $role->name }}</option>
            @endforeach
        </select>
       <span  style="color: red" class="field-error" id="role_id-error"></span>
        </div>
        <div class="col-md-4">
           <label for="status">Status</label>
           <select class="form-select" data-control="select2" data-placeholder="Select Status" name="status" value="{{ $user->status ?? old('status') }}">
            <option  value="">Select Status</option>
            <option value="1" @if(isset($user)){{$user->status == 1  ? 'selected' : '' }} @endif>Active</option>
            <option value="0" @if(isset($user)){{$user->status== 0  ? 'selected' : '' }}@endif >In Active</option>
        </select>
            <span  style="color: red" class="field-error" id="status-error"></span>
        </div>
      </div>
       <div class="row mt-4">
        <div class="col-md-4"></div>
         <div class="col-md-4">
       <button id="user_submit" type="button" class="btn btn-primary">Submit</button>
        </div><div class="col-md-4"></div>
     </div>
     <br>
    </form>
    <!--end::Table container-->
  </div>
</div>
@endsection
@section('script')
    @parent
    <script>
        var id = $('#id').val();
        if(id){
          var submit_url="{{ route('user.update') }}";
        }else{
          var submit_url="{{ route('user.save') }}";
        }
        console.log(id);
     $('#user_submit').click(function () {
      let form_data = $("#user_form").serialize();
      form_data.id=id;
      console.log(form_data);
      $.ajax({
          url: submit_url,
          type: "POST",
          data:form_data,
          success: function (response) {
            $('.field-error').text(" ");
              toastr.success(response.message);
              window.location = "{{route('user.index')}}";
          },
          error: function (response) {
            console.log(response);
              $("#user_form").attr("disabled", false);
              $.each(response.responseJSON.errors, function (field_name, error) {
                  $('#' + field_name + '-error').text(error[0]);
              });
              // toastr.error(response.responseJSON.message);
          }
      });
      });
    
      </script>
@endsection
