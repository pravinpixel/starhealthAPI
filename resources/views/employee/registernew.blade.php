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
.passport_photo .overlay {
    position: absolute;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    height: 100%;
    width: 250px;
    opacity: 0;
    transition: opacity .3s ease;
    background-color: rgba(102, 99, 99, 0.5); /* Adjust opacity as needed */
    display: flex;
    justify-content: center;
    align-items: center;
}

.passport_photo:hover .overlay {
    opacity: 1;
}

.overlay .icon {
    color: white;
    text-align: center;
    cursor: pointer;
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
     <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">{{$title}}</h1>
     <!--end::Title-->
     <!--begin::Breadcrumb-->
     <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
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
      <li class="breadcrumb-item text-muted">{{$title}}</li>
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
         <input type="text" id="searchInput" class="form-control form-control-solid w-350px ps-15" placeholder="Search {{$title}}" />
        </div>
       </div>
       <div  class="mt-5 mb-0" id="count_data">
              <h4 style="color: #3498db;margin-left: -17px;"> Total Count : {{$employees->total()}}</h4>
       </div>
       <div class="card-toolbar" style="gap: 25px">
        <div>
            <button type="submit" class="btn btn-primary btn-sm" id="selectbutton" style="height: 40px">Move to Shortlisted</button>
        </div>
        <div>
        <button type="button" class="btn btn-light-primary me-3" data-bs-toggle="tooltip" id="filter_panel">
            <i class="fa-solid fa-filter"><span class="path1"></span><span class="path2"></span></i>
            Filter
        </button>  
    </div>
     </div>
        </div>
           @include('employee.filter')        
        <div class="card-body pt-0">
          <div style="overflow-x:auto;">
            <div class="card-header border-0 pt-6">
                <div class="card-title" id="contentDiv">
                    @if($employees->isEmpty())
                    <tr>
                        <td colspan="9" class="text-center">No results found.</td>
                    </tr>
                @else
                <div class="row" style="margin-left:40px">
                @foreach($employees as $employee)
                <div class="w-250px me-3" style="display:flex;flex-direction:column;position:relative;">
                    <div class="col-md-4 mt-3 passport_photo" style="position:relative;">
                        <img src="{{ $employee->passport_photo}}" style="height:250px;width:250px" alt="">
                        <div class="overlay">
                            <a data-passport="{{$employee->passport_photo}}"  data-profile="{{$employee->profile_photo ?? ''}}" data-family="{{$employee->family_photo ?? ''}}" class="icon moreimages" title="passport image">
                                <i class="bi bi-eye-fill fs-2x text-white"></i>
                            </a>
                        </div>
                    </div>
                    <div style="display:flex;justify-content:center;" class="form-check form-check-custom form-check-success form-check-solid mt-5 mb-5">
                        <input style="border: 2px solid #bcbcbc;cursor: pointer;" class="form-check-input" id="select" name="select" type="checkbox" value="{{$employee->id}}" />
                    </div>
                    <div style="text-align: center">
                        <p>{{ $employee->employee_name}}<br>
                        {{ $employee->mobile_number}}</p>
                        </div>
                </div>
                
                    @endforeach
                </div>
                    @endif
                </div>
            </div>
          </div>
          <div class="row">
         <div  id="paginationLinks" class="col-lg-12 col-md-12 col-sm-12">
         {{ $employees->links('pagination::bootstrap-4') }}
         </div>
     </div> 
         </div>
        </div>
       </div>
      </div>
     </div>
</div>
<img id="test_image"/>
<input type="hidden" name="routename" id="routename" value="{{route( Route::currentRouteName()) }}">
<input type="hidden" name="title" id="title" value="{{$title}}">
<div style="display: none" id="hidden_image">

</div>

@endsection
@section('script')
    @parent
<script>
 $(document).ready(function () {
            var routename = $('#routename').val();
            $('#searchInput').keyup(function () {
                updateTableData();
            });
             $(document).on('change', '[name="designation"]', function (e) {
                updateTableData();
              });
              $(document).on('change', '[name="state"]', function (e) {
                updateTableData();
              });
              $(document).on('change', '[name="city"]', function (e) {
                updateTableData();
              });
              $(document).on('change', '[name="department"]', function (e) {
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
            $(document).on('click', '#selectbutton', function (e) {
                    $('#pageLoader').fadeIn();
                    let array = []; 
                    $("input:checkbox[name=select]:checked").each(function() { 
                        array.push($(this).val()); 
                    });      

                    if(array.length === 0) {
                        $('#pageLoader').fadeOut();
                    } else {
                        selectstatus(array);
                    }
            });
            $(window).on('beforeunload', function() {
              $('#searchInput').val('');
              $('[name="designation"]').val('');
              $('[name="state"]').val('');
              $('[name="city"]').val('');
              $('[name="department"]').val('');
                });

                $(document).on('click', '.moreimages', function (e) {
            e.preventDefault();
            $('#hidden_image').html('');
            var lightbox = new FsLightbox();
            $('#pageLoader').fadeIn();
            lightbox.props.sources = [];
            let family = $(this).attr('data-family');
                let profile = $(this).attr('data-profile');
                let passport = $(this).attr('data-passport');
                if(family){
                    $('#hidden_image').append('<img src="' + family + '" id="family1" />');
                    lightbox.props.sources.push(document.getElementById("family1"));
                }
                if(profile){
                    $('#hidden_image').append('<img src="' + profile + '" id="profile1" />');
                    lightbox.props.sources.push(document.getElementById("profile1"));
                }
                if(passport){
                $('#hidden_image').append('<img src="' + passport + '" id="passport1" />');
                lightbox.props.sources.push(document.getElementById("passport1"));
                }
                setTimeout(function () {
                    $('#pageLoader').fadeOut();
                    lightbox.open();
                }, 1000);
        });
       function updateTableData(page = '') {
            var searchTerm = $('#searchInput').val();
            var designation = $('[name="designation"]').val();
            var state = $('[name="state"]').val();
            var city = $('[name="city"]').val();
            var department = $('[name="department"]').val();
            loadTableData(searchTerm, designation,state,department,city,page);
      }
       updateTableData();
    function loadTableData(searchTerm, designation,state,department,city,page = '') {
       $.ajax({
           url:routename +  "?search=" + searchTerm + "&designation=" + designation + "&city=" + city +"&page=" + page + "&state=" + state + "&department=" + department,
           type: "GET",
           data: {
                search: searchTerm,
                designation: designation,
                state: state,
                city:city,
                department: department,
                page: page
            },
           dataType: 'html',
           success: function (response) {
            $('#contentDiv').html($(response).find('#contentDiv').html());
               $('#paginationLinks').html($(response).find('#paginationLinks').html());
               $('#count_data').html($(response).find('#count_data').html());
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
                $('#contentDiv').html($(response).find('#contentDiv').html());
                $('#count_data').html($(response).find('#count_data').html());
               updateTableData(); 
               },
               error: function (xhr, status, error) {
                   console.error('AJAX Error:', error);
               }
           });
       }
    function selectstatus(array) {
            var pagename = $('#title').val();
          $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
           });
           $.ajax({
            url:"{{ route('select') }}" ,
            type: "POST",
            data: {
                id: array,
                pagename:pagename,
            },
            success: function (response) {
                toastr.success(response.message);
                $('#pageLoader').fadeOut(function () {
                   refreshTableContent();
               });
             
            },
            error: function (response) {
                $('#pageLoader').fadeOut();
              console.log(response.message);
            }
        });     
    }
});
</script>
@endsection