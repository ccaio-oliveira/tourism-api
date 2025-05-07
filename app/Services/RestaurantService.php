<?php
namespace App\Services;

use App\Models\Restaurant;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class RestaurantService
{
    public function createWithImages(array $data, ?array $images = []): Restaurant
    {
        try {
            $restaurant = Restaurant::create($data);

            if ($images) {
                foreach ($images as $image) {
                    $path = $image->store('restaurants', 'public');
                    $restaurant->images()->create(['image_path' => $path]);
                }
            }

            return $restaurant->load('images');
        } catch (Exception $e) {
            Log::error('Erro ao criar restaurante', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $data,
            ]);
            throw new Exception('Erro ao criar restaurante. Tente novamente mais tarde.');
        }
    }

    public function updateWithImages(Restaurant $restaurant, array $data, ?array $images = []): Restaurant
    {
        try {
            $restaurant->update($data);

            if ($images) {
                foreach ($images as $image) {
                    $path = $image->store('restaurants', 'public');
                    $restaurant->images()->create(['image_path' => $path]);
                }
            }

            return $restaurant->load('images');
        } catch (Exception $e) {
            Log::error('Erro ao atualizar restaurante', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $data,
                'restaurant_id' => $restaurant->id,
            ]);
            throw new Exception('Erro ao atualizar restaurante. Tente novamente mais tarde.');
        }
    }

    public function deleteRestaurant(Restaurant $restaurant): void
    {
        try {
            foreach ($restaurant->images as $image) {
                Storage::disk('public')->delete($image->image_path);
            }

            $restaurant->delete();
        } catch (Exception $e) {
            Log::error('Erro ao deletar restaurante', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'restaurant_id' => $restaurant->id,
            ]);
            throw new Exception('Erro ao deletar restaurante. Tente novamente mais tarde.');
        }
    }
}
