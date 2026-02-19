<table style="width: 100%; margin-top: 10px; font-size: 0.8em;" border="1px">
    <thead>
        <tr>
            @foreach ($headers as $key => $header)
                @if ($key == 0)
                    <th rowspan="2" align="center" style="vertical-align: middle;">
                        {{ $header }}
                    </th>
                @else
                    <th colspan="3" width="100%" style="background-color: greenyellow" align="center">
                        {{ $header }}
                    </th>
                @endif
            @endforeach
        </tr>
        <tr>
            @foreach ($headers as $key => $header)
                @if ($key != 0)
                    <th style="background-color:darkgreen; color:white">
                        Open
                    </th>
                    <th style="background-color: red; color:white">
                        Close
                    </th>
                    <th style="background-color:cornflowerblue; color:white">
                        Usage
                    </th>
                @endif
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($stock_datas as $main_key => $stock_data)
            <tr>
                <td width="100%">{{ $stock_data['sku_code'] != null ? $stock_data['product_name'] . ' - ' . $stock_data['sku_code'] : $stock_data['product_name'] }}</td>
                @foreach ($stock_data['stock'] as $sub_key => $stocks)
                    <td>{{ $stocks['openingstock'] }}</td>
                    <td>{{ $stocks['closingstock'] }}</td>
                    <td>{{ $stocks['usage_stock'] }}</td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
