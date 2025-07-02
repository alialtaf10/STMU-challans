@extends('layouts.app')

@section('content')
<div class="container">
    <a href="{{ route('dashboard') }}" class="btn btn-warning float-end">Back to Dashboard</a>
    <a href="{{ url()->previous() }}" class="btn btn-info float-end me-2">Back</a>
    <h2>Student Fee Breakdown: {{ $student->name }}</h2>

    <div class="card"
         x-data="challanApp({{ $student->credit_hrs }}, {{ $finalScholarship }}, {{ $semesterFee->fee_type_id }})"
         x-init="initFees()">
        <div class="card-header">
            <h4>Scholarship: <span class="text-primary">{{ $scholarshipName }}</span></h4>
        </div>

        <div class="card-body">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Fee Type</th>
                        <th>Total Amount</th>
                        <th>Term</th>
                        <th>Installment</th>
                        <th>Challan</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $semesterFee->feeType->title }}</td>
                        <td>Rs. <span x-text="format(calculateTotal(0))"></span></td>
                        <td>{{ $semesterFee->term->name ?? '-' }}</td>
                        <td>{{$installment_exists}}</td>
                        <td>
                            <a href="{{ route('challans.show', [$student->id, $semesterFee->id]) }}" class="btn btn-sm btn-primary">
                                Show Challan
                            </a>
                            @if($installment_exists == "Yes")
                                <a href="{{ route('challans.installments', $student) }}" class="btn btn-sm btn-warning">
                                    Split Installments
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="p-0">
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
                                            <input type="number" step="0.01" class="form-control form-control-sm"
                                                   x-model.number="fees[0]['{{ $key }}']"
                                                   @input="calculateTotal(0)">
                                            @if($key === 'tuition_fee')
                                            <small class="text-muted">
                                                Tuition 
                                                <template x-if="feeTypeId !== 1">
                                                    <span>({{ $student->credit_hrs }} x per credit)</span>
                                                </template>
                                                — {{ $finalScholarship }}% scholarship applies
                                            </small>
                                            @endif
                                            @if($key === 'tuition_fee_discount')
                                            <small class="text-success">
                                                Scholarship Discount: Rs. 
                                                <span x-text="format((fees[0].tuition_fee || 0) * (scholarship / 100))"></span>
                                            </small>
                                            @endif
                                            @if($key === 'special_discount' || $key === 'tuition_fee_discount')
                                            <small style="color: red">* This amount will be subtracted from total</small>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach

                                    <tr>
                                        <td><strong>Due Date</strong></td>
                                        <td>
                                            <input type="date" name="due_date" class="form-control"
                                                   :value="new Date(new Date().setDate(new Date().getDate() + 15)).toISOString().split('T')[0]">
                                        </td>
                                    </tr>

                                    <tr class="table-dark fw-bold">
                                        <td>Total</td>
                                        <td>Rs. <span x-text="format(calculateTotal(0))"></span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="mt-3 d-flex justify-content-end">
                <a href="{{ route('student_fees.updated') }}" class="btn btn-secondary">Back to Students List</a>
                <form action="{{ route('student_fees.approve', $semesterFee->id) }}" method="POST" class="ms-2" style="display: inline">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-success">Approve</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function challanApp(creditHrs, scholarship, feeTypeId) {
    return {
        creditHrs,
        scholarship,
        feeTypeId,
        fees: [],

        initFees() {
            this.fees = [{
                id: {{ $semesterFee->id }},
                semester_id: {{ $semesterFee->term->id ?? 'null' }},
                fee_type_id: this.feeTypeId,
                @foreach($heads as $key => $label)
                {{ $key }}: {{ $semesterFee->$key ?? 0 }},
                @endforeach
            }];
        },

        format(value) {
            return parseFloat(value || 0).toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        },

        calculateTotal(index) {
            const data = this.fees[index];
            let total = 0;

            for (const key in data) {
                if (['id', 'semester_id', 'fee_type_id'].includes(key)) continue;
                const value = parseFloat(data[key] || 0);

                if (key === 'tuition_fee') {
                    const discount = (value * this.scholarship) / 100;
                    total += value - discount;
                } else if (key === 'special_discount' || key === 'tuition_fee_discount') {
                    total -= value;
                } else {
                    total += value;
                }
            }

            return total.toFixed(2);
        }
    };
}
</script>
@endsection
