@isset($status)
    @foreach (config('app.statusinactive') as $item)
        @if ($status == $item['value'])
            <span class="badge bg-{{ $item['color'] }}">{{ $item['name'] }}</span>
        @endif
    @endforeach
@endisset
@isset($default)
    @foreach (config('app.defaultstatus') as $item)
        @if ($default == $item['value'])
            <span class="badge bg-{{ $item['color'] }}">{{ $item['name'] }}</span>
        @endif
    @endforeach
@endisset
@isset($payroll_types)
    @foreach (config('app.payroll_types') as $item)
        @if ($payroll_types == $item['value'])
            <span class="badge bg-{{ $item['color'] }}">{{ $item['name'] }}</span>
        @endif
    @endforeach
@endisset
@isset($holiday_type)
    @foreach (config('app.holiday_type') as $item)
        @if ($holiday_type == $item['value'])
            <span class="badge bg-{{ $item['color'] }}">{{ $item['name'] }}</span>
        @endif
    @endforeach
@endisset
@isset($is_half_day)
    @foreach (config('app.is_half_day') as $item)
        @if ($is_half_day == $item['value'])
            <span class="badge bg-{{ $item['color'] }}">{{ $item['name'] }}</span>
        @endif
    @endforeach
@endisset
@isset($employee_id)
    @foreach (Staff::where('id', $employee_id)->where('status', 1)->get() as $item)
        @if ($employee_id == $item->id)
            <span class="badge bg-red">{{ $item['first_name'] }}{{ $item['last_name'] }}</span>
        @endif
    @endforeach
@endisset
@isset($approved_status)
    @foreach (config('app.approved_status') as $item)
        @if ($approved_status == $item['value'])
            <span class="badge bg-{{ $item['color'] }}">{{ $item['name'] }}</span>
        @endif
    @endforeach
@endisset

@isset($indent_status)
    @foreach (config('app.purchase_status') as $item)
        @if ($indent_status == $item['value'])
            <span class="badge bg-{{ $item['color'] }}" style="color:white;">{{ $item['name'] }}</span>
        @endif
    @endforeach
@endisset

@isset($payment_status)
    @foreach (config('app.payment_status') as $item)
        @if ($payment_status == $item['value'])
            <span class="badge bg-{{ $item['color'] }}" style="color:white;">{{ $item['name'] }}</span>
        @endif
    @endforeach
@endisset

@isset($admin_type)
    @foreach (config('app.admin_of_user_type') as $item)
        @if ($admin_type == $item['value'])
            <span class="badge bg-{{ $item['color'] }}">{{ $item['name'] }}</span>
        @endif
    @endforeach
@endisset

@isset($adjustment_status)
    @foreach (config('app.adjustment_status') as $item)
        @if ($adjustment_status == $item['value'])
            <span class="badge bg-{{ $item['color'] }}">{{ $item['name'] }}</span>
        @endif
    @endforeach
@endisset

@isset($user_type)
    @foreach (config('app.user_of_user_type') as $item)
        @if ($user_type == $item['value'])
            <span class="badge bg-{{ $item['color'] }}">{{ $item['name'] }}</span>
        @endif
    @endforeach
@endisset
@isset($account_type)
    @foreach (config('app.account_type') as $item)
        @if ($account_type == $item['value'])
            <span class="badge bg-{{ $item['color'] }}">{{ $item['name'] }}</span>
        @endif
    @endforeach
@endisset
@isset($transaction_type)
    @foreach (config('app.transaction_type') as $item)
        @if ($transaction_type == $item['value'])
            <span class="badge bg-{{ $item['color'] }}">{{ $item['name'] }}</span>
        @endif
    @endforeach
@endisset
@isset($type)
    @foreach (config('app.payment_type') as $item)
        @if ($type == $item['value'])
            <span class="badge bg-{{ $item['color'] }}">{{ $item['name'] }}</span>
        @endif
    @endforeach
@endisset
@isset($paymentTransactionType)
    @foreach (config('app.payment_transactions_type') as $item)
        @if ($paymentTransactionType == $item['value'])
            <span class="badge bg-{{ $item['color'] }}">{{ $item['name'] }}</span>
        @endif
    @endforeach
@endisset
@isset($interestFrequency)
    @foreach (config('app.interest_frequency') as $item)
        @if ($interestFrequency == $item['value'])
            <span class="badge bg-{{ $item['color'] }}">{{ $item['name'] }}</span>
        @endif
    @endforeach
@endisset
@isset($is_open)
    @foreach (config('app.is_opened') as $item)
        @if ($is_open == $item['value'])
            <span class="badge bg-{{ $item['color'] }}">{{ $item['name'] }}</span>
        @endif
    @endforeach
@endisset
@isset($cash_register_transaction_type)
    @foreach (config('app.cash_register_transaction_type') as $item)
        @if ($cash_register_transaction_type == $item['value'])
            <span class="badge bg-{{ $item['color'] }}">{{ $item['name'] }}</span>
        @endif
    @endforeach
@endisset
@isset($related_to)
    @foreach (config('app.related_to') as $item)
        @if ($related_to == $item['value'])
            <span class="badge bg-{{ $item['color'] }}">{{ $item['name'] }}</span>
        @endif
    @endforeach
@endisset
@isset($income_expense_type_id)
    @foreach (config('app.incomeexpense') as $item)
        @if ($income_expense_type_id == $item['value'])
            <span class="badge bg-{{ $item['color'] }}">{{ $item['name'] }}</span>
        @endif
    @endforeach
@endisset
@isset($income_expense_payment_status)
    @foreach (config('app.payment_status') as $item)
        @if ($income_expense_payment_status == $item['value'])
            <span class="badge bg-{{ $item['color'] }}">{{ $item['name'] }}</span>
        @endif
    @endforeach
@endisset
@isset($sales_payment_status)
    @foreach (config('app.payment_status') as $item)
        @if ($sales_payment_status == $item['value'])
            <span class="badge bg-{{ $item['color'] }}">{{ $item['name'] }}</span>
        @endif
    @endforeach
@endisset
@isset($operator)
    @foreach (config('app.unit_oprators') as $item)
        @if ($operator == $item['value'])
            <span>{{ $item['value'] }}</span>
        @endif
    @endforeach
@endisset
@isset($payment_category)
    @foreach (config('app.payment_category') as $item)
        @if ($payment_category == $item['value'])
            <span class="badge bg-{{ $item['color'] }}">{{ $item['name'] }}</span>
        @endif
    @endforeach
@endisset
