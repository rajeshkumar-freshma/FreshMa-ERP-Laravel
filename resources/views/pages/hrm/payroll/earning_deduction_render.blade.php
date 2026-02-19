<div class="col-md-6">
    <div class="card earningcontainer">
        <div class="card-body">
            <h5 class="card-title bg-light p-2">{{ __('Earnings') }}</h5>
            <div class="row" id="payrollEarningContainer">
                @if (isset($earnings))
                    @foreach (@$earnings as $earning)
                        <div class="row mb-2 remove">
                            <label class="col-md-3 col-form-label text-success">
                                {{ $earning['payroll_type_name'] }}
                            </label>
                            <input type="hidden" name="payroll_data[{{ $earning['payroll_type_id'] }}][payroll_type_id]" value="{{ $earning['payroll_type_id'] }}">
                            <div class="col-md-5">
                                <input name="payroll_data[{{ $earning['payroll_type_id'] }}][amount]" type="text" class="form-control form-control-sm amount-input" placeholder="Enter Amount" id="amount{{ $earning['payroll_type_id'] }}" value="{{ $earning['amount'] }}">
                            </div>
                            <div class="col-md-2">
                                <span class="input-text border-0 text-danger" onclick="removeRow(this)">
                                    <i class="fas fa-times fs-4"></i>
                                </span>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p>No earnings found.</p>
                @endif
            </div>

            <div class="row">
                <div class="row-lg-1">
                    <div class="row mb-2">
                        <div class="row-12">
                            <button type="button" class="btn btn-success btn-sm" onclick="addPayroll(1)">
                                Add <span><i class="fas fa-plus"></i> </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ... existing code ... -->
        </div>
    </div>
</div>

<div class="col-md-6">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title bg-light p-2">{{ __('Deductions') }}</h5>
            <div class="row" id="payrollDeductionContainer">
                @if (isset($deductions))
                    @foreach (@$deductions as $deduction)
                        <div class="row mb-2 remove">
                            <label class="col-md-3 col-form-label text-danger">
                                {{ $deduction['payroll_type_name'] }}
                            </label>
                            <input type="hidden" name="payroll_data[{{ $deduction['payroll_type_id'] }}][payroll_type_id]" value="{{ $deduction['payroll_type_id'] }}">
                            <div class="col-md-5">
                                <input name="payroll_data[{{ $deduction['payroll_type_id'] }}][amount]" type="text" class="form-control form-control-sm amount-input" placeholder="Enter Amount" id="amount{{ $deduction['payroll_type_id'] }}" value="{{ $deduction['amount'] ? $deduction['amount'] : '' }}">
                            </div>
                            <div class="col-md-2">
                                <span class="input-text border- text-danger" onclick="removeRow(this)">
                                    <i class="fas fa-times fs-4"></i>
                                </span>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p>No deduction found.</p>
                @endif
            </div>
            <div class="row">
                <div class="row-lg-1">
                    <div class="row mb-2">
                        <div class="row-12">
                            <button type="button" class="btn btn-danger btn-sm" onclick="addPayroll(2)">
                                Add <span><i class="fas fa-plus"></i> </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ... existing code ... -->
        </div>
    </div>
</div>
