<div class="card mt-2">
    <!--begin::Card header-->
    @include('pages.partials.form_collapse_header', ['header_name' => 'Stock Update History', 'card_key' => 'stock_hstory'])
    <!--begin::Card header-->

    <div id="stock_hstory" class="collapse">
        <!--begin::Card body-->
        <div class="card-body border-top px-9 py-4 table-responsive">
            <table class="table table-bordered align-middle table-striped table-row-dashed">
                <thead>
                    <tr class="fw-bold fs-6 text-gray-800">
                        <th>New Stock</th>
                        <th>Old Stock</th>
                        <th>New Status</th>
                        <th>Old Status</th>
                        <th>Old Box Number</th>
                        <th>New Box Number</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($histories as $key=>$item)
                        <tr>
                            <td>{{ $item->old_stock }}</td>
                            <td>{{ $item->new_stock }}</td>
                            <td>{{ $item->warehouse_stock_update->status }}</td>
                            <td>{{ $item->status }}</td>
                            <td>{{ $item->old_box_number }}</td>
                            <td>{{ $item->new_box_number }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">No History Available</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
