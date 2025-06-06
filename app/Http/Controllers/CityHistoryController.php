<?php

namespace App\Http\Controllers;

use App\Http\Resources\CityHistoryResource;
use App\Models\CityHistory;
use App\Services\CityHistoryService;
use Illuminate\Http\Request;

class CityHistoryController extends Controller
{
    protected $cityHistoryService;

    public function __construct(CityHistoryService $cityHistoryService)
    {
        $this->cityHistoryService = $cityHistoryService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return CityHistoryResource::collection(CityHistory::with('images')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $city_history = $this->cityHistoryService->createWithImages(
            $request->only(['title', 'content']),
            $request->file('images', [])
        );

        return new CityHistoryResource($city_history);
    }

    /**
     * Display the specified resource.
     */
    public function show(CityHistory $cityHistory)
    {
        return new CityHistoryResource($cityHistory->load('images'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CityHistoryResource $request, CityHistory $cityHistory)
    {
        $city_history = $this->cityHistoryService->updateWithImages(
            $cityHistory,
            $request->only(['title', 'content']),
            $request->file('images', [])
        );

        return new CityHistoryResource($city_history);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CityHistory $cityHistory)
    {
        $this->cityHistoryService->deleteCityHistory($cityHistory);
        return response()->json(null, 204);
    }
}
