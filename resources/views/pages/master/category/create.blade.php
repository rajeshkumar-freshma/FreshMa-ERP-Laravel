<x-default-layout>
    @section('_toolbar')
        @include('pages.partials.common-toolbar', [
            'title' => 'Category',
            'menu_1_link' => route('admin.category.index'),
            'menu_2' => 'Add Category',
        ])
    @endsection
    <div class="card">
        <!--begin::Card header-->
        @include('pages.partials.form_header', ['header_name' => 'Add Category'])
        <!--begin::Card header-->

        <!--begin::Content-->
        <div id="item_details" class="collapse show">
            <!--begin::Form-->
            <form id="category_details_form" class="form" method="POST" action="{{ route('admin.category.store') }}"
                enctype="multipart/form-data">
                @csrf
                <!--begin::Card body-->
                <div class="card-body border-top px-9 py-4">
                    <!--begin::Input group-->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="name" class="required form-label">{{ __('Name') }}</label>
                                <input type="text" name="name" value="{{ old('name') }}"
                                    class="form-control form-control-sm form-control-solid" id="name"
                                    placeholder="Enter Category Name" required />
                                @if ($errors->has('name'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="slug" class="form-label">{{ __('Slug') }}</label>
                                <input type="text" name="slug" value="{{ old('slug') }}"
                                    class="form-control form-control-sm form-control-solid" id="slug"
                                    placeholder="Enter Slug" />
                                @if ($errors->has('slug'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('slug') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="parent_id" class="form-label">{{ __('Parent Category') }}</label>

                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="parent_id" id="parent_id"
                                            aria-label="{{ __('Select Parent Category') }}" data-control="select2"
                                            data-placeholder="{{ __('Select Parent Category..') }}"
                                            class="form-select form-select-sm form-select-solid"
                                            data-allow-clear="true">
                                            <option value="">{{ __('Select Parent Category..') }}</option>
                                            @foreach ($categories as $key => $cat)
                                                <option value="{{ $cat->id }}"
                                                    {{ $cat->id == old('parent_id') ? 'selected' : '' }}
                                                    class="optionGroup">{{ ucFirst($cat->name) }}</option>
                                                @foreach ($cat->getChildrenCategory as $key => $childcat)
                                                    <option value="{{ $childcat->id }}"
                                                        {{ $childcat->id == old('parent_id') ? 'selected' : '' }}>
                                                        |--{{ ucFirst($childcat->name) }}</option>
                                                    @include('pages.master.category.child_category', [
                                                        'child_category' => $childcat,
                                                    ])
                                                @endforeach
                                            @endforeach
                                            {{-- @foreach (\App\Core\Data::getCurrencyList() as $key => $value)
                                                <option data-kt-flag="{{ $value['country']['flag'] }}" value="{{ $key }}" {{ $key == old('currency', $info->currency ?? '') ? 'selected' :'' }}><b>{{ $key }}</b>&nbsp;-&nbsp;{{ $value['name'] }}</option>
                                            @endforeach --}}
                                        </select>
                                    </div>
                                </div>
                                @if ($errors->has('parent_id'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('parent_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="description" class="form-label">{{ __('Description') }}</label>
                                <textarea name="description" class="form-control form-control-sm form-control-solid" id="description"
                                    placeholder="Enter Description">{{ old('description') }}</textarea>
                                @if ($errors->has('description'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="meta_title" class="form-label">{{ __('Meta Title') }}</label>
                                <input type="text" name="meta_title" value="{{ old('meta_title') }}"
                                    class="form-control form-control-sm form-control-solid" id="meta_title"
                                    placeholder="Enter Meta Title" />
                                @if ($errors->has('meta_title'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('meta_title') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="meta_description" class="form-label">{{ __('Meta Description') }}</label>
                                <textarea name="meta_description" class="form-control form-control-sm form-control-solid"
                                    id="meta_description kt_docs_ckeditor_classic" placeholder="Enter Meta Description">{{ old('meta_description') }}</textarea>
                                @if ($errors->has('meta_description'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('meta_description') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="meta_keywords" class="form-label">{{ __('Meta Keywords') }}</label>
                                <textarea name="meta_keywords" class="form-control form-control-sm form-control-solid"
                                    id="meta_keywords kt_docs_ckeditor_classic" placeholder="Enter Meta Keywords">{{ old('meta_keywords') }}</textarea>
                                @if ($errors->has('meta_keywords'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('meta_keywords') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="status" class="required form-label">{{ __('Status') }}</label>

                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="status" id="status" aria-label="{{ __('Select Status') }}"
                                            data-control="select2" data-placeholder="{{ __('Select Status..') }}"
                                            class="form-select form-select-sm form-select-solid"
                                            data-allow-clear="true" required>
                                            <option value="">{{ __('Select Status..') }}</option>
                                            @foreach (config('app.statusinactive') as $key => $value)
                                                <option data-kt-flag="{{ $value['value'] }}"
                                                    value="{{ $value['value'] }}"
                                                    {{ $value['value'] == old('status') ? 'selected' : '' }}>
                                                    {{ $value['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if ($errors->has('status'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('status') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="is_featured" class="required form-label">{{ __('is Featured') }}</label>

                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="is_featured" id="is_featured"
                                            aria-label="{{ __('Select Option') }}" data-control="select2"
                                            data-placeholder="{{ __('Select Option..') }}"
                                            class="form-select form-select-sm form-select-solid"
                                            data-allow-clear="true" required>
                                            <option value="">{{ __('Select Option..') }}</option>
                                            @foreach (config('app.yesorno') as $key => $value)
                                                <option data-kt-flag="{{ $value['value'] }}"
                                                    value="{{ $value['value'] }}"
                                                    {{ $value['value'] == old('status') ? 'selected' : '' }}>
                                                    {{ $value['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if ($errors->has('is_featured'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('is_featured') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="image" class="form-label">{{ __('Attachments') }}</label>
                                <input type="file" name="image" class="form-control form-control-sm"
                                    id="image">
                                @if ($errors->has('image'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('image') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Card body-->

                <!--begin::Actions-->
                @include('pages.partials.form_footer', [
                    'is_save' => true,
                    'back_url' => route('admin.category.index'),
                ])
                <!--end::Actions-->
            </form>
            <!--end::Form-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Basic info-->
</x-default-layout>
