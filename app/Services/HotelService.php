<?php
namespace App\Services;

use App\Models\Hotel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class HotelService
{
    public function createWithImages(array $data, ?array $images = []): Hotel
    {
        try {
            $hotel = Hotel::create($data);

            if ($images) {
                foreach ($images as $image) {
                    $path = $image->store('hotels', 'public');
                    $hotel->images()->create(['image_path' => $path]);
                }
            }

            return $hotel->load('images');
        } catch (Exception $e) {
            Log::error('Erro ao criar hotel', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $data,
            ]);
            throw new Exception('Erro ao criar hotel. Tente novamente mais tarde.');
        }
    }

    public function updateWithImages(Hotel $hotel, array $data, ?array $images = []): Hotel
    {
        try {
            $hotel->update($data);

            if ($images) {
                foreach ($images as $image) {
                    $path = $image->store('hotels', 'public');
                    $hotel->images()->create(['image_path' => $path]);
                }
            }

            return $hotel->load('images');
        } catch (Exception $e) {
            Log::error('Erro ao atualizar hotel', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $data,
                'hotel_id' => $hotel->id,
            ]);
            throw new Exception('Erro ao atualizar hotel. Tente novamente mais tarde.');
        }
    }

    public function deleteHotel(Hotel $hotel): void
    {
        try {
            foreach ($hotel->images as $image) {
                Storage::disk('public')->delete($image->image_path);
            }

            $hotel->delete();
        } catch (Exception $e) {
            Log::error('Erro ao deletar hotel', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'hotel_id' => $hotel->id,
            ]);
            throw new Exception('Erro ao deletar hotel. Tente novamente mais tarde.');
        }
    }
}
