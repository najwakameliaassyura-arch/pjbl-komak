<?php

namespace App\Models;

use CodeIgniter\Model;

class PenghuniModel extends Model
{
    protected $table            = 'penghuni';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'kamar_id',
        'nama_lengkap',
        'nik',
        'no_hp',
        'pekerjaan',
        'foto_ktp',
        'tgl_masuk',
        'tgl_keluar',
        'status',
        'created_at',
        'updated_at',
    ];

    protected bool $allowEmptyInserts = false;
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getPenghuniWithDetails(?int $id = null)
    {
        $builder = $this->select('penghuni.*, users.email, users.username, users.role, kamar.nomor_kamar, kamar.lantai, tipe_kamar.nama_tipe, tipe_kamar.harga_bulanan, tipe_kamar.fasilitas')
                        ->join('users', 'users.id = penghuni.user_id', 'left')
                        ->join('kamar', 'kamar.id = penghuni.kamar_id', 'left')
                        ->join('tipe_kamar', 'tipe_kamar.id = kamar.tipe_id', 'left');

        if ($id !== null) {
            return $builder->where('penghuni.id', $id)->first();
        }

        return $builder->orderBy('penghuni.id', 'DESC')->findAll();
    }

    public function getByUserId(int $userId)
    {
        return $this->select('penghuni.*, users.email, users.username, kamar.nomor_kamar, kamar.lantai, kamar.foto as foto_kamar, tipe_kamar.nama_tipe, tipe_kamar.harga_bulanan, tipe_kamar.fasilitas, tipe_kamar.deskripsi as tipe_deskripsi')
                    ->join('users', 'users.id = penghuni.user_id', 'left')
                    ->join('kamar', 'kamar.id = penghuni.kamar_id', 'left')
                    ->join('tipe_kamar', 'tipe_kamar.id = kamar.tipe_id', 'left')
                    ->where('penghuni.user_id', $userId)
                    ->first();
    }

    public function getPenghuniAktif()
    {
        return $this->select('penghuni.*, kamar.nomor_kamar, tipe_kamar.nama_tipe, tipe_kamar.harga_bulanan')
                    ->join('kamar', 'kamar.id = penghuni.kamar_id')
                    ->join('tipe_kamar', 'tipe_kamar.id = kamar.tipe_id')
                    ->where('penghuni.status', 'Aktif')
                    ->findAll();
    }
}
