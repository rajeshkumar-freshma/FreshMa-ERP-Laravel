<!--begin::Table wrapper-->
<div class="d-flex flex-column">
    <!-- Styles in resources/freshma/src/sass/components/_datatables-toolbar.scss -->
    <div class="datatable-toolbar mb-3">
        <!-- Main Toolbar Row: Toggle Button (Mobile Only) -->
        <div class="datatable-toolbar-main d-flex align-items-center gap-2 mb-2">
            <!-- Collapse Toggle Button (Mobile Only) -->
            <button id="{{ $tableId }}-filter-toggle" class="btn btn-icon btn-sm btn-light-primary d-lg-none" type="button" title="Toggle Filters">
                <i class="fas fa-chevron-down"></i>
            </button>
        </div>
        
        <!-- Filter Row: Search, Rows, Status, Date Range (Collapsible on Mobile) -->
        <div id="{{ $tableId }}-filter-row" class="datatable-filter-row d-flex align-items-center gap-2" style="display: flex;">
            <div class="flex-grow-1">
                <div class="input-group input-group-sm flex-nowrap">
                    <span class="input-group-text bg-transparent border-0"><i class="fas fa-search"></i></span>
                    <input id="{{ $tableId }}-global-search" type="search" class="form-control form-control-sm form-control-solid" placeholder="Search" />
                </div>
            </div>
            <div style="min-width: 80px;">
                <select id="{{ $tableId }}-page-length" class="form-select form-select-sm form-select-solid w-100">
                    <option value="10">10</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="500">500</option>
                </select>
            </div>
            <div style="min-width: 120px;">
                <select id="{{ $tableId }}-status-filter" class="form-select form-select-sm form-select-solid w-100">
                    <option value="">All Status</option>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
            <div>
                <div class="input-group input-group-sm flex-nowrap">
                    <input id="{{ $tableId }}-date-from" type="text" class="form-control form-control-sm form-control-solid fsh_flat_datepicker" placeholder="From" autocomplete="off" style="width: 95px;" />
                    <input id="{{ $tableId }}-date-to" type="text" class="form-control form-control-sm form-control-solid fsh_flat_datepicker" placeholder="To" autocomplete="off" style="width: 95px;" />
                </div>
            </div>
            <div id="{{ $tableId }}-dt-buttons" class="btn-toolbar ms-auto justify-content-end"></div>
        </div>
    </div>

    <div class="table-responsive">
        {{ $dataTable->table(['class' => 'table table-striped table-row-bordered table-sm align-middle fs-7 gy-3'], true) }}
    </div>
</div>
<!--end::Table wrapper-->

