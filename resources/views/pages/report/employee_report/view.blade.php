<x-default-layout>
    <div class="card mb-5 mb-xl-10">
        <div class="card card-flush h-xl-100">
            <div class="card-body">
                <table class="table align-middle table-row-dashed fs-6 gy-3">
                    <thead>
                        <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                            <th class="text-center pe-0 min-w-50px">S.no</th>
                            <th class="text-center pe-0 min-w-50px">Staff ID</th>
                            <th class="text-center pe-0 min-w-50px">Joined At</th>
                            <th class="text-center pe-0 min-w-50px">Advance Amount</th>
                            <th class="text-center pe-0 min-w-50px">Departments</th>
                            <th class="text-center pe-0 min-w-50px">Designation</th>
                            <th class="text-center pe-0 min-w-50px">View</th>
                        </tr>
                    </thead>
                    <tbody class="fw-bold text-gray-600">
                        @foreach ($staffDetails as $key => $item)
                            <tr>
                                <td class="text-center">{{ $key + 1 }}</td>
                                <td class="text-center">{{ $item->admin->first_name }}</td>
                                <td class="text-center">{{ $item->joined_at }}</td>
                                <td class="text-center">100</td>
                                <td class="text-center">
                                    @if ($item->department)
                                        {{ $item->department->name }}
                                    @else
                                        N/A
                                    @endif
                                </td class="text-center">
                                <td class="text-center">{{ $item->designation->name }}</td>
                                <td>
                                    <a href="{{ route('admin.employee.show', $item->staff_id) }}">
                                        <button class="btn btn-sm btn-light btn-active-light-primary">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-default-layout>
