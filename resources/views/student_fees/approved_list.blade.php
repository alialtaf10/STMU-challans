@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <a href="{{route('dashboard')}}" class="btn btn-warning float-end">Back to Dashboard</a>
    <a href="{{ url()->previous() }}" class="btn btn-info float-end me-2">Back</a>
    <h2 class="text-2xl font-bold mb-4">Approved Student Fees</h2>

    <form action="{{ route('student_fees.send_emails') }}" method="POST">
        @csrf
        <div class="overflow-x-auto">
            <table class="table table-zebra w-full">
                <thead class="bg-base-200">
                    <tr>
                        <th><input type="checkbox" id="select-all"></th>
                        <th>#</th>
                        <th>Student Name</th>
                        <th>Fee Type</th>
                        <th>Term</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($approvedFees as $index => $fee)
                    <tr>
                        <td>
                            <input type="checkbox" name="student_fee_ids[]" value="{{ $fee->id }}" class="row-checkbox">
                        </td>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $fee->student->name ?? 'N/A' }}</td>
                        <td>{{ $fee->feeType->title ?? 'N/A' }}</td>
                        <td>{{ $fee->term->name ?? '-' }}</td>
                        <td><span class="badge badge-success">Approved</span></td>
                        <td>
                            <a href="{{ route('challans.view', ['student' => $fee->student_id]) }}" class="btn btn-sm btn-primary">View</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">No approved student fees found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-success">Send Email to Selected</button>
        </div>
    </form>
</div>

<script>
    // Select All Checkbox Logic
    document.getElementById('select-all').addEventListener('change', function () {
        const checkboxes = document.querySelectorAll('.row-checkbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });
</script>
@endsection
