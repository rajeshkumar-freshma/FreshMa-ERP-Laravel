<!--begin::Logo-->
<div class="app-sidebar-logo px-6" id="kt_app_sidebar_logo">
    <!--begin::Logo image-->
    <a href="{{ route('admin.dashboard') }}">
        {{-- @php
            $default_image = \Storage::disk('s3')->temporaryUrl('media/logos/main_logo.png', '+20 minutes');
            $small_image = \Storage::disk('s3')->temporaryUrl('media/logos/small_logo.png', '+20 minutes');
            $default_image = image('logos/fav.png');
        @endphp
        @if (isDarkSidebar())
            <img alt="Logo" src="{{ $default_image }}" onerror="this.onerror=null;this.src='{{ $default_image }}';"
                class="h-40px app-sidebar-logo-default" />
            <!-- Making the text "FreshMa" big, white, and 20 pixels in size -->
            <b style="font-size: 20px; color: white;">FreshMa</b>
        @else
            <img alt="Logo" src="{{ $default_image }}" onerror="this.onerror=null;this.src='{{ $default_image }}';"
                class="h-40px app-sidebar-logo-default" />
            <b style="font-size: 20px; color: white;">FreshMa</b>
        @endif
        <img alt="Logo" src="{{ $small_image }}" onerror="this.onerror=null;this.src='{{ $default_image }}';"
            class="h-40px app-sidebar-logo-minimize" /> --}}
            <span class="fs-1 text-success">FreshMa</span>
    </a>
    <!--end::Logo image-->
    <!--begin::Sidebar toggle-->
    <!--begin::Minimized sidebar setup:
            if (isset($_COOKIE["sidebar_minimize_state"]) && $_COOKIE["sidebar_minimize_state"] === "on") {
                1. "src/js/layout/sidebar.js" adds "sidebar_minimize_state" cookie value to save the sidebar minimize state.
                2. Set data-kt-app-sidebar-minimize="on" attribute for body tag.
                3. Set data-kt-toggle-state="active" attribute to the toggle element with "kt_app_sidebar_toggle" id.
                4. Add "active" class to to sidebar toggle element with "kt_app_sidebar_toggle" id.
            }
        -->
    <div id="kt_app_sidebar_toggle"
        class="app-sidebar-toggle btn btn-icon btn-shadow btn-sm btn-color-muted btn-active-color-primary h-30px w-30px position-absolute top-50 start-100 translate-middle rotate"
        data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body"
        data-kt-toggle-name="app-sidebar-minimize">{!! getIcon('black-left-line', 'fs-3 rotate-180 ms-1') !!}</div>
    <!--end::Sidebar toggle-->
</div>
<!--end::Logo-->
