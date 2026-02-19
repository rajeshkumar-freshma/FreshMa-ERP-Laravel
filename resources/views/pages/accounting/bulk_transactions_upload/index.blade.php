<x-default-layout>
    <!--begin::Card-->
    @section('_toolbar')
        @include('pages.partials.common-toolbar', [
            'title' => 'Bulk Transaction Upload',
            'menu_1_link' => route('admin.dashboard'),
            'menu_2' => 'Bulk Transaction Upload',
        ])
    @endsection
    <!--begin::Card-->
    <div class="card">
        <!--begin::Card body-->
        <div class="card-body pt-6">
            <form action="{{ route('admin.bulk-transactions-upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('POST')
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-5">
                            <label for="bank_id" class="form-label required">{{ __('Bank Account Holder name') }}</label>
                            <div class="input-group input-group-sm flex-nowrap">
                                <span class="input-group-text border-0"><i class="fas fa-home"></i></span>
                                <div class="overflow-hidden flex-grow-1">
                                    <select name="bank_id" id="bank_id" aria-label="{{ __('Select Bank') }}"
                                        data-control="select2" data-placeholder="{{ __('Select Bank..') }}"
                                        class="form-select form-select-sm form-select-solid" data-allow-clear="true">
                                        <option value="">{{ __('Select Branch..') }}</option>
                                        @foreach (@$accounts as $key => $value)
                                            <option value="{{ old('bank_id', $value->id) }}">
                                                {{ $value->account_holder_name }} - {{$value->bank_name}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @if ($errors->has('bank_id'))
                                <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('bank_id') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-4" id="file-preview">
                        <div class="mb-5">
                            <label for="file" class="form-label required">{{ __('File') }}</label>
                            <div class="input-group input-group-sm flex-nowrap">
                                <input type="file" name="file" id="file" class="form-control border-0">
                                <label class="input-group-text border-0" for="file">
                                    <i class="fas fa-file"></i>
                                </label>
                            </div>
                            <p class="text-color-danger">Note: xlsx and csv only</p>
                            @if ($errors->has('file'))
                                <span class="fv-plugins-message-container invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('file') }}</strong>
                                </span>
                            @endif
                        </div>

                    </div>
                    @if (auth()->user()->can('Bulk Transaction Upload Data'))
                        <div class="col-md-4">
                            <div class="mb-5">
                                <div class="d-flex justify-content-right py-6">
                                    {{-- <a><button type="button"
                                        class="btn btn-sm btn-primary btn-active-light-primary me-2">{{ __('Download') }}</button></a> --}}
                                    <button type="submit"
                                        class="btn btn-sm btn-success me-2 upload_button btn-active-light-success">{{ __('Upload') }}</button>
                                    <a href="{{ route('admin.upload-transactions.index') }}"><button type="button"
                                            class="btn btn-sm btn-warning btn-active-light-warning me-2">{{ __('Clear') }}</button></a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </form>
        </div>
        <div class="card-body">
            @include('pages.accounting.bulk_transactions_upload._table')
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Card-->
    @section('scripts')
        <script>
            // Add an event listener to the file input field
            document.getElementById('file').addEventListener('change', function(event) {
                const file = event.target.files[0];
                const reader = new FileReader();

                // Define a function to handle file reading completion
                reader.onload = function(e) {
                    const contents = e.target.result;
                    const rows = contents.split('\n'); // Split file contents by new line

                    // Assuming the first row contains column headers
                    const headers = rows[0].split(',');

                    // Display the column headers dynamically
                    const headerContainer = document.getElementById('file-preview');
                    headerContainer.innerHTML = '';
                    headers.forEach(header => {
                        const div = document.createElement('div');
                        div.textContent = header;
                        headerContainer.appendChild(div);
                    });

                    // You can also display other rows' data here if needed
                };

                // Read the selected file as text
                reader.readAsText(file);
            });
        </script>
    @endsection
</x-default-layout>
