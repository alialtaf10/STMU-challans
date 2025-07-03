@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Installment Breakdown for {{ $student->name }}</h2>
    
    <form id="installmentForm" method="POST" action="{{ route('installments.send-email') }}">
        @csrf
        <input type="hidden" name="student_id" value="{{ $student->id }}">
        
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-light fw-bold d-flex justify-content-between align-items-center">
                        <span>Installment 1</span>
                        <small class="text-muted">Issue Date: {{ now()->format('d-M-Y') }}</small>
                    </div>
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
                        
                        <div class="form-group mt-3">
                            <label for="due_date_1">Due Date</label>
                            <input type="date" 
                                   class="form-control" 
                                   id="due_date_1" 
                                   name="due_date_1"
                                   value="{{ now()->addDays(15)->format('Y-m-d') }}"
                                   min="{{ now()->format('Y-m-d') }}">
                        </div>
                        
                        <div class="text-end mt-3">
                            <a href="#" 
                               class="btn btn-primary generate-challan" 
                               data-installment="1"
                               data-due-date-id="due_date_1"
                               target="_blank">
                                Generate Challan
                            </a>
                            
                            @if($installmentStatus < 1)
                                <button type="submit" 
                                        class="btn btn-success ms-2" 
                                        name="installment_number" 
                                        value="1">
                                    Send Email
                                </button>
                            @else
                                <span class="text-success ms-2">
                                    <i class="fas fa-check"></i> Email sent
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-light fw-bold d-flex justify-content-between align-items-center">
                        <span>Installment 2</span>
                        <small class="text-muted">Issue Date: {{ now()->format('d-M-Y') }}</small>
                    </div>
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
                        
                        <div class="form-group mt-3">
                            <label for="due_date_2">Due Date</label>
                            <input type="date" 
                                   class="form-control" 
                                   id="due_date_2" 
                                   name="due_date_2"
                                   value="{{ now()->addDays(45)->format('Y-m-d') }}"
                                   min="{{ now()->format('Y-m-d') }}">
                        </div>
                        
                        <div class="text-end mt-3">
                            <a href="#" 
                               class="btn btn-primary generate-challan" 
                               data-installment="2"
                               data-due-date-id="due_date_2"
                               target="_blank">
                                Generate Challan
                            </a>
                            
                            @if($installmentStatus < 2)
                                <button type="submit" 
                                        class="btn btn-success ms-2" 
                                        name="installment_number" 
                                        value="2"
                                        @if($installmentStatus < 1) disabled @endif>
                                    Send Email
                                </button>
                            @else
                                <span class="text-success ms-2">
                                    <i class="fas fa-check"></i> Email sent
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="mt-3 d-flex justify-content-end">
        <a href="{{ route('challans.index') }}" class="btn btn-secondary">Back</a>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle generate challan button clicks
    document.querySelectorAll('.generate-challan').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const installmentNumber = this.getAttribute('data-installment');
            const dueDateId = this.getAttribute('data-due-date-id');
            const dueDate = document.getElementById(dueDateId).value;
            
            // Format the URL with the due date
            const url = `{{ route('challans.installment-challan', ['student' => $student->id, 'installmentNumber' => 'INSTALLMENT_NUMBER']) }}`
                .replace('INSTALLMENT_NUMBER', installmentNumber) + `?due_date=${dueDate}`;
            
            window.open(url, '_blank');
        });
    });
});
</script>
@endsection