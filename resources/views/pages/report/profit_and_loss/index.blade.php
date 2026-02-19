<x-default-layout>
    <!--begin::Card-->
    @section('_toolbar')
        @include('pages.partials.common-toolbar', [
            'title' => 'Profit And Loss Report',
            'menu_1_link' => route('admin.dashboard'),
            'menu_2' => 'Profit And Loss Report',
        ])
    @endsection
    <!-- Card Section -->
    <div class="card">
        <!-- Card Body - Filter Form -->
        <div class="card-body pt-6">
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
        </div>

        <!-- Card Body - Data Section -->
        <div class="card-body pt-6">
            <div id="section">
                <div class="row">
                    <!-- Sales Count Card -->
                    <div class="col-lg-4 col-md-6 col-sm-6 col-12 mb-5">
                        <div class="card card-icon-big text-center mb-30 bg-primary text-white">
                            <!-- Add text-white for white text on blue background -->
                            <div class="card-body">
                                <i class="i-Shopping-Cart"></i>
                                <div class="content">
                                    <p class="text-white mt-2 mb-0">{{ __('Sales Count') }}:
                                        <span class="text-white">{{ @$salesOrdersData['count'] }}</span>
                                    </p>
                                    <p class="text-white text-24 line-height-1 mb-2">
                                        {{ $salesOrdersData['totalSalesAmount'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Purchases Count Card -->
                    <div class="col-lg-4 col-md-6 col-sm-6 col-12 mb-5">
                        <div class="card card-icon-big text-center mb-30 bg-success text-white">
                            <!-- Add text-white for white text on green background -->
                            <div class="card-body">
                                <i class="i-Shopping-Bag"></i>
                                <div class="content">
                                    <p class="text-white mt-2 mb-0">{{ __('Purchases Count') }}:
                                        <span class="text-white">{{ @$purchaseOrdersData['count'] }}</span>
                                    </p>
                                    <p class="text-white text-24 line-height-1 mb-2">
                                        {{ @$purchaseOrdersData['totalPurchaseAmount'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sales Returns Count Card -->
                    <div class="col-lg-4 col-md-6 col-sm-6 col-12 mb-5">
                        <div class="card card-icon-big text-center mb-30 bg-warning text-white">
                            <!-- Add text-white for white text on yellow background -->
                            <div class="card-body">
                                <i class="i-Back"></i>
                                <div class="content">
                                    <p class="text-white mt-2 mb-0">{{ __('Sales Returns Count') }}:
                                        <span class="text-white">{{ @$salesOrdersReturnsData['count'] }}</span>
                                    </p>
                                    <p class="text-white text-24 line-height-1 mb-2">
                                        {{ @$salesOrdersReturnsData['totalSalesReturnsAmount'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Incomes Card -->
                    <div class="col-lg-4 col-md-6 col-sm-6 col-12 mb-5">
                        <div class="card card-icon-big text-center mb-30 bg-danger text-white">
                            <!-- Add text-white for white text on red background -->
                            <div class="card-body">
                                <i class="i-Back"></i>
                                <div class="content">
                                    <p class="text-white mt-2 mb-0">{{ __('Incomes') }}:
                                        <span class="text-white">{{ @$incomesData['incomesAmount'] }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Expenses Card -->
                    <div class="col-lg-4 col-md-6 col-sm-6 col-12 mb-5">
                        <div class="card card-icon-big text-center mb-30 bg-info text-white">
                            <!-- Add text-white for white text on blue background -->
                            <div class="card-body">
                                <i class="i-Money-2"></i>
                                <div class="content">
                                    <p class="text-white mt-2 mb-0">{{ __('Expenses') }}:
                                        <span class="text-white">{{ @$expensesData['expenseAmount'] }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Profit/Loss Card -->
                    <div class="col-lg-4 col-md-6 col-sm-6 col-12 mb-5">
                        <div class="card card-icon-big text-center mb-30 bg-secondary text-white">
                            <!-- Add text-white for white text on grey background -->
                            <div class="card-body">
                                <i class="i-Money-2"></i>
                                <div class="content">
                                    <p class="text-black mt-2 mb-0">{{ __('Profit/Loss') }}:
                                        <span
                                            class="text-black">{{ $salesOrdersData['totalSalesAmount'] - $purchaseOrdersData['totalPurchaseAmount'] }}</span>
                                    </p>
                                    <p class="text-black text-24 line-height-1 mb-2">
                                        ({{ $salesOrdersData['totalSalesAmount'] }} {{ __('Sales') }} -
                                        {{ $purchaseOrdersData['totalPurchaseAmount'] }} {{ __('Purchase') }})
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Revenue Card -->
                    <div class="col-lg-4 col-md-6 col-sm-6 col-12 mb-5">
                        <div class="card card-icon-big text-center mb-30 bg-light text-black">
                            <!-- Add text-black for black text on light grey background -->
                            <div class="card-body">
                                <i class="i-Money-2"></i>
                                <div class="content">
                                    <p class="text-black mt-2 mb-0">{{ __('Revenue') }}:
                                        <span
                                            class="text-black">{{ $salesOrdersData['totalSalesAmount'] - @$salesOrdersReturnsData['totalSalesReturnsAmount'] }}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="card-footer">
                                <span class="bold text-black">{{ $salesOrdersData['totalSalesAmount'] }}
                                    {{ __('Sales') }} -
                                    {{ @$salesOrdersReturnsData['totalSalesReturnsAmount'] }}
                                    {{ __('Returns') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    @section('scripts')
        @include('pages.accounting.transactions.script')
    @endsection
</x-default-layout>
