<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PembayaranModel;
use App\Models\TagihanModel;
use App\Models\PenghuniModel;
use App\Models\KamarModel;
use Dompdf\Dompdf;

class LaporanController extends BaseController
{
    protected PembayaranModel $pembayaranModel;
    protected TagihanModel $tagihanModel;
    protected PenghuniModel $penghuniModel;
    protected KamarModel $kamarModel;

    public function __construct()
    {
        $this->pembayaranModel = new PembayaranModel();
        $this->tagihanModel    = new TagihanModel();
        $this->penghuniModel   = new PenghuniModel();
        $this->kamarModel      = new KamarModel();
    }

    public function pemasukan()
    {
        $bulan = $this->request->getGet('bulan');
        $tahun = $this->request->getGet('tahun');

        $builder = $this->pembayaranModel
            ->select('pembayaran.*, tagihan.kode_tagihan, tagihan.bulan, tagihan.tahun, penghuni.nama_lengkap as nama_penghuni, kamar.nomor_kamar, tipe_kamar.nama_tipe')
            ->join('tagihan', 'tagihan.id = pembayaran.tagihan_id')
            ->join('penghuni', 'penghuni.id = tagihan.penghuni_id')
            ->join('kamar', 'kamar.id = tagihan.kamar_id')
            ->join('tipe_kamar', 'tipe_kamar.id = kamar.tipe_id')
            ->where('pembayaran.status_verifikasi', 'Diterima');

        if (! empty($bulan)) {
            $builder->where('MONTH(pembayaran.tgl_bayar)', (int) $bulan);
        }
        if (! empty($tahun)) {
            $builder->where('YEAR(pembayaran.tgl_bayar)', (int) $tahun);
        }

        $transaksi = $builder->orderBy('pembayaran.tgl_bayar', 'DESC')->findAll();

        $totalPemasukan = 0;
        foreach ($transaksi as $t) {
            $totalPemasukan += (float) $t['nominal_bayar'];
        }

        $data = [
            'title'          => 'Laporan Pemasukan Keuangan',
            'transaksi'      => $transaksi,
            'totalPemasukan' => $totalPemasukan,
            'bulan'          => $bulan,
            'tahun'          => $tahun,
        ];

        return view('admin/laporan/pemasukan', $data);
    }

    public function pemasukanPdf()
    {
        $bulan = $this->request->getGet('bulan');
        $tahun = $this->request->getGet('tahun');

        $builder = $this->pembayaranModel
            ->select('pembayaran.*, tagihan.kode_tagihan, tagihan.bulan, tagihan.tahun, penghuni.nama_lengkap as nama_penghuni, kamar.nomor_kamar, tipe_kamar.nama_tipe')
            ->join('tagihan', 'tagihan.id = pembayaran.tagihan_id')
            ->join('penghuni', 'penghuni.id = tagihan.penghuni_id')
            ->join('kamar', 'kamar.id = tagihan.kamar_id')
            ->join('tipe_kamar', 'tipe_kamar.id = kamar.tipe_id')
            ->where('pembayaran.status_verifikasi', 'Diterima');

        if (! empty($bulan)) {
            $builder->where('MONTH(pembayaran.tgl_bayar)', (int) $bulan);
        }
        if (! empty($tahun)) {
            $builder->where('YEAR(pembayaran.tgl_bayar)', (int) $tahun);
        }

        $transaksi = $builder->orderBy('pembayaran.tgl_bayar', 'ASC')->findAll();

        $totalPemasukan = 0;
        foreach ($transaksi as $t) {
            $totalPemasukan += (float) $t['nominal_bayar'];
        }

        $data = [
            'title'          => 'Laporan Pemasukan Keuangan KostKu',
            'transaksi'      => $transaksi,
            'totalPemasukan' => $totalPemasukan,
            'bulan'          => $bulan,
            'tahun'          => $tahun,
            'tglCetak'       => date('d F Y'),
        ];

        $html = view('pdf/laporan_pemasukan', $data);

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filename = 'Laporan_Pemasukan_KostKu_' . date('Ymd_His') . '.pdf';
        $dompdf->stream($filename, ['Attachment' => false]);
    }

