<x-default-layout>
    <!--begin::Card header-->
    @section('_toolbar')
        @include('pages.partials.common-toolbar', [
            'title' => 'Product',
            'menu_1_link' => route('admin.product.index'),
            'menu_2' => 'Edit Product',
        ])
    @endsection
    <!--begin::Card header-->
    <!--begin::Form-->
    <form id="kt_ecommerce_add_product_form" method="POST" action="{{ route('admin.product.update', $product->id) }}"
        class="form d-flex flex-column flex-lg-row" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <!--begin::Aside column-->
        <div class="d-flex flex-column gap-7 gap-lg-10 w-100 w-lg-300px mb-7 me-lg-10">
            <!--begin::Thumbnail settings-->
            <div class="card card-flush py-4">
                <!--begin::Card header-->
                <div class="card-header">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <h2 class="required">Thumbnail</h2>
                    </div>
                    <!--end::Card title-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body text-center pt-0">
                    <!--begin::Image input-->
                    <!--begin::Image input placeholder-->
                    <style>
                        .image-input-placeholder {
                            background-image: url('<?php echo str_replace('&amp;', '&', $product->image_full_url); ?>');
                        }

                        [data-bs-theme="dark"] .image-input-placeholder {
                            background-image: url('<?php echo str_replace('&amp;', '&', $product->image_full_url); ?>');
                        }
                    </style>
                    <!--end::Image input placeholder-->
                    <div class="image-input image-input-empty image-input-outline image-input-placeholder mb-3"
                        data-kt-image-input="true">
                        <!--begin::Preview existing avatar-->
                        <div class="image-input-wrapper w-150px h-150px"></div>
                        <!--end::Preview existing avatar-->
                        <!--begin::Label-->
                        <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                            data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change avatar">
                            <i class="bi bi-pencil-fill fs-7"></i>
                            <!--begin::Inputs-->
                            <input type="file" name="image" accept=".png, .jpg, .jpeg" />
                            <!--end::Inputs-->
                        </label>
                        <!--end::Label-->
                        <!--begin::Cancel-->
                        <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                            data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel avatar">
                            <i class="bi bi-x fs-2"></i>
                        </span>
                        <!--end::Cancel-->
                        <!--begin::Remove-->
                        <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                            data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove avatar">
                            <i class="bi bi-x fs-2"></i>
                        </span>
                        <!--end::Remove-->
                    </div>
                    <!--end::Image input-->
                    <!--begin::Description-->
                    <div class="text-muted fs-7">Set the product thumbnail image. Only *.png, *.jpg and *.jpeg image
                        files are accepted</div>
                    <!--end::Description-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Thumbnail settings-->

            <!--begin::Status-->
            <div class="card card-flush py-4">
                <!--begin::Card header-->
                <div class="card-header">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <h2 class="required">Status</h2>
                    </div>
                    <!--end::Card title-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <div class="input-group input-group-sm flex-nowrap">
                        <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                        <div class="overflow-hidden flex-grow-1">
                            <select name="status" id="status" aria-label="{{ __('Select Status') }}"
                                data-control="select2" data-placeholder="{{ __('Select Status..') }}"
                                class="form-select form-select-solid" data-allow-clear="true" data-hide-search="true"
                                required>
                                <option value="">{{ __('Select Status..') }}</option>
                                @foreach (config('app.statusinactive') as $key => $value)
                                    <option data-kt-flag="{{ $value['value'] }}" value="{{ $value['value'] }}"
                                        {{ $value['value'] == old('status', $product->status) ? 'selected' : '' }}>
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
                <!--end::Card body-->
            </div>
            <!--end::Status-->
        </div>
        <!--end::Aside column-->
        <!--begin::Main column-->
        <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
            <!--begin:::Tabs-->
            <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-n2">
                <!--begin:::Tab item-->
                <li class="nav-item">
                    <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab"
                        href="#kt_ecommerce_add_product_general">General</a>
                </li>
                <!--end:::Tab item-->
                <!--begin:::Tab item-->
                <li class="nav-item">
                    <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab"
                        href="#kt_ecommerce_add_product_advanced">Advanced</a>
                </li>
                <!--end:::Tab item-->
            </ul>
            <!--end:::Tabs-->
            <!--begin::Tab content-->
            <div class="tab-content">
                <!--begin::Tab pane-->
                <div class="tab-pane fade show active" id="kt_ecommerce_add_product_general" role="tab-panel">
                    <div class="d-flex flex-column gap-7 gap-lg-10">
                        <!--begin::General options-->
                        <div class="card card-flush py-4">
                            <!--begin::Card header-->
                            <div class="card-header">
                                <div class="card-title">
                                    <h2>General</h2>
                                </div>
                            </div>
                            <!--end::Card header-->
                            <!--begin::Card body-->
                            <div class="card-body pt-0">
                                <!--begin::Input group-->

                                <div class="mb-5">
                                    <label for="name" class="required form-label">{{ __('Product Name') }}</label>
                                    <input type="text" name="name" value="{{ old('name', $product->name) }}"
                                        class="form-control form-control-solid" id="name"
                                        placeholder="Enter Product Name" required />
                                    @if ($errors->has('name'))
                                        <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="mb-5">
                                    <label for="slug" class="form-label">{{ __('Slug') }}</label>
                                    <input type="text" name="slug" value="{{ old('slug', $product->slug) }}"
                                        class="form-control form-control-solid" id="slug"
                                        placeholder="Enter Slug" />
                                    @if ($errors->has('slug'))
                                        <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('slug') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="mb-5">
                                    <label for="category_id" class="form-label required">{{ __('Category') }}</label>

                                    <div class="input-group input-group-sm flex-nowrap">
                                        <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                        <div class="overflow-hidden flex-grow-1">
                                            <select name="category_id[]" id="category_id"
                                                aria-label="{{ __('Select Category') }}" data-control="select2"
                                                data-placeholder="{{ __('Select Category..') }}"
                                                class="form-select form-select-solid" data-allow-clear="true" required
                                                multiple>
                                                <option value="">{{ __('Select Category..') }}</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}"
                                                        {{ in_array($category->id, old('category_id', $product->product_category->pluck('category_id')->toArray())) ? 'selected' : '' }}
                                                        class="optionGroup">{{ ucFirst($category->name) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    @if ($errors->has('category_id'))
                                        <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('category_id') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="mb-5">
                                    <label for="sku_code" class="form-label">{{ __('SKU Code') }}</label>
                                    <input type="text" name="sku_code"
                                        value="{{ old('sku_code', $product->sku_code) }}"
                                        class="form-control form-control-solid" id="sku_code"
                                        placeholder="Enter SKU Code" />
                                    @if ($errors->has('sku_code'))
                                        <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('sku_code') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="mb-5">
                                    <label for="hsn_code" class="form-label">{{ __('HSN Code') }}</label>
                                    <input type="text" name="hsn_code"
                                        value="{{ old('hsn_code', $product->hsn_code) }}"
                                        class="form-control form-control-solid" id="hsn_code"
                                        placeholder="Enter HSN Code" />
                                    @if ($errors->has('hsn_code'))
                                        <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('hsn_code') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="mb-5">
                                    <label for="description" class="form-label">{{ __('Description') }}</label>

                                    <textarea name="description" class="form-control form-control-solid" id="description kt_docs_ckeditor_classic"
                                        placeholder="Enter Description" rows="5">{{ old('description', $product->product_description) }}</textarea>
                                    @if ($errors->has('description'))
                                        <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('description') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <!--end::Input group-->

                            </div>
                            <!--end::Card header-->
                        </div>
                        <!--end::General options-->
                    </div>
                </div>
                <!--end::Tab pane-->
                <!--begin::Tab pane-->
                <div class="tab-pane fade" id="kt_ecommerce_add_product_advanced" role="tab-panel">
                    <div class="d-flex flex-column gap-7 gap-lg-10">
                        <!--begin::Inventory-->
                        <div class="card card-flush py-4">
                            <!--begin::Card header-->
                            <div class="card-header">
                                <div class="card-title">
                                    <h2>Inventory</h2>
                                </div>
                            </div>
                            <!--end::Card header-->
                            <!--begin::Card body-->
                            <div class="card-body pt-0">
                                <!--begin::Input group-->
                                <div class="mb-5">
                                    <label for="item_type_id" class="form-label">{{ __('Item Type') }}</label>

                                    <div class="input-group input-group-sm flex-nowrap">
                                        <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                        <div class="overflow-hidden flex-grow-1">
                                            <select name="item_type_id" id="item_type_id"
                                                aria-label="{{ __('Select Item Type') }}" data-control="select2"
                                                data-placeholder="{{ __('Select Item Type..') }}"
                                                class="form-select form-select-solid" data-allow-clear="true">
                                                <option value="">{{ __('Select Item Type..') }}</option>
                                                @foreach ($item_types as $item_type)
                                                    <option value="{{ $item_type->id }}"
                                                        {{ $item_type->id == old('item_type_id', $product->item_type_id) ? 'selected' : '' }}
                                                        class="optionGroup">{{ ucFirst($item_type->name) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    @if ($errors->has('item_type_id'))
                                        <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('item_type_id') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="mb-5">
                                    <label for="unit_id" class="form-label">{{ __('Unit') }}</label>

                                    <div class="input-group input-group-sm flex-nowrap">
                                        <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                        <div class="overflow-hidden flex-grow-1">
                                            <select name="unit_id" id="unit_id"
                                                aria-label="{{ __('Select Unit') }}" data-control="select2"
                                                data-placeholder="{{ __('Select Unit..') }}"
                                                class="form-select form-select-solid" data-allow-clear="true">
                                                <option value="">{{ __('Select Unit..') }}</option>
                                                @foreach ($units as $unit)
                                                    <option value="{{ $unit->id }}"
                                                        {{ $unit->id == old('unit_id', $product->unit_id) ? 'selected' : '' }}
                                                        class="optionGroup">{{ ucFirst($unit->unit_name) }} -
                                                        {{ $unit->unit_short_code }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    @if ($errors->has('unit_id'))
                                        <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('unit_id') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="mb-5">
                                    <label for="tax_type" class="form-label">{{ __('Tax Type') }}</label>

                                    <div class="input-group input-group-sm flex-nowrap">
                                        <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                        <div class="overflow-hidden flex-grow-1">
                                            <select name="tax_type" id="tax_type"
                                                aria-label="{{ __('Select Tax Type') }}" data-control="select2"
                                                data-placeholder="{{ __('Select Tax Type..') }}"
                                                class="form-select form-select-solid" data-allow-clear="true"
                                                data-hide-search="true">
                                                <option value="">{{ __('Select Tax Type..') }}</option>
                                                @foreach (config('app.tax_type') as $key => $value)
                                                    <option data-kt-flag="{{ $key }}"
                                                        value="{{ $key }}"
                                                        {{ $key == old('tax_type', $product->tax_type) ? 'selected' : '' }}>
                                                        {{ ucfirst($value) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    @if ($errors->has('tax_type'))
                                        <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('tax_type') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="mb-5">
                                    <label for="tax_id" class="form-label">{{ __('Tax Rate') }}</label>

                                    <div class="input-group input-group-sm flex-nowrap">
                                        <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                        <div class="overflow-hidden flex-grow-1">
                                            <select name="tax_id" id="tax_id"
                                                aria-label="{{ __('Select Tax Rate') }}" data-control="select2"
                                                data-placeholder="{{ __('Select Tax Rate..') }}"
                                                class="form-select form-select-solid" data-allow-clear="true">
                                                <option value="">{{ __('Select Tax Rate..') }}</option>
                                                @foreach ($tax_rates as $tax_rate)
                                                    <option value="{{ $tax_rate->id }}"
                                                        {{ $tax_rate->id == old('tax_id', $product->tax_id) ? 'selected' : '' }}
                                                        class="optionGroup">{{ ucFirst($tax_rate->tax_name) }} -
                                                        {{ $tax_rate->tax_rate }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    @if ($errors->has('tax_id'))
                                        <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('tax_id') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Card header-->
                        </div>
                        <!--end::Inventory-->

                        <!--begin::Media-->
                        <div class="card card-flush py-4">
                            <!--begin::Card header-->
                            <div class="card-header">
                                <div class="card-title">
                                    <h2>Media {!! count($product->product_image) > 0
                                        ? '<i data-bs-toggle="modal" data-bs-target="#product_image_modal" class="fa fa-eye"></i>'
                                        : '' !!}</h2>
                                </div>
                            </div>
                            <!--end::Card header-->
                            <!--begin::Card body-->
                            <div class="card-body pt-0">
                                <!--begin::Input group-->
                                <div class="fv-row mb-2">
                                    <!--begin::Dropzone-->
                                    <input type="file" class="form-control" id="file_upload"
                                        name="product_images[]" multiple>
                                    <!--end::Dropzone-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Description-->
                                <div class="text-muted fs-7">Set the product media gallery.</div>
                                <!--end::Description-->
                            </div>
                            <!--end::Card header-->
                        </div>
                        <!--end::Media-->

                        <!--begin::Meta options-->
                        <div class="card card-flush py-4">
                            <!--begin::Card header-->
                            <div class="card-header">
                                <div class="card-title">
                                    <h2>Meta Options</h2>
                                </div>
                            </div>
                            <!--end::Card header-->
                            <!--begin::Card body-->
                            <div class="card-body pt-0">
                                <!--begin::Input group-->
                                <div class="mb-5">
                                    <label for="meta_title" class="form-label">{{ __('Meta Title') }}</label>
                                    <input type="text" name="meta_title"
                                        value="{{ old('meta_title', $product->meta_title) }}"
                                        class="form-control form-control-solid" id="meta_title"
                                        placeholder="Enter Meta Title" />
                                    @if ($errors->has('meta_title'))
                                        <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('meta_title') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="mb-5">
                                    <label for="meta_description"
                                        class="form-label">{{ __('Meta Description') }}</label>
                                    <textarea name="meta_description" class="form-control form-control-solid" rows="5"
                                        id="meta_description kt_docs_ckeditor_classic" placeholder="Enter Meta Description">{{ old('meta_description', $product->meta_description) }}</textarea>
                                    @if ($errors->has('meta_description'))
                                        <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('meta_description') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="mb-5">
                                    <label for="meta_keywords" class="form-label">{{ __('Meta Keywords') }}</label>
                                    <textarea name="meta_keywords" class="form-control form-control-solid" rows="5"
                                        id="meta_keywords kt_docs_ckeditor_classic" placeholder="Enter Meta Keywords">{{ old('meta_keywords', $product->meta_keywords) }}</textarea>
                                    @if ($errors->has('meta_keywords'))
                                        <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('meta_keywords') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Card header-->
                        </div>
                        <!--end::Meta options-->
                    </div>
                </div>
                <!--end::Tab pane-->
            </div>
            <!--end::Tab content-->

            <!--begin::Actions-->
            @include('pages.partials.form_footer', [
                'is_save' => false,
                'back_url' => route('admin.product.index'),
            ])
            <!--end::Actions-->
        </div>
        <!--end::Main column-->
    </form>

    <!--Modal -->
    <!--begin::Modal - Two-factor authentication-->
    <div class="modal fade" id="product_image_modal" tabindex="-1" aria-hidden="true">
        <!--begin::Modal header-->
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Product Images</h5>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                        aria-label="Close">
                        <i class="ki-duotone ki-cross fs-2x"><span class="path1"></span><span
                                class="path2"></span></i>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body">
                    @if (count($product->product_image) > 0)
                        <div class="row">
                            @foreach ($product->product_image as $item)
                                <div class="col-lg-4 mb-2">
                                    <!--begin::Card-->
                                    <div class="card  overlay overflow-hidden">
                                        <div class="card-body p-0">
                                            <div class="overlay-wrapper">
                                                <img src="{{ $item->image_full_url }}" alt=""
                                                    class="w-100 rounded" />
                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Card-->
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
        <!--end::Modal header-->
    </div>
    <!--end::Modal - Two-factor authentication-->

    <!--Modal -->
    <!--end::Form-->
</x-default-layout>
