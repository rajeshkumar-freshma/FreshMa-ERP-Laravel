<!--begin::Tab pane-->
<div class="tab-pane fade" id="add_partner_detail" role="tab-panel">
    <div class="d-flex flex-column gap-7 gap-lg-10">
        <!--begin::Meta options-->
        <div class="card card-flush py-4">
            <!--begin::Card body-->
            <div class="card-body pt-0">
                <!--begin::Input group-->
                <div class="" data-form-repeater="auto-options">
                    <!--begin::Repeater-->
                    <div id="repeater_form">
                        <!--begin::Form group-->
                        @if(!empty($data) && count($data)>0)
                            @foreach ($data as $key=> $item)
                                <div class="form-group repeater-form{{ $key }}">
                                    <div class="d-flex flex-column gap-3">
                                        <div class="form-group d-flex flex-wrap align-items-center gap-5">
                                            <div class="row">
                                                <input type="hidden" name="partnership_store[{{ $key }}][id]" value="{{ old('id', isset($data[$key]) ? @$data[$key]['id'] : @$data['id'][$key]) }}" class="form-control form-control-sm">
                                                <div class="col-md-3 mb-5 partnership_type_div">
                                                    <label for="partnership_type_id" class="form-label">{{ __('Partnership Type') }}</label>

                                                    <div class="input-group input-group-sm flex-nowrap">
                                                        <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                                        <div class="overflow-hidden flex-grow-1">
                                                            <select name="partnership_store[{{ $key }}][partnership_type_id]" aria-label="{{ __('Select Partnership Type') }}" data-control="select2" data-placeholder="{{ __('Select Partnership Type..') }}" class="form-select form-select-sm form-select-solid partnership-type-dropdown" data-allow-clear="true">
                                                                <option value="">{{ __('Select Partnership Type..') }}</option>
                                                                @foreach($partnership_types as $keys => $partnership_type)
                                                                    <option data-kt-flag="{{ $partnership_type->id }}" value="{{ $partnership_type->id }}" {{ $partnership_type->id == old('partnership_type_id', isset($data[$key]) ? @$data[$key]['partnership_type_id'] : @$data['partnership_type_id'][$key]) ? 'selected' :'' }}>{{ $partnership_type->partnership_name }} - {{ $partnership_type->partnership_percentage }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    @if ($errors->has('partnership_store.partnership_type_id.'.$key))
                                                        <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('partnership_store.partnership_type_id.'.$key) }}</strong>
                                                        </span>
                                                    @endif
                                                </div>

                                                <div class="col-md-3 mb-5">
                                                    <label for="store_id" class="form-label">{{ __('Store') }}</label>

                                                    <div class="input-group input-group-sm flex-nowrap">
                                                        <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                                        <div class="overflow-hidden flex-grow-1">
                                                            <select name="partnership_store[{{ $key }}][store_id]" aria-label="{{ __('Select Store') }}" data-control="select2" data-placeholder="{{ __('Select Store..') }}" class="form-select form-select-sm form-select-solid store-dropdown" data-allow-clear="true">
                                                                <option value="">{{ __('Select Store..') }}</option>
                                                                @foreach($stores as $keys => $store)
                                                                    <option data-kt-flag="{{ $store->id }}" value="{{ $store->id }}" {{ $store->id == old('store_id', isset($data[$key]) ? @$data[$key]['store_id'] : @$data['store_id'][$key]) ? 'selected' :'' }}>{{ $store->store_code }} - {{ $store->store_name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    @if ($errors->has('partnership_store.store_id.'.$key))
                                                        <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('partnership_store.store_id.'.$key) }}</strong>
                                                        </span>
                                                    @endif
                                                </div>

                                                <div class="col-md-2 mb-5">
                                                    <label for="status" class="form-label required">{{ __('Status') }}</label>

                                                    <div class="input-group input-group-sm flex-nowrap">
                                                        <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                                        <div class="overflow-hidden flex-grow-1">
                                                            <select name="partnership_store[{{ $key }}][status]" aria-label="{{ __('Select Status') }}" data-control="select2" data-placeholder="{{ __('Select Status..') }}" class="form-select form-select-sm form-select-solid status" data-allow-clear="true">
                                                                <option value="">{{ __('Select Status..') }}</option>
                                                                @foreach(config('app.statusinactive') as $keys => $value)
                                                                    <option data-kt-flag="{{ $value['value'] }}" value="{{ $value['value'] }}" {{ ($value['value'] == old('status', isset($data[$key]) ? $data[$key]['status'] : $data['status'][$key])) ? 'selected' :'' }}>{{ $value['name'] }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    @if ($errors->has('partnership_store.status.'.$key))
                                                        <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('partnership_store.status.'.$key) }}</strong>
                                                        </span>
                                                    @endif
                                                </div>

                                                <div class="col-md-3 mb-5">
                                                    <label for="joined_at" class="form-label">{{ __('Joined Date') }} </label>
                                                    <div class="input-group">
                                                        <input name="partnership_store[{{ $key }}][joined_at]" type="text" class="form-control form-select-sm form-control-solid fsh_flat_datepicker" placeholder="Enter Joined Date" value="{{ old('joined_at', isset($data[$key]['joined_at']) ? (isset($data[$key]) ? $data[$key]['joined_at'] : @$data['joined_at'][$key]) : (isset($data[$key]) ? @$data[$key]['assigned_at'] : @$data['joined_at'][$key])) }}"/>
                                                        <span class="input-group-text border-0">
                                                            <i class="fas fa-calendar"></i>
                                                        </span>
                                                    </div>
                                                    @if ($errors->has('partnership_store.joined_at.'.$key))
                                                        <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('partnership_store.joined_at.'.$key) }}</strong>
                                                        </span>
                                                    @endif
                                                </div>

                                                <div class="col-md-3 mb-5">
                                                    <label for="remarks" class="form-label">{{ __('Remarks') }}</label>
                                                    <input name="partnership_store[{{ $key }}][remarks]" type="text" class="form-control form-select-sm form-control-solid" placeholder="Enter Remarks" value="{{ old('remarks', isset($data[$key]) ? @$data[$key]['remarks'] : @$data['remarks'][$key]) }}"/>
                                                    @if ($errors->has('partnership_store.remarks.'.$key))
                                                        <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('partnership_store.remarks.'.$key) }}</strong>
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
                            <div class="form-group repeater-form0">
                                <div data-repeater-list="partnership_store" class="d-flex flex-column gap-3">
                                    <div data-repeater-item="" class="form-group d-flex flex-wrap align-items-center gap-5">
                                        <div class="row">
                                            <div class="col-md-3 mb-5 partnership_type_div">
                                                <label for="partnership_type_id" class="form-label">{{ __('Partnership Type') }}</label>

                                                <div class="input-group input-group-sm flex-nowrap">
                                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                                    <div class="overflow-hidden flex-grow-1">
                                                        <select name="partnership_store[0][partnership_type_id]" aria-label="{{ __('Select Partnership Type') }}" data-control="select2" data-placeholder="{{ __('Select Partnership Type..') }}" class="form-select form-select-sm form-select-solid partnership-type-dropdown" data-allow-clear="true">
                                                            <option value="">{{ __('Select Partnership Type..') }}</option>
                                                            @foreach($partnership_types as $key => $partnership_type)
                                                                <option data-kt-flag="{{ $partnership_type->id }}" value="{{ $partnership_type->id }}" {{ $partnership_type->id == old('partnership_type_id') ? 'selected' :'' }}>{{ $partnership_type->partnership_name }} - {{ $partnership_type->partnership_percentage }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                @if ($errors->has('partnership_store.partnership_type_id.0'))
                                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('partnership_store.partnership_type_id.0') }}</strong>
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="col-md-3 mb-5">
                                                <label for="store_id" class="form-label">{{ __('Store') }}</label>

                                                <div class="input-group input-group-sm flex-nowrap">
                                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                                    <div class="overflow-hidden flex-grow-1">
                                                        <select name="partnership_store[0][store_id]" aria-label="{{ __('Select Store') }}" data-control="select2" data-placeholder="{{ __('Select Store..') }}" class="form-select form-select-sm form-select-solid store-dropdown" data-allow-clear="true">
                                                            <option value="">{{ __('Select Store..') }}</option>
                                                            @foreach($stores as $key => $store)
                                                                <option data-kt-flag="{{ $store->id }}" value="{{ $store->id }}" {{ $store->id == old('store_id') ? 'selected' :'' }}>{{ $store->store_code }} - {{ $store->store_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                @if ($errors->has('partnership_store.store_id.0'))
                                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('partnership_store.store_id.0') }}</strong>
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="col-md-2 mb-5">
                                                <label for="status" class="form-label required">{{ __('Status') }}</label>

                                                <div class="input-group input-group-sm flex-nowrap">
                                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                                    <div class="overflow-hidden flex-grow-1">
                                                        <select name="partnership_store[0][status]" aria-label="{{ __('Select Status') }}" data-control="select2" data-placeholder="{{ __('Select Status..') }}" class="form-select form-select-sm form-select-solid status" data-allow-clear="true">
                                                            <option value="">{{ __('Select Status..') }}</option>
                                                            @foreach(config('app.statusinactive') as $key => $value)
                                                                <option data-kt-flag="{{ $value['value'] }}" value="{{ $value['value'] }}" {{ $value['value'] == old('status') ? 'selected' :'' }}>{{ $value['name'] }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                @if ($errors->has('partnership_store.status.0'))
                                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('partnership_store.status.0') }}</strong>
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="col-md-3 mb-5">
                                                <label for="joined_at" class="form-label">{{ __('Joined Date') }}</label>
                                                <div class="input-group">
                                                    <input name="partnership_store[0][joined_at]" id="joined_at" type="text" class="form-control form-select-sm form-control-solid fsh_flat_datepicker" placeholder="Enter Joined Date" value="{{ old('joined_at') }}"/>
                                                    <span class="input-group-text border-0">
                                                        <i class="fas fa-calendar"></i>
                                                    </span>
                                                </div>
                                                @if ($errors->has('partnership_store.joined_at.0'))
                                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('partnership_store.joined_at.0') }}</strong>
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="col-md-3 mb-5">
                                                <label for="remarks" class="form-label">{{ __('Remarks') }} </label>
                                                <input name="partnership_store[0][remarks]" type="text" class="form-control form-select-sm form-control-solid" placeholder="Enter Remarks" value="{{ old('remarks') }}"/>
                                                @if ($errors->has('partnership_store.remarks.0'))
                                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('partnership_store.remarks.0') }}</strong>
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
                            <button type="button" class="btn btn-sm btn-light-primary partnership_store_add">
                            <i class="fa fa-plus"></i> Add another store</button>
                        </div>
                        <!--end::Form group-->
                    </div>
                    <!--end::Repeater-->
                </div>

                <hr>
                <!--begin::Input group-->
                <div class="assign_warehouse_content" data-form-repeater="auto-options assign_warehouse_content">
                    <!--begin::Repeater-->
                    <div id="repeater_form">
                        <!--begin::Form group-->
                        @if(!empty($warehousedata) && count($warehousedata)>0)
                            @foreach ($warehousedata as $key=> $item)
                                <div class="form-group repeater-form{{ $key }}">
                                    <div class="d-flex flex-column gap-3">
                                        <div class="form-group d-flex flex-wrap align-items-center gap-5">
                                            <div class="row">
                                                <input type="hidden" name="warehouse_assign[{{ $key }}][id]" value="{{ old('id', isset($warehousedata[$key]) ? @$warehousedata[$key]['id'] : @$warehousedata['id'][$key]) }}" class="form-control form-control-sm">

                                                <div class="col-md-3 mb-5">
                                                    <label for="warehouse_id" class="form-label">{{ __('Warehouse') }}</label>

                                                    <div class="input-group input-group-sm flex-nowrap">
                                                        <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                                        <div class="overflow-hidden flex-grow-1">
                                                            <select name="warehouse_assign[{{ $key }}][warehouse_id]" aria-label="{{ __('Select Warehouse') }}" data-control="select2" data-placeholder="{{ __('Select Warehouse..') }}" class="form-select form-select-sm form-select-solid store-dropdown" data-allow-clear="true">
                                                                <option value="">{{ __('Select Warehouse..') }}</option>
                                                                @foreach($warehouses as $keys => $warehouse)
                                                                    <option data-kt-flag="{{ $warehouse->id }}" value="{{ $warehouse->id }}" {{ $warehouse->id == old('warehouse_id', isset($warehousedata[$key]) ? @$warehousedata[$key]['warehouse_id'] : @$warehousedata['warehouse_id'][$key]) ? 'selected' :'' }}>{{ $warehouse->code }} - {{ $warehouse->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    @if ($errors->has('warehouse_assign.warehouse_id.'.$key))
                                                        <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('warehouse_assign.warehouse_id.'.$key) }}</strong>
                                                        </span>
                                                    @endif
                                                </div>

                                                <div class="col-md-2 mb-5">
                                                    <label for="status" class="form-label required">{{ __('Status') }}</label>

                                                    <div class="input-group input-group-sm flex-nowrap">
                                                        <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                                        <div class="overflow-hidden flex-grow-1">
                                                            <select name="warehouse_assign[{{ $key }}][status]" aria-label="{{ __('Select Status') }}" data-control="select2" data-placeholder="{{ __('Select Status..') }}" class="form-select form-select-sm form-select-solid status" data-allow-clear="true">
                                                                <option value="">{{ __('Select Status..') }}</option>
                                                                @foreach(config('app.statusinactive') as $keys => $value)
                                                                    <option data-kt-flag="{{ $value['value'] }}" value="{{ $value['value'] }}" {{ ($value['value'] == old('status', isset($warehousedata[$key]) ? $warehousedata[$key]['status'] : $warehousedata['status'][$key])) ? 'selected' :'' }}>{{ $value['name'] }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    @if ($errors->has('warehouse_assign.status.'.$key))
                                                        <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('warehouse_assign.status.'.$key) }}</strong>
                                                        </span>
                                                    @endif
                                                </div>

                                                <div class="col-md-3 mb-5">
                                                    <label for="joined_at" class="form-label">{{ __('Joined Date') }} </label>
                                                    <div class="input-group">
                                                        <input name="warehouse_assign[{{ $key }}][joined_at]" type="text" class="form-control form-select-sm form-control-solid fsh_flat_datepicker" placeholder="Enter Joined Date" value="{{ old('joined_at', isset($warehousedata[$key]['assigned_at']) ? (isset($warehousedata[$key]) ? $warehousedata[$key]['assigned_at'] : @$warehousedata['assigned_at'][$key]) : (isset($warehousedata[$key]) ? @$warehousedata[$key]['assigned_at'] : @$warehousedata['assigned_at'][$key])) }}"/>
                                                        <span class="input-group-text border-0">
                                                            <i class="fas fa-calendar"></i>
                                                        </span>
                                                    </div>
                                                    @if ($errors->has('warehouse_assign.joined_at.'.$key))
                                                        <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('warehouse_assign.joined_at.'.$key) }}</strong>
                                                        </span>
                                                    @endif
                                                </div>

                                                <div class="col-md-3 mb-5">
                                                    <label for="remarks" class="form-label">{{ __('Remarks') }}</label>
                                                    <input name="warehouse_assign[{{ $key }}][remarks]" type="text" class="form-control form-select-sm form-control-solid" placeholder="Enter Remarks" value="{{ old('remarks', isset($warehousedata[$key]) ? @$warehousedata[$key]['remarks'] : @$warehousedata['remarks'][$key]) }}"/>
                                                    @if ($errors->has('warehouse_assign.remarks.'.$key))
                                                        <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('warehouse_assign.remarks.'.$key) }}</strong>
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
                            <div class="form-group repeater-form0">
                                <div data-repeater-list="warehouse_assign" class="d-flex flex-column gap-3">
                                    <div data-repeater-item="" class="form-group d-flex flex-wrap align-items-center gap-5">
                                        <div class="row">
                                            <div class="col-md-3 mb-5">
                                                <label for="warehouse_id" class="form-label">{{ __('Warehouse') }}</label>

                                                <div class="input-group input-group-sm flex-nowrap">
                                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                                    <div class="overflow-hidden flex-grow-1">
                                                        <select name="warehouse_assign[0][warehouse_id]" aria-label="{{ __('Select Warehouse') }}" data-control="select2" data-placeholder="{{ __('Select Warehouse..') }}" class="form-select form-select-sm form-select-solid store-dropdown" data-allow-clear="true">
                                                            <option value="">{{ __('Select Warehouse..') }}</option>
                                                            @foreach($warehouses as $key => $warehouse)
                                                                <option data-kt-flag="{{ $warehouse->id }}" value="{{ $warehouse->id }}" {{ $warehouse->id == old('warehouse_id') ? 'selected' :'' }}>{{ $warehouse->code }} - {{ $warehouse->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                @if ($errors->has('warehouse_assign.warehouse_id.0'))
                                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('warehouse_assign.warehouse_id.0') }}</strong>
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="col-md-2 mb-5">
                                                <label for="status" class="form-label required">{{ __('Status') }}</label>

                                                <div class="input-group input-group-sm flex-nowrap">
                                                    <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                                    <div class="overflow-hidden flex-grow-1">
                                                        <select name="warehouse_assign[0][status]" aria-label="{{ __('Select Status') }}" data-control="select2" data-placeholder="{{ __('Select Status..') }}" class="form-select form-select-sm form-select-solid status" data-allow-clear="true">
                                                            <option value="">{{ __('Select Status..') }}</option>
                                                            @foreach(config('app.statusinactive') as $key => $value)
                                                                <option data-kt-flag="{{ $value['value'] }}" value="{{ $value['value'] }}" {{ $value['value'] == old('status') ? 'selected' :'' }}>{{ $value['name'] }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                @if ($errors->has('warehouse_assign.status.0'))
                                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('warehouse_assign.status.0') }}</strong>
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="col-md-3 mb-5">
                                                <label for="joined_at" class="form-label">{{ __('Joined Date') }}</label>
                                                <div class="input-group">
                                                    <input name="warehouse_assign[0][joined_at]" id="joined_at" type="text" class="form-control form-select-sm form-control-solid fsh_flat_datepicker" placeholder="Enter Joined Date" value="{{ old('joined_at') }}"/>
                                                    <span class="input-group-text border-0">
                                                        <i class="fas fa-calendar"></i>
                                                    </span>
                                                </div>
                                                @if ($errors->has('warehouse_assign.joined_at.0'))
                                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('warehouse_assign.joined_at.0') }}</strong>
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="col-md-3 mb-5">
                                                <label for="remarks" class="form-label">{{ __('Remarks') }} </label>
                                                <input name="warehouse_assign[0][remarks]" type="text" class="form-control form-select-sm form-control-solid" placeholder="Enter Remarks" value="{{ old('remarks') }}"/>
                                                @if ($errors->has('warehouse_assign.remarks.0'))
                                                    <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('warehouse_assign.remarks.0') }}</strong>
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
                        <div class="appendWarehouseBodyData"></div>
                        <!--end::Form group-->
                        <!--begin::Form group-->
                        <div class="form-group mt-5">
                            <button type="button" class="btn btn-sm btn-light-primary assign_warehouse_add">
                            <i class="fa fa-plus"></i> Add another Warehouse</button>
                        </div>
                        <!--end::Form group-->
                    </div>
                    <!--end::Repeater-->
                </div>
                <!--end::Input group-->
                <!--end::Input group-->
            </div>
            <!--end::Card header-->
        </div>
        <!--end::Meta options-->
    </div>
</div>
<!--end::Tab pane-->
