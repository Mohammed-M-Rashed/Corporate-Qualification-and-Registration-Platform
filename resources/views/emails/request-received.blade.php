<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تم استلام طلب التأهيل</title>
</head>
<body style="font-family: Arial, sans-serif; direction: rtl;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; background: #f5f5f5;">
        <div style="background: white; padding: 30px; border-radius: 10px;">
            <h1 style="color: #3b82f6; text-align: center;">تم استلام طلب التأهيل</h1>
            <p>عزيزي/عزيزتي {{ $company->name }},</p>
            <p>نود إعلامكم بأننا قد استلمنا طلب تأهيلكم برقم: <strong>{{ $request->request_number }}</strong></p>
            <p>سيتم مراجعة الطلب من قبل اللجنة المختصة وسنقوم بإشعاركم بنتيجة المراجعة.</p>
            <p>يمكنكم متابعة حالة الطلب من خلال رقم الطلب أعلاه.</p>
            <p>شكراً لكم,<br>المؤسسة الوطنية للنفط</p>
        </div>
    </div>
</body>
</html>

