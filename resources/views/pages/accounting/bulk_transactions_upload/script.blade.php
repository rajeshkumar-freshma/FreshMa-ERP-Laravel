@section('scripts')
    @include('pages.partials.date_picker')
    <!-- Add this script within the <x-default-layout> tag -->
    <script>
        // Add an event listener to the file input field
        $(document).getElementById('file').addEventListener('change', function(event) {
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
