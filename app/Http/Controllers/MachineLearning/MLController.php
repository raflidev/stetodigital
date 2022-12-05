<?php

namespace App\Http\Controllers\MachineLearning;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Pasiens\PasienController;
use App\Models\MachineLearning;
use App\Models\Pasien;
use App\Models\User;

class MLController extends Controller
{
    protected $PasienController;
    public function __construct(PasienController $PasienController)
    {
        $this->PasienController = $PasienController;
    }

    public function runml(Request $request)
    {
        ini_set('max_execution_time', 300);
        if (Storage::disk('public')->exists("uploads/$request->name")) {
            $data = Storage::disk('public')->path("uploads/$request->name");
            $model = (base_path('/model.tflite'));
            $script = escapeshellcmd(base_path('/ml_scrypt.py'));
            $exe_command = 'python ' . $script . ' ' . $data . ' ' . $model;
            $descriptorspec = array(
                0 => array("pipe", "r"),  // stdin
                1 => array("pipe", "w"),  // stdout -> we use this
                2 => array("pipe", "w")   // stderr
            );
            $process = proc_open($exe_command, $descriptorspec, $pipes);
            $a = [];
            if (is_resource($process)) {
                while (!feof($pipes[1])) {
                    $return_message = fgets($pipes[1], 1024);
                    if (strlen($return_message) == 0) break;
                    array_push($a, $return_message);
                    break;
                }
            }
            $z = $a[0][7];
            MachineLearning::where('name', $request->name)->update([
                'result' => $z
            ]);
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
            $dataml = MachineLearning::where('name', $request->name)->firstOrfail();
            // dd($dataml->pasien_id);
            $datauser = Pasien::where('user_id', $dataml->pasien_id)->firstOrfail();
            $username = User::where('id', $datauser->dokter_id)->get();
            $namafile = $request->name;
            // return view('pasien.pasien.result', compact('username', 'namafile', 'as', 'mr', 'ms', 'mvp', 'n'));
            return view('pasien.pasien.result', [
                'username' => $username,
                'namafile' => $namafile,
                'as' => $as,
                'mr' => $mr,
                'ms' => $ms,
                'mvp' => $mvp,
                'n' => $n
            ]);
        }
    }

    public function runIndocnur($id)
    {
        ini_set('max_execution_time', 300);
        $readpasien = MachineLearning::where('id', $id)->firstOrfail();
        if (Storage::disk('public')->exists("uploads/$readpasien->name")) {
            $data = Storage::disk('public')->path("uploads/$readpasien->name");
            $model = (base_path('/model.tflite'));
            $python = '/usr/bin/python3.8';
            $script = escapeshellcmd(base_path('/ml_scrypt.py'));
            $command = "$python $script $data $model";
            ob_start();
            $output = exec($command);
            $result = json_decode($output, true);
            $z = dump($result["z"]);
            $y = dump($result["y"]);
            $x = dump($result["x"]);
            MachineLearning::where('name', $readpasien->name)->update([
                'result' => $z
            ]);
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
            return view('dokter.dokter.resultOffline', compact('username', 'namafile', 'as', 'mr', 'ms', 'mvp', 'n', 'y', 'x'));
        }
    }
}
