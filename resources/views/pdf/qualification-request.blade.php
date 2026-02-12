<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقرير طلب التأهيل - {{ $request->request_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #007bff;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #007bff;
            font-size: 24px;
            margin-bottom: 10px;
        }
        .header h2 {
            color: #666;
            font-size: 18px;
        }
        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }
        .section-title {
            background-color: #007bff;
            color: white;
            padding: 10px;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        .info-row {
            display: flex;
            margin-bottom: 10px;
            padding: 8px;
            background-color: #f8f9fa;
            border-right: 3px solid #007bff;
        }
        .info-label {
            font-weight: bold;
            width: 200px;
            color: #007bff;
        }
        .info-value {
            flex: 1;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 11px;
        }
        .status-new { background-color: #6c757d; color: white; }
        .status-under_review { background-color: #ffc107; color: #000; }
        .status-approved { background-color: #28a745; color: white; }
        .status-rejected { background-color: #dc3545; color: white; }
        .documents-list {
            list-style: none;
            padding: 0;
        }
        .documents-list li {
            padding: 8px;
            margin-bottom: 5px;
            background-color: #f8f9fa;
            border-right: 3px solid #007bff;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #ddd;
            text-align: center;
            color: #666;
            font-size: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table th, table td {
            padding: 8px;
            text-align: right;
            border: 1px solid #ddd;
        }
        table th {
            background-color: #007bff;
            color: white;
        }
        table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>تقرير طلب التأهيل</h1>
        <h2>المؤسسة الوطنية للنفط</h2>
    </div>

    <div class="section">
        <div class="section-title">معلومات الطلب</div>
        <div class="info-row">
            <span class="info-label">رقم الطلب:</span>
            <span class="info-value">{{ $request->request_number }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">تاريخ الإنشاء:</span>
            <span class="info-value">{{ $request->created_at->format('Y-m-d H:i') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">الحالة:</span>
            <span class="info-value">
                <span class="status-badge status-{{ $request->status->value }}">
                    @if($request->status->value == 'new') جديد
                    @elseif($request->status->value == 'under_review') قيد المراجعة
                    @elseif($request->status->value == 'approved') مقبول
                    @elseif($request->status->value == 'rejected') مرفوض
                    @endif
                </span>
            </span>
        </div>
        @if($request->rejectionReasons->isNotEmpty())
        <div class="info-row">
            <span class="info-label">أسباب الرفض:</span>
            <span class="info-value">
                {{ $request->rejectionReasons->pluck('reason')->implode('، ') }}
            </span>
        </div>
        @endif
    </div>

    <div class="section">
        <div class="section-title">معلومات الشركة</div>
        <div class="info-row">
            <span class="info-label">اسم الشركة:</span>
            <span class="info-value">{{ $company->name }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">المدينة:</span>
            <span class="info-value">{{ $company->city?->name ?? 'غير محدد' }}</span>
        </div>
        @if($company->is_agent)
        <div class="info-row">
            <span class="info-label">وكيل معتمد:</span>
            <span class="info-value">نعم</span>
        </div>
        @endif
        <div class="info-row">
            <span class="info-label">رقم السجل التجاري:</span>
            <span class="info-value">{{ $company->commercial_register_number ?? 'غير متوفر' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">البريد الإلكتروني:</span>
            <span class="info-value">{{ $company->email }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">رقم الهاتف:</span>
            <span class="info-value">{{ $company->phone }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">العنوان:</span>
            <span class="info-value">{{ $company->address }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">حالة التأهيل:</span>
            <span class="info-value">{{ $company->is_qualified ? 'مؤهلة' : 'غير مؤهلة' }}</span>
        </div>
        @if($company->qualification_expiry_date)
        <div class="info-row">
            <span class="info-label">تاريخ انتهاء التأهيل:</span>
            <span class="info-value">{{ $company->qualification_expiry_date->format('Y-m-d') }}</span>
        </div>
        @endif
    </div>

    @if($request->legalDocuments->count() > 0)
    <div class="section">
        <div class="section-title">الوثائق القانونية</div>
        <ul class="documents-list">
            @foreach($request->legalDocuments as $doc)
            <li>
                <strong>{{ $doc->document_type->value }}:</strong> {{ $doc->file_name }} 
                ({{ number_format($doc->file_size / 1024, 2) }} KB)
            </li>
            @endforeach
        </ul>
    </div>
    @endif

    @if($request->technicalDocuments->count() > 0)
    <div class="section">
        <div class="section-title">الوثائق الفنية</div>
        <ul class="documents-list">
            @foreach($request->technicalDocuments as $doc)
            <li>
                <strong>{{ $doc->document_type->value }}:</strong> {{ $doc->file_name }}
                (مستوى الخبرة: {{ $doc->experience_level->value }})
                ({{ number_format($doc->file_size / 1024, 2) }} KB)
            </li>
            @endforeach
        </ul>
    </div>
    @endif

    @if($request->financialDocuments->count() > 0)
    <div class="section">
        <div class="section-title">الوثائق المالية</div>
        <ul class="documents-list">
            @foreach($request->financialDocuments as $doc)
            <li>
                <strong>{{ $doc->document_type->value }}:</strong> {{ $doc->file_name }}
                ({{ number_format($doc->file_size / 1024, 2) }} KB)
            </li>
            @endforeach
        </ul>
    </div>
    @endif

    @if($request->requestActions->count() > 0)
    <div class="section">
        <div class="section-title">سجل الإجراءات</div>
        <table>
            <thead>
                <tr>
                    <th>التاريخ</th>
                    <th>المستخدم</th>
                    <th>نوع الإجراء</th>
                    <th>ملاحظات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($request->requestActions as $action)
                <tr>
                    <td>{{ $action->created_at->format('Y-m-d H:i') }}</td>
                    <td>{{ $action->user->name }}</td>
                    <td>
                        @if($action->action_type == \App\Enums\RequestActionType::InitialApproval) قبول مبدئي
                        @elseif($action->action_type == \App\Enums\RequestActionType::InitialRejection) رفض مبدئي
                        @elseif($action->action_type == \App\Enums\RequestActionType::FinalApproval) قبول نهائي
                        @elseif($action->action_type == \App\Enums\RequestActionType::FinalRejection) رفض نهائي
                        @endif
                    </td>
                    <td>{{ $action->notes ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <div class="footer">
        <p>تم إنشاء هذا التقرير تلقائياً من نظام تأهيل أدوات التنفيذ المحلية</p>
        <p>تاريخ الطباعة: {{ now()->format('Y-m-d H:i:s') }}</p>
        <p>حقوق النشر محفوظة © 2025 المؤسسة الوطنية للنفط</p>
    </div>
</body>
</html>

