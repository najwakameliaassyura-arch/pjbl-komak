<?php

namespace App\Models;

use CodeIgniter\Model;

class TagihanModel extends Model
{
    protected $table            = 'tagihan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'kode_tagihan',
        'penghuni_id',
        'kamar_id',
        'bulan',
        'tahun',
        'nominal',
        'jatuh_tempo',
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

    public function getTagihanWithDetails(?int $id = null)
    {
        $builder = $this->select('tagihan.*, penghuni.nama_lengkap as nama_penghuni, penghuni.nik, penghuni.no_hp, kamar.nomor_kamar, tipe_kamar.nama_tipe, tipe_kamar.harga_bulanan')
                        ->join('penghuni', 'penghuni.id = tagihan.penghuni_id')
                        ->join('kamar', 'kamar.id = tagihan.kamar_id')
                        ->join('tipe_kamar', 'tipe_kamar.id = kamar.tipe_id');

        if ($id !== null) {
            return $builder->where('tagihan.id', $id)->first();
        }

        return $builder->orderBy('tagihan.id', 'DESC')->findAll();
    }

    public function getTagihanByPenghuni(int $penghuniId)
    {
        return $this->select('tagihan.*, kamar.nomor_kamar, tipe_kamar.nama_tipe')
                    ->join('kamar', 'kamar.id = tagihan.kamar_id')
                    ->join('tipe_kamar', 'tipe_kamar.id = kamar.tipe_id')
                    ->where('tagihan.penghuni_id', $penghuniId)
                    ->orderBy('tagihan.id', 'DESC')
                    ->findAll();
    }

    public function generateKodeTagihan(int $bulan, int $tahun): string
    {
        $prefix = sprintf('TAG-%04d%02d-', $tahun, $bulan);
        $last = $this->like('kode_tagihan', $prefix, 'after')
                     ->orderBy('id', 'DESC')
                     ->first();

        $nextNum = 1;
        if ($last) {
            $parts = explode('-', $last['kode_tagihan']);
            if (isset($parts[2])) {
                $nextNum = (int)$parts[2] + 1;
            }
        }

        return sprintf('%s%03d', $prefix, $nextNum);
    }

    /**
     * Generate Tagihan Massal untuk seluruh penghuni aktif di periode tertentu
     * Return [ 'created' => int, 'skipped' => int ]
     */
    public function generateTagihanMassal(int $bulan, int $tahun, string $jatuhTempo): array
    {
        $penghuniModel = new PenghuniModel();
        $penghuniAktif = $penghuniModel->getPenghuniAktif();

        $created = 0;
        $skipped = 0;

        foreach ($penghuniAktif as $p) {
            // Cek apakah sudah ada tagihan untuk penghuni ini di bulan & tahun yang sama
            $exists = $this->where('penghuni_id', $p['id'])
                           ->where('bulan', $bulan)
                           ->where('tahun', $tahun)
                           ->first();

            if ($exists) {
                $skipped++;
                continue;
            }

            $kodeTagihan = $this->generateKodeTagihan($bulan, $tahun);

            $this->insert([
                'kode_tagihan' => $kodeTagihan,
                'penghuni_id'  => $p['id'],
                'kamar_id'     => $p['kamar_id'],
                'bulan'        => $bulan,
                'tahun'        => $tahun,
                'nominal'      => $p['harga_bulanan'],
                'jatuh_tempo'  => $jatuhTempo,
                'status'       => 'Belum Lunas',
                'keterangan'   => sprintf('Tagihan sewa kamar %s periode bulan %d tahun %d', $p['nomor_kamar'], $bulan, $tahun),
            ]);

            $created++;
        }

        return ['created' => $created, 'skipped' => $skipped];
    }
}
