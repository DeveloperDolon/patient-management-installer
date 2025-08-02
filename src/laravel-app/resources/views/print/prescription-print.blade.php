<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Prescription - Dr. Sahadev Adhikari</title>
        <style>
            * {
                box-sizing: border-box;
                margin: 0;
                padding: 0;
            }

            .bn_font {
                font-family: 'solaimanlipi', sans-serif;
            }

            body {
                font-family: Arial, Helvetica, sans-serif;
                font-size: 13px;
                line-height: 1.4;
                color: #000;
                background: white;
                height: 100%;
                margin: 0;
                padding: 0px;
            }

            /* Header Section */
            .header {
                margin-bottom: 10px;
                width: 100%;
                padding: 30px 50px 0px;
            }

            .dr-info-left,
            .dr-info-right {
                width: 50%;
                vertical-align: top;
                font-size: 14px;
            }

            .dr-info-right {
                text-align: right;
            }

            .doctor-name {
                font-size: 16px;
                font-weight: bold;
                margin-bottom: 3px;
            }

            .doctor-name-bn {
                font-size: 16px;
                margin-bottom: 3px;
            }

            .qualifications {
                font-size: 10px;
                margin-bottom: 2px;
            }

            .specialization {
                font-size: 10px;
                margin-bottom: 8px;
            }

            .clinic-info {
                font-size: 9px;
                line-height: 1.3;
            }

            /* Patient Info Section */
            .patient-info {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 10px 50px;
                width: 100% !important;
                border: 1px solid #000;
                gap: 20px;
                background: rgb(223, 242, 255);
            }

            .info {
                display: inline-block;
                width: calc(25% - 2.5px) !important;
                font-size: 12px;
                font-weight: 600;
            }

            .patient-row {
                display: table-row;
            }

            .patient-cell {
                display: table-cell;
                border: 1px solid #000;
                padding: 5px 8px;
                vertical-align: top;
            }

            .patient-label {
                font-weight: bold;
                font-size: 10px;
            }

            .patient-value {
                font-size: 11px;
                min-height: 15px;
            }

            /* Age and Sex inline */
            .age-sex-container {
                display: table;
                width: 100%;
            }

            .age-sex-row {
                display: table-row;
            }

            .age-cell,
            .sex-cell {
                display: table-cell;
                border: 1px solid #000;
                padding: 5px 8px;
                width: 50%;
            }

            /* Main Content Area - Fixed height and layout */
            .main-container {
                display: flex;
                flex-direction: column;
                height: 209mm;
                width: 86%;
                margin: 0 auto
            }

            .main-content {
                width: 100%;
                border: 2px solid #000;
                margin-bottom: 10px;
                height: 100%!important;
                background: red;
            }

            .content-row {
                height: 100%;
            }

            .left-section,
            .right-section {
                float: left;
                padding: 30px 10px 10px;
                box-sizing: border-box;
                height: 210mm;
            }

            .left-section {
                width: 32%;
                border-right: 0.5px solid #000;
            }

            .right-section {
                width: 62%;
            }

            /* Left Section Styling */
            .complaints-section,
            .history-section,
            .investigation-section {
                margin-bottom: 15px;
            }

            .section-header {
                font-weight: bold;
                font-size: 10px;
                margin-bottom: 5px;
                text-decoration: underline;
            }

            .section-content {
                font-size: 10px;
                line-height: 1.4;
                min-height: 40px;
            }

            .section-content p {
                margin: 2px 0;
                padding: 0;
            }

            /* Complaints specific styling */
            .complaints-grid {
                width: 100%;
            }

            .complaints-row {
                display: table-row;
            }

            .complaint-item {
                display: table-cell;
                width: 50%;
                padding-right: 10px;
                font-size: 10px;
            }

            /* Investigation grid */
            .investigation-grid {
                display: table;
                width: 100%;
            }

            .investigation-row {
                display: table-row;
            }

            .investigation-item {
                display: table-cell;
                padding: 2px 5px;
                font-size: 9px;
                border-bottom: 1px dotted #ccc;
            }

            /* Right Section - Prescription */
            .rx-header {
                font-size: 14px;
                font-weight: bold;
                margin-bottom: 10px;
                text-align: center;
            }

            .prescription-item {
                margin-bottom: 12px;
                font-size: 10px;
            }

            .prescription-number {
                font-weight: bold;
                margin-bottom: 3px;
            }

            .prescription-details {
                line-height: 1.3;
                margin-left: 15px;
            }

            /* Footer - Fixed at bottom */
            .footer-container {
                background: #c3e1ff6b;
            }

            .footer {
                text-align: right;
                width: 100%;
                page-break-inside: avoid;
            }

            .signature-line {
                border-top: 1px solid #000;
                width: 200px;
                margin-left: auto;
                margin-bottom: 5px;
            }

            .signature-text {
                font-size: 10px;
            }

            .date-section {
                margin-top: 20px;
                font-size: 10px;
            }

            /* clinic information */
            .prescription-footer {
                width: 100%;
                margin: 0 auto;
                padding: 5px 20px 10px;
            }

            .footer-header {
                text-align: center;
            }

            .clinic-name {
                font-size: 22px;
                font-weight: 600;
                color: #2c3e50;
                margin-bottom: 5px;
                letter-spacing: 0.5px;
            }

            .footer-content {
                display: flex;
                justify-content: space-between;
                align-items: center;
                flex-wrap: wrap;
                text-align: center;
                gap: 15px;
            }

            .contact-info {
                flex: 1;
                min-width: 250px;
            }

            .info-row {
                display: flex;
                align-items: center;
                margin-bottom: 6px;
                font-size: 14px;
                color: #34495e;
            }

            .info-label {
                font-weight: 500;
                margin-right: 8px;
                min-width: 60px;
                font-size: 12px;
            }

            .info-value {
                font-weight: 400;
            }

            .visit-time {
                flex: 1;
                min-width: 200px;
                text-align: right;
            }

            .visit-time-title {
                font-weight: 600;
                font-size: 14px;
                color: #2c3e50;
                margin-bottom: 5px;
            }

            .time-details {
                font-size: 13px;
                color: #34495e;
                line-height: 1.4;
            }

            /* Print optimizations */
            @media print {
                body {
                    -webkit-print-color-adjust: exact;
                    print-color-adjust: exact;
                    height: auto;
                    padding: 0;
                    margin: 0;
                }

                .no-break {
                    page-break-inside: avoid;
                }

                .main-content {
                    height: auto;
                    min-height: 0;
                }
            }
        </style>
    </head>
    <body>
        <!-- Header -->
        <table class="header">
            <tr>
                <td class="dr-info-left" style="line-height: 15px;">
                    <div class="doctor-name">{{ 'Dr. Shahadev Adhikari' }}</div>
                    <div class="qualifications">MBBS, BCS ( Health), FCPS( Dermatology), DDV(BSMMU)</div>
                    <div class="qualifications">Post Graduation Diploma in cosmetics and Laser surgery (India)</div>
                    <div class="clinic-info">
                        City Center, 2nd Floor, Shop No. 17
                        <br />
                        Fingone West, Noapara, Block -A
                        <br />
                        Phone : 9800
                        <br />
                        Phone : 8100
                        <br />
                    </div>
                </td>

                <td class="dr-info-right bn_font" style="line-height: 15px;">
                    <div class="doctor-name-bn">ডাঃ শহাদেব অধিকারী</div>
                    <div class="qualifications">
                        এমবিবিএস, বিসিএস (স্বাস্থ্য), এফসিপিএস (ডার্মাটোলজি), ডিডিভি (বিএসএমএমইউ)
                    </div>
                    <div class="qualifications">কসমেটিকস ও লেজার সার্জারিতে পোস্ট গ্র্যাজুয়েশন ডিপ্লোমা (ভারত)</div>
                    <div class="clinic-info">
                        সিটি সেন্টার, ২য় তলা, দোকান নং- ১৭
                        <br />
                        ফিনগন ওয়েস্ট, নোয়াপাড়া, ব্লক-এ
                        <br />
                        ফোন : ৯৮০০
                        <br />
                        ফোন : ৮১০০
                        <br />
                    </div>
                </td>
            </tr>
        </table>

        <!-- Patient Information -->
        <table class="patient-info">
            <tr>
                <td class="info">Name: {{ $patient->name }}</td>
                <td class="info">Age : {{ $patient->age }}</td>
                <td class="info" style="text-align: right">Sex : {{ ucfirst($patient->gender) }}</td>
                <td class="info" style="text-align: right">Date : {{ $patient->created_at }}</td>
            </tr>
        </table>

        <!-- Main Container for content and footer -->
        <div class="main-container">
            <!-- Main Content -->
            {{-- <div class="main-content"> --}}
                <!-- Left Section -->
                <div class="left-section">
                    <!-- Complaints -->
                    <div class="complaints-section">
                        <div class="section-header">CHIEF COMPLAINTS</div>
                        <div class="section-content">
                            @if (!$prescription->complaints)
                                <p style="font-style: italic;">No Complaints</p>
                            @else
                               @foreach ($prescription->complaints as $complaint)
                                        <p>• {{ $complaint }}</p>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <!-- History -->
                    <div class="history-section">
                        <div class="section-header">PRESENT ILLNESS</div>
                        <div class="section-content">
                            @if (!$prescription->present_illness)
                                <p style="font-style: italic;">No illness </p>
                            @else
                               @foreach ($prescription->present_illness as $illness)
                                    <p>• {{ $illness }}</p>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    <div class="history-section">
                        <div class="section-header">PAST ILLNESS</div>
                        <div class="section-content">
                            @if (!$prescription->past_illness)
                                <p style="font-style: italic;">No illness </p>
                            @else
                               @foreach ($prescription->present_illness as $illness)
                                    <p>• {{ $illness }}</p>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    <!-- Illness -->
                    <div class="history-section">
                        <div class="section-header">CONCOMITANT MEDICAL ILLNESS</div>
                        <div class="section-content">
                            @if (!$prescription->history_of_concomitant_illness)
                                <p style="font-style: italic;">No illness found</p>
                            @else
                                @foreach ($prescription->history_of_concomitant_illness as $illness)
                                <p>• {{ $illness }}</p>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    
                    {{-- operational history --}}
                    <div class="investigation-section">
                        <div class="section-header">OPERATIONAL HISTORY</div>
                        <div class="section-content">
                            @if (!$prescription->operational_history)
                                <p style="font-style: italic;">No operational history found</p>
                            @else
                                @foreach ($prescription->operational_history as $history)
                                    <p>• {{ $history }}</p>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    <!-- family history -->
                    <div class="investigation-section">
                        <div class="section-header">FAMILY HISTORY</div>
                        <div class="section-content">
                            @if (!$prescription->family_disease_history)
                                <p style="font-style: italic;">No family history found</p>
                            @else
                                @foreach ($prescription->family_disease_history as $disease)
                                    <p>• {{ $disease }}</p>
                                @endforeach
                            @endif
                        </div>
                    </div>

                     <!-- personal history -->
                    <div class="investigation-section">
                        <div class="section-header">PERSONAL HISTORY</div>
                        <div class="section-content">
                            @if (!$prescription->personal_history)
                                <p style="font-style: italic;">No personal history found</p>
                            @else
                                @foreach ($prescription->personal_history as $history)
                                    <p>• {{ $history }}</p>
                                @endforeach
                            @endif
                        </div>
                    </div>

                     <!-- vaccination history -->
                    <div class="investigation-section">
                        <div class="section-header">VACCINATION HISTORY</div>
                        <div class="section-content">
                            @if (!$prescription->vaccination_history)
                                <p style="font-style: italic;">No vaccination history found</p>
                            @else
                                @foreach ($prescription->vaccination_history as $history)
                                    <p>• {{ $history }}</p>
                                @endforeach
                            @endif
                        </div>
                    </div>

                     <!-- obstetric history -->
                    <div class="investigation-section">
                        <div class="section-header">OBSTETRIC HISTORY</div>
                        <div class="section-content">
                            @if (!$prescription->obstetric_history)
                                <p style="font-style: italic;">No obstetric history found</p>
                            @else
                                @foreach ($prescription->obstetric_history as $history)
                                    <p>• {{ $history }}</p>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

                <?php
                    $allFrequency = ['morning', 'noon', 'night'];
                ?>

                <!-- Right Section - Prescriptions -->
                <div class="right-section">
                    <div class="rx-header">Rx</div>
                    <div style="min-height: 200px">
                        @if ($prescription['medication_guidelines'] != null)
                            @foreach ($prescription->medication_guidelines as $index => $guideline)
                                <div class="prescription-item bn_font">
                                    <div class="prescription-number">{{ $index + 1 }}. {{ $guideline['name'] }}</div>
                                    <div class="prescription-details bn_font">

                                            @foreach ($allFrequency as $index => $frequency)
                                                @if (in_array($frequency, $guideline['frequency']))
                                                    {{ count($allFrequency) != $index + 1 ? '১ +' : '১' }}
                                                @else
                                                    {{ count($allFrequency) != $index + 1 ? '০ +' : '০' }}
                                                @endif
                                            @endforeach

                                            -------- (খাবারের {{ $guideline['meal_instruction'] == 'after' ? 'পরে' : 'আগে' }}) {{ $guideline['duration_days'] }} দিন
                                        
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p style="text-align: center">No medication guideline</p>
                        @endif
                    </div>
                    <!-- Additional sections with Bengali text placeholders -->
                    <div style="font-size: 10px; margin-top: 15px">
                        <div class="section-header">ADVICES</div>
                        @if ($prescription->advices != null)
                            @foreach ($prescription->advices as $advice)
                                <p style="font-size: 10px">=> {{ $advice }}</p>
                            @endforeach
                        @endif
                    </div>

                    <div class="footer">
                        <div class="date-section">Date: 25/05/2025</div>
                        <div class="signature-line"></div>
                        <div class="signature-text">Signature 25/5/25</div>
                    </div>
                </div>
            {{-- </div> --}}
        </div>

        <div style="border-bottom: 1px solid #000;"></div>
        </div>
    </body>
</html>
