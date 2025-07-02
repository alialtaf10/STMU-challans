@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <a href="{{ route('dashboard') }}" class="btn btn-warning float-end">Back to Dashboard</a>
    <a href="{{ url()->previous() }}" class="btn btn-info float-end me-2">Back</a>

    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold">Approved Student Fees</h2>
    </div>

    @if($studentFees->count() > 0)
        <form action="{{ route('fees.bulkAction') }}" method="POST">
            @csrf
            <div class="table-responsive">
                <table id="approvedFeesTable" class="table table-bordered table-striped nowrap" style="width:100%">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Student Name</th>
                            <th>Reg No.</th>
                            <th>Semester</th>
                            <th>Term</th>
                            <th>Status</th>
                            <th>Action</th>
                            <th class="text-center">
                                <input type="checkbox" id="checkAll" title="Select/Unselect All">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($studentFees as $index => $fee)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $fee->student->name ?? 'N/A' }}</td>
                                <td>{{ $fee->student->reg_no ?? 'N/A' }}</td>
                                <td>{{ $fee->semester->name ?? 'N/A' }}</td>
                                <td>{{ $fee->term->name ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-success text-white">{{ $fee->status }}</span>
                                </td>
                                <td>
                                    <a class="btn btn-sm btn-primary" href="{{ route('challans.view', ['student' => $fee->student_id]) }}">
                                        View
                                    </a>
                                </td>
                                <td class="text-center">
                                    <input type="checkbox" name="fee_ids[]" class="fee-checkbox" value="{{ $fee->id }}">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4 text-end">
                <button type="submit" class="btn btn-lg btn-outline btn-primary">
                    Perform Bulk Approval
                </button>
            </div>
        </form>
    @else
        <div class="alert alert-info shadow-sm mt-4">
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M12 20h.01" />
                </svg>
                <span>No updated student fees found.</span>
            </div>
        </div>
    @endif
</div>
@endsection

@push('styles')
    <!-- DataTables + Buttons CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
@endpush

@push('scripts')
    <!-- jQuery + DataTables + Buttons -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

    <script>
        $(document).ready(function () {
            // DataTable
            $('#approvedFeesTable').DataTable({
                dom: 'Bfrtip',
                buttons: ['excelHtml5'],
                responsive: true,
                pageLength: 30,
                order: [[0, 'asc']],
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search student..."
                }
            });

            // Check/uncheck all checkboxes
            $('#checkAll').on('change', function () {
                $('.fee-checkbox').prop('checked', this.checked);
            });
        });
    </script>
@endpush
