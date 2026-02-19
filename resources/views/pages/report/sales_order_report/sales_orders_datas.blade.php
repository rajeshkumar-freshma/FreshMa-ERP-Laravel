@if (isset($sales_from))
    @foreach (config('app.sales_from') as $item)
        @if ($sales_from == $item['value'])
            <span class="badge bg-{{ $item['color'] }}">{{ $item['name'] }}</span>
        @endif
    @endforeach
@endif

@if (isset($sales_type))
    @foreach (config('app.sales_type') as $item)
        @if ($sales_type == $item['value'])
            <span class="badge bg-{{ $item['color'] }}">{{ $item['name'] }}</span>
        @endif
    @endforeach
@endif
