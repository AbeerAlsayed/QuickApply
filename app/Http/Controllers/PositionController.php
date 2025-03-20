<?php

namespace App\Http\Controllers;

use App\Enums\PositionEnum;
use App\Http\Requests\PositionRequest;
use App\Http\Resources\PositionEnumResource;
use App\Http\Resources\PositionResource;
use App\Models\Position;
use App\Services\PositionService;
use Illuminate\Http\Request;

class PositionController extends BaseController
{
    private $positionService;

    public function __construct(PositionService $positionService)
    {
        $this->positionService = $positionService;
    }

    public function index()
    {
        $positions = Position::all();
        return $this->sendSuccess(PositionResource::collection($positions), 'Positions retrieved successfully');
    }

    public function getPositionsFromEnum()
    {
        $positions = PositionEnum::cases();

        $data = array_map(fn($position) => $position->value, $positions);

        return $this->sendSuccess($data, 'Positions from Enum retrieved successfully');
    }


    public function store(PositionRequest $request)
    {
        $position = $this->positionService->create($request->validated());
        return $this->sendSuccess(new PositionResource($position), 'Position created successfully');
    }

    public function update(PositionRequest $request, Position $position)
    {
        $updatedPosition = $this->positionService->update($position, $request->validated());
        return $this->sendSuccess(new PositionResource($updatedPosition), 'Position updated successfully');
    }

    public function destroy($id)
    {
        try {
            $position = Position::find($id);

            if (!$position) {
                throw new \App\Exceptions\ModelNotFoundException('Position', 'ID not found');
            }

            $this->positionService->delete($position);

            return $this->sendSuccess([], 'Position deleted successfully');
        } catch (\App\Exceptions\ModelNotFoundException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete position: ' . $e->getMessage(),
            ], 500);
        }
    }
}
