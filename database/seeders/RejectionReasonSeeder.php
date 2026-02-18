<?php

namespace Database\Seeders;

use App\Models\RejectionReason;
use Illuminate\Database\Seeder;

class RejectionReasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reasons = [
            ['reason' => 'وثائق قانونية ناقصة أو غير مكتملة', 'is_active' => true, 'order' => 1],
            ['reason' => 'انتهاء صلاحية السجل التجاري', 'is_active' => true, 'order' => 2],
            ['reason' => 'عدم مطابقة عقد التأسيس للمتطلبات', 'is_active' => true, 'order' => 3],
            ['reason' => 'رخصة النشاط غير صالحة أو منتهية', 'is_active' => true, 'order' => 4],
            ['reason' => 'شهادة الغرفة التجارية غير محدثة', 'is_active' => true, 'order' => 5],
            ['reason' => 'الشهادة الضريبية غير مرفقة أو منتهية', 'is_active' => true, 'order' => 6],
            ['reason' => 'شهادة الضمان الاجتماعي غير مكتملة', 'is_active' => true, 'order' => 7],
            ['reason' => 'الكادر الفني غير مؤهل أو غير كافٍ', 'is_active' => true, 'order' => 8],
            ['reason' => 'عدم وجود خبرة كافية في المجال المطلوب', 'is_active' => true, 'order' => 9],
            ['reason' => 'ملف المشاريع المنفذة غير مكتمل', 'is_active' => true, 'order' => 10],
            ['reason' => 'شهادات الجودة والاعتماد غير مرفقة', 'is_active' => true, 'order' => 11],
            ['reason' => 'البيانات المالية غير كافية أو غير موثقة', 'is_active' => true, 'order' => 12],
            ['reason' => 'شهادة الملاءة المالية منتهية أو غير مقبولة', 'is_active' => true, 'order' => 13],
            ['reason' => 'عدم استيفاء الحد الأدنى للمتطلبات المالية', 'is_active' => true, 'order' => 14],
            ['reason' => 'معلومات أو وثائق غير صحيحة أو مزورة', 'is_active' => true, 'order' => 15],
            ['reason' => 'النشاط المسجل لا يطابق نطاق التأهيل المطلوب', 'is_active' => true, 'order' => 16],
            ['reason' => 'عدم الالتزام بالمواعيد المحددة لتقديم المستندات', 'is_active' => true, 'order' => 17],
            ['reason' => 'أسباب أخرى (يُحدد في الملاحظات)', 'is_active' => true, 'order' => 18],
        ];

        foreach ($reasons as $item) {
            RejectionReason::firstOrCreate(
                ['reason' => $item['reason']],
                [
                    'is_active' => $item['is_active'],
                    'order' => $item['order'],
                ]
            );
        }
    }
}
