<x-default-layout>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title px-sm-4 px-md-1 px-lg-2 px-xl-3">Payroll Type Details</h3>
            <div class="card-tools px-sm-4 px-md-1 px-lg-2 px-xl-3" title="Minimize/Maximize">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row text-center px-sm-4 px-md-1 px-lg-2 px-xl-3">
                <div class="col-md-6">
                    <div class="row border border-default h-100">
                        <div class="col-6 col-md-4 p-0">
                            <p class="font-weight-bold mb-0 p-2 bg-secondary">Name</p>
                        </div>
                        <div class="col-6 col-md-8">
                            <p class="mb-0 p-2 text-md font-weight-bold">
                                {{ @$payroll_types->name != null ? @$payroll_types->name : '-' }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row border border-default h-100">
                        <div class="col-6 col-md-4 p-0">
                            <p class="font-weight-bold mb-0 p-2 bg-secondary">Details Date</p>
                        </div>
                        <div class="col-6 col-md-8">
                            <p class="mb-0 p-2 text-md font-weight-bold">
                                {{ @$payroll_types->details != null ? @$payroll_types->details : '-' }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="row border border-default h-100">
                        <div class="col-6 col-md-4 p-0">
                            <p class="font-weight-bold mb-0 p-2 bg-secondary">Payroll Type</p>
                        </div>
                        <div class="col-6 col-md-8">
                            @foreach (config('app.payroll_types') as $item)
                                @if (@$item['value'] == $payroll_types->payroll_types)
                                    <p class="mb-0 p-2 text-md font-weight-bold">
                                        {{ $item['name'] }}</p>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="row border border-default h-100">
                        <div class="col-6 col-md-4 p-0">
                            <p class="font-weight-bold mb-0 p-2 bg-secondary">Staus</p>
                        </div>
                        <div class="col-6 col-md-8">
                            <p class="mb-0 p-2 text-md font-weight-bold">
                                {{ @$payroll_types->status == 1 ? 'Active' : 'inactive' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Content-->
    </div>
    <!--end::Basic info-->
</x-default-layout>
