@forelse ($overall_requests as $key=>$item)
    <tr>
        <td>
            <div class="form-check form-check-custom form-check-solid">
                <input class="form-check-input product_checkbox product_checkbox{{ $item->product_id }}" type="checkbox" value="{{ $item->product_id }}" id="product_id{{ $item->product_id }}" name="product_ids[]" data-id="{{ $item->product_id }}" data-unit_id="{{ $item->unit_id }}" data-quantity="{{ $item->quantity }}"/>
            </div>
        </td>
        <td class="text-gray-800 fw-bold fs-6 me-1 text-success">{{ $item->name }}</td>
        <td class="text-gray-800 fw-bold fs-6 me-1 text-success">{{ $item->unit_name . ' (' . $item->unit_short_code . ')' }}</td>
        <td class="text-gray-800 fw-bold fs-6 me-1 text-success">{{ $item->quantity }}</td>
    </tr>
@empty
    <tr>
        <td colspan="9">No Data Available</td>
    </tr>
@endforelse
