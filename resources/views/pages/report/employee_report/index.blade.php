<x-default-layout>
    <!--begin::Card-->
    @section('_toolbar')
        @include('pages.partials.common-toolbar', [
            'title' => 'Employee Report',
            'menu_1_link' => route('admin.dashboard'),
            'menu_2' => 'Employee Report',
        ])
    @endsection
    <!-- Card Section -->
    <div class="card">
        <!-- Card Body - Filter Form -->
        {{-- <div class="card-body pt-6">
            <form action="{{ route('admin.profit_and_loss') }}" method="GET" enctype="multipart/form-data">
                @csrf
                @method('get')
                <div class="row">
                    <!-- From Date Input -->
                    <div class="col-md-4">
                        <div class="mb-5">
                            <label for="from_date" class="form-label">{{ __('From Date') }}</label>
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

                    <!-- To Date Input -->
                    <div class="col-md-4">
                        <div class="mb-5">
                            <label for="to_date" class="form-label">{{ __('To Date') }}</label>
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

                    <!-- Filter Buttons -->
                    <div class="col-md-4">
                        <div class="mb-5">
                            <div class="d-flex justify-content-end py-6">
                                <button type="submit"
                                    class="btn btn-sm btn-success me-2 filter_button">{{ __('Filter') }}</button>
                                <a href="{{ route('admin.profit_and_loss') }}">
                                    <button type="button"
                                        class="btn btn-sm btn-warning btn-active-light-primary me-2">{{ __('Clear Filter') }}</button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div> --}}
  <!--begin::Card body-->
  <div class="card-body pt-6">
    @include('pages.report.employee_report._table')
</div>
<!--end::Card body-->
    </div>

    <!-- Scripts -->
    {{-- @section('scripts')
        @include('pages.accounting.transactions.script')
    @endsection --}}
</x-default-layout>
