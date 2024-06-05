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
           
        </div>
        <!--end::Toolbar container-->
    </div>

    


@endsection

@section('script')
    @parent

@endsection
