<?php

namespace Database\Seeders;

use App\Enums\MemberType;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('name', 'Admin')->first();
        $committeeMemberRole = Role::where('name', 'Committee Member')->first();
        $chairmanRole = Role::where('name', 'Chairman')->first();
        
        // Create Admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'مدير النظام',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        if (!$admin->hasRole('Admin')) {
            $admin->assignRole($adminRole);
        }

        // Create Committee Members (will be linked to committee in CommitteeSeeder)
        $members = [
            [
                'name' => 'رئيس اللجنة',
                'email' => 'chairman@example.com',
                'is_chairman' => true,
                'member_type' => null,
            ],
            [
                'name' => 'العضو القانوني',
                'email' => 'legal@example.com',
                'is_chairman' => false,
                'member_type' => MemberType::Legal,
            ],
            [
                'name' => 'العضو الفني',
                'email' => 'technical@example.com',
                'is_chairman' => false,
                'member_type' => MemberType::Technical,
            ],
            [
                'name' => 'العضو المالي',
                'email' => 'financial@example.com',
                'is_chairman' => false,
                'member_type' => MemberType::Financial,
            ],
        ];

        foreach ($members as $memberData) {
            $isChairman = $memberData['is_chairman'] ?? false;
            $memberType = $memberData['member_type'] ?? null;
            unset($memberData['is_chairman'], $memberData['member_type']);
            
            $member = User::firstOrCreate(
                ['email' => $memberData['email']],
                [
                    'name' => $memberData['name'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                    'member_type' => $memberType,
                ]
            );
            
            // تحديث member_type إذا كان المستخدم موجود
            if ($memberType && !$member->member_type) {
                $member->update(['member_type' => $memberType]);
            }
            
            if ($isChairman) {
                // رئيس اللجنة يحصل على دور Chairman
                if (!$member->hasRole('Chairman')) {
                    $member->assignRole($chairmanRole);
                }
            } else {
                // عضو اللجنة يحصل على دور Committee Member
                if (!$member->hasRole('Committee Member')) {
                    $member->assignRole($committeeMemberRole);
                }
            }
        }
    }
}

