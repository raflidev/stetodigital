<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\helpers\ResponseFormatter;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Pasien;
use App\Models\MachineLearning;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Exceptions\InvalidOrderException;
use App\Http\Controllers\Dokters\DokterController;

class PasienController extends Controller
{
    public function all(Request $request)
    {
        # menampilkan data pasien yang sudah diupload
        $id = Auth::user()->id;
        $activeUser = User::find(Auth::id());

        if ($id) {
            $dataML = MachineLearning::where('pasien_id', "$activeUser->id")->get();
            // dd($data);

            if ($dataML) {
                return ResponseFormatter::success(
                    $dataML,
                    'Data Pasien Berhasil Diambil'
                );
            } else {
                return ResponseFormatter::error(
                    null,
                    'Data Pasien Tidak ada',
                    404
                );
            }
        }

        return ResponseFormatter::success(
            $pasien->paginate($limit),
            'Data Pasien Berhasil diambil'
        );
    }

    public function uploadAudio(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:audio/mpeg,mpga,mp3,wav,aac'
        ]);

        //  $id = Auth::user()->id;
        $activeUser = User::find(Auth::id());
        $id = $activeUser->id;

        if ($id) {
            $fileModel = new MachineLearning;

            if ($request->file()) {
                $fileName = time() . '_' . $request->file->getClientOriginalName();
                $filePath = $request->file('file')->storeAs('uploads', $fileName, 'public');

                $fileModel->pasien_id = $activeUser->id;
                $fileModel->name = time() . '_' . $request->file->getClientOriginalName();
                $fileModel->file_path = '/storage/' . $filePath;
                $fileModel->save();

                //  return back()
                //      ->with('success', 'File has been uploaded.')
                //      ->with('file', $fileName);
            }
            if ($fileModel) {
                return ResponseFormatter::success(
                    $fileModel,
                    'Berhasil Upload Data'
                );
            } else {
                return ResponseFormatter::error(
                    null,
                    'Data Gagal Upload',
                    404
                );
            }
        }

        return ResponseFormatter::success(
            $fileModel->paginate($limit),
            'Berhasil Upload Data'
        );
    }
}
