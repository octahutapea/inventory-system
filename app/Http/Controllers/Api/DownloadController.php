<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class DownloadController extends Controller
{
    public function index($filename)
    {
        $documentPath = storage_path('app/public/template_document/' . $filename);

        if (!Storage::exists('public/template_document/' . $filename)) {
            abort(404);
        }

        // Menggunakan response dengan header content-type yang sesuai
        return response()->file($documentPath, ['Content-Type' => mime_content_type($documentPath)]);
    }
}
