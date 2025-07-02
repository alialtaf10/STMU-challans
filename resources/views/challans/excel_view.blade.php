@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <a href="{{ route('dashboard') }}" class="btn btn-warning float-end mb-3">Back to Dashboard</a>
    <h2 class="mb-4">Challan Fee Overview</h2>

    <div class="table-responsive">
        <table id="challanTable" class="table table-bordered table-striped nowrap" style="width:100%">
            <thead class="table-dark">
                <tr>
                    <th class="sticky-col">Sr #</th>
                    <th class="sticky-col">Reg No</th>
                    <th class="sticky-col">Name</th>
                    <th>Program</th>
                    <th>Current Term</th>
                    <th>Fee Type</th>
                    <th>Installment</th>
                    <th>Scholarship</th>
                    <th>Tuition Fee Discount</th>
                    @foreach($heads as $key => $label)
                        <th>{{ $label }}</th>
                    @endforeach
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $row)
                    @php $total = 0; @endphp
                    <tr>
                        <td class="sticky-col">{{ $loop->iteration }}</td>
                        <td class="sticky-col">{{ $row['student']->reg_no }}</td>
                        <td class="sticky-col">{{ $row['student']->name }}</td>
                        <td>{{ $row['student']->program }}</td>
                        <td>{{ $row['term'] }}</td>
                        <td>{{ $row['fee_type'] }}</td>
                        <td>{{ $row['installment'] }}</td>
                        <td>{{ $row['scholarship'] }}</td>
                        <td>{{ number_format($row['fees']['Tuition Fee Discount'] ?? 0) }}</td>
                        @foreach($heads as $label)
                            @php $amount = $row['fees'][$label] ?? 0; $total += $amount; @endphp
                            <td>{{ number_format($amount) }}</td>
                        @endforeach
                        <td><strong>{{ number_format($total) }}</strong></td>
                    </tr>
                @empty
                <tr>
                    <td colspan="{{ count($heads) + 10 }}" class="text-center">No data found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
<style>
    th.sticky-col, td.sticky-col {
        position: sticky;
        left: 0;
        background: white;
        z-index: 1;
    }
    td.sticky-col:nth-child(2) { left: 60px; }
    td.sticky-col:nth-child(3) { left: 120px; }
    th.sticky-col:nth-child(2) { left: 60px; }
    th.sticky-col:nth-child(3) { left: 120px; }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script>
    $(document).ready(function () {
        $('#challanTable').DataTable({
            dom: 'Bfrtip',
            buttons: ['excelHtml5'],
            scrollX: true,
            pageLength: 25
        });
    });
</script>
@endpush
