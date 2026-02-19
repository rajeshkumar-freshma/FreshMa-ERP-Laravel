<table class="table table-striped table-bordered table-sm">
    <thead>
        <tr>
            <th>S.No.</th>
            <th scope="col">Machine Name</th>
            <th scope="col">Order Count</th>
            <th scope="col">Total Order Amount</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($datas as $key => $sales_order_data)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $sales_order_data->machine_name}}</td>
                <td>{{ $sales_order_data->order_count }}</td>
                <td>{{ $sales_order_data->total_amount }}</td>
            </tr>
        @endforeach
    </tbody>
    {{-- <tfoot>
        <tr >
            <th>Total</th>
            @foreach ($datas as $count)
                <th colspan="4" style="text-align:right">Total Quantity</th>
                <th class="text-right">{{ $count->total_amount }}</th>
                <th colspan="4" style="text-align:right">Total Amount</th>
                <th class="text-right">
                    {{ '₹' . number_format($count->total_sale_price, 2) }}</th>
            @endforeach
        </tr>
    </tfoot> --}}
</table>
