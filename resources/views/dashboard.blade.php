@extends('layouts.index')

@section('title', 'Dashboard')

@section('style')
    @parent
    <style>
        .box {
            transition: transform .6s;

        }

        .box:hover {
            -ms-transform: scale(1);
            -webkit-transform: scale(1);
            transform: scale(1.2);
        }

        .list:hover {
            color: black !important;
        }


    </style>
@endsection

@section('content')
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <!--begin::Toolbar container-->
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <!--begin::Page title-->
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <!--begin::Title-->
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                    Dashboard</h1>
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

    <div id="kt_app_content_container" class="app-container container">
        <div class="row">
            <h1 style="">Total submission</h1>
            <div class="col-md-6" style="padding:20px">
                <a href="#">
                    <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end h-xl-100 box"
                         style="width:74%;  background-color: #7dc0e7;background-image: linear-gradient(62deg,#293A83 24%,#7dc0e7 79%);
                         margin-left:80px;height:172px ! important;">
                        <div style="justify-content: center; padding:13%; " class="card-body d-flex">
                            <h2 style="color:white; "><br>
                                <center><span class="badge badge-light ">{{ $customerartist ??'456' }}</span></center>
                            </h2>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-6" style="padding: 20px;margin-top:-51px;">
                <h1 style="margin-bottom: 26px">Total Completed</h1>
                <a href="#">
                    <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end h-xl-100 box"
                         style="width:74%; background-color: #7dc0e7;background-image: linear-gradient(62deg,#293A83 24%,#7dc0e7 79%);
                          margin-left:80px;height:172px ! important;">
                        <div style="justify-content: center; padding:13%; " class="card-body d-flex">
                            <h2 style="color:white; "> <br>
                                <center><span class="badge badge-light ">{{ $business  ??'123'}}</span></center>
                            </h2>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <div class="row">
            <h1 style="">Total Incompleted</h1>
            <div class="col-md-6" style="padding:20px">
                <a href="#">
                    <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end h-xl-100 box"
                         style="width:74%;  background-color: #7dc0e7;background-image: linear-gradient(62deg,#293A83 24%,#7dc0e7 79%);
                         margin-left:80px;height:172px ! important;">
                        <div style="justify-content: center; padding:13%; " class="card-body d-flex">
                            <h2 style="color:white; "><br>
                                <center><span class="badge badge-light ">{{ $customerartist ??'453' }}</span></center>
                            </h2>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-6" style="padding: 20px;margin-top:-51px;">
                <h1 style="margin-bottom: 26px">Total Shortlisted</h1>
                <a href="#">
                    <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end h-xl-100 box"
                         style="width:74%;  background-color: #7dc0e7;background-image: linear-gradient(62deg,#293A83 24%,#7dc0e7 79%);
                          margin-left:80px;height:172px ! important;">
                        <div style="justify-content: center; padding:13%; " class="card-body d-flex">
                            <h2 style="color:white; "> <br>
                                <center><span class="badge badge-light ">{{ $business  ??'123'}}</span></center>
                            </h2>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <div class="row">
            <h1 style="margin-left:482px;margin-top:30px">Final List</h1>
            <div class="col-md-6" style="padding:20px">
                <a href="#">
                    <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end h-xl-100 box"
                         style="width:74%;  background-color: #7dc0e7;background-image: linear-gradient(62deg,#293A83 24%,#7dc0e7 79%);
                         margin-left:378px;height:172px ! important;">
                        <div style="justify-content: center; padding:13%; " class="card-body d-flex">
                            <h2 style="color:white;"><br>
                                <center><span class="badge badge-light ">{{ $customerartist ??'456' }}</span></center>
                            </h2>
                        </div>
                    </div>
                </a>
            </div>
            
        </div>
        </div>
    </div>


@endsection

@section('script')
    @parent

@endsection
