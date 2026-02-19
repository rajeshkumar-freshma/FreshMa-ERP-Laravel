<table class="table table-striped table-bordered table-hover">
    <thead>
        <tr>
            <th>Product/Store</th>
            @foreach ($stores as $store)
                <th>{{ $store->store_name }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @php
            $transfer_created_date = Carbon\Carbon::parse($transfer_created_date)->format('Y-m-d 00:00:00');
            $exists_transfer_histories = App\Models\ProductBulkTransfer::where([['from_warehouse_id', $from_warehouse_id], ['product_bulk_transfers.transfer_created_date', $transfer_created_date]])
                ->Join('product_bulk_transfer_histories', function ($join) {
                    $join->on('product_bulk_transfer_histories.product_bulk_transfer_id', 'product_bulk_transfers.id');
                })
                ->pluck('product_bulk_transfer_details_data')
                ->toArray();
                $exists_warehouse_indent_requests =
                App\Models\StoreIndentRequest::where([['warehouse_id', $from_warehouse_id],
                                                    ['request_date', $transfer_created_date]])->get();
        @endphp

        @foreach ($products as $product)
            <tr>
                <td>
                    {{ $product->name }}
                    <input type="hidden" class="form-control form-control-sm" name="transfer_product[product_id][]" id="product{{ $product->id }}" value="{{ $product->id }}">
                </td>
                @foreach ($stores as $keys => $store)
                    @php
                        $targetProductId = $product->id;
                        $targetStoreId = $store->id;
                        $historyContent = '';
                        $indent_request_quantity = '';
                        $lastQuantity = 0;
                    @endphp
                    @foreach ($exists_transfer_histories as $key => $exists_transfer_history)
                        @foreach (json_decode($exists_transfer_history) as $keys => $exists_transfer)
                            @if ($exists_transfer->store_id == $targetStoreId && $exists_transfer->product_id == $targetProductId)
                                @php
                                    $historyContent .= $exists_transfer->given_quantity . '+';
                                    $lastQuantity += $exists_transfer->given_quantity;
                                @endphp
                            @endif
                        @endforeach
                    @endforeach
                    @foreach ($exists_warehouse_indent_requests as $key => $exists_indent_request)
                            @if ($exists_indent_request->store_id == $targetStoreId && in_array($targetProductId, $exists_indent_request->store_indent_product_details->pluck('product_id')->toArray()))
                                @php
                                  $products = $exists_indent_request->store_indent_product_details->pluck('product_id')->toArray();
                                  $key = array_search($targetProductId, $products);
                                $indent_request_product_details = App\Models\StoreIndentRequestDetail::where([
                                    ['store_indent_request_id', $exists_indent_request->id],
                                    ['product_id', $products[$key]]]
                                    )->first();
                                    $indent_request_quantity .= $indent_request_product_details->request_quantity . '+';
                                    // $indent_request_quantity .=  rtrim($indent_request_product_details->request_quantity, '.000') . '+';
                                @endphp
                            @endif
                    @endforeach
                    <td>
                        <span class="badge bg-success">{!! $indent_request_quantity != "" ? substr_replace($indent_request_quantity, '', -1) : 0 !!}</span>
                        <input type="text" class="form-control form-control-sm" name="transfer_product[quantity][{{ $store->id }}][{{ $product->id }}]" id="transfer_product{{ $store->id }}{{ $product->id }}" placeholder="{{ $lastQuantity!=0 ? $lastQuantity : NULL }}">
                        <span class="badge bg-danger">{!! $historyContent != "" ? substr_replace($historyContent, '', -1) : 0 !!}</span>
                    </td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
