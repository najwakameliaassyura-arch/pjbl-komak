<?php

namespace App\Models;

use CodeIgniter\Model;

class PembayaranModel extends Model
{
    protected $table            = 'pembayaran';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'kode_pembayaran',
        'tagihan_id',
        'tgl_bayar',
        'nominal_bayar',
        'metode_bayar',
        'bukti_bayar',
        'status_verifikasi',
        'catatan_admin',
        'verifikasi_oleh',
        'tgl_verifikasi',
        'created_at',
        'updated_at',
    ];

    protected bool $allowEmptyInserts = false;
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getPembayaranWithDetails(?int $id = null)
    {
        $builder = $this->select('pembayaran.*, tagihan.kode_tagihan, tagihan.bulan, tagihan.tahun, tagihan.nominal as nominal_tagihan, tagihan.status as status_tagihan, penghuni.nama_lengkap as nama_penghuni, penghuni.nik, penghuni.no_hp, kamar.nomor_kamar, tipe_kamar.nama_tipe, admin.nama_lengkap as nama_verifikator')
                        ->join('tagihan', 'tagihan.id = pembayaran.tagihan_id')
                        ->join('penghuni', 'penghuni.id = tagihan.penghuni_id')
                        ->join('kamar', 'kamar.id = tagihan.kamar_id')
                        ->join('tipe_kamar', 'tipe_kamar.id = kamar.tipe_id')
                        ->join('users as admin', 'admin.id = pembayaran.verifikasi_oleh', 'left');

        if ($id !== null) {
            return $builder->where('pembayaran.id', $id)->first();
        }

        return $builder->orderBy('pembayaran.id', 'DESC')->findAll();
    }

    public function getPembayaranByTagihan(int $tagihanId)
    {
        return $this->where('tagihan_id', $tagihanId)->orderBy('id', 'DESC')->first();
    }

    public function generateKodePembayaran(): string
    {
        $prefix = 'PAY-' . date('Ym') . '-';
        $last = $this->like('kode_pembayaran', $prefix, 'after')
                     ->orderBy('id', 'DESC')
                     ->first();

        $nextNum = 1;
        if ($last) {
            $parts = explode('-', $last['kode_pembayaran']);
            if (isset($parts[2])) {
                $nextNum = (int)$parts[2] + 1;
            }
        }

        return sprintf('%s%03d', $prefix, $nextNum);
    }
}
