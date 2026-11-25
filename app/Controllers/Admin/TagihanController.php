<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\TagihanModel;
use App\Models\PenghuniModel;
use App\Models\KamarModel;

class TagihanController extends BaseController
{
    protected TagihanModel $tagihanModel;
    protected PenghuniModel $penghuniModel;
    protected KamarModel $kamarModel;

    public function __construct()
    {
        $this->tagihanModel  = new TagihanModel();
        $this->penghuniModel = new PenghuniModel();
        $this->kamarModel    = new KamarModel();
    }

    public function index()
    {
        $data = [
            'title'   => 'Manajemen Tagihan Bulanan',
            'tagihan' => $this->tagihanModel->getTagihanWithDetails(),
        ];
        return view('admin/tagihan/index', $data);
    }

    public function new()
    {
        $penghuniAktif = $this->penghuniModel->getPenghuniAktif();

        $data = [
            'title'         => 'Buat Tagihan Manual',
            'penghuniAktif' => $penghuniAktif,
        ];
        return view('admin/tagihan/form', $data);
    }

    public function create()
    {
        $rules = [
            'penghuni_id' => 'required|numeric',
            'bulan'       => 'required|numeric|greater_than_equal_to[1]|less_than_equal_to[12]',
            'tahun'       => 'required|numeric|greater_than_equal_to[2020]',
            'nominal'     => 'required|numeric|greater_than[0]',
            'jatuh_tempo' => 'required|valid_date',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $penghuniId = (int) $this->request->getPost('penghuni_id');
        $bulan      = (int) $this->request->getPost('bulan');
        $tahun      = (int) $this->request->getPost('tahun');

        $penghuni = $this->penghuniModel->find($penghuniId);
        if (! $penghuni || ! $penghuni['kamar_id']) {
            return redirect()->back()->withInput()->with('error', 'Penghuni tidak valid atau belum menempati kamar.');
        }

        // Cek duplikasi tagihan
        $exists = $this->tagihanModel->where('penghuni_id', $penghuniId)
                                     ->where('bulan', $bulan)
                                     ->where('tahun', $tahun)
                                     ->first();
        if ($exists) {
            return redirect()->back()->withInput()->with('error', "Penghuni ini sudah memiliki tagihan untuk periode {$bulan}/{$tahun}.");
        }

        $kodeTagihan = $this->tagihanModel->generateKodeTagihan($bulan, $tahun);

        $this->tagihanModel->insert([
            'kode_tagihan' => $kodeTagihan,
            'penghuni_id'  => $penghuniId,
            'kamar_id'     => $penghuni['kamar_id'],
            'bulan'        => $bulan,
            'tahun'        => $tahun,
            'nominal'      => (float) $this->request->getPost('nominal'),
            'jatuh_tempo'  => $this->request->getPost('jatuh_tempo'),
            'status'       => 'Belum Lunas',
            'keterangan'   => trim((string) $this->request->getPost('keterangan')),
        ]);

        return redirect()->to(base_url('admin/tagihan'))->with('success', "Tagihan baru {$kodeTagihan} berhasil dibuat.");
    }

    public function edit(int $id)
    {
        $tagihan = $this->tagihanModel->getTagihanWithDetails($id);
        if (! $tagihan) {
            return redirect()->to(base_url('admin/tagihan'))->with('error', 'Tagihan tidak ditemukan.');
        }

        $data = [
            'title'         => 'Edit Tagihan ' . esc($tagihan['kode_tagihan']),
            'tagihan'       => $tagihan,
            'penghuniAktif' => $this->penghuniModel->getPenghuniAktif(),
        ];
        return view('admin/tagihan/form', $data);
    }

    public function update(int $id)
    {
        $tagihan = $this->tagihanModel->find($id);
        if (! $tagihan) {
            return redirect()->to(base_url('admin/tagihan'))->with('error', 'Tagihan tidak ditemukan.');
        }

        $rules = [
            'nominal'     => 'required|numeric|greater_than[0]',
            'jatuh_tempo' => 'required|valid_date',
            'status'      => 'required|in_list[Belum Lunas,Menunggu Verifikasi,Lunas]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->tagihanModel->update($id, [
            'nominal'     => (float) $this->request->getPost('nominal'),
            'jatuh_tempo' => $this->request->getPost('jatuh_tempo'),
            'status'      => (string) $this->request->getPost('status'),
            'keterangan'  => trim((string) $this->request->getPost('keterangan')),
        ]);

        return redirect()->to(base_url('admin/tagihan'))->with('success', 'Data tagihan berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        $tagihan = $this->tagihanModel->find($id);
        if (! $tagihan) {
            return redirect()->to(base_url('admin/tagihan'))->with('error', 'Tagihan tidak ditemukan.');
        }

        $this->tagihanModel->delete($id);
        return redirect()->to(base_url('admin/tagihan'))->with('success', 'Tagihan berhasil dihapus.');
    }

    /**
     * 1-Click Generate Tagihan Massal untuk seluruh penghuni aktif
     */
    public function generateMassal()
    {
        $rules = [
            'bulan'       => 'required|numeric|greater_than_equal_to[1]|less_than_equal_to[12]',
            'tahun'       => 'required|numeric|greater_than_equal_to[2020]',
            'jatuh_tempo' => 'required|valid_date',
        ];

        if (! $this->validate($rules)) {
            return redirect()->to(base_url('admin/tagihan'))->with('error', 'Input periode atau tanggal jatuh tempo tidak valid.');
        }

        $bulan      = (int) $this->request->getPost('bulan');
        $tahun      = (int) $this->request->getPost('tahun');
        $jatuhTempo = (string) $this->request->getPost('jatuh_tempo');

        $result = $this->tagihanModel->generateTagihanMassal($bulan, $tahun, $jatuhTempo);

        $msg = sprintf(
            'Generate Tagihan Massal selesai! %d tagihan baru berhasil dibuat, %d penghuni dilewati (sudah memiliki tagihan periode ini).',
            $result['created'],
            $result['skipped']
        );

        return redirect()->to(base_url('admin/tagihan'))->with('success', $msg);
    }
}
