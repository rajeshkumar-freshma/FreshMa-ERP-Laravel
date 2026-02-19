<!-- resources/views/includes/common-toolbar.blade.php -->
@php
    $data['title'] = $title ?? 'Default Title';
    $data['menu_1'] = $menu_1 ?? 'Home';
    $data['menu_1_link'] = $menu_1_link ?? route('admin.dashboard');
    $data['menu_2'] = $menu_2 ?? 'Dashboard';
@endphp

@include(config('settings.KT_THEME_LAYOUT_DIR') . '/partials/sidebar-layout/_toolbar', [
    'data' => $data,
])
