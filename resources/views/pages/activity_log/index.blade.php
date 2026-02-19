<x-default-layout>
    <!--begin::Card-->
    @section('_toolbar')
        @include('pages.partials.common-toolbar', [
            'title' => 'Activity Logs',
            'menu_1_link' => route('admin.dashboard'),
            'menu_2' => 'Activity Logs',
        ])
    @endsection
    <!--begin::Card-->
    <div class="card">
        <div class="card-body pt-6">
            <form action="#">
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-5">
                            <label for="from_date" class=" form-label">{{ __('From Date') }}</label>
                            <div class="input-group">
                                <input id="from_date" type="text"
                                    class="form-control form-control-sm form-control-solid fsh_flat_datepicker"
                                    name="from_date"
                                    value="{{ old('from_date', isset($_REQUEST['from_date']) ? $_REQUEST['from_date'] : null) }}" />
                                <span class="input-group-text border-0">
                                    <i class="fas fa-calendar"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-5">
                            <label for="to_date" class=" form-label">{{ __('To Date') }}</label>
                            <div class="input-group">
                                <input id="to_date" type="text"
                                    class="form-control form-control-sm form-control-solid fsh_flat_datepicker"
                                    name="to_date"
                                    value="{{ old('to_date', isset($_REQUEST['to_date']) ? $_REQUEST['to_date'] : null) }}" />
                                <span class="input-group-text border-0">
                                    <i class="fas fa-calendar"></i>
                                </span>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-4">
                        <div class="mb-5">
                            <label for="ip_address" class=" form-label">{{ __('Ip Address') }}</label>
                            <div class="input-group">
                                <input id="ip_address" type="text"
                                    class="form-control form-control-sm form-control-solid" name="ip_address"
                                    value="{{ old('ip_address', isset($_REQUEST['ip_address']) ? $_REQUEST['ip_address'] : null) }}" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-5">
                            <label for="subject_type" class=" form-label">{{ __('Subject Type') }}</label>
                            <div class="input-group">
                                <input id="subject_type" type="text"
                                    class="form-control form-control-sm form-control-solid" name="subject_type"
                                    value="{{ old('subject_type', isset($_REQUEST['subject_type']) ? $_REQUEST['subject_type'] : null) }}" />
                            </div>
                        </div>
                    </div>
                    {{-- <div class="col-md-4">
                        <div class="mb-5">
                            <label for="properties" class=" form-label">{{ __('Properties') }}</label>
                            <div class="input-group">
                                <input id="properties" type="text"
                                    class="form-control form-control-sm form-control-solid" name="properties"
                                    value="{{ old('properties', isset($_REQUEST['properties']) ? $_REQUEST['properties'] : null) }}" />
                            </div>
                        </div>
                    </div> --}}


                    <div class="col-md-4">
                        <div class="mb-5">
                            <div class="d-flex justify-content-right py-6">
                                <button type="button"
                                    class="btn btn-sm btn-success me-2 filter_button">{{ __('Filter') }}</button>
                                <a href="{{ route('admin.activitylog') }}"><button type="button"
                                        class="btn btn-sm btn-warning btn-active-light-primary me-2">{{ __('Clear Filter') }}</button></a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!--begin::Card body-->
        <div class="card-body pt-6">
            @include('pages.activity_log._table')
        </div>
        <!--end::Card body-->
    </div>
</x-default-layout>
