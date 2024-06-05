@extends('layouts.index')
@section('title', $title)
@section('style')
    @parent
<style>
.page-loader {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5); 
    display: none;
    z-index: 9999;
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
<div class="app-main flex-column flex-row-fluid-xxl" id="kt_app_main">
 <div class="d-flex flex-column flex-column-fluid">
  <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
   <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
     <!--begin::Title-->
     <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">{{$title}} List</h1>
     <!--end::Title-->
     <!--begin::Breadcrumb-->
     {{-- <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
      <!--begin::Item-->
      <li class="breadcrumb-item text-muted">
       <a href="{{url('dashboard')}}"  class="text-muted text-hover-primary">Home</a>
      </li>
      <!--end::Item-->
      <!--begin::Item-->
      <li class="breadcrumb-item">
       <span class="bullet bg-gray-400 w-5px h-2px"></span>
      </li>
      <!--end::Item-->
      <!--begin::Item-->
      <li class="breadcrumb-item text-muted">Masters</li>
      <!--end::Item-->
      <!--begin::Item-->
      <li class="breadcrumb-item">
       <span class="bullet bg-gray-400 w-5px h-2px"></span>
      </li>
      <!--end::Item-->
      <!--begin::Item-->
      <li class="breadcrumb-item text-muted">Year</li>
      <!--end::Item-->
     </ul> --}}
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
    <div id="kt_app_content_container" class="app-container container">
     <!--begin::Card-->
     <div class="card">
      <!--begin::Card header-->
      <div class="card-header border-0 pt-6">
         <div class="card-title">
         <div class="d-flex align-items-center position-relative my-1">
         <span class="svg-icon svg-icon-1 position-absolute ms-6">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
           <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
           <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
          </svg>
         </span>
         <input type="text" id="searchInput" class="form-control form-control-solid w-350px ps-15" placeholder="Search register list" />
        </div>
       </div>
       <div class="card-toolbar">
        <button type="button" class="btn btn-light-primary me-3" data-bs-toggle="tooltip" id="filter_panel">
            <i class="fa-solid fa-filter"><span class="path1"></span><span class="path2"></span></i>
            Filter
        </button>  
     </div>
        </div>
           @include('filter')        
        <div class="card-body pt-0">
         <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_customers_table">
          <thead style="color: #3498db">
           <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0" style="color:#3498db !important">
           <th  class="min-w-115px">Employee Name</th>
            <th class="min-w-125px">Employee Code</th>
            <th class="min-w-125px">Department</th>
            <th class="min-w-125px">Designation</th>
            <th class="min-w-125px">Mobile</th>
            <th class="min-w-125px">Email</th>
            <th class="min-w-125px">State</th>       
            <th class="min-w-115px">Uploaded Images</th>
            @if(in_array($title, ['Register', 'Shortlisted']))
            <th class="min-w-100px">Select</th>
            @endif
           </tr>
          </thead>
          <tbody class="fw-semibold text-gray-600">
          @if($employees->isEmpty())
        <tr>
            <td colspan="9" class="text-center">No results found.</td>
        </tr>
          @else
           @foreach($employees as $employee)
           <tr>
            <td>
                {{$employee->employee_name}}
                {{-- {{ $serialNumberStart++ }} --}}
            </td>
             <td>
            {{$employee->employee_code}}
            </td>
            <td>
                {{$employee->department}}
                </td>
                <td>
                    {{$employee->designation}}
                </td>
                <td>
                    {{$employee->mobile_number}}
                </td>
                <td>
                    {{$employee->email}}
                </td>
                <td>
                    {{$employee->state}}
                </td>
                <td>
                    <img style="height:50px;wigth:35px !important;" src="{{ asset('images/admin-image.png') }}" alt="user"/>
                </td>
                @if(in_array($title, ['Register', 'Shortlisted']))
                <td>
                    <div class="form-check form-check-custom form-check-success form-check-solid">
                        <input  class="form-check-input" id="select" name="select" type="checkbox" value="{{$employee->id}}" />
                    </div>
                </td>
            @endif
            </tr>
           @endforeach
           @endif
           </tbody> 
          </table>
          <!--end::Table-->
          <div class="row">
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="show-pg">
             </div>
         </div>
         <div class="col-lg-4 col-md-0 col-sm-0">
         </div>
         <div  id="paginationLinks" class="col-lg-4 col-md-6 col-sm-12">
         {{ $employees->links('pagination::bootstrap-4') }}
         </div>
     </div> 
         </div>
        </div>
       </div>
      </div>
     </div>
</div>
<input type="hidden" name="routename" id="routename" value="{{route( Route::currentRouteName()) }}">
<input type="hidden" name="title" id="title" value="{{$title}}">

@endsection
@section('script')
    @parent

<script>
 $(document).ready(function () {
            var routename = $('#routename').val();
           
            $('#searchInput').keyup(function () {
                updateTableData();
            });
            $('[data-kt-ecommerce-order-filter="status"]').on('change', function () {
                updateTableData();
            });
            $(document).on('click', '#paginationLinks a', function (e) {
                    e.preventDefault();
                    var page = $(this).attr('href').split('page=')[1];
                    updateTableData(page);
            });
            $(document).on('click', '#filter_panel', function (e) {
                $("#filter_sub").toggle();
            });
            $(document).on('click', '#select', function (e) {
                selectstatus(this);
            });

       function updateTableData(page = '') {
            var searchTerm = $('#searchInput').val();
            var selectedStatus = $('[data-kt-ecommerce-order-filter="status"]').val();
            loadTableData(searchTerm, selectedStatus, page);
      }
       updateTableData();
    function loadTableData(searchTerm, selectedStatus, page = '') {
       $.ajax({
           url:routename +  "?search=" + searchTerm + "&status=" + selectedStatus + "&page=" + page,
           type: "GET",
           data: {
                search: searchTerm,
                status: selectedStatus,
                page: page
            },
           dataType: 'html',
           success: function (response) {
               $('#kt_customers_table tbody').html($(response).find('#kt_customers_table tbody').html());
               $('#paginationLinks').html($(response).find('#paginationLinks').html());
           },
           error: function () {
               console.error('Error loading table data.');
           }
       });
   }
    function refreshTableContent() {
           $.ajax({
               url: routename, 
               type: "GET",
               dataType: 'html',
               success: function (response) {
                   $('#kt_customers_table tbody').html($(response).find('#kt_customers_table tbody').html());
               updateTableData(); 
               },
               error: function (xhr, status, error) {
                   console.error('AJAX Error:', error);
               }
           });
       }
    function selectstatus(checkbox) {
            var isChecked = $(checkbox).is(':checked');
            var value = $(checkbox).val();
            var pagename = $('#title').val();
        if(isChecked == true){
          $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
           });
           $.ajax({
            url:"{{ route('select') }}" ,
            type: "POST",
            data: {
                id: value,
                pagename:pagename,
            },
            success: function (response) {
                toastr.success(response.message);
                refreshTableContent()
            },
            error: function (response) {
              console.log(response.message);
            }
        });
        }     
    }
});
</script>
@endsection