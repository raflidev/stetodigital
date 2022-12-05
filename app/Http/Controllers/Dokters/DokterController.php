<?php

namespace App\Http\Controllers\Dokters;

use App\Http\Controllers\Controller;
use App\Models\MachineLearning;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pasien;
use Illuminate\Support\Facades\Storage;
use DB;

class DokterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Gate::denies('manage-dokters')) {
            abort(403);
        }

        return view('dokter.dokter.index');
    }

    public function viewPasien()
    {
        $getDocNurID = Auth::id();

        if (request()->search) {
            return Pasien::where('dokter_id', $getDocNurID)
                ->where('name', 'like', '%' . request()->search . '%')
                ->get();
        } else {
            return Pasien::where('dokter_id', $getDocNurID)->get();
        }
    }

    public function classification($id)
    {

        $readpasien = Pasien::where('user_id', $id)->get();
        $readDataML = MachineLearning::where('pasien_id', $id)->get();
        $user = User::where('id', $id)->firstOrfail();
        $user_id = $user->id;
        return view('dokter.dokter.classification', compact('readpasien', 'readDataML', 'user_id'));
    }

    public function uploadsignal(Request $request, $user_id)
    {
        $request->validate([
            'file' => 'required|mimes:audio/mpeg,mpga,mp3,wav,aac'
        ]);

        $fileModel = new MachineLearning;

        if ($request->file()) {
            $fileName = time() . '_' . $request->file->getClientOriginalName();
            $filePath = $request->file('file')->storeAs('uploads', $fileName, 'public');

            $fileModel->pasien_id = $user_id;
            $fileModel->name = time() . '_' . $request->file->getClientOriginalName();
            $fileModel->file_path = '/storage/' . $filePath;
            $fileModel->save();

            return back()
                ->with('success', 'File has been uploaded.')
                ->with('file', $fileName);
        }
    }

    public function result($id)
    {
        ini_set('max_execution_time', 300);
        $readpasien = MachineLearning::where('id', $id)->firstOrfail();
        if (Storage::disk('public')->exists("uploads/$readpasien->name")) {
            $data = Storage::disk('public')->path("uploads/$readpasien->name");
            $python = '/usr/bin/python3.8';
            $script = escapeshellcmd(base_path('/graph.py'));
            $command = "$python $script $data";
            ob_start();
            $output = exec($command);
            $result = json_decode($output, true);
            $y = dump($result["y"]);
            $x = dump($result["x"]);
            $z = $readpasien->result;
            if ($z == '0') {
                $as = 'True';
                $mr = 'False';
                $ms = 'False';
                $mvp = 'False';
                $n = 'False';
            } elseif ($z == '1') {
                $as = 'False';
                $mr = 'True';
                $ms = 'False';
                $mvp = 'False';
                $n = 'False';
            } elseif ($z == '2') {
                $as = 'False';
                $mr = 'False';
                $ms = 'True';
                $mvp = 'False';
                $n = 'False';
            } elseif ($z == '3') {
                $as = 'False';
                $mr = 'False';
                $ms = 'False';
                $mvp = 'True';
                $n = 'False';
            } elseif ($z == '4') {
                $as = 'False';
                $mr = 'False';
                $ms = 'False';
                $mvp = 'False';
                $n = 'True';
            } else {
                $as = 'False';
                $mr = 'False';
                $ms = 'False';
                $mvp = 'False';
                $n = 'False';
            }
            $output = ob_get_clean();
            $username = Pasien::where('user_id', $readpasien->pasien_id)->get();
            $namafile = $readpasien->name;

            return view('dokter.dokter.result', compact('username', 'namafile', 'x', 'y', 'as', 'mr', 'ms', 'mvp', 'n'));
        }
    }

    public function patientProfile($id)
    {
        $readpasien = Pasien::where('user_id', $id)->get();
        $user = User::where('id', $id)->firstOrfail();
        $user_id = $user->id;
        return view('dokter.dokter.pasien', compact('readpasien', 'user_id'));
    }
    public function detailPatient($id)
    {
        $readpasien = Pasien::where('user_id', $id)->get();
        $readDataML = MachineLearning::where('pasien_id', $id)->get();
        $user = User::where('id', $id)->firstOrfail();
        $user_id = $user->id;
        return view('dokter.dokter.details', compact('readpasien', 'readDataML', 'user_id'));
    }
    public function addDetails($id)
    {
    }

    public function onlineCheck()
    {
        return view('pasien.pasien.onlineCheck');
    }
}
