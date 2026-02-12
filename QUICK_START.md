# دليل البدء السريع

## 🚀 خطوات سريعة للتشغيل

### 1. تثبيت Dependencies
```bash
composer install
npm install
```

### 2. إعداد البيئة
```bash
cp .env.example .env
php artisan key:generate
```

### 3. إعداد قاعدة البيانات في `.env`
```env
DB_DATABASE=ltt_qualification
DB_USERNAME=root
DB_PASSWORD=
```

### 4. تشغيل Migrations و Seeders
```bash
php artisan migrate
php artisan db:seed
```

### 5. إنشاء Storage Link
```bash
php artisan storage:link
```

### 6. تجميع Assets
```bash
npm run build
```

### 7. تشغيل الخادم
```bash
php artisan serve
```

## 🔑 تسجيل الدخول

- **URL**: http://localhost:8000/admin
- **Email**: `superadmin@example.com`
- **Password**: `password`

⚠️ **غير كلمة المرور فوراً!**

## 📋 الخطوات التالية

1. **إعدادات SMTP**: `/admin/system-settings`
2. **إنشاء اللجان**: `/admin/committees`
3. **إضافة المستخدمين**: `/admin/users`
4. **إضافة FAQs**: `/admin/faqs`

## ✅ التحقق من التثبيت

- ✅ Landing Page: http://localhost:8000
- ✅ تسجيل شركة: http://localhost:8000/register
- ✅ لوحة التحكم: http://localhost:8000/admin
- ✅ Dashboard: http://localhost:8000/admin/dashboard

## 🎨 المميزات الجاهزة

- ✅ Landing Page بتصميم أزرق حديث
- ✅ Stepper Form لتسجيل الشركات
- ✅ نظام Workflow كامل (عضو لجنة → رئيس لجنة)
- ✅ نظام البريد الإلكتروني
- ✅ طباعة PDF للتقارير
- ✅ إدارة كاملة من Filament Admin Panel

## 📞 الدعم

للمزيد من التفاصيل، راجع:
- `README.md` - الوثائق الكاملة
- `INSTALLATION.md` - دليل التثبيت المفصل

