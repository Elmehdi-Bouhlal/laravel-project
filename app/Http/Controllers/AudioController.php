<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AudioController extends Controller
{
    public function transcribe(Request $request)
    {
        // Valide le fichier audio
        // $request->validate([
        //     'audio' => 'required|file|mimetypes:audio/wav,audio/x-wav,audio/mpeg,audio/mp3,audio/m4a|max:10240'
        // ]);

        // Récupère le fichier audio depuis la requête
        $audioFile = $request->file('audio');

        try {
            // Appel à l'API Whisper
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
            ])->attach(
                'file', 
                fopen($audioFile->getPathname(), 'r'), 
                'recording.wav' // Nom du fichier
            )->post('https://api.openai.com/v1/audio/transcriptions', [
                'model' => 'whisper-1'
            ]);            

            if ($response->successful()) {
                return response()->json(['transcription' => $response->json()['text']]);
            } else {
                // Affiche les erreurs retournées par l'API
                return response()->json([
                    'error' => 'Erreur de transcription',
                    'details' => $response->json()
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Une erreur s\'est produite lors de l\'envoi du fichier audio.',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}
