<?php

namespace App\Services;

use App\Models\Position;
use Illuminate\Support\Facades\DB;

class PositionService
{
    public function create(array $data): position
    {
        return DB::transaction(function () use ($data) {
            return Position::create($data);
        });
    }

    public function update(Position $position, array $data): position
    {
        return DB::transaction(function () use ($position, $data) {
            $position->update($data);
            return $position;
        });
    }


    public function delete(Position $position)
    {
        return DB::transaction(function () use ($position) {
            return $position->delete();
        });
    }
}
