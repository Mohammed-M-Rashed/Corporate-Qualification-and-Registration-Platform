<?php

namespace App\Services;

use App\Models\Committee;
use App\Models\CommitteeType;
use Illuminate\Support\Facades\DB;

class CommitteeService
{
    public function createCommittee(array $data): Committee
    {
        return DB::transaction(function () use ($data) {
            // If activating this committee, deactivate others of the same type
            if (isset($data['is_active']) && $data['is_active']) {
                Committee::where('committee_type_id', $data['committee_type_id'])
                    ->where('is_active', true)
                    ->update(['is_active' => false]);
            }

            return Committee::create($data);
        });
    }

    public function updateCommittee(Committee $committee, array $data): Committee
    {
        return DB::transaction(function () use ($committee, $data) {
            // If activating this committee, deactivate others of the same type
            if (isset($data['is_active']) && $data['is_active'] && !$committee->is_active) {
                Committee::where('committee_type_id', $committee->committee_type_id)
                    ->where('id', '!=', $committee->id)
                    ->where('is_active', true)
                    ->update(['is_active' => false]);
            }

            $committee->update($data);

            return $committee->fresh();
        });
    }

    public function activateCommittee(Committee $committee): void
    {
        DB::transaction(function () use ($committee) {
            // Deactivate other committees of the same type
            Committee::where('committee_type_id', $committee->committee_type_id)
                ->where('id', '!=', $committee->id)
                ->where('is_active', true)
                ->update(['is_active' => false]);

            $committee->update(['is_active' => true]);
        });
    }

    public function deactivateCommittee(Committee $committee): void
    {
        $committee->update(['is_active' => false]);
    }
}
