<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PembayaranModel;
use App\Models\TagihanModel;

class PembayaranController extends BaseController
{
    protected PembayaranModel $pembayaranModel;
    protected TagihanModel $tagihanModel;

    public function __construct()
    {
        $this->pembayaranModel = new PembayaranModel();
        $this->tagihanModel    = new TagihanModel();
    }

    public function index()
    {
        $data = [
            'title'      => 'Manajemen Pembayaran & Verifikasi',
            'pembayaran' => $this->pembayaranModel->getPembayaranWithDetails(),
        ];
        return view('admin/pembayaran/index', $data);
    }

    public function new(?int $tagihanId = null)
    {
        // Tagihan yang belum lunas
        $tagihanBelumLunas = $this->tagihanModel
            ->select('tagihan.*, penghuni.nama_lengkap as nama_penghuni, kamar.nomor_kamar')
            ->join('penghuni', 'penghuni.id = tagihan.penghuni_id')
            ->join('kamar', 'kamar.id = tagihan.kamar_id')
            ->whereIn('tagihan.status', ['Belum Lunas', 'Menunggu Verifikasi'])
            ->orderBy('tagihan.id', 'DESC')
            ->findAll();

        $selectedTagihan = null;
        if ($tagihanId !== null) {
            $selectedTagihan = $this->tagihanModel->getTagihanWithDetails($tagihanId);
        }

        $data = [
            'title'             => 'Pencatatan Pembayaran Manual',
            'tagihanList'       => $tagihanBelumLunas,
            'selectedTagihan'   => $selectedTagihan,
        ];
        return view('admin/pembayaran/form', $data);
    }

    public function create()
    {
        $rules = [
            'tagihan_id'    => 'required|numeric',
            'tgl_bayar'     => 'required|valid_date',
            'nominal_bayar' => 'required|numeric|greater_than[0]',
            'metode_bayar'  => 'required|in_list[Transfer,Tunai]',
            'bukti_bayar'   => 'permit_empty|uploaded[bukti_bayar]|is_image[bukti_bayar]|mime_in[bukti_bayar,image/jpg,image/jpeg,image/png,image/webp]|max_size[bukti_bayar,3072]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $tagihanId = (int) $this->request->getPost('tagihan_id');
        $tagihan   = $this->tagihanModel->find($tagihanId);
        if (! $tagihan) {
            return redirect()->back()->withInput()->with('error', 'Tagihan tidak ditemukan.');
        }

        $buktiBayar = null;
        $fileBukti  = $this->request->getFile('bukti_bayar');
        if ($fileBukti && $fileBukti->isValid() && ! $fileBukti->hasMoved()) {
            $buktiBayar = $fileBukti->getRandomName();
            $fileBukti->move(FCPATH . 'uploads/bukti_bayar', $buktiBayar);
        }

        $kodePembayaran = $this->pembayaranModel->generateKodePembayaran();

        // Admin mencatat pembayaran: langsung terverifikasi 'Diterima'
        $this->pembayaranModel->insert([
            'kode_pembayaran'   => $kodePembayaran,
            'tagihan_id'        => $tagihanId,
            'tgl_bayar'         => $this->request->getPost('tgl_bayar'),
            'nominal_bayar'     => (float) $this->request->getPost('nominal_bayar'),
            'metode_bayar'      => (string) $this->request->getPost('metode_bayar'),
            'bukti_bayar'       => $buktiBayar,
            'status_verifikasi' => 'Diterima',
            'catatan_admin'     => trim((string) $this->request->getPost('catatan_admin')),
            'verifikasi_oleh'   => (int) session()->get('user_id'),
            'tgl_verifikasi'    => date('Y-m-d H:i:s'),
        ]);

        // Otomatis update status tagihan menjadi Lunas
        $this->tagihanModel->update($tagihanId, ['status' => 'Lunas']);

        return redirect()->to(base_url('admin/pembayaran'))->with('success', "Pembayaran {$kodePembayaran} berhasil dicatat dan status tagihan otomatis Lunas.");
    }

    public function verifikasi(int $id)
    {
        $pembayaran = $this->pembayaranModel->find($id);
        if (! $pembayaran) {
            return redirect()->to(base_url('admin/pembayaran'))->with('error', 'Data pembayaran tidak ditemukan.');
        }

        $aksi = (string) $this->request->getPost('aksi'); // 'Diterima' atau 'Ditolak'
        if (! in_array($aksi, ['Diterima', 'Ditolak'], true)) {
            return redirect()->to(base_url('admin/pembayaran'))->with('error', 'Aksi verifikasi tidak valid.');
        }

        $catatanAdmin = trim((string) $this->request->getPost('catatan_admin'));

        // Update pembayaran
        $this->pembayaranModel->update($id, [
            'status_verifikasi' => $aksi,
            'catatan_admin'     => $catatanAdmin,
            'verifikasi_oleh'   => (int) session()->get('user_id'),
            'tgl_verifikasi'    => date('Y-m-d H:i:s'),
        ]);

        // Update status tagihan terkait
        if ($aksi === 'Diterima') {
            $this->tagihanModel->update($pembayaran['tagihan_id'], ['status' => 'Lunas']);
            $msg = 'Pembayaran berhasil diverifikasi (Diterima) dan tagihan kini LUNAS.';
        } else {
            $this->tagihanModel->update($pembayaran['tagihan_id'], ['status' => 'Belum Lunas']);
            $msg = 'Pembayaran DITOLAK. Status tagihan dikembalikan menjadi Belum Lunas.';
        }

        return redirect()->to(base_url('admin/pembayaran'))->with('success', $msg);
    }
}
