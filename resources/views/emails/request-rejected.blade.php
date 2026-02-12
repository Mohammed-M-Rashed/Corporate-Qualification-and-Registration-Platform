<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تم رفض طلب التأهيل</title>
</head>
<body style="font-family: Arial, sans-serif; direction: rtl;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; background: #f5f5f5;">
        <div style="background: white; padding: 30px; border-radius: 10px;">
            <h1 style="color: #ef4444; text-align: center;">تم رفض طلب التأهيل</h1>
            <p>عزيزي/عزيزتي {{ $company->name }},</p>
            <p>نأسف لإعلامكم بأن طلب تأهيلكم برقم <strong>{{ $request->request_number }}</strong> قد تم رفضه.</p>
            <p><strong>سبب الرفض:</strong></p>
            <p style="background: #fee2e2; padding: 15px; border-radius: 5px;">{{ $reason }}</p>
            <p>يمكنكم التقديم مرة أخرى بعد تصحيح الأخطاء المذكورة أعلاه.</p>
            <p>شكراً لكم,<br>المؤسسة الوطنية للنفط</p>
        </div>
    </div>
</body>
</html>

