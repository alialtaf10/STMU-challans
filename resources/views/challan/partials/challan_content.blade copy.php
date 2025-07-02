<style>
    .university-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        text-align: center;
        margin-bottom: 10px;
        padding: 10px 0;
        border-bottom: 1px solid #ccc;
    }
    .challan-section {
        font-size: 9px;
        line-height: 1.5;
    }
    .challan-section table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 4px;
    }
    .challan-section th,
    .challan-section td {
        border: 1px solid #000;
        padding: 2px 4px;
    }
    .challan-section th {
        background-color: #f3f3f3;
    }
    .challan-meta,
    .challan-info {
        display: flex;
        justify-content: space-between;
        margin: 2mm 0;
    }
    .challan-meta div,
    .challan-info div {
        flex: 1;
    }
    .signature-row {
        display: flex;
        justify-content: space-between;
        margin-top: 6mm;
        font-size: 8px;
    }
    .signature-block {
        text-align: center;
        flex: 1;
    }
    .signature-line {
        margin-top: 20px;
        border-top: 1px solid #000;
    }
    .official-stamp {
        margin-top: 10px;
        font-size: 8px;
    }
</style>

<div class="university-header">
    <img src="{{ asset('asset/imgs/stmu-logo.jpg') }}" alt="STMU Logo" class="logo left-logo" height="70" width="70">
    
    <div class="university-text">
        <strong style="font-size: 17px">Shifa Tameer-e-Millat University</strong><br>
        <span style="font-size: 12px">Fee Challan</span>
    </div>

    <img src="{{ asset('asset/imgs/dubai-islami.jpg') }}" alt="Bank Islami Logo" class="logo right-logo" height="57" width="57">
</div>

<div class="challan-section">
    <div class="challan-meta">
        <div><strong>Ch./Receipt/Slip No:</strong> {{ $challan_no }}</div>
        <div><strong>Issue Date:</strong> {{ $issue_date }}</div>
        <div><strong>Due Date:</strong> {{ $due_date }}</div>
    </div>

    <div><strong>Payable at:</strong> BankIslami Pakistan Limited</div>
    <div><strong>Transaction to be processed via:</strong> LinkIslami Only</div>

    <div class="challan-meta">
        <div><strong>Instrument Type:</strong> Cash □ PO/DD □ Other □</div>
        <div><strong>Instrument No:</strong> __________</div>
        <div><strong>Date:</strong> __________</div>
    </div>

    <div><strong>Drawn on Bank / Branch:</strong> __________________</div>
    <div><strong>Location:</strong> __________ | <strong>Amount Rs:</strong> {{ number_format($total, 2) }}</div>
    <div><strong>In Words:</strong> {{ $amount_in_words }}</div>

    <div class="challan-info">
        <div><strong>Reg No:</strong> {{ $student->reg_no }}</div>
        <div><strong>Name:</strong> {{ $student->name }}</div>
    </div>

    <div class="challan-info">
        <div><strong>Program:</strong> {{ $student->program }}</div>
        <div><strong>Semester:</strong> {{ $fee->term->name ?? '-' }}</div>
    </div>

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
                    'examination_tution_fee' => 'Examination Fee',
                    'co_curricular_activities_fee' => 'Co-Curricular Activities',
                    'hostel_fee' => 'Hostel Fee',
                    'pharmacy_council_reg_fee' => 'Pharmacy Council Reg.',
                    'clinical_charge' => 'Clinical Charge',
                    'library_fee' => 'Library Fee',
                    'migration_fee' => 'Migration Fee',
                    'document_verification_fee' => 'Document Verification Fee',
                    'application_prospectus_fee' => 'Application/Prospectus Fee',
                    'degree_convocation_fee' => 'Degree and Convocation Fee',
                    'research_thesis' => 'Research & Thesis Fee',
                    'special_discount' => 'LESS: Special Discount'
                ];
            @endphp

            @foreach ($heads as $key => $label)
                @php
                    $value = $fee->$key ?? 0;
                    if ($key === 'tuition_fee' && strtolower($fee->feeType->title) === 'varied') {
                        $value = $fee->tuition_fee * $student->credit_hrs;
                    }
                @endphp
                <tr>
                    <td>{{ $label }}</td>
                    <td>{{ $key === 'special_discount' ? '-' : '' }}{{ number_format($value, 2) }}</td>
                </tr>
            @endforeach

            <tr class="total-row">
                <td><strong>Total</strong></td>
                <td><strong>{{ number_format($total, 2) }}</strong></td>
            </tr>
        </tbody>
    </table>

    <div class="note mt-2" style="font-size: 8px;">
        Note: The Voucher Shall be Invalid after the Due Date. A late fee of Rs. 200/day will apply and will be used for charitable purposes.
    </div>

    <div class="signature-row">
        <div class="signature-block">
            Depositor's Signature
            <div class="signature-line"></div>
        </div>
        <div class="signature-block">
            Bank's Teller
            <div class="signature-line"></div>
        </div>
        <div class="signature-block">
            Bank Officer
            <div class="signature-line"></div>
        </div>
    </div>

    <div class="official-stamp">
        <strong>Official Stamp:</strong>
    </div>
</div>
