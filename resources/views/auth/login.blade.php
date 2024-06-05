<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
 <title>@yield('title',  config('app.name') )</title>

 <meta charset="utf-8"/>
 <meta name="description" content="StartHelath Admin"/>
 <meta name="keywords" content="admin"/>
 <meta name="viewport" content="width=device-width, initial-scale=1"/>
 <meta property="og:locale" content="en_US"/>
 <meta property="og:type" content="article"/>
 <meta property="og:title" content="StartHelath"/>
 <meta property="og:site_name" content="StartHelath | Admin"/>
 <link rel="shortcut icon" href="{{ asset('images/Group.png') }}"/>
<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
 @section('style')
 <link href="{{asset('css/style.bundle.css')}}" rel="stylesheet" type="text/css" />
 <link rel="stylesheet" href="{{ asset('css/style.css') }}"/>
 @show

</head>
<div class="d-flex flex-column flex-root" style="background-color: rgb(255, 255, 255)">
  <div class="d-flex flex-column flex-xl-row flex-column-fluid">
      <div class="d-flex flex-column flex-center flex-lg-row-fluid">
         <div class="d-flex align-items-start flex-column p-5 p-lg-15">
          <a href="javascript:void(0)" class="mb-15" style="background-color:#293A83">
              <img alt="Logo" src="{{ asset('images/Group.png') }}" class="h-40px" />
          </a>
          <h1 class="text-dark fs-2x mb-3">Welcome, Admin</h1>
          <img src="{{ asset('images/Frame 7.png') }}" class="h-250px h-lg-450px" />
      </div>
      </div>

      <div class="flex-row-fluid d-flex flex-center justfiy-content-xl-first p-10">
          <div class="d-flex flex-center p-15 shadow-sm bg-body rounded w-100 w-md-550px mx-auto ms-xl-20">
              {{-- <form class="form w-100 fv-plugins-bootstrap5 fv-plugins-framework" novalidate="novalidate"
                  action="{{ route('login') }}" method="POST">
                  @csrf
                  <div class="text-center mb-10">
                      <h1 class="text-dark mb-3">Sign In to <img alt="Logo" src="{{ asset('assets/logo/logo.webp') }}" class="h-20px" /></h1>
                      <div class="text-gray-400 fw-bold fs-4">
                          <div class="fv-plugins-message-container invalid-feedback">
                              @error('email')
                                  <div data-field="email" data-validator="notEmpty">{{ $message }}</div>
                              @enderror
                          </div>
                      </div>
                  </div>
                  <div class="fv-row mb-10 fv-plugins-icon-container">
                      <label class="form-label fs-6 fw-bolder text-dark">Email</label>
                      <input class="form-control form-control-lg form-control-solid" type="text" name="email"
                          autocomplete="off" value="{{ old('email') }}" required>
                     
                      <div class="fv-plugins-message-container invalid-feedback">                        
                      </div>
                  </div>
                  <div class="fv-row mb-10 fv-plugins-icon-container">
                      <div class="d-flex flex-stack mb-2">
                          <label class="form-label fw-bolder text-dark fs-6 mb-0">Password</label>
                          @if (Route::has('password.request'))
                              <a href="{{ route('password.request') }}" class="link-primary fs-6 fw-bolder">Forgot
                                  Password ?</a>
                          @endif
                      </div>
                      <input class="form-control form-control-lg form-control-solid" type="password"
                          name="password" autocomplete="off">
                      <div class="fv-plugins-message-container invalid-feedback">
                          @error('password')
                                  <strong>{{ $message }}</strong>
                          @enderror
                      </div>
                     
                  </div>
                  <div class="d-grid mb-10">
                    <button type="submit" id="kt_sign_in_submit" class="btn btn-primary">
                     <span class="indicator-label">Log In</span>
                     <span class="indicator-progress">Please wait...
                      <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                     </button>
                    </div>
                  <div></div>
              </form> --}}
              <form class="form w-100 fv-plugins-bootstrap5 fv-plugins-framework" novalidate="novalidate" method="post" action="{{ url('login') }}">
                @csrf
                <div class="text-center mb-11">
                 <!--begin::Title-->
                 <h1 class="text-dark mb-3">Sign In to <img alt="Logo" style="background-color:#293A83" src="{{ asset('images/Group.png') }}" class="h-30px" /></h1>
                 <!--end::Title-->
                 <div class="text-gray-400 fw-bold fs-4">
                 @if (session()->has('message'))
                <div class="alert alert-success" role="alert">
                    {{ session('message') }}
                </div>
                @endif
                 </div>
       
                </div>
                <div class="fv-row mb-8">
                   <label class=" form-label">Email:</label>
                 <input type="text" placeholder="Email" name="email" autocomplete="off" class=" form-control bg-transparent" value="{{old('email')}}" required />
                 @error('email')
                   <div data-field="email" data-validator="notEmpty" style="color:red;font-size:15px">{{ $message }}</div>
                   @enderror
                </div>
                <div class="fv-row mb-3">
                 <label class=" form-label">Password:</label>
                 <input type="password" id="password-field"  placeholder="Password" name="password" class="form-control bg-transparent" required/>
                <span toggle="#password-field" class="fa fa-fw fa-eye-slash field-icon toggle-password"></span>
                @error('password')
                   <div data-field="password" data-validator="notEmpty" style="color:red;font-size:15px">{{ $message }}</div>
                   @enderror
              </div>
                <div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold mb-8">
                 <div></div>
       
                </div>
                <div class="d-grid mb-10">
                 <button type="submit" id="kt_sign_in_submit" class="btn btn-primary">
                  <span class="indicator-label">Log In</span>
                  <span class="indicator-progress">Please wait...
                   <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                  </button>
                 </div>
                  
                 </form>
          </div>
      </div>
  </div>
</div>
    @section('script')
    <script>var defaultThemeMode = "light"; var themeMode; if ( document.documentElement ) { if ( document.documentElement.hasAttribute("data-theme-mode")) { themeMode = document.documentElement.getAttribute("data-theme-mode"); } else { if ( localStorage.getItem("data-theme") !== null ) { themeMode = localStorage.getItem("data-theme"); } else { themeMode = defaultThemeMode; } } if (themeMode === "system") { themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light"; } document.documentElement.setAttribute("data-theme", themeMode); }</script>
    <script src="{{ asset('plugins/global/plugins.bundle.js')}}"></script>
    <script src="{{ asset('js/scripts.bundle.js')}}"></script>
    <script src="{{ asset('js/common.js') }}"></script>
    @show
   </body>
   </html>
