@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Installment Breakdown for {{ $student->name }}</h2>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-light fw-bold">Installment 1</div>
                <div class="card-body">
                    <table class="table table-bordered">
                        @foreach ($heads as $key => $label)
                        <tr>
                            <td>{{ $label }}</td>
                            <td>Rs. {{ number_format($installment1[$key] ?? 0, 2) }}</td>
                        </tr>
                        @endforeach
                        <tr class="table-dark fw-bold">
                            <td>Total</td>
                            <td>Rs. {{ number_format(array_sum($installment1), 2) }}</td>
                        </tr>
                    </table>
                    <div class="text-end mt-3">
                        <a href="{{ route('challans.installment-challan', ['student' => $student->id, 'installmentNumber' => 1]) }}" 
                           class="btn btn-primary" target="_blank">
                            Generate Challan
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-light fw-bold">Installment 2</div>
                <div class="card-body">
                    <table class="table table-bordered">
                        @foreach ($heads as $key => $label)
                        <tr>
                            <td>{{ $label }}</td>
                            <td>Rs. {{ number_format($installment2[$key] ?? 0, 2) }}</td>
                        </tr>
                        @endforeach
                        <tr class="table-dark fw-bold">
                            <td>Total</td>
                            <td>Rs. {{ number_format(array_sum($installment2), 2) }}</td>
                        </tr>
                    </table>
                    <div class="text-end mt-3">
                        <a href="{{ route('challans.installment-challan', ['student' => $student->id, 'installmentNumber' => 2]) }}" 
                           class="btn btn-primary" target="_blank">
                            Generate Challan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-3 d-flex justify-content-end">
        <a href="{{ route('challans.index') }}" class="btn btn-secondary">Back</a>
    </div>
</div>
@endsection