<?php

namespace Database\Seeders;

use App\Models\Committee;
use App\Models\CommitteeMember;
use App\Models\CommitteeType;
use App\Models\User;
use App\Enums\MemberType;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CommitteeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $qualificationType = CommitteeType::where('name', 'Qualification')->first();
        
        if (!$qualificationType) {
            $this->command->warn('نوع اللجنة "Qualification" غير موجود. يرجى تشغيل CommitteeTypeSeeder أولاً.');
            return;
        }

        // Create Qualification Committee
        $committee = Committee::firstOrCreate(
            [
                'committee_type_id' => $qualificationType->id,
                'name' => 'لجنة التأهيل الرئيسية',
            ],
            [
                'start_date' => Carbon::now()->subMonths(6),
                'end_date' => Carbon::now()->addYear(),
                'is_active' => true,
            ]
        );

        // Get chairman
        $chairman = User::where('email', 'chairman@example.com')->first();
        if (!$chairman) {
            $this->command->warn('رئيس اللجنة غير موجود. يرجى تشغيل UserSeeder أولاً.');
        } else {
            // Assign chairman to committee
            if (!$committee->chairman_id || $committee->chairman_id !== $chairman->id) {
                $committee->update(['chairman_id' => $chairman->id]);
                $this->command->info('تم تعيين رئيس اللجنة: ' . $chairman->name);
            }
        }

        // Get committee members (member_type now in users table)
        $memberEmails = [
            'legal@example.com',
            'technical@example.com',
            'financial@example.com',
        ];

        foreach ($memberEmails as $email) {
            $member = User::where('email', $email)->first();
            if (!$member) {
                $this->command->warn("العضو غير موجود: {$email}");
                continue;
            }

            // Create or update committee member (no member_type column needed)
            CommitteeMember::firstOrCreate(
                [
                    'committee_id' => $committee->id,
                    'user_id' => $member->id,
                ]
            );
            
            $memberTypeLabel = $member->member_type ? $member->member_type->label() : 'غير محدد';
            $this->command->info("تم ربط {$member->name} ({$memberTypeLabel}) بلجنة التأهيل");
        }

        // Verify committee setup
        $this->command->info('=== معلومات لجنة التأهيل ===');
        $this->command->info('اسم اللجنة: ' . $committee->name);
        $this->command->info('رئيس اللجنة: ' . ($committee->chairman ? $committee->chairman->name . ' (' . $committee->chairman->email . ')' : 'غير معين'));
        $this->command->info('عدد الأعضاء: ' . $committee->committeeMembers()->count());
        $this->command->info('الحالة: ' . ($committee->is_active ? 'نشطة' : 'غير نشطة'));
        
        // عرض تفاصيل الأعضاء
        $this->command->info('=== تفاصيل الأعضاء ===');
        foreach ($committee->committeeMembers as $committeeMember) {
            $memberTypeLabel = $committeeMember->member_type ? $committeeMember->member_type->label() : 'غير محدد';
            $this->command->info("- {$committeeMember->user->name} ({$memberTypeLabel})");
        }
    }
}