    public function penghuniPdf()
    {
        $kamar = $this->kamarModel->getKamarWithTipe();

        foreach ($kamar as &$k) {
            $penghuni = $this->penghuniModel
                ->select('penghuni.*, users.email')
                ->join('users', 'users.id = penghuni.user_id', 'left')
                ->where('penghuni.kamar_id', $k['id'])
                ->where('penghuni.status', 'Aktif')
                ->first();

            $k['penghuni'] = $penghuni;
        }

        $totalKamar    = count($kamar);
        $totalTerisi   = count(array_filter($kamar, fn($item) => $item['status'] === 'Terisi'));
        $totalTersedia = count(array_filter($kamar, fn($item) => $item['status'] === 'Tersedia'));

        $data = [
            'title'         => 'Laporan Data Kamar & Penghuni Aktif',
            'kamar'         => $kamar,
            'totalKamar'    => $totalKamar,
            'totalTerisi'   => $totalTerisi,
            'totalTersedia' => $totalTersedia,
            'tglCetak'      => date('d F Y'),
        ];

        $html = view('pdf/laporan_penghuni', $data);

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $filename = 'Laporan_Penghuni_KostKu_' . date('Ymd_His') . '.pdf';
        $dompdf->stream($filename, ['Attachment' => false]);
    }

    public function kwitansi(int $tagihanId)
    {
        $tagihan = $this->tagihanModel->getTagihanWithDetails($tagihanId);
        if (! $tagihan) {
            return redirect()->back()->with('error', 'Tagihan tidak ditemukan.');
        }

        if ($tagihan['status'] !== 'Lunas') {
            return redirect()->back()->with('error', 'Kwitansi hanya dapat dicetak untuk tagihan yang berstatus LUNAS.');
        }

        $pembayaran = $this->pembayaranModel
            ->select('pembayaran.*, admin.nama_lengkap as nama_admin')
            ->join('users as admin', 'admin.id = pembayaran.verifikasi_oleh', 'left')
            ->where('pembayaran.tagihan_id', $tagihanId)
            ->where('pembayaran.status_verifikasi', 'Diterima')
            ->orderBy('pembayaran.id', 'DESC')
            ->first();

        $data = [
            'title'      => 'Kwitansi Pembayaran - ' . $tagihan['kode_tagihan'],
            'tagihan'    => $tagihan,
            'pembayaran' => $pembayaran,
            'terbilang'  => $this->penyebut((int) $tagihan['nominal']) . ' Rupiah',
            'tglCetak'   => date('d F Y'),
        ];

        $html = view('pdf/kwitansi', $data);

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filename = 'Kwitansi_' . $tagihan['kode_tagihan'] . '.pdf';
        $dompdf->stream($filename, ['Attachment' => false]);
    }

    private function penyebut(int $nilai): string
    {
        $nilai = abs($nilai);
        $huruf = ["", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas"];
        $temp = "";

        if ($nilai < 12) {
            $temp = " " . $huruf[$nilai];
        } elseif ($nilai < 20) {
            $temp = $this->penyebut($nilai - 10) . " Belas";
        } elseif ($nilai < 100) {
            $temp = $this->penyebut((int)($nilai / 10)) . " Puluh" . $this->penyebut($nilai % 10);
        } elseif ($nilai < 200) {
            $temp = " Seratus" . $this->penyebut($nilai - 100);
        } elseif ($nilai < 1000) {
            $temp = $this->penyebut((int)($nilai / 100)) . " Ratus" . $this->penyebut($nilai % 100);
        } elseif ($nilai < 2000) {
            $temp = " Seribu" . $this->penyebut($nilai - 1000);
        } elseif ($nilai < 1000000) {
            $temp = $this->penyebut((int)($nilai / 1000)) . " Ribu" . $this->penyebut($nilai % 1000);
        } elseif ($nilai < 1000000000) {
            $temp = $this->penyebut((int)($nilai / 1000000)) . " Juta" . $this->penyebut($nilai % 1000000);
        } elseif ($nilai < 1000000000000) {
            $temp = $this->penyebut((int)($nilai / 1000000000)) . " Milyar" . $this->penyebut(fmod($nilai, 1000000000));
        }

        return trim($temp);
    }
}
