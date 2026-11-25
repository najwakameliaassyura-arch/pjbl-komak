<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="card card-primary card-outline shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title font-weight-bold">
            <i class="fas fa-tools mr-1 text-primary"></i> Laporan Keluhan & Kerusakan Kamar Saya
        </h3>
        <div class="card-tools ml-auto">
            <a href="<?= base_url('penghuni/keluhan/new') ?>" class="btn btn-primary btn-sm font-weight-bold">
                <i class="fas fa-plus mr-1"></i> Buat Laporan Kerusakan Baru
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped dataTable-standard">
                <thead class="thead-light">
                    <tr>
                        <th width="5%">No</th>
                        <th>Judul Keluhan</th>
                        <th>Rincian Masalah</th>
                        <th>Foto</th>
                        <th>Tgl Kirim</th>
                        <th>Status</th>
                        <th>Respon / Solusi Pengelola</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($keluhan as $kl) : ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <td><strong class="text-dark font-weight-bold"><?= esc($kl['judul_keluhan']) ?></strong></td>
                            <td><?= nl2br(esc($kl['deskripsi_keluhan'])) ?></td>
                            <td class="text-center">
                                <?php if (! empty($kl['foto_keluhan']) && file_exists(FCPATH . 'uploads/keluhan/' . $kl['foto_keluhan'])) : ?>
                                    <a href="<?= base_url('uploads/keluhan/' . $kl['foto_keluhan']) ?>" target="_blank">
                                        <img src="<?= base_url('uploads/keluhan/' . $kl['foto_keluhan']) ?>" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                    </a>
                                <?php else : ?>
                                    <span class="text-muted text-xs italic">Tanpa Foto</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-sm"><?= date('d/m/Y H:i', strtotime($kl['created_at'])) ?></td>
                            <td class="text-center">
                                <?php if ($kl['status'] === 'Pending') : ?>
                                    <span class="badge badge-danger px-2 py-1"><i class="fas fa-hourglass-start mr-1"></i> Pending</span>
                                <?php elseif ($kl['status'] === 'Diproses') : ?>
                                    <span class="badge badge-warning px-2 py-1"><i class="fas fa-spinner fa-spin mr-1"></i> Diproses</span>
                                <?php else : ?>
                                    <span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle mr-1"></i> Selesai</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-sm">
                                <?php if (! empty($kl['tanggapan_admin'])) : ?>
                                    <div class="p-2 bg-light rounded text-dark font-weight-bold">
                                        <i class="fas fa-comment-dots text-primary mr-1"></i> <?= esc($kl['tanggapan_admin']) ?>
                                    </div>
                                <?php else : ?>
                                    <span class="text-muted text-xs italic">Menunggu respon pengelola...</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
