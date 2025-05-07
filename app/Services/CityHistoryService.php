<?php
namespace App\Services;

use App\Models\CityHistory;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CityHistoryService
{
    public function createWithImages(array $data, ?array $images = []): CityHistory
    {
        try {
            $city_history = CityHistory::create($data);

            if ($images) {
                foreach ($images as $image) {
                    $path = $image->store('city_histories', 'public');
                    $city_history->images()->create(['image_path' => $path]);
                }
            }

            return $city_history->load('images');
        } catch (Exception $e) {
            Log::error('Erro ao criar cidade', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $data,
            ]);
            throw new Exception('Erro ao criar cidade. Tente novamente mais tarde.');
        }
    }

    public function updateWithImages(CityHistory $city_history, array $data, ?array $images = []): CityHistory
    {
        try {
            $city_history->update($data);

            if ($images) {
                foreach ($images as $image) {
                    $path = $image->store('city_histories', 'public');
                    $city_history->images()->create(['image_path' => $path]);
                }
            }

            return $city_history->load('images');
        } catch (Exception $e) {
            Log::error('Erro ao atualizar cidade', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $data,
                'city_history_id' => $city_history->id,
            ]);
            throw new Exception('Erro ao atualizar cidade. Tente novamente mais tarde.');
        }
    }

    public function deleteCityHistory(CityHistory $city_history): void
    {
        try {
            foreach ($city_history->images as $image) {
                Storage::disk('public')->delete($image->image_path);
            }

            $city_history->delete();
        } catch (Exception $e) {
            Log::error('Erro ao deletar cidade', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'city_history_id' => $city_history->id,
            ]);
            throw new Exception('Erro ao deletar cidade. Tente novamente mais tarde.');
        }
    }
}
