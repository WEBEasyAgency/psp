<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class UploadController extends Controller
{
    /**
     * Загрузка файла на сервер
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            // Валидация загруженного файла
            $validated = $request->validate([
                'file' => [
                    'required',
                    'file',
                    'max:51200', // 50MB в килобайтах
                    'mimes:pdf,doc,docx,jpg,jpeg,png,svg,zip,rar,ai,psd,cdr'
                ]
            ], [
                'file.required' => 'Файл не выбран',
                'file.max' => 'Файл слишком большой. Максимальный размер: 50 МБ',
                'file.mimes' => 'Недопустимый тип файла. Разрешены: PDF, DOC, DOCX, JPG, PNG, SVG, ZIP, RAR, AI, PSD, CDR'
            ]);

            $file = $request->file('file');

            // Генерация уникального имени файла
            $extension = $file->getClientOriginalExtension();
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

            // Безопасное имя: дата + время + uniqid + расширение
            $filename = date('Ymd_His') . '_' . uniqid() . '.' . $extension;

            // Сохранение файла в storage/app/public/uploads/
            $path = $file->storeAs('uploads', $filename, 'public');

            // Получение публичного URL
            $url = Storage::url($path);

            // Логирование успешной загрузки
            Log::info('File uploaded successfully', [
                'original_name' => $file->getClientOriginalName(),
                'saved_name' => $filename,
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'url' => $url
            ]);

            return response()->json([
                'success' => true,
                'url' => $url,
                'filename' => $filename,
                'original_name' => $file->getClientOriginalName(),
                'size' => $file->getSize()
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Ошибка валидации
            return response()->json([
                'success' => false,
                'message' => 'Ошибка валидации файла',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            // Общая ошибка
            Log::error('File upload failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Ошибка при загрузке файла: ' . $e->getMessage()
            ], 500);
        }
    }
}
