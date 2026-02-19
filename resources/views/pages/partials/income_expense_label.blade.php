@foreach (config('app.incomeexpense') as $item)
    @if($type == $item['value'])
        <span class="badge bg-{{$item['color']}}">{{ $item['name'] }}</span>
    @endif
@endforeach