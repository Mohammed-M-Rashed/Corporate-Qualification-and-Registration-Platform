<?php

namespace App\Enums;

enum FinancialDocumentType: string
{
    case FinancialStatements = 'financial_statements';
    case SolvencyCertificate = 'solvency_certificate';
    
    public function label(): string
    {
        return match($this) {
            self::FinancialStatements => 'القوائم المالية',
            self::SolvencyCertificate => 'شهادة الملاءة المالية',
        };
    }
}

