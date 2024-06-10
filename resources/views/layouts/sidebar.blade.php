<div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="225px" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle"> 
      <div class="app-sidebar-logo px-6" id="kt_app_sidebar_logo">
        <a href="" style="background-color:#293A83">
          <img alt="Logo" src="{{ asset('images/Group.png') }}" class="h-50px app-sidebar-logo-default theme-light-show" />
          <img alt="Logo" src="{{ asset('images/Group.png') }}" class="h-50px app-sidebar-logo-default theme-dark-show" />
          <img alt="Logo" src="{{ asset('images/Group.png') }}"  class="h-20px app-sidebar-logo-minimize" />
        </a>
        <div id="kt_app_sidebar_toggle" class="app-sidebar-toggle btn btn-icon btn-shadow btn-sm btn-color-muted btn-active-color-primary body-bg h-30px w-30px position-absolute top-50 start-100 translate-middle rotate" data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body" data-kt-toggle-name="app-sidebar-minimize">
              <span class="svg-icon svg-icon-2 rotate-180">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path opacity="0.5" d="M14.2657 11.4343L18.45 7.25C18.8642 6.83579 18.8642 6.16421 18.45 5.75C18.0358 5.33579 17.3642 5.33579 16.95 5.75L11.4071 11.2929C11.0166 11.6834 11.0166 12.3166 11.4071 12.7071L16.95 18.25C17.3642 18.6642 18.0358 18.6642 18.45 18.25C18.8642 17.8358 18.8642 17.1642 18.45 16.75L14.2657 12.5657C13.9533 12.2533 13.9533 11.7467 14.2657 11.4343Z" fill="currentColor" />
                  <path d="M8.2657 11.4343L12.45 7.25C12.8642 6.83579 12.8642 6.16421 12.45 5.75C12.0358 5.33579 11.3642 5.33579 10.95 5.75L5.40712 11.2929C5.01659 11.6834 5.01659 12.3166 5.40712 12.7071L10.95 18.25C11.3642 18.6642 12.0358 18.6642 12.45 18.25C12.8642 17.8358 12.8642 17.1642 12.45 16.75L8.2657 12.5657C7.95328 12.2533 7.95328 11.7467 8.2657 11.4343Z" fill="currentColor" />
                </svg>
              </span>
        </div>
      </div>
  <div class="app-sidebar-menu overflow-hidden flex-column-fluid" style=" background-color: #7dc0e7;background-image: linear-gradient(62deg,#293A83 24%,#7dc0e7 79%);">
        <div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper hover-scroll-overlay-y my-5" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer" data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px" data-kt-scroll-save-state="true">
          <div class="menu menu-column menu-rounded menu-sub-indention px-3" id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false">
                <div class="menu-item">
                    <a style="" class="menu-link {{(request()->is('dashboard*')) ? 'active' : '' }}" href="{{url('dashboard')}}">
                          <span class="menu-icon">
                            <span class="svg-icon svg-icon-2">
                              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="2" y="2" width="9" height="9" rx="2" fill="currentColor" />
                                <rect opacity="0.3" x="13" y="2" width="9" height="9" rx="2" fill="currentColor" />
                                <rect opacity="0.3" x="13" y="13" width="9" height="9" rx="2" fill="currentColor" />
                                <rect opacity="0.3" x="2" y="13" width="9" height="9" rx="2" fill="currentColor" />
                              </svg>
                            </span>
                          </span>
                          <span  style="color: white" class="menu-title">Dashboard</span>
                        </a>
                </div>
                @php
                 $name=Auth::user()->role->name;
                @endphp
                @if($name =='Super Admin')
                <div class="menu-item">
                  <a class="menu-link {{(request()->is('user*')) ? 'active' : '' }}" href="{{url('user')}}">
                        <span class="menu-icon">
                          <span class="svg-icon svg-icon-2">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304H178.3z"/></svg>
                          </span>
                        </span>
                        <span style="color: white" class="menu-title">User</span>
                      </a>
              </div>
              @endif
                <div class="menu-item">
                  <a class="menu-link {{(request()->is('employee/register-list*')) ? 'active' : '' }}" href="{{url('employee/register-list')}}">
                        <span class="menu-icon">
                          <span class="svg-icon svg-icon-2">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <rect x="2" y="2" width="9" height="9" rx="2" fill="currentColor" />
                              <rect opacity="0.3" x="13" y="2" width="9" height="9" rx="2" fill="currentColor" />
                              <rect opacity="0.3" x="13" y="13" width="9" height="9" rx="2" fill="currentColor" />
                              <rect opacity="0.3" x="2" y="13" width="9" height="9" rx="2" fill="currentColor" />
                            </svg>
                          </span>
                        </span>
                        <span  style="color: white" class="menu-title">Register List</span>
                      </a>
              </div>
              <div class="menu-item">
                <a class="menu-link {{(request()->is('employee/short-list*')) ? 'active' : '' }}" href="{{url('employee/short-list')}}">
                      <span class="menu-icon">
                        <span class="svg-icon svg-icon-2">
                          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="2" y="2" width="9" height="9" rx="2" fill="currentColor" />
                            <rect opacity="0.3" x="13" y="2" width="9" height="9" rx="2" fill="currentColor" />
                            <rect opacity="0.3" x="13" y="13" width="9" height="9" rx="2" fill="currentColor" />
                            <rect opacity="0.3" x="2" y="13" width="9" height="9" rx="2" fill="currentColor" />
                          </svg>
                        </span>
                      </span>
                      <span  style="color: white" class="menu-title">Shortlisted List</span>
                    </a>
            </div>
            <div class="menu-item">
              <a class="menu-link {{(request()->is('employee/final-list*')) ? 'active' : '' }}" href="{{url('employee/final-list')}}">
                    <span class="menu-icon">
                      <span class="svg-icon svg-icon-2">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <rect x="2" y="2" width="9" height="9" rx="2" fill="currentColor" />
                          <rect opacity="0.3" x="13" y="2" width="9" height="9" rx="2" fill="currentColor" />
                          <rect opacity="0.3" x="13" y="13" width="9" height="9" rx="2" fill="currentColor" />
                          <rect opacity="0.3" x="2" y="13" width="9" height="9" rx="2" fill="currentColor" />
                        </svg>
                      </span>
                    </span>
                    <span  style="color: white" class="menu-title">Final List</span>
                  </a>
             </div>
              <div>        
              </div>
            </div>
        </div>
  </div>
</div>
