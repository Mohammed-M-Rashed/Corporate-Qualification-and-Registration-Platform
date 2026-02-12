<?php

namespace App\Enums;

enum TechnicalDocumentType: string
{
    case CompletedProjects = 'completed_projects';
    case TechnicalStaff = 'technical_staff';
    case QualityCertificates = 'quality_certificates';
    
    public function label(): string
    {
        return match($this) {
            self::CompletedProjects => 'المشاريع المنجزة',
            self::TechnicalStaff => 'الكادر الفني',
            self::QualityCertificates => 'شهادات الجودة',
        };
    }
}

