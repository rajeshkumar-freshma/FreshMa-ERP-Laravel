<!--begin::Card-->
<div class="card">
    <!--begin::Card body-->
    <div class="card-body pt-6">
        <div class="col-md-8">
            <div class="mb-5">
                <div class="d-flex justify-content-right">
                    @can('Cash Register Create')
                        @foreach (@$store as $item)
                            <a href="{{ route('admin.cash-register-create', $item->id) }}"><button type="button"
                                    class="btn btn-sm btn-success btn-active-light me-2">{{ $item->store_name }}</button></a>
                        @endforeach
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>
<!--ending::Card-->

<!--begin::Table-->
{{ $dataTable->table() }}
<!--end::Table-->

{{-- Inject Scripts --}}
@section('scripts')
    {{ $dataTable->scripts() }}
    <script src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const tabButtons = document.querySelectorAll('.tab-btn');
            const tabPanes = document.querySelectorAll('.tab-pane');

            tabButtons.forEach(button => {
                button.addEventListener('click', () => {
                    // Hide all tab panes
                    tabPanes.forEach(pane => {
                        pane.style.display = 'none';
                    });

                    // Show the selected tab pane
                    const selectedStore = button.getAttribute('data-store');
                    const selectedPane = document.querySelector(
                        `.tab-pane[data-store="${selectedStore}"]`);
                    selectedPane.style.display = 'block';
                });
            });
        });
    </script>
@endsection
