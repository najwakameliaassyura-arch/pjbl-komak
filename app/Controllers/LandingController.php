<?php

namespace App\Controllers;

use App\Models\KamarModel;
use App\Models\TipeKamarModel;

class LandingController extends BaseController
{
    public function index()
    {
        $kamarModel     = new KamarModel();
        $tipeKamarModel = new TipeKamarModel();

        $kamarTersedia = $kamarModel->getKamarTersedia();
        $semuaTipe     = $tipeKamarModel->findAll();

        $data = [
            'title'         => 'KostKu - Sistem Informasi Manajemen Kos & Kontrakan',
            'kamarTersedia' => $kamarTersedia,
            'semuaTipe'     => $semuaTipe,
            'totalTersedia' => count($kamarTersedia),
        ];

        return view('landing/index', $data);
    }
}
