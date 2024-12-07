<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserImage;
use GuzzleHttp\Client;

class ImageController extends Controller
{
    public function uploadImage(Request $request)
    {
        // Logging request data for debugging
        \Log::info('Upload request:', $request->all());

        try {
            // Validasi input file
            $request->validate([
                'user_id' => 'required|exists:users,user_id',  // Pastikan user_id valid
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:16384', // Validasi file image
                'ageRange' => 'nullable|integer',
            ]);

            if ($request->hasFile('image')) {
                // Simpan gambar ke server Laravel (storage/app/public/user_images)
                $image = $request->file('image');
                $path = $image->store('user_images', 'public'); // Menyimpan file di storage/public

                // Kirim gambar ke Flask untuk memprediksi usia
                $predictedAge = $this->sendImageToFlask(storage_path('app/public/' . $path));

                // Simpan data dengan prediksi usia
                $userImage = new UserImage();
                $userImage->user_id = $request->user_id;
                $userImage->path = 'user_images/' . basename($path);
                $userImage->ageRange = $predictedAge;  // Simpan usia yang diprediksi
                $userImage->save();

                // Kembalikan response dengan pesan sukses dan data gambar
                return response()->json([
                    'message' => 'Image uploaded and predicted successfully',
                    'path' => asset('storage/' . $userImage->path),
                    'ageRange' => $predictedAge, // Kirim usia yang diprediksi
                ], 200);                
            }

            return response()->json(['message' => 'No image found'], 400);
        } catch (\Exception $e) {
            \Log::error('Image upload error: ' . $e->getMessage());
            return response()->json(['message' => 'Upload failed'], 500);
        }
    }

    // Kirim gambar ke Flask untuk mendapatkan usia yang diprediksi
    private function sendImageToFlask($imagePath)
    {
        // Membuat client HTTP menggunakan Guzzle
        $client = new Client();
        $response = $client->request('POST', 'http://ageprediction.healthify.web.id/predict', [
            'multipart' => [
                [
                    'name'     => 'image',  // Nama param       eter yang akan diterima oleh Flask
                    'contents' => fopen($imagePath, 'r'),  // Membuka gambar untuk dikirim
                    'filename' => 'image.jpg',  // Nama file yang dikirim
                ],
            ],
        ]);
        if ($response->getStatusCode() == 200) {
            $responseBody = json_decode($response->getBody()->getContents(), true);
            return $responseBody['predicted_age'];  // Kembalikan usia yang diprediksi
        } else {
            \Log::error('Failed to get valid response from Flask');
            return null;
        }
        // Ambil hasil prediksi usia dari response Flask
        $responseBody = json_decode($response->getBody()->getContents(), true);
        return $responseBody['predicted_age'];  // Kembalikan usia yang diprediksi
    }
}
