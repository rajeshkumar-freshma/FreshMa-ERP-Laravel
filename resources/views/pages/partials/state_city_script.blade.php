<!-- Ajax for Country and state selector-->
<script>
    $(document).ready(function() {
        $('#country-dropdown').on('click change', function() {
            var url = "{{ route('admin.getStatesByCountry') }}";
            var country_id = this.value;
            $.ajax({
                type: "GET",
                url: url,
                data: {
                    'country_id': country_id
                },
                success: (function(result) {
                    $("#state-dropdown").empty();
                    if (result.status == 200) {
                        $("#state-dropdown").append('<option value="">Select option</option>');
                        $.each(result.states, function(key, value) {
                            $("#state-dropdown").append('<option value="' + value.id + '">' + value.name + '</option>');
                        })
                    }
                })
            });
        }).trigger('change');

        $('#state-dropdown').on('click change', function() {
            var url = "{{ route('admin.getCityByState') }}";
            var state_id = this.value;
            $.ajax({
                type: "GET",
                url: url,
                data: {
                    'state_id': state_id
                },
                success: (function(result) {
                    $("#city-dropdown").empty();
                    if (result.status == 200) {
                        $('#city-dropdown').append('<option value="">Select option</option>');
                        $.each(result.cities, function(key, value) {
                            $("#city-dropdown").append('<option value="' + value.id + '">' + value.name + '</option>');
                        })
                    }
                })
            });
        }).trigger('change');
    });

    @if (!empty(old('country_id')))
        $(function() {
            var country_id = "{{ old('country_id') }}";
            var state_id = "{{ old('state_id') }}";
            var url = "{{ route('admin.getStatesByCountry') }}";
            var city_id = "{{ old('city_id') }}";

            $.ajax({
                type: "GET",
                url: url,
                data: {
                    'country_id': country_id
                },
                success: (function(result) {
                    $("#state-dropdown").empty();
                    if (result.status == 200) {
                        $.each(result.states, function(key, value) {
                            $("#state-dropdown").append('<option value="' + value.id + '" ' + ((value.id == state_id) ? 'selected' : '') + ' >' + value.name + '</option>');
                        })
                        //Call City For Edit
                        var url = "{{ route('admin.getCityByState') }}";
                        $.ajax({
                            type: "GET",
                            url: url,
                            data: {
                                'state_id': state_id
                            },
                            success: (function(result) {
                                $("#city-dropdown").empty();
                                if (result.status == 200) {
                                    $.each(result.cities, function(key, value) {
                                        $("#city-dropdown").append('<option value="' + value.id + '" ' + ((value.id == city_id) ? 'selected' : '') + ' >' + value.name + '</option>');
                                    })
                                }
                            })
                        });
                    }
                })
            });
        })
    @endif

    @if (isset($country_id) && !empty($country_id))
        $(function() {
            var country_id = "{{ $country_id }}";
            var state_id = "{{ $state_id }}";
            var url = "{{ route('admin.getStatesByCountry') }}";
            var city_id = "{{ $city_id }}";

            $.ajax({
                type: "GET",
                url: url,
                data: {
                    'country_id': country_id
                },
                success: (function(result) {
                    $("#state-dropdown").empty();
                    if (result.status == 200) {
                        $.each(result.states, function(key, value) {
                            $("#state-dropdown").append('<option value="' + value.id + '" ' + ((value.id == state_id) ? 'selected' : '') + ' >' + value.name + '</option>');
                        })
                        //Call City For Edit
                        var url = "{{ route('admin.getCityByState') }}";
                        $.ajax({
                            type: "GET",
                            url: url,
                            data: {
                                'state_id': state_id
                            },
                            success: (function(result) {
                                $("#city-dropdown").empty();
                                if (result.status == 200) {
                                    $.each(result.cities, function(key, value) {
                                        $("#city-dropdown").append('<option value="' + value.id + '" ' + ((value.id == city_id) ? 'selected' : '') + ' >' + value.name + '</option>');
                                    })
                                }
                            })
                        });
                    }
                })
            });
        })
    @endif
</script>
