<!DOCTYPE html>
<html lang="bn">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Prescription Footer</title>
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@300;400;500;600&display=swap');

            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: 'Noto Sans Bengali', Arial, sans-serif;
                background: white;
                padding: 20px;
            }

            .prescription-footer {
                width: 100%;
                max-width: 800px;
                margin: 0 auto;
                border: 2px solid #333;
                border-radius: 8px;
                background: #f8f9fa;
                padding: 15px 20px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }

            .footer-header {
                text-align: center;
                border-bottom: 1px solid #666;
                padding-bottom: 8px;
                margin-bottom: 10px;
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

            /* Print styles */
            @media print {
                body {
                    padding: 0;
                }

                .prescription-footer {
                    border: 2px solid #000;
                    box-shadow: none;
                    max-width: none;
                    width: 100%;
                }
            }

            /* Mobile responsive */
            @media (max-width: 600px) {
                .footer-content {
                    flex-direction: column;
                    text-align: center;
                }

                .visit-time {
                    text-align: center;
                }

                .clinic-name {
                    font-size: 18px;
                }
            }
        </style>
    </head>
    <body>
        <div class="prescription-footer">
            <div class="footer-header">
                <div class="clinic-name">মাইনুল ডায়াগনস্টিক সেন্টার</div>
            </div>

            <div class="footer-content">
                <div class="contact-info">
                    <div class="info-row">
                        <span class="info-label">ঠিকানা:</span>
                        <span class="info-value">কুমিল্লা</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">ফোন:</span>
                        <span class="info-value">০১৭৩০-৪৮৮৫০৮</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">রেজি:</span>
                        <span class="info-value">
                            ডাঃ তাসনুভা খানম (কৃষ্ণা), হৃদরোগ, ডায়াবেটিস ও জেনেরাল ফিজিশিয়ান
                        </span>
                    </div>
                </div>

                <div class="visit-time">
                    <div class="visit-time-title">ভিজিট টাইম</div>
                    <div class="time-details">
                        সকাল ৯টা - দুপুর ১টা
                        <br />
                        সন্ধ্যা ৫টা - রাত ৯টা
                        <br />
                        শুক্রবার বন্ধ
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
