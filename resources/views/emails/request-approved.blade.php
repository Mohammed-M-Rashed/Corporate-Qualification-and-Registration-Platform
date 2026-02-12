<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تم قبول طلب التأهيل</title>
</head>
<body style="font-family: Arial, sans-serif; direction: rtl;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; background: #f5f5f5;">
        <div style="background: white; padding: 30px; border-radius: 10px;">
            <h1 style="color: #10b981; text-align: center;">تم قبول طلب التأهيل</h1>
            <p>عزيزي/عزيزتي {{ $company->name }},</p>
            <p>نود إعلامكم بأن طلب تأهيلكم برقم <strong>{{ $request->request_number }}</strong> قد تم قبوله.</p>
            <p>تم تأهيل شركتكم بنجاح وتاريخ انتهاء التأهيل: <strong>{{ $company->qualification_expiry_date->format('Y-m-d') }}</strong></p>
            <p>تهانينا!</p>
            <p>شكراً لكم,<br>المؤسسة الوطنية للنفط</p>
        </div>
    </div>
</body>
</html>

