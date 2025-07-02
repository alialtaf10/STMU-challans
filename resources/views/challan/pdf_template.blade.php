<!DOCTYPE html>
<html>
<head>
    <style>
        /* Simplified CSS for PDF */
        body { 
            font-family: Arial; 
            font-size: 9pt; 
            margin: 0; 
            padding: 0; 
        }
        .challan-container {
            width: 100%;
            display: inline-flex;
        }
        .copy { 
            width: 33.33%;  
            padding: 2mm; 
            border-right: 1px dashed #999; 
            box-sizing: border-box;
            height: 100vh /* Ensure full page height */
        }
        .copy:last-child {
            border-right: none;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            font-size: 8pt; 
            margin-top: 2mm;
        }
        th, td { 
            border: 1px solid #000; 
            padding: 1mm; 
        }
        th {
            background-color: #f2f2f2;
        }
        .header {
            text-align: center;
            font-weight: bold;
            margin-bottom: 3mm;
        }
        .info-row {
            margin-bottom: 1mm;
            font-size: 8pt;
        }
        .total-row {
            font-weight: bold;
        }
        .note {
            font-size: 7pt;
            margin-top: 3mm;
        }
        .signature-line {
            border-top: 1px solid #000;
            width: 70%;
            margin-top: 15px;
            display: inline-block;
        }
        .copy-label {
            position: absolute;
            top: 2mm;
            right: 2mm;
            font-weight: bold;
            font-size: 8pt;
        }
    </style>
</head>
<body>
    <div class="challan-container">
        @foreach(['Parent Copy', 'Bank Copy', 'STMU Copy'] as $copyType)
        <div class="copy" style="position: relative;">
            <div class="copy-label">{{ $copyType }}</div>
            
            <div class="header">
                Shifa Tameer-e-Millat University<br>
                Fee Challan
            </div>
            
            <div class="info-row">
                <strong>Ch./Receipt/Slip No:</strong> {{ $challan_no }}
            </div>
            
            <div class="info-row">
                <strong>Issue Date:</strong> {{ $issue_date }} | <strong>Due Date:</strong> {{ $due_date }}
            </div>
            
            <div class="info-row">
                <strong>Payable at:</strong> Bank Islami Pakistan Limited
            </div>
            
            <div class="info-row">
                <strong>Transaction via:</strong> LinkIslami Only
            </div>
            
            <div class="info-row">
                <strong>Instrument Type:</strong> □ Cash □ PO/DD □ Other
            </div>
            
            <div class="info-row">
                <strong>Instrument No:</strong> __________ | <strong>Date:</strong> __________
            </div>
            
            <div class="info-row">
                <strong>Drawn on Bank/Branch:</strong> ________________
            </div>
            
            <div class="info-row">
                <strong>Location:</strong> __________ | <strong>Amount Rs:</strong> {{ number_format($total, 2) }}
            </div>
            
            <div class="info-row">
                <strong>In Words:</strong> {{ $amount_in_words }}
            </div>
            
            <div class="info-row">
                <strong>Reg No:</strong> {{ $student->reg_no }} | <strong>Name:</strong> {{ $student->name }}
            </div>
            
            <div class="info-row">
                <strong>Program:</strong> {{ $student->program }} | <strong>Semester:</strong> {{ $fee->term->name }}
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
                            'application_prospectus_fee' => 'Prospectus/Application Fee',
                            'degree_convocation_fee' => 'Degree/Convocation Fee',
                            'research_thesis' => 'Research & Thesis',
                            'special_discount' => 'LESS: Special Discount'
                        ];
                    @endphp

                    @foreach ($heads as $key => $label)
                        @php
                            $value = $fee->$key ?? 0;
                            
                            // Calculate tuition fee based on credit hours if fee type is varied
                            if ($key === 'tuition_fee' && strtolower($fee->feeType->title) === 'varied') {
                                $value = $fee->tuition_fee * $student->credit_hrs;
                            }
                            
                            $isDiscount = $key === 'special_discount';
                        @endphp
                        <tr>
                            <td>{{ $label }}</td>
                            <td>{{ $isDiscount ? '-' : '' }}{{ number_format($value, 2) }}</td>
                        </tr>
                    @endforeach
                    
                    <tr class="total-row">
                        <td><strong>Total</strong></td>
                        <td><strong>{{ number_format($total, 2) }}</strong></td>
                    </tr>
                </tbody>
            </table>
            
            <div class="note">
                Note: After due date, a late fee of Rs. 200/day will apply and be used for charitable purposes.
            </div>
            
            <div style="margin-top: 5mm;">
                <div style="display: inline-block; width: 30%;">
                    <div class="signature-line">Depositor's Signature</div>
                </div>
                <div style="display: inline-block; width: 30%; margin-left: 5%;">
                    <div class="signature-line">Bank's Teller</div>
                </div>
                <div style="display: inline-block; width: 30%; margin-left: 5%;">
                    <div class="signature-line">Bank Officer</div>
                </div>
            </div>
            
            <div style="margin-top: 3mm;">
                <strong>Official Stamp:</strong>
            </div>
        </div>
        @endforeach
    </div>
</body>
</html>