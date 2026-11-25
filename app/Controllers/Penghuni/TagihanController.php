<?php

namespace App\Controllers\Penghuni;

use App\Controllers\BaseController;
use App\Models\TagihanModel;
use App\Models\PembayaranModel;
use App\Models\PenghuniModel;
use Dompdf\Dompdf;

class TagihanController extends BaseController
{
    protected TagihanModel $tagihanModel;
    protected PembayaranModel $pembayaranModel;
    protected PenghuniModel $penghuniModel;

    public function __construct()
    {
        $this->tagihanModel    = new TagihanModel();
        $this->pembayaranModel = new PembayaranModel();
        $this->penghuniModel   = new PenghuniModel();
    }

    private function getTenantId(): int
    {
        $userId   = (int) session()->get('user_id');
        $penghuni = $this->penghuniModel->where('user_id', $userId)->first();
        return $penghuni ? (int) $penghuni['id'] : 0;
    }

    public function index()
    {
        $tenantId = $this->getTenantId();
        $tagihan  = $this->tagihanModel->getTagihanByPenghuni($tenantId);

        // Cari riwayat pembayaran untuk masing-masing tagihan
        foreach ($tagihan as &$tg) {
            $tg['pembayaran'] = $this->pembayaranModel->getPembayaranByTagihan($tg['id']);
        }

        $data = [
            'title'   => 'Daftar Tagihan Kamar Saya',
            'tagihan' => $tagihan,
        ];

        return view('penghuni/tagihan/index', $data);
    }

    public function bayar(int $id)
    {
        $tenantId = $this->getTenantId();
        $tagihan  = $this->tagihanModel->getTagihanWithDetails($id);

        if (! $tagihan || (int) $tagihan['penghuni_id'] !== $tenantId) {
            return redirect()->to(base_url('penghuni/tagihan'))->with('error', 'Tagihan tidak ditemukan atau bukan milik Anda.');
        }

        if ($tagihan['status'] === 'Lunas') {
            return redirect()->to(base_url('penghuni/tagihan'))->with('info', 'Tagihan ini sudah lunas.');
        }

        $pembayaranTerakhir = $this->pembayaranModel->getPembayaranByTagihan($id);

        $data = [
            'title'              => 'Pembayaran Tagihan ' . esc($tagihan['kode_tagihan']),
            'tagihan'            => $tagihan,
            'pembayaranTerakhir' => $pembayaranTerakhir,
        ];

        return view('penghuni/tagihan/bayar', $data);
    }

    public function storeBayar(int $id)
    {
        $tenantId = $this->getTenantId();
        $tagihan  = $this->tagihanModel->find($id);

        if (! $tagihan || (int) $tagihan['penghuni_id'] !== $tenantId) {
            return redirect()->to(base_url('penghuni/tagihan'))->with('error', 'Tagihan tidak valid.');
        }

        $rules = [
            'tgl_bayar'     => 'required|valid_date',
            'metode_bayar'  => 'required|in_list[Transfer,Tunai]',
            'bukti_bayar'   => 'uploaded[bukti_bayar]|is_image[bukti_bayar]|mime_in[bukti_bayar,image/jpg,image/jpeg,image/png,image/webp]|max_size[bukti_bayar,3072]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $buktiBayar = null;
        $fileBukti  = $this->request->getFile('bukti_bayar');
        if ($fileBukti && $fileBukti->isValid() && ! $fileBukti->hasMoved()) {
            $buktiBayar = $fileBukti->getRandomName();
            $fileBukti->move(FCPATH . 'uploads/bukti_bayar', $buktiBayar);
        }

        $kodePembayaran = $this->pembayaranModel->generateKodePembayaran();

        // Simpan data pembayaran berstatus 'Menunggu Verifikasi'
        $this->pembayaranModel->insert([
            'kode_pembayaran'   => $kodePembayaran,
            'tagihan_id'        => $id,
            'tgl_bayar'         => $this->request->getPost('tgl_bayar'),
            'nominal_bayar'     => (float) $tagihan['nominal'],
            'metode_bayar'      => (string) $this->request->getPost('metode_bayar'),
            'bukti_bayar'       => $buktiBayar,
            'status_verifikasi' => 'Menunggu Verifikasi',
            'catatan_admin'     => null,
        ]);

        // Update status tagihan menjadi 'Menunggu Verifikasi'
        $this->tagihanModel->update($id, ['status' => 'Menunggu Verifikasi']);

        return redirect()->to(base_url('penghuni/tagihan'))->with('success', 'Bukti pembayaran berhasil dikirim! Menunggu verifikasi dari pengelola kos.');
    }

    public function kwitansi(int $id)
    {
        $tenantId = $this->getTenantId();
        $tagihan  = $this->tagihanModel->getTagihanWithDetails($id);

        if (! $tagihan || (int) $tagihan['penghuni_id'] !== $tenantId) {
            return redirect()->to(base_url('penghuni/tagihan'))->with('error', 'Tagihan tidak ditemukan atau bukan milik Anda.');
        }

        if ($tagihan['status'] !== 'Lunas') {
            return redirect()->to(base_url('penghuni/tagihan'))->with('error', 'Kwitansi hanya dapat dicetak jika tagihan sudah berstatus LUNAS.');
        }

        $pembayaran = $this->pembayaranModel
            ->select('pembayaran.*, admin.nama_lengkap as nama_admin')
            ->join('users as admin', 'admin.id = pembayaran.verifikasi_oleh', 'left')
            ->where('pembayaran.tagihan_id', $id)
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
