<x-default-layout>
    @section('_toolbar')
        @php
            $data['title'] = 'Accounts';
            $data['menu_1'] = 'Accounts';
            $data['menu_1_link'] = route('admin.accounts.index');
            $data['menu_2'] = 'Edit Accounts';
        @endphp
        @include(config('settings.KT_THEME_LAYOUT_DIR') . '/partials/sidebar-layout/_toolbar', ['data' => $data])
    @endsection

    <!--begin::Basic info-->
    <div class="card">
        <!--begin::Card header-->
        @include('pages.partials.form_header', ['header_name' => 'Edit Accounts'])
        <!--begin::Card header-->

        <!--begin::Content-->
        <div id="bank" class="collapse show">
            <!--begin::Form-->
            <form id="bank_form" class="form" method="POST" action="{{ route('admin.accounts.update',$account->id) }}" enctype="multipart/form-data">
                @csrf
                @method('put')
                <!--begin::Card body-->
                <div class="card-body border-top px-9 py-4">
                    <!--begin::Input group-->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="name" class="required form-label">{{ __('Account Holder Name') }}</label>
                                <input type="text" name="name" value="{{ old('name',$account->account_holder_name ) }}" class="form-control form-control-sm form-control-solid" id="name" placeholder="Enter Name" required />
                                @if ($errors->has('name'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="account_number" class="form-label required">{{ __('Account Number') }}</label>
                                <input type="text" name="account_number" value="{{ old('account_number',$account->account_number ) }}" class="form-control form-control-sm form-control-solid" id="account_number" placeholder="Enter Account Number" required />
                                @if ($errors->has('account_number'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('account_number') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="bank_name" class="form-label required">{{ __('Bank Name') }}</label>
                                <input type="text" name="bank_name" value="{{ old('bank_name',$account->bank_name ) }}" class="form-control form-control-sm form-control-solid" id="bank_name" placeholder="Enter Bank Name" required/>
                                @if ($errors->has('bank_name'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('bank_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="branch_name" class="required form-label">{{ __('Branch Name') }}</label>
                                <input type="text" name="branch_name" value="{{ old('branch_name',$account->branch_name ) }}" class="form-control form-control-sm form-control-solid" id="branch_name" placeholder="Enter Branch Name" required />
                                @if ($errors->has('branch_name'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('branch_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="account_type" class="form-label required">{{ __('Account Type') }}</label>
                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="account_type" id="account_type" aria-label="{{ __('Select account_type') }}" data-control="select2" data-placeholder="{{ __('Select Account Type..') }}" class="form-select form-select-sm form-select-solid" data-allow-clear="true" required>
                                            <option value="">{{ __('Select account_type..') }}</option>
                                            @foreach (config('app.account_type') as $key => $value)
                                                <option data-kt-flag="{{ $value['value'] }}" value="{{ $value['value'] }}" {{ $value['value'] == old('account_type',$account->account_type ) ? 'selected' : '' }}>{{ $value['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if ($errors->has('account_type'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('status') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="bank_ifsc_code" class="required form-label">{{ __('Bank IFSC Code') }}</label>
                                <input type="text" name="bank_ifsc_code" value="{{ old('bank_ifsc_code',$account->bank_ifsc_code ) }}" class="form-control form-control-sm form-control-solid" id="bank_ifsc_code" placeholder="Enter Bank Ifsc Code" required />
                                @if ($errors->has('bank_ifsc_code'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('bank_ifsc_code') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="initial_balance" class="required form-label">{{ __('Bank Balance') }}</label>
                                <input type="decimal" name="initial_balance" value="{{ old('initial_balance',$account->balance ) }}" class="form-control form-control-sm form-control-solid" id="initial_balance" placeholder="Enter Bank Blance" required />
                                @if ($errors->has('initial_balance'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('initial_balance') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="address" class="form-label">{{ __('Address') }}</label>
                                <textarea name="address" class="form-control form-control-sm form-control-solid" id="address" placeholder="Enter Address">{{ old('address',$account->address ) }}</textarea>
                                @if ($errors->has('address'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('address') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="notes" class="form-label">{{ __('Notes') }}</label>
                                <textarea name="notes" class="form-control form-control-sm form-control-solid" id="notes" placeholder="Enter Notes">{{ old('notes',$account->notes) }}</textarea>
                                @if ($errors->has('notes'))
                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('notes') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-5">
                                <label for="status" class="form-label required">{{ __('Status') }}</label>

                                <div class="input-group input-group-sm flex-nowrap">
                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="status" id="status" aria-label="{{ __('Select Status') }}" data-control="select2" data-placeholder="{{ __('Select Status..') }}" class="form-select form-select-sm form-select-solid" data-allow-clear="true" required>
                                            <option value="">{{ __('Select Status..') }}</option>
                                            @foreach (config('app.statusinactive') as $key => $value)
                                                <option data-kt-flag="{{ $value['value'] }}" value="{{ $value['value'] }}" {{ $value['value'] == old('status',$account->status) ? 'selected' : '' }}>{{ $value['name'] }}</option>
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
                    </div>
                </div>

                <!--end::Card body-->

                <!--begin::Actions-->
                @include('pages.partials.form_footer', ['is_save' => true, 'back_url' => route('admin.accounts.index')])
                <!--end::Actions-->
            </form>
            <!--end::Form-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Basic info-->

    @section('scripts')
        @include('pages.partials.date_picker')
    @endsection
</x-default-layout>
