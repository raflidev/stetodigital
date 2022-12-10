<?php

namespace App\Http\Controllers\Pasiens;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pasien;
use App\Models\MachineLearning;
use Illuminate\Support\Facades\Storage;
use App\Exceptions\InvalidOrderException;
use Illuminate\Support\Facades\Http;
use PhpMqtt\Client\MqttClient;
use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class PasienController extends Controller
{
    public function index()
    {
        return view('pasien.pasien.dashboard');
    }

    public function user()
    {
        $active = User::find(Auth::id());
        return User::where('id', $active)->get();
    }

    public function addDocNur()
    {
        $addDocNur = User::where('role_id', '2')->get();

        return view('pasien.pasien.addDocNur', compact('addDocNur'));
    }

    public function ownCheck()
    {
        $activeUser = User::find(Auth::id());
        $dataSignal = MachineLearning::where('pasien_id', $activeUser->id)->get();
        return view('pasien.pasien.owncheck', compact('dataSignal'));
    }
    
    public function resultbest()
    {
        $activeUser = User::find(Auth::id());
        return view('pasien.pasien.result');
    }

    public function postScanning()
    {
        $activeUser = User::find(Auth::id());

        $fileModel = new MachineLearning;

        // $wav = asset('/Normal.wav');
        $data = Storage::disk('public')->path("Normal.wav");
        // dd($data);
        // Storage::disk('local')->put('Normal.wav', 'Contents');
        // $wav = Storage::get('\public\Normal.wav');
        // dd($wav);

        // $fileName = time() . '_' . $wav;
        // $filePath = $request->file->storeAs('uploads', $fileName, 'public');
        $filePath = Storage::putFile('public/uploads/', $data);

        dd($filePath);
        // $fileModel->pasien_id = $activeUser->id;
        // $fileModel->name = time() . '_' . $wav;
        // $fileModel->file_path = '/storage/' . $filePath;
        // $fileModel->save();

        // return back()
        //     ->with('success', 'File has been uploaded.')
        //     ->with('file', $fileName);
    }

    public function store(Request $request)
    {
        $activeUser = User::find(Auth::id());
        $gender = $activeUser->gender;
        $address = $activeUser->address;

        if ($gender == null or $address == null) {
            return view('profile.show');
        } else {
            try {
                Pasien::create([
                    'user_id' => $activeUser->id,
                    'name' => $activeUser->name,
                    'gender' => $activeUser->gender,
                    'address' => $activeUser->address,
                    'email' => $activeUser->email,
                    'phonenumber' => $activeUser->phonenumber,
                    'dokter_id' => $request->id,
                ])->save();
            } catch (\Throwable $th) {
                //throw $th;
                $errorCode = $th->errorInfo[1];
                if ($errorCode == '1062') {
                    // dd('Duplicate Entry');
                    return redirect()->back()->withErrors($errorCode);
                }
            }
        }

        return redirect()->back();
    }

    public function uploadSignal(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:audio/mpeg,mpga,mp3,wav,aac'
        ]);

        $activeUser = User::find(Auth::id());

        $fileModel = new MachineLearning;

        if ($request->file()) {
            $fileName = time() . '_' . $request->file->getClientOriginalName();
            $filePath = $request->file('file')->storeAs('uploads', $fileName, 'public');
            $fileModel->pasien_id = $activeUser->id;
            $fileModel->name = time() . '_' . $request->file->getClientOriginalName();

            $fileModel->file_path = '/storage/' . $filePath;
            $fileModel->save();

            return back()
                ->with('success', 'File has been uploaded.')
                ->with('file', $fileName);
        }
    }

    public function dataML()
    {
        $activeUser = User::find(Auth::id());
        return MachineLearning::where('pasien_id', $activeUser->id)->get();
    }

    public function result($id)
    {
        ini_set('max_execution_time', 300);
        $readpasien = MachineLearning::where('id', $id)->firstOrfail();
        if (Storage::disk('public')->exists("uploads/$readpasien->name")) {
            $data = Storage::disk('public')->path("uploads/$readpasien->name");
            // $python = '/usr/bin/python3.8';
            // $script = escapeshellcmd(base_path('/graph.py'));
            // $command = "$python $script $data";
            // ob_start();
            // $output = exec($command);
            // $result = json_decode($output, true);
            // $y = dump($result["y"]);
            // $x = dump($result["x"]);
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
            // $output = ob_get_clean();
            $datauser = Pasien::where('user_id', $readpasien->pasien_id)->firstOrfail();
            $username = User::where('id', $datauser->dokter_id)->get();
            $namafile = $readpasien->name;
            return view('pasien.pasien.result', compact('username', 'namafile', 'as', 'mr', 'ms', 'mvp', 'n'));
        }
    }

    public function mqtt()
    {
        $server   = 'broker.hivemq.com';
        $port     = 1883;
        $clientId = 'dokter';

        $mqtt = new MqttClient($server, $port, $clientId);
        $mqtt->connect();
        for ($i = 0; $i < 10; $i++) {
            $mqtt->publish('php-mqtt/client/test/pasien', 'Hello World!', 0);
        }
        $mqtt->publish('php-mqtt/client/test/pasien', 'end', 0);
        $mqtt->disconnect();
    }

    public function mqtt_sub()
    {
        $data = Http::get('http://localhost:3000/');
        echo $data;
    }
}