{{-- Inject Scripts --}}
@section('scripts')
    {{ $dataTable->scripts() }}
    <script src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>

    <script>
        $(function() {
            var tableId = '{{ $tableId }}';
            var filterRow = $('#' + tableId + '-filter-row');
            var toggleBtn = $('#' + tableId + '-filter-toggle');
            var isCollapsed = false;
            
            // Function to update initial state on page load
            function initializeFilterState() {
                if ($(window).width() < 992) {
                    filterRow.hide();
                    isCollapsed = true;
                    toggleBtn.find('i').removeClass('fa-chevron-up').addClass('fa-chevron-down');
                } else {
                    filterRow.show();
                    isCollapsed = false;
                    toggleBtn.find('i').removeClass('fa-chevron-down').addClass('fa-chevron-up');
                    toggleBtn.hide();
                }
            }
            
            // Initialize on load
            initializeFilterState();
            
            // Handle toggle button click with event delegation
            $(document).on('click', '#' + tableId + '-filter-toggle', function(e) {
                e.preventDefault();
                var icon = $(this).find('i');
                
                if (isCollapsed) {
                    filterRow.slideDown(300, function() {
                        isCollapsed = false;
                    });
                    icon.removeClass('fa-chevron-down').addClass('fa-chevron-up');
                } else {
                    filterRow.slideUp(300, function() {
                        isCollapsed = true;
                    });
                    icon.removeClass('fa-chevron-up').addClass('fa-chevron-down');
                }
            });
            
            // Handle window resize
            $(window).on('resize', function() {
                setTimeout(function() {
                    if ($(window).width() >= 992) {
                        filterRow.show();
                        toggleBtn.hide();
                        isCollapsed = false;
                    } else {
                        toggleBtn.show();
                        if (isCollapsed) {
                            filterRow.hide();
                        } else {
                            filterRow.show();
                        }
                    }
                }, 100);
            });
        });
        
        (function() {
            // After DataTable init wire up UI controls to DataTable API
            $(document).on('init.dt', function(e, settings) {
                var tableId = '{{ $tableId }}';
                var currentTableId = settings.nTable.getAttribute('id');
                if (currentTableId !== tableId) return;

                var tableElement = $('#' + currentTableId);
                if (tableElement.data('toolbar-initialized') === true) return;
                tableElement.data('toolbar-initialized', true);

                var table = tableElement.DataTable();

                // Move buttons into our toolbar
                try {
                    var btns = $(settings.nTable).closest('.dataTables_wrapper').find('.dt-buttons');
                    if (btns.length) {
                        $('#' + tableId + '-dt-buttons').append(btns);
                    }
                } catch (err) {}

                // Customize create button text/icon and size
                var createBtn = $('#' + tableId + '-dt-buttons').find('.buttons-create, .dt-button.buttons-create');
                if (createBtn.length) {
                    createBtn.html('<i class="fas fa-plus-circle text-white me-1"></i>Add New');
                    createBtn.removeClass('btn-primary btn-secondary btn-lg').addClass('btn-success btn-xs btn-sm');
                }

                // Apply compact classes
                tableElement.addClass('table-sm');

                // Wire global search
                var searchDebounce;
                $('#' + tableId + '-global-search').off('keyup').on('keyup', function() {
                    var val = this.value;
                    clearTimeout(searchDebounce);
                    searchDebounce = setTimeout(function() {
                        table.search(val).draw();
                    }, 300);
                });

                // Wire status filter
                $('#' + tableId + '-status-filter').off('change').on('change', function() {
                    table.ajax.reload(null, false);
                });

                // Wire date filters
                $('#' + tableId + '-date-from, #' + tableId + '-date-to').off('change').on('change', function() {
                    table.ajax.reload(null, false);
                });

                // Wire page length
                $('#' + tableId + '-page-length').off('change').on('change', function() {
                    table.page.len(this.value).draw();
                });

                // Initialize flatpickr for date pickers
                try {
                    if (typeof flatpickr === 'function') {
                        flatpickr('#' + tableId + '-date-from, #' + tableId + '-date-to', {
                            dateFormat: 'Y-m-d',
                            onChange: function() {
                                table.ajax.reload(null, false);
                            }
                        });
                    }
                } catch (e) {}
            });
        })();

        // Master status toggle handler (outside init.dt, like Warehouse page)
        $(function() {
            var tableId = '{{ $tableId }}';
            var masterStatusUrl = '{{ route('admin.master.statuschange') }}';

            $('body').on('click', '.master-status-toggle', function() {
                var toggle = $(this);
                var id = toggle.data('id');
                var entity = toggle.data('entity');
                var statusValue = this.checked ? 1 : 0;

                $.ajax({
                    type: 'POST',
                    url: masterStatusUrl,
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        id: id,
                        entity: entity,
                        status_value: statusValue
                    },
                    success: function(result) {
                        alertMessage(result.message, result.status);
                    },
                    error: function() {
                        toggle.prop('checked', !statusValue);
                        alertMessage('Unable to update status.', 400);
                    }
                });
            });

            function alertMessage(message, status) {
                if (status == 200) {
                    var titleData = "Success";
                    var display_icon = "success";
                } else {
                    var titleData = "Oops!";
                    var display_icon = "error";
                }
                Swal.fire({
                    title: titleData,
                    text: message,
                    icon: display_icon,
                    showCancelButton: false,
                    confirmButtonText: 'Okay!',
                    customClass: {
                        confirmButton: 'btn btn-primary',
                        cancelButton: 'btn btn-outline-danger ms-1'
                    },
                    buttonsStyling: false
                }).then(function(result) {
                    if (result.isConfirmed) {
                        var table = $('#' + tableId);
                        table.DataTable().ajax.reload();
                        return false;
                    }
                });
            }
        });
    </script>
@endsection
