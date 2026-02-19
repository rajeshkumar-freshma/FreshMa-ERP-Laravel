<x-default-layout>
    <!--begin::Card-->
    @section('_toolbar')
        @include('pages.partials.common-toolbar', [
            'title' => 'Edit Staff Advance',
            'menu_1_link' => route('admin.staff-advance.index'),
            'menu_2' => 'Edit Staff Advance',
        ])
    @endsection
    <!--begin::Basic info-->
    <div class="card">
        <!--begin::Card header-->
        @include('pages.partials.form_header', ['header_name' => 'Staff Advance'])
        <!--begin::Card header-->
        <!--begin::Content-->
        <div id="staff_advanced" class="collapse show">
            <!--begin::Form-->
            <form id="staff_advanced_form" class="form" method="POST"
                action="{{ route('admin.staff-advance.update', @$staff_advanced->id) }}" enctype="multipart/form-data">
                @csrf
                @method('put')
                <!--begin::Card body-->
                <div class="card-body border-top px-9 py-4">
                    <!--begin::Input group-->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="name" class="required form-label">{{ __('Staff') }}</label>
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="user_id" id="user_id" aria-label="{{ __('Select Staff') }}"
                                            data-control="select2" data-placeholder="{{ __('Select Staff..') }}"
                                            class="form-select form-select-sm form-select-solid" data-allow-clear="true"
                                            required>
                                            <option value="">{{ __('Select Staff..') }}</option>
                                            @foreach (@$Staff as $key => $users)
                                                <option data-kt-flag="{{ $users->id }}" value="{{ $users->id }}"
                                                    {{ $users->id == old('user_id', @$staff_advanced->staff_id) ? 'selected' : '' }}>
                                                    {{ $users->first_name . '-' . $users->last_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if ($errors->has('user_id'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('user_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="name" class="required form-label">{{ __('Payment Type') }}</label>
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="payment_type_id" id="payment_type_id"
                                            aria-label="{{ __('Select Payment Type') }}" data-control="select2"
                                            data-placeholder="{{ __('Select Payment Type..') }}"
                                            class="form-select form-select-sm form-select-solid" data-allow-clear="true"
                                            required>
                                            <option value="">{{ __('Select Payment Type..') }}</option>
                                            @foreach (@$paymentTypes as $key => $types)
                                                <option data-kt-flag="{{ $types->id }}" value="{{ $types->id }}"
                                                    {{ $types->id == old('payment_type_id', @$staff_advanced->payment_type_id) ? 'selected' : '' }}>
                                                    {{ $types->payment_type }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if ($errors->has('payment_type_id'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('payment_type_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="date" class="required form-label">{{ __('Date') }}</label>
                                <div class="input-group">
                                    <input id="date" type="button"
                                        class="form-control form-control-sm form-control-solid fsh_flat_datepicker "
                                        placeholder="Enter Date" name="date"
                                        value="{{ old('date', @$staff_advanced->date) }}" required />
                                    <span class="input-group-text border-0">
                                        <i class="fas fa-calendar"></i>
                                    </span>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="amount" class="required form-label">{{ __('Amount') }}</label>
                                <div class="input-group">
                                    <input id="amount" type="text"
                                        value="{{ old('amount', @$staff_advanced->amount) }}"
                                        placeholder="Enter Amount"
                                        class="form-control form-control-sm form-control-solid" name="amount"
                                        value="" required />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="staff_advance_documents" id="ir_file"
                                    class="required form-label">{{ __('Attachment') }}</label>
                                <div class="input-group">
                                    <!-- Image preview -->
                                    @if (isset($staff_advanced) && !empty($staff_advanced->staff_adavance_history->image_full_url))
                                        <img src="{{ $staff_advanced->staff_adavance_history->image_full_url }}"
                                            alt="Image Preview" class="img-thumbnail preview-image"
                                            style="max-width: 100px; max-height: 100px; margin-right: 10px;">
                                    @endif
                                    <!-- File input -->
                                    <input id="ir_file" type="file"
                                        class="form-control form-control-sm form-control-solid"
                                        name="staff_advance_documents" value="{{ old('staff_advance_documents') }}" />
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="status" class="form-label required">{{ __('Status') }}</label>
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
                                                    {{ $value['value'] == old('status', @$staff_advanced->status) ? 'selected' : '' }}>
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

                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="notes" class="form-label ">{{ __('notes') }}</label>
                                <textarea name="notes" id="notes" rows="5" class="form-control form-control-solid">{{ old('notes', @$staff_advanced->note) }}</textarea>
                                @if ($errors->has('notes'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('notes') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <!--End::Input group-->
                </div>
                <!--end::Card body-->

                <!--begin::Actions-->
                @include('pages.partials.form_footer', [
                    'is_save' => true,
                    'back_url' => route('admin.staff-advance.index'),
                ])
                <!--end::Actions-->
            </form>
            <!--end::Form-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Basic info-->
    @section('scripts')
    @include('pages.partials.common_script')
        <script>
            $(function() {
                $(".fsh_flat_datepicker").flatpickr();
            });
        </script>
    @endsection
</x-default-layout>
