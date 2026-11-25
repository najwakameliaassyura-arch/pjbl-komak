<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card card-primary card-outline shadow-sm">
            <div class="card-header">
                <h3 class="card-title font-weight-bold">
                    <i class="fas fa-file-invoice mr-1 text-primary"></i> <?= esc($title) ?>
                </h3>
            </div>
            
            <?php $isEdit = isset($tagihan); ?>
            <form action="<?= $isEdit ? base_url('admin/tagihan/update/' . $tagihan['id']) : base_url('admin/tagihan') ?>" method="post">
                <?= csrf_field() ?>

                <div class="card-body">
                    <?php if (session()->getFlashdata('errors')) : ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach (session()->getFlashdata('errors') as $err) : ?>
                                    <li><?= esc($err) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <?php if (! $isEdit) : ?>
                        <div class="form-group">
                            <label class="font-weight-bold">Pilih Penghuni Aktif <span class="text-danger">*</span></label>
                            <select name="penghuni_id" id="penghuni_select" class="form-control" required onchange="updateNominal(this)">
                                <option value="">-- Pilih Penghuni Aktif --</option>
                                <?php foreach ($penghuniAktif as $pa) : ?>
                                    <option value="<?= $pa['id'] ?>" data-harga="<?= $pa['harga_bulanan'] ?>">
                                        <?= esc($pa['nama_lengkap']) ?> - Kamar <?= esc($pa['nomor_kamar']) ?> (<?= esc($pa['nama_tipe']) ?> - Rp <?= number_format($pa['harga_bulanan'], 0, ',', '.') ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php else : ?>
                        <div class="form-group">
                            <label class="font-weight-bold">Penghuni & Kamar</label>
                            <input type="text" class="form-control bg-light" value="<?= esc($tagihan['nama_penghuni']) ?> (Kamar <?= esc($tagihan['nomor_kamar']) ?> - <?= esc($tagihan['nama_tipe']) ?>)" readonly>
                        </div>
                    <?php endif; ?>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">Bulan Periode <span class="text-danger">*</span></label>
                            <?php 
                            $indonesianMonths = [
                                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
                                7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                            ];
                            $curM = old('bulan', $tagihan['bulan'] ?? (int)date('m'));
                            ?>
                            <select name="bulan" class="form-control" <?= $isEdit ? 'disabled' : 'required' ?>>
                                <?php for ($m = 1; $m <= 12; $m++) : ?>
                                    <option value="<?= $m ?>" <?= (int)$curM === $m ? 'selected' : '' ?>>
                                        <?= $indonesianMonths[$m] ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                            <?php if ($isEdit) : ?><input type="hidden" name="bulan" value="<?= $tagihan['bulan'] ?>"><?php endif; ?>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">Tahun Periode <span class="text-danger">*</span></label>
                            <input type="number" name="tahun" class="form-control" value="<?= old('tahun', $tagihan['tahun'] ?? date('Y')) ?>" <?= $isEdit ? 'readonly' : 'required' ?>>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">Nominal Tagihan (Rp) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text font-weight-bold">Rp</span>
                                </div>
                                <input type="number" step="1000" name="nominal" id="nominal_input" class="form-control" placeholder="Contoh: 1200000" value="<?= old('nominal', $tagihan['nominal'] ?? '') ?>" required>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">Tanggal Jatuh Tempo <span class="text-danger">*</span></label>
                            <input type="date" name="jatuh_tempo" class="form-control" value="<?= old('jatuh_tempo', $tagihan['jatuh_tempo'] ?? date('Y-m-10', strtotime('+10 days'))) ?>" required>
                        </div>
                    </div>

                    <?php if ($isEdit) : ?>
                        <div class="form-group">
                            <label class="font-weight-bold">Status Pembayaran <span class="text-danger">*</span></label>
                            <select name="status" class="form-control" required>
                                <?php $curSt = old('status', $tagihan['status'] ?? 'Belum Lunas'); ?>
                                <option value="Belum Lunas" <?= $curSt === 'Belum Lunas' ? 'selected' : '' ?>>Belum Lunas</option>
                                <option value="Menunggu Verifikasi" <?= $curSt === 'Menunggu Verifikasi' ? 'selected' : '' ?>>Menunggu Verifikasi</option>
                                <option value="Lunas" <?= $curSt === 'Lunas' ? 'selected' : '' ?>>Lunas</option>
                            </select>
                        </div>
                    <?php endif; ?>

                    <div class="form-group">
                        <label class="font-weight-bold">Keterangan Tagihan</label>
                        <textarea name="keterangan" rows="2" class="form-control" placeholder="Keterangan tambahan (opsional)"><?= old('keterangan', $tagihan['keterangan'] ?? '') ?></textarea>
                    </div>
                </div>

                <div class="card-footer bg-light d-flex justify-content-between">
                    <a href="<?= base_url('admin/tagihan') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Batal & Kembali
                    </a>
                    <button type="submit" class="btn btn-primary font-weight-bold px-4">
                        <i class="fas fa-save mr-1"></i> Simpan Data Tagihan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function updateNominal(select) {
        const selected = select.options[select.selectedIndex];
        const harga = selected.getAttribute('data-harga');
        if (harga) {
            document.getElementById('nominal_input').value = harga;
        }
    }
</script>
<?= $this->endSection() ?>
