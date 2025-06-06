<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRestaurantRequest;
use App\Http\Resources\RestaurantResource;
use App\Models\Restaurant;
use App\Services\RestaurantService;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    protected $restaurantService;

    public function __construct(RestaurantService $restaurantService)
    {
        $this->restaurantService = $restaurantService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return RestaurantResource::collection(Restaurant::with('images')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRestaurantRequest $request)
    {
        $restaurant = $this->restaurantService->createWithImages(
            $request->only(['name', 'description', 'address', 'phone', 'whatsapp', 'latitude', 'longitude']),
            $request->file('images', [])
        );

        return new RestaurantResource($restaurant);
    }

    /**
     * Display the specified resource.
     */
    public function show(Restaurant $restaurant)
    {
        return new RestaurantResource($restaurant->load('images'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreRestaurantRequest $request, Restaurant $restaurant)
    {
        $restaurant = $this->restaurantService->updateWithImages(
            $restaurant,
            $request->only(['name', 'description', 'address', 'phone', 'whatsapp', 'latitude', 'longitude']),
            $request->file('images', [])
        );

        return new RestaurantResource($restaurant);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Restaurant $restaurant)
    {
        $this->restaurantService->deleteRestaurant($restaurant);
        return response()->json(null, 204);
    }
}
