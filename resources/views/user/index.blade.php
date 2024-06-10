@extends('layouts.index')

@section('title', 'Users')

@section('style')
    @parent
    <style>
    .loading-cursor {
    cursor: wait !important;
}

.del{
  margin-top:10% !important;
}

.let{
  font-size:120% !important;

}
</style>
<style>
/* Add this to your CSS stylesheet */
.page-loader {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent black background */
    display: none;
    z-index: 9999; /* Make sure it's above other elements */
}

.loader {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    border: 4px solid #f3f3f3;
    border-top: 4px solid #3498db;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    animation: spin 2s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
@endsection
@section('content')
<div id="pageLoader" class="page-loader">
    <div class="loader"></div>
</div>
<meta name="csrf-token" content="{{ csrf_token() }}">
<div id="successMessage" class="alert alert-success" style="display: none;">
    <strong>Success:</strong> User has been successfully updated.
</div>




<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
 <!--begin::Content wrapper-->
 <div class="d-flex flex-column flex-column-fluid">
  <!--begin::Toolbar-->
  <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
   <!--begin::Toolbar container-->
   <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
    <!--begin::Page title-->
    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
     <!--begin::Title-->
     <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Users List</h1>
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

      <li class="breadcrumb-item text-muted">Users List</li>
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
   <!--begin::Content-->
   <div id="kt_app_content" class="app-content flex-column-fluid">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-xxl">
     <!--begin::Card-->
     <div class="card">
      <!--begin::Card header-->
      <div class="card-header border-0 pt-6">
       <!--begin::Card title-->
       <div class="card-title">
        <!--begin::Search-->
        <div class="d-flex align-items-center position-relative my-1">
         <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
         <span class="svg-icon svg-icon-1 position-absolute ms-6">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
           <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
           <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
          </svg>
         </span>
         <!--end::Svg Icon-->
         <input type="text" id="searchInput" class="form-control form-control-solid w-250px ps-15" placeholder="Search Users" />
        </div>
        <!--end::Search-->
       </div>
       <!--begin::Card title-->
       <!--begin::Card toolbar-->
       <div class="card-toolbar">
        <!--begin::Toolbar-->
        <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
         <!--begin::Filter-->
         <div class="w-150px me-3">
          <!--begin::Select2-->
          <select class="form-select form-select-solid"
          data-control="select2" data-hide-search="true" data-placeholder="Status" data-kt-ecommerce-order-filter="status">
      <option value="all">All</option>
      <option value="1">Active</option>
      <option value="0">Inactive</option>
  </select>
          <!--end::Select2-->
         </div>
         <!--end::Filter-->
         <!--begin::Export-->
          <!--end::Export-->
          <!--begin::Add customer-->
          <a type="button" class="btn btn-primary"
          href="{{url('user/create')}}">Add User</a>
          <!--end::Add customer-->
         </div>
         <!--end::Toolbar-->
         <!--begin::Group actions-->
         <div class="d-flex justify-content-end align-items-center d-none" data-kt-customer-table-toolbar="selected">
          <div class="fw-bold me-5">
           <span class="me-2" data-kt-customer-table-select="selected_count"></span>Selected</div>
           <button type="button" class="btn btn-danger" data-kt-customer-table-select="delete_selected">Delete Selected</button>
          </div>
          <!--end::Group actions-->
         </div>
         <!--end::Card toolbar-->
        </div>
        <!--end::Card header-->
        <!--begin::Card body-->
        <div class="card-body pt-0">
         <!--begin::Table-->
         <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_customers_table">
          <!--begin::Table head-->
          <thead>
           <!--begin::Table row-->
           <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
            {{-- <th class="w-10px pe-2">
             <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
              <input class="form-check-input" type="checkbox" data-kt-check="true" data-kt-check-target="#kt_customers_table .form-check-input" value="1" />
             </div>
            </th> --}}

            <th class="min-w-125px">Name</th>
            <th class="min-w-125px">Role</th>
            <th class="min-w-125px">Email</th>
            <th class="min-w-125px">Mobile Number</th>
            <th class="min-w-125px">Status</th>
            <th class="min-w-125px">Actions</th>
           </tr>
           <!--end::Table row-->
          </thead>
          <!--end::Table head-->
          <!--begin::Table body-->
          <tbody class="fw-semibold text-gray-600">
          @if($users->isEmpty())
        <tr>
            <td colspan="6" class="text-center">No results found.</td>
        </tr>
        @else
           @foreach($users as $user)
           <tr>
            <!--begin::Checkbox-->
             <!-- <td>
             <div class="form-check form-check-sm form-check-custom form-check-solid">
              <input class="form-check-input" type="checkbox" value="1" />
             </div>
            </td>  -->
            <!--end::Checkbox-->
            <!--begin::Name=-->
            <td>
            {{$user->name}}
            </td>
            <!--end::Name=-->
            <!--begin::Email=-->

            <!--end::Email=-->
            <!--begin::Status=-->
            <td>
                @if(isset($user->role->name))
            {{ $user->role->name }}
            @endif
           </td>
           <td>
            {{ $user->email }}
           </td>
            <!--end::Status=-->
             <!--begin::Status=-->
             <td>
             {{$user->mobile_number}}
            </td>
            <!--end::Status=-->
             <!--begin::Status=-->

            <!--end::Status=-->
            <!--begin::IP Address=-->
            <td>
                                    @if($user->status==1)
                                    <a href="#" class="badge badge-light-success" >Active</a>
                                    @else
                                     <a href="#" class="badge badge-light-danger" >In Active</a>
                                @endif</td>
                            <td class="td">
                             <a   href="{{url('user/edit', $user->id)}}" class="btn btn-icon btn-active-primary btn-light-primary mx-1 w-30px h-30px ">
                                <i class="fa fa-edit"></i>
                                </a>

                                <button type="button" class="btn btn-icon btn-active-danger btn-light-danger mx-1 w-30px h-30px"
                      onclick="deleteUser('{{ $user->id }}')">
                  <i class="fa fa-trash"></i></button>
                              </tr>
           @endforeach
           @endif
           </tbody>
           <!--end::Table body-->


          </table>
          <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="show-pg">
                         <!--  <p>Showing 1 to 9 of 9 entries</p> -->
                     </div>
                 </div>
                 <div class="col-lg-4 col-md-0 col-sm-0">

                 </div>
                 <div id="pagination-links" class="col-lg-4 col-md-6 col-sm-12">
                   {{ $users->links('pagination::bootstrap-4') }}
                </div>

             </div>
     </div>
          <!--end::Table-->
         </div>
         <!--end::Card body-->
        </div>
        <!--end::Card-->
        <!--begin::Modals-->
        <!--begin::Modal - Customers - Add-->
        <!--end::Modal - Customers - Add-->
       </div>
       <!--end::Content container-->
      </div>

      <!--end::Content-->

     </div>
     <!--end::Content wrapper-->
    </div>


    <!-- <-----delete model---->
<div class="modal fade del" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

            </div>
            <input type="hidden" id="cityId" name="city_id" value="">
            <div class="modal-body let">
                Are you sure you want to delete this record?

            </div>
            <div class="modal-footer">
            <button type="button"class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    @parent


    <script type="text/javascript">
$(document).ready(function () {
    $('#searchInput').keyup(function () {
        updateTableData();
    });
    $('[data-kt-ecommerce-order-filter="status"]').on('change', function () {
        updateTableData();
    });
    $('#pagination-links').on('click', '.pagination a', function (e) {
        e.preventDefault();
        var page = $(this).attr('href').split('page=')[1]; 
        updateTableData(page);
    });
    function loadTableData(searchTerm, selectedStatus, page = '') {
        $.ajax({
            url: "{{ route('user.index') }}?search=" + searchTerm + "&status=" + selectedStatus + "&page=" + page,
            type: "GET",
            dataType: 'html',
            success: function (response) {
                console.log(response);
                $('#kt_customers_table tbody').html($(response).find('#kt_customers_table tbody').html());
                $('#pagination-links').html($(response).find('#pagination-links').html());
                attachEventListeners();
            },
            error: function () {
                console.error('Error loading table data.');
            }
        });
    }

            function updateTableData(page = '') {
                var searchTerm = $('#searchInput').val();
                var selectedStatus = $('[data-kt-ecommerce-order-filter="status"]').val();
                var currentPage = $('ul.pagination li.active span').text();
                loadTableData(searchTerm, selectedStatus, page || currentPage); 
            }
    updateTableData();
     });
    function deleteUser(userId) {
    Swal.fire({
        text: "Are you sure you would like to delete?",
        icon: "warning",
        showCancelButton: true,
        buttonsStyling: false,
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, return",
        customClass: {
            confirmButton: "btn btn-danger",
            cancelButton: "btn btn-active-light"
        }
    }).then(function (result) {
        if (result.isConfirmed) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{url('/user')}}/" + userId,
                type: 'DELETE',
                success: function (res) {
                  refreshTableContent();
                    Swal.fire({
                        title: "Deleted!",
                        text: res.message,
                        icon: "success",
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn btn-success"
                        },
                        timer: 3000,
                    });

                }
            });
        }
    });
}

        function refreshTableContent() {
            $.ajax({
                url: "{{ route('user.index') }}", 
                type: "GET",
                dataType: 'html',
                success: function (response) {
                    $('#kt_customers_table tbody').html($(response).find('#kt_customers_table tbody').html());
                },
                error: function () {
                    console.error('Error loading table content.');
                }
            });
        }
</script>
@endsection


