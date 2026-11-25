<?php

namespace App\Models;

use CodeIgniter\Model;

class KeluhanModel extends Model
{
    protected $table            = 'keluhan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'penghuni_id',
        'kamar_id',
        'judul_keluhan',
        'deskripsi_keluhan',
        'foto_keluhan',
        'status',
        'tanggapan_admin',
        'created_at',
        'updated_at',
    ];

    protected bool $allowEmptyInserts = false;
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getKeluhanWithDetails(?int $id = null)
    {
        $builder = $this->select('keluhan.*, penghuni.nama_lengkap as nama_penghuni, penghuni.no_hp, kamar.nomor_kamar')
                        ->join('penghuni', 'penghuni.id = keluhan.penghuni_id')
                        ->join('kamar', 'kamar.id = keluhan.kamar_id');

        if ($id !== null) {
            return $builder->where('keluhan.id', $id)->first();
        }

        return $builder->orderBy('keluhan.id', 'DESC')->findAll();
    }

    public function getKeluhanByPenghuni(int $penghuniId)
    {
        return $this->select('keluhan.*, kamar.nomor_kamar')
                    ->join('kamar', 'kamar.id = keluhan.kamar_id')
                    ->where('keluhan.penghuni_id', $penghuniId)
                    ->orderBy('keluhan.id', 'DESC')
                    ->findAll();
    }
}
