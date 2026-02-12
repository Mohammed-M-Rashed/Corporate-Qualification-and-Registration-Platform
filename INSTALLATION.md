# دليل التثبيت الكامل

## الخطوة 1: إعداد المشروع

```bash
# نسخ المشروع
cd ltt

# تثبيت Dependencies
composer install
npm install
```

## الخطوة 2: إعداد قاعدة البيانات

1. أنشئ قاعدة بيانات MySQL جديدة
2. عدّل ملف `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ltt_qualification
DB_USERNAME=root
DB_PASSWORD=
```

## الخطوة 3: تشغيل Migrations

```bash
php artisan migrate
```

## الخطوة 4: تشغيل Seeders

```bash
php artisan db:seed --class=RolePermissionSeeder
```

هذا سينشئ:
- الأدوار: Super Admin, Admin, Committee Member
- مستخدم Super Admin افتراضي:
  - Email: `superadmin@example.com`
  - Password: `password`

## الخطوة 5: إنشاء Storage Link

```bash
php artisan storage:link
```

## الخطوة 6: تجميع Assets

```bash
clea
```

## الخطوة 7: تشغيل الخادم

```bash
php artisan serve
```

## الوصول للنظام

- **الواجهة العامة**: http://localhost:8000
- **لوحة التحكم**: http://localhost:8000/admin
- **تسجيل الدخول**: 
  - Email: `superadmin@example.com`
  - Password: `password`

## الخطوات التالية

1. **تغيير كلمة المرور**: بعد تسجيل الدخول، غيّر كلمة المرور فوراً
2. **إعدادات SMTP**: اذهب إلى `/admin/system-settings` وأدخل إعدادات البريد
3. **رفع الشعار**: ارفع شعار المؤسسة من إعدادات النظام
4. **إنشاء اللجان**: أنشئ اللجان المطلوبة من `/admin/committees`
5. **إضافة المستخدمين**: أضف المستخدمين وعيّنهم للجان

## إنشاء Committee Types

يمكنك إنشاء أنواع اللجان من لوحة التحكم أو من Tinker:

```bash
php artisan tinker
```

```php
\App\Models\CommitteeType::create([
    'name' => 'Qualification',
    'name_ar' => 'لجنة التأهيل',
    'description' => 'لجنة تقييم وتأهيل الشركات',
    'is_active' => true,
]);
```

## إضافة FAQs

من لوحة التحكم: `/admin/faqs`

أو من Tinker:

```php
\App\Models\Faq::create([
    'question' => 'كيف يمكنني التسجيل؟',
    'answer' => 'يمكنك التسجيل من خلال النقر على زر "تسجيل شركة" في الصفحة الرئيسية',
    'order' => 1,
    'is_active' => true,
]);
```

## ملاحظات الأمان

1. **تغيير APP_KEY**: تأكد من أن `APP_KEY` في `.env` فريد
2. **تغيير كلمة المرور الافتراضية**: غيّر كلمة المرور فوراً
3. **إعدادات SMTP**: استخدم SMTP آمن (TLS/SSL)
4. **صلاحيات الملفات**: تأكد من الصلاحيات الصحيحة:
   ```bash
   chmod -R 775 storage
   chmod -R 775 bootstrap/cache
   ```

## استكشاف الأخطاء

### خطأ في الصلاحيات
```bash
php artisan permission:cache-reset
php artisan cache:clear
```

### خطأ في Storage
```bash
php artisan storage:link
sudo chown -R www-data:www-data storage
sudo chown -R www-data:www-data bootstrap/cache
```

### خطأ في Migrations
```bash
php artisan migrate:fresh
php artisan db:seed --class=RolePermissionSeeder
```

