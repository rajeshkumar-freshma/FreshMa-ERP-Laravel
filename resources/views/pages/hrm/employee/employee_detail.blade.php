    <div class="card-header" role="button" data-bs-toggle="collapse" data-bs-target="#employee_store_assign" aria-expanded="false">
        <h3 class="card-title fw-bolder m-0">{{ __('Store Assign') }}</h3>
        <div class="card-toolbar rotate-180">
            <span class="svg-icon svg-icon-1">
                ...
            </span>
        </div>
    </div>

    <div id="employee_store_assign" class="collapse">
        <div class="card-body border-top">
            <!--begin::Tab pane-->
            <div class="flex-column gap-7 gap-lg-10">
                <!--begin::Meta options-->
                {{-- <div class="card card-flush py-4">
            <!--begin::Card body-->
            <div class="card-body pt-0"> --}}
                <!--begin::Input group-->
                <div class="" data-form-repeater="auto-options">
                    <!--begin::Repeater-->
                    <div id="repeater_form">
                        <!--begin::Form group-->
                        @if (!empty($data) && count($data) > 0)
                            @foreach ($data as $key => $item)
                                <div class="form-group employee_store_row repeater-form{{ $key }}">
                                    <div class="flex-column gap-3">
                                        <div class="form-group flex-wrap align-items-center gap-5">
                                            <div class="row">
                                                <input type="hidden" name="employee_store[{{ $key }}][id]" value="{{ old('id', isset($data[$key]) ? @$data[$key]['id'] : @$data['id'][$key]) }}" class="form-control form-control-sm">
                                                <div class="col-md-3 mb-5">
                                                    <label for="store_id" class="form-label">{{ __('Store') }}</label>

                                                    <div class="input-group input-group-sm flex-nowrap">
                                                        <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                                        <div class="overflow-hidden flex-grow-1">
                                                            <select name="employee_store[{{ $key }}][store_id]" aria-label="{{ __('Select Store') }}" data-control="select2" data-placeholder="{{ __('Select Store..') }}" class="form-select form-select-sm form-select-solid store-dropdown" data-allow-clear="true">
                                                                <option value="">{{ __('Select Store..') }}</option>
                                                                @foreach ($stores as $keys => $store)
                                                                    <option data-kt-flag="{{ $store->id }}" value="{{ $store->id }}" {{ $store->id == old('store_id', isset($data[$key]) ? @$data[$key]['store_id'] : @$data['store_id'][$key]) ? 'selected' : '' }}>{{ $store->store_code }} - {{ $store->store_name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    @if ($errors->has('employee_store.store_id.' . $key))
                                                        <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('employee_store.store_id.' . $key) }}</strong>
                                                        </span>
                                                    @endif
                                                </div>

                                                <div class="col-md-3 mb-5">
                                                    <label for="department_id" class="form-label">{{ __('Department') }}</label>

                                                    <div class="input-group input-group-sm flex-nowrap">
                                                        <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                                        <div class="overflow-hidden flex-grow-1">
                                                            <select name="employee_store[{{ $key }}][department_id]" aria-label="{{ __('Select Department') }}" data-control="select2" data-placeholder="{{ __('Select Department..') }}" class="form-select form-select-sm form-select-solid store-dropdown" data-allow-clear="true">
                                                                <option value="">{{ __('Select Department..') }}</option>
                                                                @foreach ($departments as $keys => $department)
                                                                    <option data-kt-flag="{{ $department->id }}" value="{{ $department->id }}" {{ $department->id == old('department_id', isset($data[$key]) ? @$data[$key]['department_id'] : @$data['department_id'][$key]) ? 'selected' : '' }}>{{ $department->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    @if ($errors->has('employee_store.department_id.' . $key))
                                                        <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('employee_store.department_id.' . $key) }}</strong>
                                                        </span>
                                                    @endif
                                                </div>

                                                <div class="col-md-3 mb-5">
                                                    <label for="designation_id" class="form-label">{{ __('Designation') }}</label>

                                                    <div class="input-group input-group-sm flex-nowrap">
                                                        <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                                        <div class="overflow-hidden flex-grow-1">
                                                            <select name="employee_store[{{ $key }}][designation_id]" aria-label="{{ __('Select Designation') }}" data-control="select2" data-placeholder="{{ __('Select Designation..') }}" class="form-select form-select-sm form-select-solid store-dropdown" data-allow-clear="true">
                                                                <option value="">{{ __('Select Designation..') }}</option>
                                                                @foreach ($designations as $keys => $designation)
                                                                    <option data-kt-flag="{{ $designation->id }}" value="{{ $designation->id }}" {{ $designation->id == old('designation_id', isset($data[$key]) ? @$data[$key]['designation_id'] : @$data['designation_id'][$key]) ? 'selected' : '' }}>{{ $designation->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    @if ($errors->has('employee_store.designation_id.' . $key))
                                                        <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('employee_store.designation_id.' . $key) }}</strong>
                                                        </span>
                                                    @endif
                                                </div>

                                                <div class="col-md-2 mb-5">
                                                    <label for="status" class="form-label required">{{ __('Status') }}</label>

                                                    <div class="input-group input-group-sm flex-nowrap">
                                                        <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                                        <div class="overflow-hidden flex-grow-1">
                                                            <select name="employee_store[{{ $key }}][status]" aria-label="{{ __('Select Status') }}" data-control="select2" data-placeholder="{{ __('Select Status..') }}" class="form-select form-select-sm form-select-solid status" data-allow-clear="true">
                                                                <option value="">{{ __('Select Status..') }}</option>
                                                                @foreach (config('app.statusinactive') as $keys => $value)
                                                                    <option data-kt-flag="{{ $value['value'] }}" value="{{ $value['value'] }}" {{ $value['value'] == old('status', isset($data[$key]) ? $data[$key]['status'] : $data['status'][$key]) ? 'selected' : '' }}>{{ $value['name'] }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    @if ($errors->has('employee_store.status.' . $key))
                                                        <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('employee_store.status.' . $key) }}</strong>
                                                        </span>
                                                    @endif
                                                </div>

                                                <div class="col-md-3 mb-5">
                                                    <label for="joined_at" class="form-label">{{ __('Joined Date') }} </label>
                                                    <div class="input-group">
                                                        <input name="employee_store[{{ $key }}][joined_at]" type="text" class="form-control form-select-sm form-control-solid fsh_flat_datepicker" placeholder="Enter Joined Date" value="{{ old('joined_at', isset($data[$key]['joined_at']) ? (isset($data[$key]) ? $data[$key]['joined_at'] : @$data['joined_at'][$key]) : (isset($data[$key]) ? @$data[$key]['assigned_at'] : @$data['joined_at'][$key])) }}"/>
                                                        <span class="input-group-text border-0">
                                                            <i class="fas fa-calendar"></i>
                                                        </span>
                                                    </div>
                                                    @if ($errors->has('employee_store.joined_at.' . $key))
                                                        <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('employee_store.joined_at.' . $key) }}</strong>
                                                        </span>
                                                    @endif
                                                </div>

                                                <div class="col-md-3 mb-5">
                                                    <label for="remarks" class="form-label">{{ __('Remarks') }}</label>
                                                    <input name="employee_store[{{ $key }}][remarks]" type="text" class="form-control form-select-sm form-control-solid" placeholder="Enter Remark" value="{{ old('remarks', isset($data[$key]) ? @$data[$key]['remarks'] : @$data['remarks'][$key]) }}" />
                                                    @if ($errors->has('employee_store.remarks.' . $key))
                                                        <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('employee_store.remarks.' . $key) }}</strong>
                                                        </span>
                                                    @endif
                                                </div>

                                                <div class="col-md-1 mb-5">
                                                    <button type="button" class="btn btn-sm btn-icon btn-light-danger mt-8 store_assign_delete">
                                                        <i class="fa fa-close"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="form-group employee_store_row repeater-form0">
                                <div data-repeater-list="employee_store" class="flex-column gap-3">
                                    <div data-repeater-item="" class="form-group flex-wrap align-items-center gap-5">
                                        <div class="row">
                                            <div class="col-md-3 mb-5">
                                                <label for="store_id" class="form-label">{{ __('Store') }}</label>

                                                <div class="input-group input-group-sm flex-nowrap">
                                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                                    <div class="overflow-hidden flex-grow-1">
                                                        <select name="employee_store[0][store_id]" aria-label="{{ __('Select Store') }}" data-control="select2" data-placeholder="{{ __('Select Store..') }}" class="form-select form-select-sm form-select-solid store-dropdown" data-allow-clear="true">
                                                            <option value="">{{ __('Select Store..') }}</option>
                                                            @foreach ($stores as $key => $stores)
                                                                <option data-kt-flag="{{ $stores->id }}" value="{{ $stores->id }}" {{ $stores->id == old('store_id') ? 'selected' : '' }}>{{ $stores->store_code }} - {{ $stores->store_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                @if ($errors->has('employee_store.store_id.0'))
                                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('employee_store.store_id.0') }}</strong>
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="col-md-3 mb-5">
                                                <label for="department_id" class="form-label">{{ __('Department') }}</label>

                                                <div class="input-group input-group-sm flex-nowrap">
                                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                                    <div class="overflow-hidden flex-grow-1">
                                                        <select name="employee_store[0][department_id]" aria-label="{{ __('Select Department') }}" data-control="select2" data-placeholder="{{ __('Select Department..') }}" class="form-select form-select-sm form-select-solid store-dropdown" data-allow-clear="true">
                                                            <option value="">{{ __('Select Department..') }}</option>
                                                            @foreach ($departments as $keys => $department)
                                                                <option data-kt-flag="{{ $department->id }}" value="{{ $department->id }}" {{ $department->id == old('department_id') ? 'selected' : '' }}>{{ $department->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                @if ($errors->has('employee_store.department_id.' . $key))
                                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('employee_store.department_id.' . $key) }}</strong>
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="col-md-3 mb-5">
                                                <label for="designation_id" class="form-label">{{ __('Designation') }}</label>

                                                <div class="input-group input-group-sm flex-nowrap">
                                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                                    <div class="overflow-hidden flex-grow-1">
                                                        <select name="employee_store[0][designation_id]" aria-label="{{ __('Select Designation') }}" data-control="select2" data-placeholder="{{ __('Select Designation..') }}" class="form-select form-select-sm form-select-solid store-dropdown" data-allow-clear="true">
                                                            <option value="">{{ __('Select Designation..') }}</option>
                                                            @foreach ($designations as $keys => $designation)
                                                                <option data-kt-flag="{{ $designation->id }}" value="{{ $designation->id }}" {{ $designation->id == old('designation_id') ? 'selected' : '' }}>{{ $designation->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                @if ($errors->has('employee_store.designation_id.' . $key))
                                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('employee_store.designation_id.' . $key) }}</strong>
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="col-md-2 mb-5">
                                                <label for="status" class="form-label required">{{ __('Status') }}</label>

                                                <div class="input-group input-group-sm flex-nowrap">
                                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                                    <div class="overflow-hidden flex-grow-1">
                                                        <select name="employee_store[0][status]" aria-label="{{ __('Select Status') }}" data-control="select2" data-placeholder="{{ __('Select Status..') }}" class="form-select form-select-sm form-select-solid status" data-allow-clear="true">
                                                            <option value="">{{ __('Select Status..') }}</option>
                                                            @foreach (config('app.statusinactive') as $key => $value)
                                                                <option data-kt-flag="{{ $value['value'] }}" value="{{ $value['value'] }}" {{ $value['value'] == old('status') ? 'selected' : '' }}>{{ $value['name'] }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                @if ($errors->has('employee_store.status.0'))
                                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('employee_store.status.0') }}</strong>
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="col-md-3 mb-5">
                                                <label for="joined_at" class="form-label">{{ __('Joined Date') }}</label>
                                                <div class="input-group">
                                                    <input name="employee_store[0][joined_at]" id="joined_at" type="text" class="form-control form-select-sm form-control-solid fsh_flat_datepicker" placeholder="Enter Joined Date" value="{{ old('joined_at') }}" />
                                                    <span class="input-group-text border-0">
                                                        <i class="fas fa-calendar"></i>
                                                    </span>
                                                </div>
                                                @if ($errors->has('employee_store.joined_at.0'))
                                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('employee_store.joined_at.0') }}</strong>
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="col-md-3 mb-5">
                                                <label for="remarks" class="form-label">{{ __('Remarks') }} </label>
                                                <input name="employee_store[0][remarks]" type="text" class="form-control form-select-sm form-control-solid" placeholder="Enter Remark" value="{{ old('remarks') }}" />
                                                @if ($errors->has('employee_store.remarks.0'))
                                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('employee_store.remarks.0') }}</strong>
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="col-md-1 mb-5">
                                                <button type="button" class="btn btn-sm btn-icon btn-light-danger mt-8 store_assign_delete">
                                                    <i class="fa fa-close"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="appendBodyData"></div>
                        <!--end::Form group-->
                        <!--begin::Form group-->
                        <div class="form-group mt-5">
                            <button type="button" class="btn btn-sm btn-light-primary employee_store_add">
                                <i class="fa fa-plus"></i> Add another store</button>
                        </div>
                        <!--end::Form group-->
                    </div>
                    <!--end::Repeater-->
                </div>
                <!--end::Input group-->
                <!--end::Input group-->
                {{-- </div>
            <!--end::Card header-->
        </div> --}}
                <!--end::Meta options-->
            </div>
        </div>
    </div>
    <!--end::Tab pane-->
