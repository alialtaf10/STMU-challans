@extends('layouts.app')

@section('content')
<div class="flex min-h-screen">
    <!-- Sidebar -->
    <div class="w-64 bg-base-200 p-6 shadow-md">
        <h2 class="text-xl font-bold mb-4 text-center">Dashboard</h2>
        <div class="flex flex-col space-y-2">
            <p class="text-sm text-center mb-4">Welcome, {{ Auth::user()->name }}</p>

            @if(auth()->user()->role == 'admin')
                

                <a href="{{ route('challans.excel_view') }}" class="btn btn-outline w-full">
                    <i class="fas fa-file-invoice mr-2"></i> Excel shaped Challans
                </a>

                <a href="{{ route('student_fees.updated') }}" class="btn btn-outline w-full">
                    Pending Challans
                </a>
                <a href="{{ route('student_fees-installment.updated') }}" class="btn btn-outline w-full">
                    Pending Installments
                </a>

                
            @endif
            

            @if(auth()->user()->role == 'student_affairs')

            <a href="{{ route('import.form') }}" class="btn btn-outline btn-success w-full">
                Excel Form
            </a>

            {{-- <a href="{{ route('challans.index') }}" class="btn btn-outline w-full">
                <i class="fas fa-file-invoice mr-2"></i> Fee Challans
            </a> --}}
            
            
            

            <a href="{{ route('student_fees.approved_list') }}" class="btn btn-outline w-full">
                Approved Students
            </a>

            <a href="{{ route('student_fees_installments.approved_list') }}" class="btn btn-outline w-full">
                Approved Installments
            </a>

            <a href="{{ route('student_fees.email_sent') }}" class="btn btn-outline w-full">
                Sent Emails
            </a>

            <a href="{{ route('student_fees_installment.email_sent') }}" class="btn btn-outline w-full">
                Sent Emails Installment
            </a>

            
            @endif

            <a href="{{ route('password.change') }}" class="btn btn-sm btn-outline mt-2">Change Password</a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-error btn-outline w-full mt-4">Logout</button>
            </form>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="flex-1 p-10">
        <h1 class="text-3xl font-bold mb-4">Dashboard</h1>
        <p>Use the sidebar to access your tools.</p>
    </div>
</div>
@endsection
