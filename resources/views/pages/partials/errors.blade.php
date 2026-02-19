@if (session()->has('success'))
    <div id="successAlert" class="alert alert-success alert-dismissible fade show" role="alert" data-allow-clear="true">
        {{ Session::get('success') }}
    </div>
@endif

@if (session()->has('errors'))
    <div id="errorAlert" class="erroralerts alert-danger alert-dismissible fade show" role="alert"
        data-allow-clear="true">
        {{ Session::get('errors') }}
    </div>
@endif

@if (session()->has('validation_errors'))
    @if ($errors->any())
        <div id="validationErrors" class=" erroralerts alert-danger" data-allow-clear="true">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
@endif

@if (session()->has('error'))
    <div id="errorAlert" class="erroralerts alert-danger alert-dismissible fade show" role="alert"
        data-allow-clear="true">
        {{ Session::get('error') }}
    </div>
@endif

@section('scripts')
    <script>
        $(function() {
            // Fade out alert messages after 2 seconds
            $(".alert").delay(2000).fadeOut();

            // Optional: If you want to also remove the alert messages when the button is clicked
            $("button").click(function() {
                $(".alert").fadeOut();
            });
        });
    </script>
@endsection
