@extends('layouts.app')

@section('content')
<div class="container">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <h2>Generate Challan for {{ $student->name }}</h2>

    <form method="POST" action="{{ route('challans.storeFees', $student) }}">
        @csrf

        <div class="card" id="challanApp">

            {{-- Scholarship Display --}}
            <div class="card-header">
                @if(isset($scholarship))
                    <h4>
                        Scholarship Applied:
                        <span class="text-success">{{ $scholarship->type_name }}</span>
                    </h4>
                @else
                    <h4>No Scholarship Assigned</h4>
                @endif
            </div>

            <div class="card-body">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Fee Type</th>
                            <th>Semester</th>
                            <th>Total Amount</th>
                            <th>Installment</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $semesterFee->feeType->title }}</td>
                            <td>{{ $semesterFee->term->name ?? '' }}</td>
                            <td>Rs. <span id="totalAmount">0</span></td>
                            <td>{{$installment_exists}}</td>
                            <td>
                                @if($semesterFee->challan_id)
                                    <a href="{{ route('challans.show', [$student, $semesterFee->id]) }}" class="btn btn-sm btn-primary">
                                        View Challan
                                    </a>
                                @endif
                                
                            </td>
                        </tr>

                        <tr>
                            <td colspan="4" class="p-0">
                                <table class="table table-sm table-striped mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Fee Head</th>
                                            <th>Amount (Rs.)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($heads as $key => $label)
                                        <tr>
                                            <td>{{ $label }}</td>
                                            <td>
                                                @if($key === 'tuition_fee')
                                                    <div>
                                                        {{-- <input type="number" step="1" class="form-control form-control-sm" id="tuitionFee" value="{{ intval($semesterFee->tuition_fee ?? 0) }}" readonly> --}}
                                                        <input type="number" step="1" class="form-control form-control-sm" id="tuitionFee"
                                                            value="{{ $semesterFee->feeType->title === 'varied' ? intval($semesterFee->tuition_fee) : intval($semesterFee->tuition_fee ?? 0) }}"
                                                            readonly>
                                                        <input type="hidden" name="fees[0][tuition_fee]" id="tuitionFeeHidden" value="{{ intval($semesterFee->tuition_fee ?? 0) }}">
                                                        <small class="text-muted">
                                                            Tuition 
                                                            <template x-if="{{ $semesterFee->fee_type_id }} != 1">
                                                                <span>({{ $student->credit_hrs }} x per credit)</span>
                                                            </template>
                                                            minus {{ $finalScholarship }}% scholarship( Cr. Hrs = {{ $student->credit_hrs }})
                                                        </small>
                                                    </div>
                                                @else
                                                    <input type="number" step="1" class="form-control form-control-sm" name="fees[0][{{ $key }}]" id="{{ $key }}" value="{{ intval($semesterFee->$key ?? 0) }}" oninput="calculateTotal()">
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach

                                        <input type="hidden" name="fees[0][id]" value="{{ $semesterFee->id }}">
                                        <input type="hidden" name="fees[0][semester_id]" value="{{ $semesterFee->term->id ?? '' }}">
                                        <input type="hidden" name="fees[0][fee_type_id]" value="{{ $semesterFee->feeType->id }}">
                                        <input type="hidden" name="fees[0][scholarship_percentage]" value="{{ $finalScholarship }}">

                                        <tr>
                                            <td>Scholarship Applied</td>
                                            <td>{{ $finalScholarship }}% on Tuition Fee</td>
                                        </tr>

                                        <tr class="table-dark fw-bold">
                                            <td>Total</td>
                                            <td>Rs. <span id="totalAmountDisplay">0</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="mt-3 d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Send For Approval
                    </button>
                    <a href="{{ route('challans.index') }}" class="btn btn-secondary">Back to Students List</a>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    function calculateTotal() {
        const tuitionBase = parseInt(document.getElementById('tuitionFeeHidden').value) || 0;
        const feeTypeId = {{ $semesterFee->fee_type_id }};
        const creditHrs = {{ $student->credit_hrs }};
        const scholarshipPercentage = {{ $finalScholarship }};

        let tuition = feeTypeId !== 1 ? tuitionBase * creditHrs : tuitionBase;
        const discount = Math.floor((tuition * scholarshipPercentage) / 100);
        const finalTuitionFee = tuition - discount;

        let total = finalTuitionFee;

        const feeHeads = @json($heads);
        for (const key in feeHeads) {
            if (key !== 'tuition_fee') {
                const feeInput = document.getElementById(key);
                const feeValue = parseInt(feeInput?.value || 0);
                total += feeValue;
            }
        }

        document.getElementById('totalAmount').innerText = total;
        document.getElementById('totalAmountDisplay').innerText = total;
    }

    document.addEventListener('DOMContentLoaded', calculateTotal);
</script>
@endsection
