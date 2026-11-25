<?php

namespace App\Controllers\Penghuni;

use App\Controllers\BaseController;
use App\Models\PenghuniModel;
use App\Models\TagihanModel;
use App\Models\KeluhanModel;

class DashboardController extends BaseController
{
    public function index()
    {
        $userId        = (int) session()->get('user_id');
        $penghuniModel = new PenghuniModel();
        $tagihanModel  = new TagihanModel();
        $keluhanModel  = new KeluhanModel();

        $penghuni = $penghuniModel->getByUserId($userId);

        if (! $penghuni) {
            return redirect()->to(base_url('logout'))->with('error', 'Data profil penghuni tidak ditemukan.');
        }

        $penghuniId = (int) $penghuni['id'];

        // Tagihan bulan ini
        $currentMonth = (int) date('m');
        $currentYear  = (int) date('Y');
        $tagihanBulanIni = $tagihanModel->where('penghuni_id', $penghuniId)
                                        ->where('bulan', $currentMonth)
                                        ->where('tahun', $currentYear)
                                        ->first();

        // Semua tagihan
        $semuaTagihan = $tagihanModel->getTagihanByPenghuni($penghuniId);
        $totalTunggakan = 0;
        $jumlahBelumLunas = 0;
        foreach ($semuaTagihan as $tg) {
            if ($tg['status'] === 'Belum Lunas') {
                $totalTunggakan += (float) $tg['nominal'];
                $jumlahBelumLunas++;
            }
        }

        // Keluhan kamar terakhir
        $keluhanList = $keluhanModel->getKeluhanByPenghuni($penghuniId);

        $data = [
            'title'            => 'Dashboard Penghuni KostKu',
            'penghuni'         => $penghuni,
            'tagihanBulanIni'  => $tagihanBulanIni,
            'totalTunggakan'   => $totalTunggakan,
            'jumlahBelumLunas' => $jumlahBelumLunas,
            'keluhanList'      => $keluhanList,
        ];

        return view('penghuni/dashboard/index', $data);
    }
}
