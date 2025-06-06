<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTouristAttractionRequest;
use App\Http\Resources\TouristAttractionResource;
use App\Models\TouristAttraction;
use App\Services\TouristAttractionService;
use Illuminate\Http\Request;

class TouristAttractionController extends Controller
{
    protected $touristAttractionService;

    public function __construct(TouristAttractionService $touristAttractionService)
    {
        $this->touristAttractionService = $touristAttractionService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return TouristAttractionResource::collection(TouristAttraction::with('images')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTouristAttractionRequest $request)
    {
        $tourist_attraction = $this->touristAttractionService->createWithImages(
            $request->only(['name', 'description', 'address', 'phone', 'whatsapp', 'latitude', 'longitude']),
            $request->file('images', [])
        );

        return new TouristAttractionResource($tourist_attraction);
    }

    /**
     * Display the specified resource.
     */
    public function show(TouristAttraction $touristAttraction)
    {
        return new TouristAttractionResource($touristAttraction->load('images'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreTouristAttractionRequest $request, TouristAttraction $touristAttraction)
    {
        $tourist_attraction = $this->touristAttractionService->updateWithImages(
            $touristAttraction,
            $request->only(['name', 'description', 'address', 'phone', 'whatsapp', 'latitude', 'longitude']),
            $request->file('images', [])
        );

        return new TouristAttractionResource($tourist_attraction);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TouristAttraction $touristAttraction)
    {
        $this->touristAttractionService->deleteTouristAttraction($touristAttraction);
        return response()->json(null, 204);
    }
}
