<?php

namespace App\Models;

use CodeIgniter\Model;

class KamarModel extends Model
{
    protected $table            = 'kamar';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'tipe_id',
        'nomor_kamar',
        'lantai',
        'foto',
        'status',
        'keterangan',
        'created_at',
        'updated_at',
    ];

    protected bool $allowEmptyInserts = false;
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getKamarWithTipe(?int $id = null)
    {
        $builder = $this->select('kamar.*, tipe_kamar.nama_tipe, tipe_kamar.harga_bulanan, tipe_kamar.fasilitas, tipe_kamar.deskripsi as tipe_deskripsi')
                        ->join('tipe_kamar', 'tipe_kamar.id = kamar.tipe_id');

        if ($id !== null) {
            return $builder->where('kamar.id', $id)->first();
        }

        return $builder->orderBy('kamar.nomor_kamar', 'ASC')->findAll();
    }

    public function getKamarTersedia()
    {
        return $this->select('kamar.*, tipe_kamar.nama_tipe, tipe_kamar.harga_bulanan, tipe_kamar.fasilitas')
                    ->join('tipe_kamar', 'tipe_kamar.id = kamar.tipe_id')
                    ->where('kamar.status', 'Tersedia')
                    ->orderBy('kamar.nomor_kamar', 'ASC')
                    ->findAll();
    }
}
