<?php
namespace App\Services;

use App\Models\TouristAttraction;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TouristAttractionService
{
    public function createWithImages(array $data, ?array $images = []): TouristAttraction
    {
        try {
            $tourist_attraction = TouristAttraction::create($data);

            if ($images) {
                foreach ($images as $image) {
                    $path = $image->store('tourist_attractions', 'public');
                    $tourist_attraction->images()->create(['image_path' => $path]);
                }
            }

            return $tourist_attraction->load('images');
        } catch (Exception $e) {
            Log::error('Erro ao criar atração turística', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $data,
            ]);
            throw new Exception('Erro ao criar atração turística. Tente novamente mais tarde.');
        }
    }

    public function updateWithImages(TouristAttraction $tourist_attraction, array $data, ?array $images = []): TouristAttraction
    {
        try {
            $tourist_attraction->update($data);

            if ($images) {
                foreach ($images as $image) {
                    $path = $image->store('tourist_attractions', 'public');
                    $tourist_attraction->images()->create(['image_path' => $path]);
                }
            }

            return $tourist_attraction->load('images');
        } catch (Exception $e) {
            Log::error('Erro ao atualizar atração turística', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $data,
                'tourist_attraction_id' => $tourist_attraction->id,
            ]);
            throw new Exception('Erro ao atualizar atração turística. Tente novamente mais tarde.');
        }
    }

    public function deleteTouristAttraction(TouristAttraction $tourist_attraction): void
    {
        try {
            foreach ($tourist_attraction->images as $image) {
                Storage::disk('public')->delete($image->image_path);
            }

            $tourist_attraction->delete();
        } catch (Exception $e) {
            Log::error('Erro ao deletar atração turística', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'tourist_attraction_id' => $tourist_attraction->id,
            ]);
            throw new Exception('Erro ao deletar atração turística. Tente novamente mais tarde.');
        }
    }
}
