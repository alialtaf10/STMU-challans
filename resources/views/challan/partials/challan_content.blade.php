<style>
    body {
        height: 100vh;
        margin: 0;
        padding: 0;
        font-family: Arial, sans-serif;
    }
    
    .university-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        text-align: center;
        margin-bottom: 4px;
        padding: 6px 0;
        border-bottom: 1px solid #ccc;
    }
    
    .challan-section {
        font-size: 9.5px;
        line-height: 1.4;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: calc(100vh - 110px);
        padding: 6px 8px;
        box-sizing: border-box;
    }
    
    .challan-section table {
        width: 97%;
        border-collapse: collapse;
        margin: 3px auto 0 auto;
    }
    
    .challan-section th,
    .challan-section td {
        border: 1px solid #000;
        padding: 3px 4px;
    }
    
    .challan-section th {
        background-color: #f3f3f3;
    }
    
    .challan-meta,
    .challan-info {
        display: flex;
        justify-content: space-between;
        margin: 3mm 0;
    }
    
    .challan-meta div,
    .challan-info div {
        flex: 1;
    }
    
    .signature-row {
        display: flex;
        justify-content: space-between;
        margin-top: 4mm;
        font-size: 9px;
    }
    
    .signature-block {
        text-align: center;
        flex: 1;
    }
    
    .signature-line {
        margin-top: 6px;
        border-top: 1px solid #000;
        height: 1px;
    }
    
    .official-stamp {
        margin-top: 6px;
        font-size: 8.5px;
    }
    
    .bordered-table {
        width: 97%;
        border-collapse: collapse;
        margin: 4px auto;
    }
    
    .bordered-table td, 
    .bordered-table th {
        border: 1px solid #000;
        padding: 3px;
    }
    
    .instrument-options {
        display: flex;
        justify-content: space-around;
        margin-top: 4px;
        max-width: 480px;
    }
    
    .instrument-option {
        display: flex;
        align-items: center;
        gap: 4px;
    }
    
    .bordered-bg {
        display: inline-block;
        border: 1px solid #000;
        width: 55px;
        height: 12px;
    }
</style>

<div class="university-header">
    <img src="{{ asset('asset/imgs/stmu-logo.jpg') }}" alt="STMU Logo" class="logo left-logo" height="70" width="70">
    
    <div class="university-text">
        <strong style="font-size: 17px">Shifa Tameer-e-Millat University</strong><br>
        <span style="font-size: 12px">{{ $copyTitle ?? 'Fee Challan' }}</span>
    </div>

    <img src="{{ asset('asset/imgs/dubai-islami.jpg') }}" alt="Bank Islami Logo" class="logo right-logo" height="57" width="57">
</div>

