<?php

namespace App\Exceptions;

use App\Models\ErrorLog;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Helper extends Handler
{
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ValidationException) {
            return response()->json([
                'message' => 'Erro de validação.',
                'errors' => $exception->errors()
            ], 422);
        }

        if ($exception instanceof ModelNotFoundException) {
            return response()->json([
                'message' => 'Recurso não encontrado.',
            ], 404);
        }

        if ($exception instanceof HttpException) {
            return response()->json([
                'message' => $exception->getMessage() ?: 'Erro HTTP',
            ], $exception->getStatusCode());
        }

        Log::error('[Erro não tratado]', [
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString(),
            'url' => request()->fullUrl(),
            'input' => request()->all(),
        ]);

        ErrorLog::create([
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString(),
            'url' => request()->fullUrl(),
            'input' => json_encode(request()->all()),
        ]);

        return response()->json([
            'message' => 'Ocorreu um erro inesperado. Nossa equipe já foi notificada.',
        ], 500);
    }
}