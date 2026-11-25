<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="card card-primary card-outline shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title font-weight-bold">
            <i class="fas fa-file-invoice-dollar mr-1 text-primary"></i> Manajemen Tagihan Bulanan Penghuni
        </h3>
        <div class="card-tools ml-auto">
            <!-- Tombol Generate Tagihan Massal (1-Klik) -->
            <button type="button" class="btn btn-success btn-sm font-weight-bold mr-2" data-toggle="modal" data-target="#modalGenerateMassal">
                <i class="fas fa-magic mr-1"></i> Generate Tagihan Massal
            </button>
            <a href="<?= base_url('admin/tagihan/new') ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-plus mr-1"></i> Buat Tagihan Manual
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped dataTable-standard">
                <thead class="thead-light">
                    <tr>
                        <th width="5%">No</th>
                        <th>Kode Tagihan</th>
                        <th>Penghuni & Kamar</th>
                        <th>Periode</th>
                        <th>Nominal</th>
                        <th>Jatuh Tempo</th>
                        <th>Status</th>
                        <th width="15%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    $indonesianMonths = [
                        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
                        7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                    ];
                    foreach ($tagihan as $tg) : 
                    ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <td>
                                <strong class="text-dark font-weight-bold"><?= esc($tg['kode_tagihan']) ?></strong>
                            </td>
                            <td>
                                <strong class="text-primary font-weight-bold"><?= esc($tg['nama_penghuni']) ?></strong>
                                <small class="d-block text-muted">Kamar <?= esc($tg['nomor_kamar']) ?> (<?= esc($tg['nama_tipe']) ?>)</small>
                            </td>
                            <td>
                                <span class="badge badge-light border">
                                    <?= $indonesianMonths[(int)$tg['bulan']] ?? $tg['bulan'] ?> <?= esc($tg['tahun']) ?>
                                </span>
                            </td>
                            <td class="font-weight-bold">
                                Rp <?= number_format($tg['nominal'], 0, ',', '.') ?>
                            </td>
                            <td>
                                <?php 
                                $isOverdue = (strtotime($tg['jatuh_tempo']) < time()) && ($tg['status'] !== 'Lunas');
                                ?>
                                <span class="<?= $isOverdue ? 'text-danger font-weight-bold' : '' ?>">
                                    <?= date('d M Y', strtotime($tg['jatuh_tempo'])) ?>
                                    <?php if ($isOverdue) : ?>
                                        <small class="badge badge-danger ml-1">Lewat</small>
                                    <?php endif; ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <?php if ($tg['status'] === 'Lunas') : ?>
                                    <span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle mr-1"></i> Lunas</span>
                                <?php elseif ($tg['status'] === 'Menunggu Verifikasi') : ?>
                                    <span class="badge badge-warning px-2 py-1"><i class="fas fa-clock mr-1"></i> Verifikasi</span>
                                <?php else : ?>
                                    <span class="badge badge-danger px-2 py-1"><i class="fas fa-exclamation-circle mr-1"></i> Belum Lunas</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if ($tg['status'] === 'Lunas') : ?>
                                    <a href="<?= base_url('admin/laporan/kwitansi/' . $tg['id']) ?>" target="_blank" class="btn btn-sm btn-info" title="Cetak Kwitansi PDF">
                                        <i class="fas fa-print"></i>
                                    </a>
                                <?php else: ?>
                                    <a href="<?= base_url('admin/pembayaran/new/' . $tg['id']) ?>" class="btn btn-sm btn-success" title="Catat Pembayaran">
                                        <i class="fas fa-cash-register"></i>
                                    </a>
                                <?php endif; ?>

                                <a href="<?= base_url('admin/tagihan/edit/' . $tg['id']) ?>" class="btn btn-sm btn-warning" title="Edit Tagihan">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="<?= base_url('admin/tagihan/delete/' . $tg['id']) ?>" method="post" class="d-inline">
                                    <?= csrf_field() ?>
                                    <button type="button" class="btn btn-sm btn-danger btn-delete-confirm" data-message="Hapus tagihan <?= esc($tg['kode_tagihan']) ?>?" title="Hapus Tagihan">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Generate Tagihan Massal -->
<div class="modal fade" id="modalGenerateMassal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title font-weight-bold" id="modalLabel">
                    <i class="fas fa-magic mr-1"></i> Generate Tagihan Massal Bulanan
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('admin/tagihan/generate-massal') ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="alert alert-info text-sm">
                        <i class="fas fa-info-circle mr-1"></i> Fitur ini akan otomatis membuat tagihan bulan berjalan untuk <strong>seluruh penghuni berstatus AKTIF</strong> sekaligus sesuai harga tipe kamar masing-masing. Penghuni yang sudah memiliki tagihan di periode tersebut otomatis dilewati (tidak duplikat).
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Bulan Tagihan <span class="text-danger">*</span></label>
                        <select name="bulan" class="form-control" required>
                            <?php 
                            $curM = (int) date('m');
                            for ($m = 1; $m <= 12; $m++) : 
                            ?>
                                <option value="<?= $m ?>" <?= $m === $curM ? 'selected' : '' ?>>
                                    <?= $indonesianMonths[$m] ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Tahun Tagihan <span class="text-danger">*</span></label>
                        <input type="number" name="tahun" class="form-control" value="<?= date('Y') ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Tanggal Jatuh Tempo <span class="text-danger">*</span></label>
                        <input type="date" name="jatuh_tempo" class="form-control" value="<?= date('Y-m-10', strtotime('+10 days')) ?>" required>
                        <small class="text-muted">Batas akhir pembayaran sebelum dianggap menunggak.</small>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success font-weight-bold">
                        <i class="fas fa-bolt mr-1"></i> Generate Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
