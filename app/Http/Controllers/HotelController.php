<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreHotelRequest;
use App\Http\Resources\HotelResource;
use App\Models\Hotel;
use App\Services\HotelService;

class HotelController extends Controller
{
    protected $hotelService;

    public function __construct(HotelService $hotelService)
    {
        $this->hotelService = $hotelService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return HotelResource::collection(Hotel::with('images')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreHotelRequest $request)
    {
        $hotel = $this->hotelService->createWithImages(
            $request->only(['name', 'description', 'address', 'phone', 'whatsapp', 'latitude', 'longitude']),
            $request->file('images', [])
        );

        return new HotelResource($hotel);
    }

    /**
     * Display the specified resource.
     */
    public function show(Hotel $hotel)
    {
        return new HotelResource($hotel->load('images'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreHotelRequest $request, Hotel $hotel)
    {
        $hotel = $this->hotelService->updateWithImages(
            $hotel,
            $request->only(['name', 'description', 'address', 'phone', 'whatsapp', 'latitude', 'longitude']),
            $request->file('images', [])
        );

        return new HotelResource($hotel);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Hotel $hotel)
    {
        $this->hotelService->deleteHotel($hotel);
        return response()->json(null, 204);
    }
}
