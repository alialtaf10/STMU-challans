@extends('layouts.app')

@section('content')
<div class="container" x-data="{ selectAll: true }">
    <a href="{{ route('dashboard') }}" class="btn btn-warning float-end">Back to Dashboard</a>
    <a href="{{ url()->previous() }}" class="btn btn-info float-end me-2">Back</a>
    <h2 class="mt-4">Generate Student Challans</h2>

    <div class="card mt-3">
        <div class="card-body">
            <form method="POST" action="{{ route('students.approve') }}">
                @csrf

                <table id="students-table" class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Registration No.</th>
                            <th>Student Name</th>
                            <th>Program</th>
                            <th>Current Semester</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                        <tr>
                            <td>
                                <input type="checkbox"
                                       name="student_ids[]"
                                       value="{{ $student->id }}"
                                       class="student-checkbox"
                                       :checked="selectAll"
                                >
                            </td>
                            <td>{{ $student->reg_no }}</td>
                            <td>{{ $student->name }}</td>
                            <td>{{ $student->program }}</td>
                            <td>{{ $student->currentTerm->name ?? 'N/A' }}</td>
                            <td>
                                <a href="{{ route('challans.create', $student) }}" class="btn btn-sm btn-primary">
                                    Generate Challan
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-3 d-flex justify-content-between align-items-center">
                    <div>
                        <input type="checkbox" id="selectAll"
                               class="form-check-input me-2"
                               x-model="selectAll"
                        >
                        <label for="selectAll" class="form-check-label">Select All Students</label>
                    </div>
                    <button type="submit" class="btn btn-success">
                        Approve Selected Students
                    </button>
                </div>
            </form>
        </div>
        {{ $students->links() }} 
    </div>
</div>
@endsection

