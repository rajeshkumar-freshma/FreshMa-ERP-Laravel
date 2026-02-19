<x-default-layout>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title px-sm-4 px-md-1 px-lg-2 px-xl-3">Store Details</h3>
            <div class="card-tools px-sm-4 px-md-1 px-lg-2 px-xl-3" title="Minimize/Maximize">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row text-center px-sm-4 px-md-1 px-lg-2 px-xl-3">
                <div class="col-md-6">
                    <div class="row border border-default h-100">
                        <div class="col-6 col-md-4 p-0">
                            <p class="font-weight-bold mb-0 p-2 bg-secondary">Store</p>
                        </div>
                        <div class="col-6 col-md-8">
                            <p class="mb-0 p-2 text-md font-weight-bold">{{ $stores->store_name != null ? $stores->store_name : '-' }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row border border-default h-100">
                        <div class="col-6 col-md-4 p-0">
                            <p class="font-weight-bold mb-0 p-2 bg-secondary">Slug</p>
                        </div>
                        <div class="col-6 col-md-8">
                            <p class="mb-0 p-2 text-md font-weight-bold">{{ $stores->slug != null ? $stores->slug : '-' }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row border border-default h-100">
                        <div class="col-6 col-md-4 p-0">
                            <p class="font-weight-bold mb-0 p-2 bg-secondary">Store Code </p>
                        </div>
                        <div class="col-6 col-md-8">
                            <p class="mb-0 p-2 text-md font-weight-bold">{{ $stores->store_code != null ? $stores->store_code : '-' }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row border border-default h-100">
                        <div class="col-6 col-md-4 p-0">
                            <p class="font-weight-bold mb-0 p-2 bg-secondary">Warehouse</p>
                        </div>
                        <div class="col-6 col-md-8">
                            @foreach ($warehouses as $key => $warehouse)
                                <option value="{{ @$stores->warehouse_id }}"
                                    {{ @$stores->warehouse_id == $warehouse->id ? 'selected' : '' }}>
                                    {{ $warehouse->name }}</option>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row border border-default h-100">
                        <div class="col-6 col-md-4 p-0">
                            <p class="font-weight-bold mb-0 p-2 bg-secondary">Gst Number</p>
                        </div>
                        <div class="col-6 col-md-8">
                            <p class="mb-0 p-2 text-md font-weight-bold">{{ $stores->gst_number != null ? $stores->gst_number : '-' }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row border border-default h-100">
                        <div class="col-6 col-md-4 p-0">
                            <p class="font-weight-bold mb-0 p-2 bg-secondary">Start Date</p>
                        </div>
                        <div class="col-6 col-md-8">
                            <p class="mb-0 p-2 text-md font-weight-bold">{{ $stores->start_date != null ? @$stores->start_date : '-' }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row border border-default h-100">
                        <div class="col-6 col-md-4 p-0">
                            <p class="font-weight-bold mb-0 p-2 bg-secondary">Phone Number</p>
                        </div>
                        <div class="col-6 col-md-8">
                            <p class="mb-0 p-2 text-md font-weight-bold">
                                {{ $stores->phone_number != null ? $stores->phone_number : '-' }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row border border-default h-100">
                        <div class="col-6 col-md-4 p-0">
                            <p class="font-weight-bold mb-0 p-2 bg-secondary">Email</p>
                        </div>
                        <div class="col-6 col-md-8">
                            <p class="mb-0 p-2 text-md font-weight-bold">{{ $stores->email != null ? $stores->email : '-' }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row border border-default h-100">
                        <div class="col-6 col-md-4 p-0">
                            <p class="font-weight-bold mb-0 p-2 bg-secondary">Address</p>
                        </div>
                        <div class="col-6 col-md-8">
                            <p class="mb-0 p-2 text-md font-weight-bold">{{ $stores->address != null ? $stores->address : '-' }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row border border-default h-100">
                        <div class="col-6 col-md-4 p-0">
                            <p class="font-weight-bold mb-0 p-2 bg-secondary">Country</p>
                        </div>
                        <div class="col-6 col-md-8">
                            <p class="mb-0 p-2 text-md font-weight-bold">
                                {{ $stores->country->name != null ? $stores->country->name : '-' }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row border border-default h-100">
                        <div class="col-6 col-md-4 p-0">
                            <p class="font-weight-bold mb-0 p-2 bg-secondary">City</p>
                        </div>
                        <div class="col-6 col-md-8">
                            <p class="mb-0 p-2 text-md font-weight-bold">{{ $stores->city->name != null ? $stores->city->name : '-' }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row border border-default h-100">
                        <div class="col-6 col-md-4 p-0">
                            <p class="font-weight-bold mb-0 p-2 bg-secondary">State</p>
                        </div>
                        <div class="col-6 col-md-8">
                            <p class="mb-0 p-2 text-md font-weight-bold">{{ $stores->state->name != null ? $stores->state->name : '-' }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row border border-default h-100">
                        <div class="col-6 col-md-4 p-0">
                            <p class="font-weight-bold mb-0 p-2 bg-secondary">Pin Code</p>
                        </div>
                        <div class="col-6 col-md-8">
                            <p class="mb-0 p-2 text-md font-weight-bold">{{ $stores->pincode != null ? $stores->pincode : '-' }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row border border-default h-100">
                        <div class="col-6 col-md-4 p-0">
                            <p class="font-weight-bold mb-0 p-2 bg-secondary">Latitude</p>
                        </div>
                        <div class="col-6 col-md-8">
                            <p class="mb-0 p-2 text-md font-weight-bold">{{ $stores->latitude != null ? $stores->latitude : '-' }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row border border-default h-100">
                        <div class="col-6 col-md-4 p-0">
                            <p class="font-weight-bold mb-0 p-2 bg-secondary">Longitude</p>
                        </div>
                        <div class="col-6 col-md-8">
                            <p class="mb-0 p-2 text-md font-weight-bold">{{ $stores->longitude != null ? $stores->longitude : '-' }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row border border-default h-100">
                        <div class="col-6 col-md-4 p-0">
                            <p class="font-weight-bold mb-0 p-2 bg-secondary">Direction</p>
                        </div>
                        <div class="col-6 col-md-8">
                            <p class="mb-0 p-2 text-md font-weight-bold">{{ $stores->direction != null ? $stores->direction : '-' }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row border border-default h-100">
                        <div class="col-6 col-md-4 p-0">
                            <p class="font-weight-bold mb-0 p-2 bg-secondary">Status</p>
                        </div>
                        <div class="col-6 col-md-8">
                            <p class="mb-0 p-2 text-md font-weight-bold">
                                {{ $stores->status == 1 ? 'Active' : 'Deactive' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-default-layout>
