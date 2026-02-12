<?php

namespace App\Filament\Widgets;

use App\Models\Company;
use App\Models\QualificationRequest;
use App\Models\Committee;
use App\Enums\QualificationRequestStatus;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('إجمالي الشركات', Company::count())
                ->description('عدد الشركات المسجلة')
                ->descriptionIcon('heroicon-m-building-office')
                ->color('primary'),
            Stat::make('الطلبات الجديدة', QualificationRequest::where('status', QualificationRequestStatus::New)->count())
                ->description('طلبات جديدة')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('warning'),
            Stat::make('الطلبات قيد المراجعة', QualificationRequest::where('status', QualificationRequestStatus::UnderReview)->count())
                ->description('طلبات قيد المراجعة')
                ->descriptionIcon('heroicon-m-clock')
                ->color('info'),
            Stat::make('الطلبات المقبولة', QualificationRequest::where('status', QualificationRequestStatus::Approved)->count())
                ->description('طلبات مقبولة')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
            Stat::make('الطلبات المرفوضة', QualificationRequest::where('status', QualificationRequestStatus::Rejected)->count())
                ->description('طلبات مرفوضة')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger'),
            Stat::make('اللجان النشطة', Committee::where('is_active', true)->count())
                ->description('لجان مفعّلة')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info'),
        ];
    }
}
