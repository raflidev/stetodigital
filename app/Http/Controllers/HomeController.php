<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Pasiens\PasienController;
use App\Http\Controllers\Dokters\DokterController;

class HomeController extends Controller
{
    protected $PasienController;
    protected $DokterController;
    public function __construct(PasienController $PasienController, DokterController $DokterController)
    {
        $this->PasienController = $PasienController;
        $this->DokterController = $DokterController;
    }

    public function index()
    {
        $role_id = Auth::user()->role_id;

        if ($role_id == '1') {
            $dataML = $this->PasienController->dataML();
            // dd($dataML);
            return view('pasien.pasien.dashboard', compact('dataML'));
        } else {
            $readPasien = $this->DokterController->viewPasien();
            return view('dokter.dokter.dashboard', compact('readPasien'));
        }
    }
}
