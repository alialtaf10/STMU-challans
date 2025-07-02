@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <a href="{{route('dashboard')}}" class="btn btn-warning float-end">Back to Dashboard</a>
    <a href="{{ url()->previous() }}" class="btn btn-info float-end me-2">Back</a>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success shadow-lg mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- Error Messages --}}
    @if($errors->any())
        <div class="alert alert-error shadow-lg mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
            <ul class="list-disc ml-4">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Import Form --}}
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <h2 class="card-title">Import Students</h2>

            <form action="{{ route('import.process') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Program --}}
                <div class="form-control mb-4">
                    <label class="label" for="program">
                        <span class="label-text">Program</span>
                    </label>
                    <select class="select select-bordered" name="program" id="program" required>
                        <option value="">Select Program</option>
                        <option value="BSCS">BSCS</option>
                        <option value="BSAI">BSAI</option>
                        <option value="BSSE">BSSE</option>
                        <option value="BSCyS">BSCyS</option>
                    </select>
                </div>

                {{-- Fee Type --}}
                <div class="form-control mb-4">
                    <label class="label" for="fee_type_id">
                        <span class="label-text">Fee Type</span>
                    </label>
                    <select class="select select-bordered" name="fee_type_id" id="fee_type_id" required>
                        <option value="">Select Fee Type</option>
                        @foreach($feeTypes as $feeType)
                            <option value="{{ $feeType->id }}">{{ $feeType->title }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- File Upload --}}
                <div class="form-control mb-4">
                    <label class="label" for="file">
                        <span class="label-text">Excel File</span>
                    </label>
                    <input type="file" name="file" id="file" class="file-input file-input-bordered w-full" required accept=".xlsx,.xls" />
                </div>

                {{-- Submit Button --}}
                <div class="card-actions justify-end mt-4">
                    <button type="submit" class="btn btn-primary">Import</button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
