<!--begin::Page title-->
<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
	<!--begin::Title-->
	<h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">{{ __($data['title']!=null ? $data['title'] : 'Default') }}</h1>
	<!--end::Title-->
	<!--begin::Breadcrumb-->
	<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
		<!--begin::Item-->
		<li class="breadcrumb-item text-muted">
			<a href="{{ __($data['menu_1_link']!=null ? $data['menu_1_link'] : '/') }}" class="text-muted text-hover-primary">{{ __($data['menu_1']!=null ? $data['menu_1'] : 'Default') }}</a>
		</li>
		<!--end::Item-->
		<!--begin::Item-->
		<li class="breadcrumb-item">
			<span class="bullet bg-gray-400 w-5px h-2px"></span>
		</li>
		<!--end::Item-->
		<!--begin::Item-->
		<li class="breadcrumb-item text-muted">{{ __($data['menu_2']!=null ? $data['menu_2'] : 'Default') }}</li>
		<!--end::Item-->
	</ul>
	<!--end::Breadcrumb-->
</div>
<!--end::Page title-->
