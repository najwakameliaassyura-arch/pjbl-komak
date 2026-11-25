<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\KamarModel;
use App\Models\PenghuniModel;
use App\Models\TagihanModel;
use App\Models\PembayaranModel;
use App\Models\KeluhanModel;

class DashboardController extends BaseController
{
    public function index()
    {
        $kamarModel      = new KamarModel();
        $penghuniModel   = new PenghuniModel();
        $tagihanModel    = new TagihanModel();
        $pembayaranModel = new PembayaranModel();
        $keluhanModel    = new KeluhanModel();

        // 1. Stats Counter
        $totalKamar     = $kamarModel->countAllResults();
        $kamarTersedia  = $kamarModel->where('status', 'Tersedia')->countAllResults();
        $kamarTerisi    = $kamarModel->where('status', 'Terisi')->countAllResults();
        $kamarPerbaikan = $kamarModel->where('status', 'Perbaikan')->countAllResults();
        $totalPenghuni  = $penghuniModel->where('status', 'Aktif')->countAllResults();

        // Pemasukan Bulan Ini
        $currentMonth = (int) date('m');
        $currentYear  = (int) date('Y');
        $db = \Config\Database::connect();

        $pemasukanBulanIni = $db->table('pembayaran')
            ->selectSum('nominal_bayar', 'total')
            ->where('status_verifikasi', 'Diterima')
            ->where('MONTH(tgl_bayar)', $currentMonth)
            ->where('YEAR(tgl_bayar)', $currentYear)
            ->get()->getRow()->total ?? 0;

        // Total Tunggakan (Tagihan Belum Lunas)
        $totalTunggakan = $db->table('tagihan')
            ->selectSum('nominal', 'total')
            ->where('status', 'Belum Lunas')
            ->get()->getRow()->total ?? 0;

        // 2. Chart.js Data (Pemasukan 6 Bulan Terakhir)
        $chartLabels = [];
        $chartData   = [];
        $indonesianMonths = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun',
            7 => 'Jul', 8 => 'Agu', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
        ];

        for ($i = 5; $i >= 0; $i--) {
            $time  = strtotime("-{$i} month");
            $mNum  = (int) date('n', $time);
            $yNum  = (int) date('Y', $time);
            $label = $indonesianMonths[$mNum] . ' ' . $yNum;
            $chartLabels[] = $label;

            $rev = $db->table('pembayaran')
                ->selectSum('nominal_bayar', 'total')
                ->where('status_verifikasi', 'Diterima')
                ->where('MONTH(tgl_bayar)', $mNum)
                ->where('YEAR(tgl_bayar)', $yNum)
                ->get()->getRow()->total ?? 0;

            $chartData[] = (float) $rev;
        }

        // 3. Quick Lists
        $tagihanMenungguVerifikasi = $db->table('pembayaran')
            ->select('pembayaran.*, tagihan.kode_tagihan, tagihan.bulan, tagihan.tahun, penghuni.nama_lengkap as nama_penghuni, kamar.nomor_kamar')
            ->join('tagihan', 'tagihan.id = pembayaran.tagihan_id')
            ->join('penghuni', 'penghuni.id = tagihan.penghuni_id')
            ->join('kamar', 'kamar.id = tagihan.kamar_id')
            ->where('pembayaran.status_verifikasi', 'Menunggu Verifikasi')
            ->orderBy('pembayaran.id', 'DESC')
            ->limit(5)
            ->get()->getResultArray();

        $tagihanJatuhTempo = $db->table('tagihan')
            ->select('tagihan.*, penghuni.nama_lengkap as nama_penghuni, kamar.nomor_kamar')
            ->join('penghuni', 'penghuni.id = tagihan.penghuni_id')
            ->join('kamar', 'kamar.id = tagihan.kamar_id')
            ->where('tagihan.status', 'Belum Lunas')
            ->orderBy('tagihan.jatuh_tempo', 'ASC')
            ->limit(5)
            ->get()->getResultArray();

        $keluhanTerbaru = $keluhanModel->getKeluhanWithDetails();
        $keluhanTerbaru = array_slice($keluhanTerbaru, 0, 5);

        $data = [
            'title'                     => 'Dashboard Admin KostKu',
            'totalKamar'                => $totalKamar,
            'kamarTersedia'             => $kamarTersedia,
            'kamarTerisi'               => $kamarTerisi,
            'kamarPerbaikan'            => $kamarPerbaikan,
            'totalPenghuni'             => $totalPenghuni,
            'pemasukanBulanIni'         => (float) $pemasukanBulanIni,
            'totalTunggakan'            => (float) $totalTunggakan,
            'chartLabels'               => json_encode($chartLabels),
            'chartData'                 => json_encode($chartData),
            'tagihanMenungguVerifikasi' => $tagihanMenungguVerifikasi,
            'tagihanJatuhTempo'         => $tagihanJatuhTempo,
            'keluhanTerbaru'            => $keluhanTerbaru,
        ];

        return view('admin/dashboard/index', $data);
    }
}