<div class="challan-section">
    {{-- <div class="challan-meta">
        <div><strong>Ch./Receipt/Slip No:</strong> {{ $challan_no }}</div>
        <div><strong>Issue Date:</strong> {{ $issue_date }}</div>
        <div><strong>Due Date:</strong> {{ $due_date }}</div>
    </div> --}}
    <table class="bordered-table">
        <tr>
            <td>Ch./Receipt/Slip No:</td>
            <td>{{ $challan_no }}</td>
            <td style="background-color: rgb(99, 153, 229)">KUICKPAY ID:</td>
            <td style="background-color: rgb(99, 153, 229)">{{ $kuickpay_id ?? 'N/A' }}</td>
        </tr>
        @php
            use Carbon\Carbon;
            $issueDate = Carbon::now()->format('d-m-Y');
            $dueDate = Carbon::now()->addDays(15)->format('d-m-Y');
        @endphp
        
        <tr>
            <td>Issue Date:</td>
            <td>{{ $issueDate }}</td>
            <td>Due Date:</td>
            <td>{{ $dueDate }}</td>
        </tr>
        <tr>
            <td colspan="4" style="text-align: center;"><strong>Payable at: BankIslami Pakistan Limited</strong></td>
        </tr>
        <tr>
            <td colspan="4" style="text-align: center;"><strong>Transaction to be processed via: LinkIslami Only</strong></td>
        </tr>
    </table>

    {{-- <div><strong>Payable at:</strong> BankIslami Pakistan Limited</div> --}}
    {{-- <div><strong>Transaction to be processed via: LinkIslami Only</strong></div> --}}

    <div style="margin-top:5px">
        <div><strong>Instrument Type:</strong></div>
        <div class="instrument-options">
            <div class="instrument-option" style="margin-left: 50px">
                <span>Cash</span>
                <span class="bordered-bg"></span>
            </div>
            <div class="instrument-option">
                <span>PO/DD</span>
                <span class="bordered-bg"></span>
            </div>
            <div class="instrument-option">
                <span>Other</span>
                <span class="bordered-bg"></span>
            </div>
        </div>
    
        <div style="margin-top: 10px;">Instrument No: <spans= style="margin-left: 70%">Date:</span> </div>
        <hr>
    </div>
    

    {{-- <div class="challan-meta">
        <div><strong>Instrument Type:</strong> Cash □ PO/DD □ Other □</div>
        <div><strong>Instrument No:</strong> __________</div>
        <div><strong>Date:</strong> __________</div>
    </div> --}}

    <div>
        Drawn on <br>
        Bank / Branch:
        <hr>
    </div>

    <div>
        Location: <spans= style="margin-left: 65%">Amount Rs:</span><strong style="width:100%; padding-left:28px; padding:7px; border: 1px solid black">{{ number_format($total, 2) }}</strong>
        <hr>
        in Words: <strong>{{ $amount_in_words }}</strong>
        <hr>
    </div>



    {{-- <div><strong>Drawn on Bank / Branch:</strong> __________________</div>
    <div><strong>Location:</strong> __________ | <strong>Amount Rs:</strong> {{ number_format($total, 2) }}</div>
    <div><strong>In Words:</strong> {{ $amount_in_words }}</div> --}}

    
    <table class="bordered-table">
        <tr>
            <td colspan="2">Registration No:</td>
            <td colspan="2">{{ $student->reg_no }}</td>
        </tr>

        <tr>
            <td colspan="2">Name:</td>
            <td colspan="2">{{ $student->name }}</td>
        </tr>

        <tr>
            <td>Program:</td>
            <td>{{ $student->program }}</td>
            <td>Semstr/Year:</td>
            <td>{{ $student->semester->name }}</td>
        </tr>
    </table>

    {{-- <div class="challan-info">
        <div><strong>Reg No:</strong> {{ $student->reg_no }}</div>
        <div><strong>Name:</strong> {{ $student->name }}</div>
    </div>

    <div class="challan-info">
        <div><strong>Program:</strong> {{ $student->program }}</div>
        <div><strong>Semester:</strong> {{ $fee->term->name ?? '-' }}</div>
    </div> --}}

    <table>
        <thead>
            <tr>
                <th>Particulars</th>
                <th>Amount (Rs)</th>
            </tr>
        </thead>
        <tbody>
            @php
                $heads = [
                    'tuition_fee' => 'Tuition Fee',
                    'admission_fee' => 'Admission Fee',
                    'university_registration_fee' => 'University Registration Fee',
                    'security_deposit' => 'Security Deposit',
                    'medical_checkup' => 'Medical Checkup',
                    'semester_enrollment_fee' => 'Enrollment Fee',
                    'examination_tuition_fee' => 'Examination Fee',
                    'co_curricular_activities_fee' => 'Co-Curricular Activities',
                    'hostel_fee' => 'Hostel Fee',
                    'pmc_registration' => 'PMC Registration',
                    'pharmacy_council_reg_fee' => 'Pharmacy Council Reg.',
                    'clinical_charge' => 'Clinical Charges',
                    'transport_charge' => 'Transport Charges',
                    'library_fee' => 'Library Fee',
                    'migration_fee' => 'Migration Fee',
                    'document_verification_fee' => 'Document Verification Fee',
                    'application_prospectus_fee' => 'Application/Prospectus Fee',
                    'degree_convocation_fee' => 'Degree and Convocation Fee',
                    'research_thesis' => 'Research & Thesis Fee',
                    'others_specify' => 'Others(Specify)',
                    'late_fee' => 'Late Fee',
                    'tuition_fee_discount' => 'LESS:Tuition Fee Discount/Waiver',
                    'special_discount' => 'LESS: Special Discount/Scholarship'
                ];
            @endphp

            @foreach ($heads as $key => $label)
            @php
                $value = $fee->$key ?? 0;
                if ($key === 'tuition_fee' && strtolower($fee->feeType->title) === 'varied') {
                    $value = $fee->tuition_fee;
                }
            @endphp
            <tr>
                <td>{{ $label }}</td>
                <td class="fee-amount" 
                    data-key="{{ $key }}"
                    data-amount="{{ $value }}">
                    {{ $key === 'special_discount' ? '-' : '' }}{{ number_format($value, 2) }}
                </td>
            </tr>
            @endforeach

            <tr class="total-row">
                <td><strong>Total</strong></td>
                <td><strong>{{ number_format($total, 2) }}</strong></td>
            </tr>
        </tbody>
    </table>

    <div class="note mt-2" style="font-size: 8px;">
        <span style="margin-right: 10px; margin-left:3px">Note:</span> <span style="float: right">The Voucher Shall be Invalid after the Due Date. A late fee of Rs. 200/day will apply and will be used for charitable purposes.</span>
        <hr>
    </div>

    <div class="note mt-2" style="font-size: 10px;">
        Depositor's Signature
        <hr>
    </div>

    <div class="signature-row" style="height:30px">
        <div class="signature-block">
            Bank's Teller
            <div class="signature-line"></div>
        </div>
        <div class="signature-block">
            Official Stamp
            <div class="signature-line"></div>
        </div>
        <div class="signature-block">
            Bank Officer
            <div class="signature-line"></div>
        </div>
    </div>
</div>


