<?php

namespace App\Enums;

enum LegalDocumentType: string
{
    case EstablishmentContract = 'establishment_contract';
    case CommercialRegisterExtract = 'commercial_register_extract';
    case ActivityLicense = 'activity_license';
    case ChamberRegistration = 'chamber_registration';
    case TaxCertificate = 'tax_certificate';
    case SocialSecurityCertificate = 'social_security_certificate';
    
    public function label(): string
    {
        return match($this) {
            self::EstablishmentContract => 'عقد التأسيس',
            self::CommercialRegisterExtract => 'مستخرج السجل التجاري',
            self::ActivityLicense => 'رخصة النشاط',
            self::ChamberRegistration => 'سجل الغرفة التجارية',
            self::TaxCertificate => 'شهادة الضرائب',
            self::SocialSecurityCertificate => 'شهادة الضمان الاجتماعي',
        };
    }
}

