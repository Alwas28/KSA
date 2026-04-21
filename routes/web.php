<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserRoleController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\JenisSimpananController;
use App\Http\Controllers\SimpananController;
use App\Http\Controllers\StatusAnggotaController;
use App\Http\Controllers\PendaftaranAnggotaController;
use App\Http\Controllers\ProfilAnggotaController;
use App\Http\Controllers\SimpananSayaController;
use App\Http\Controllers\SaldoSimpananController;
use App\Http\Controllers\PinjamanController;
use App\Http\Controllers\PinjamanSayaController;
use App\Http\Controllers\PembayaranAngsuranController;
use App\Http\Controllers\JadwalAngsuranController;
use App\Http\Controllers\PersetujuanPinjamanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RiwayatTransaksiController;
use App\Http\Controllers\BukuKasController;
use App\Http\Controllers\LaporanKeuanganController;
use App\Http\Controllers\ArsipFileController;
use App\Http\Controllers\KegiatanUsahaController;
use App\Http\Controllers\TransaksiKegiatanController;
use App\Http\Controllers\ShuController;
use App\Http\Controllers\RiwayatShuController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\PublicBeritaController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'index']);

// Public Berita Routes (no auth required)
Route::get('/berita', [PublicBeritaController::class, 'index'])->name('berita.index');
Route::get('/berita/{slug}', [PublicBeritaController::class, 'show'])->name('berita.show');

// Dashboard Routes
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Anggota Routes
    Route::get('/anggota', [AnggotaController::class, 'index'])->middleware('permission:anggota.read')->name('anggota.index');
    Route::post('/anggota', [AnggotaController::class, 'store'])->middleware('permission:anggota.create')->name('anggota.store');
    Route::get('/anggota/{anggotum}', [AnggotaController::class, 'show'])->middleware('permission:anggota.read')->name('anggota.show');
    Route::put('/anggota/{anggotum}', [AnggotaController::class, 'update'])->middleware('permission:anggota.update')->name('anggota.update');
    Route::delete('/anggota/{anggotum}', [AnggotaController::class, 'destroy'])->middleware('permission:anggota.delete')->name('anggota.destroy');

    // Pendaftaran Anggota Routes
    Route::get('/pendaftaran-anggota', [PendaftaranAnggotaController::class, 'index'])->middleware('permission:anggota.read')->name('pendaftaran-anggota.index');
    Route::get('/pendaftaran-anggota/{anggotum}', [PendaftaranAnggotaController::class, 'show'])->middleware('permission:anggota.read')->name('pendaftaran-anggota.show');
    Route::put('/pendaftaran-anggota/{anggotum}/approve', [PendaftaranAnggotaController::class, 'approve'])->middleware('permission:anggota.update')->name('pendaftaran-anggota.approve');
    Route::delete('/pendaftaran-anggota/{anggotum}', [PendaftaranAnggotaController::class, 'destroy'])->middleware('permission:anggota.delete')->name('pendaftaran-anggota.destroy');

    // Jenis Simpanan Routes
    Route::get('/jenis-simpanan', [JenisSimpananController::class, 'index'])->middleware('permission:jenis_simpanan.read')->name('jenis-simpanan.index');
    Route::post('/jenis-simpanan', [JenisSimpananController::class, 'store'])->middleware('permission:jenis_simpanan.create')->name('jenis-simpanan.store');
    Route::get('/jenis-simpanan/{jenisSimpanan}', [JenisSimpananController::class, 'show'])->middleware('permission:jenis_simpanan.read')->name('jenis-simpanan.show');
    Route::put('/jenis-simpanan/{jenisSimpanan}', [JenisSimpananController::class, 'update'])->middleware('permission:jenis_simpanan.update')->name('jenis-simpanan.update');
    Route::delete('/jenis-simpanan/{jenisSimpanan}', [JenisSimpananController::class, 'destroy'])->middleware('permission:jenis_simpanan.delete')->name('jenis-simpanan.destroy');

    // Simpanan Routes
    Route::get('/simpanan', [SimpananController::class, 'index'])->middleware('permission:simpanan.read')->name('simpanan.index');
    Route::post('/simpanan', [SimpananController::class, 'store'])->middleware('permission:simpanan.create')->name('simpanan.store');
    Route::get('/simpanan/{simpanan}', [SimpananController::class, 'show'])->middleware('permission:simpanan.read')->name('simpanan.show');
    Route::put('/simpanan/{simpanan}', [SimpananController::class, 'update'])->middleware('permission:simpanan.update')->name('simpanan.update');
    Route::delete('/simpanan/{simpanan}', [SimpananController::class, 'destroy'])->middleware('permission:simpanan.delete')->name('simpanan.destroy');

    // Status Anggota Routes
    Route::get('/status-anggota', [StatusAnggotaController::class, 'index'])->middleware('permission:status_anggota.read')->name('status-anggota.index');
    Route::post('/status-anggota', [StatusAnggotaController::class, 'store'])->middleware('permission:status_anggota.create')->name('status-anggota.store');
    Route::get('/status-anggota/{statusAnggotum}', [StatusAnggotaController::class, 'show'])->middleware('permission:status_anggota.read')->name('status-anggota.show');
    Route::put('/status-anggota/{statusAnggotum}', [StatusAnggotaController::class, 'update'])->middleware('permission:status_anggota.update')->name('status-anggota.update');
    Route::delete('/status-anggota/{statusAnggotum}', [StatusAnggotaController::class, 'destroy'])->middleware('permission:status_anggota.delete')->name('status-anggota.destroy');

    // Profil Anggota Routes
    Route::get('/profil-saya', [ProfilAnggotaController::class, 'index'])->name('profil-anggota.index');
    Route::get('/profil-saya/edit', [ProfilAnggotaController::class, 'edit'])->name('profil-anggota.edit');
    Route::put('/profil-saya', [ProfilAnggotaController::class, 'update'])->name('profil-anggota.update');
    Route::get('/detail-anggota/{anggotum}', [ProfilAnggotaController::class, 'show'])->middleware('permission:anggota.read')->name('detail-anggota.show');

    // Simpanan Saya Routes
    Route::get('/simpanan-saya', [SimpananSayaController::class, 'index'])->name('simpanan-saya.index');

    // Saldo Simpanan Routes
    Route::get('/saldo-simpanan', [SaldoSimpananController::class, 'index'])->middleware('permission:simpanan.read')->name('saldo-simpanan.index');
    Route::get('/saldo-simpanan/{anggotum}', [SaldoSimpananController::class, 'show'])->middleware('permission:simpanan.read')->name('saldo-simpanan.show');

    // Pinjaman Routes (Admin)
    Route::get('/pinjaman', [PinjamanController::class, 'index'])->middleware('permission:pinjaman.read')->name('pinjaman.index');
    Route::post('/pinjaman', [PinjamanController::class, 'store'])->middleware('permission:pinjaman.create')->name('pinjaman.store');
    Route::get('/pinjaman/{pinjaman}', [PinjamanController::class, 'show'])->middleware('permission:pinjaman.read')->name('pinjaman.show');
    Route::put('/pinjaman/{pinjaman}', [PinjamanController::class, 'update'])->middleware('permission:pinjaman.update')->name('pinjaman.update');
    Route::delete('/pinjaman/{pinjaman}', [PinjamanController::class, 'destroy'])->middleware('permission:pinjaman.delete')->name('pinjaman.destroy');
    Route::put('/pinjaman/{pinjaman}/approve', [PinjamanController::class, 'approve'])->middleware('permission:pinjaman.update')->name('pinjaman.approve');
    Route::put('/pinjaman/{pinjaman}/reject', [PinjamanController::class, 'reject'])->middleware('permission:pinjaman.update')->name('pinjaman.reject');
    Route::put('/pinjaman/{pinjaman}/cairkan', [PinjamanController::class, 'cairkan'])->middleware('permission:pinjaman.update')->name('pinjaman.cairkan');

    // Pinjaman Saya Routes (Member)
    Route::get('/pinjaman-saya', [PinjamanSayaController::class, 'index'])->name('pinjaman-saya.index');
    Route::post('/pinjaman-saya', [PinjamanSayaController::class, 'store'])->name('pinjaman-saya.store');

    // Persetujuan & Pencairan Pinjaman Routes (Admin)
    Route::get('/persetujuan-pinjaman', [PersetujuanPinjamanController::class, 'index'])->middleware('permission:pinjaman.read')->name('persetujuan-pinjaman.index');
    Route::post('/persetujuan-pinjaman/{pinjaman}/cairkan', [PersetujuanPinjamanController::class, 'cairkan'])->middleware('permission:pinjaman.update')->name('persetujuan-pinjaman.cairkan');

    // Pembayaran Angsuran Routes (Admin)
    Route::get('/pembayaran-angsuran', [PembayaranAngsuranController::class, 'index'])->middleware('permission:pinjaman.read')->name('pembayaran-angsuran.index');
    Route::get('/pembayaran-angsuran/{pinjaman}', [PembayaranAngsuranController::class, 'detail'])->middleware('permission:pinjaman.read')->name('pembayaran-angsuran.detail');
    Route::post('/pembayaran-angsuran', [PembayaranAngsuranController::class, 'store'])->middleware('permission:pinjaman.update')->name('pembayaran-angsuran.store');
    Route::post('/pembayaran-angsuran/{pinjaman}/cairkan', [PembayaranAngsuranController::class, 'cairkan'])->middleware('permission:pinjaman.update')->name('pembayaran-angsuran.cairkan');

    // Jadwal Angsuran Saya Routes (Member)
    Route::get('/jadwal-angsuran', [JadwalAngsuranController::class, 'index'])->name('jadwal-angsuran.index');
    Route::get('/jadwal-angsuran/{pinjaman}', [JadwalAngsuranController::class, 'detail'])->name('jadwal-angsuran.detail');

    // Riwayat Transaksi Routes (Member)
    Route::get('/riwayat-transaksi', [RiwayatTransaksiController::class, 'index'])->name('riwayat-transaksi.index');

    // Riwayat SHU Routes (Member)
    Route::get('/riwayat-shu', [RiwayatShuController::class, 'index'])->name('riwayat-shu.index');

    // SHU Management Routes (Admin)
    Route::get('/shu', [ShuController::class, 'index'])->middleware('permission:laporan_shu.read')->name('shu.index');
    Route::post('/shu', [ShuController::class, 'store'])->middleware('permission:laporan_shu.read')->name('shu.store');
    Route::get('/shu/{shuDetail}', [ShuController::class, 'show'])->middleware('permission:laporan_shu.read')->name('shu.show');
    Route::put('/shu/{shuDetail}', [ShuController::class, 'update'])->middleware('permission:laporan_shu.read')->name('shu.update');
    Route::delete('/shu/{shuDetail}', [ShuController::class, 'destroy'])->middleware('permission:laporan_shu.read')->name('shu.destroy');

    // Buku Kas Routes
    Route::get('/buku-kas', [BukuKasController::class, 'index'])->middleware('permission:simpanan.read')->name('buku-kas.index');
    Route::post('/buku-kas', [BukuKasController::class, 'store'])->middleware('permission:simpanan.create')->name('buku-kas.store');
    Route::get('/buku-kas/{id}', [BukuKasController::class, 'show'])->middleware('permission:simpanan.read')->name('buku-kas.show');
    Route::put('/buku-kas/{id}', [BukuKasController::class, 'update'])->middleware('permission:simpanan.update')->name('buku-kas.update');
    Route::delete('/buku-kas/{id}', [BukuKasController::class, 'destroy'])->middleware('permission:simpanan.delete')->name('buku-kas.destroy');

    // Laporan Keuangan Routes
    Route::get('/laporan-keuangan', [LaporanKeuanganController::class, 'index'])->middleware('permission:simpanan.read')->name('laporan-keuangan.index');
    Route::get('/laporan-keuangan/laba-rugi', [LaporanKeuanganController::class, 'labaRugi'])->middleware('permission:simpanan.read')->name('laporan-keuangan.laba-rugi');
    Route::get('/laporan-keuangan/neraca', [LaporanKeuanganController::class, 'neraca'])->middleware('permission:simpanan.read')->name('laporan-keuangan.neraca');
    Route::get('/laporan-keuangan/arus-kas', [LaporanKeuanganController::class, 'arusKas'])->middleware('permission:simpanan.read')->name('laporan-keuangan.arus-kas');
    Route::get('/laporan-keuangan/simpanan', [LaporanKeuanganController::class, 'simpanan'])->middleware('permission:simpanan.read')->name('laporan-keuangan.simpanan');
    Route::get('/laporan-keuangan/pinjaman', [LaporanKeuanganController::class, 'pinjaman'])->middleware('permission:simpanan.read')->name('laporan-keuangan.pinjaman');

    // Arsip File Routes
    Route::get('/arsip-file', [ArsipFileController::class, 'index'])->middleware('permission:simpanan.read')->name('arsip-file.index');
    Route::post('/arsip-file', [ArsipFileController::class, 'store'])->middleware('permission:simpanan.create')->name('arsip-file.store');
    Route::get('/arsip-file/{id}/download', [ArsipFileController::class, 'download'])->middleware('permission:simpanan.read')->name('arsip-file.download');
    Route::delete('/arsip-file/{id}', [ArsipFileController::class, 'destroy'])->middleware('permission:simpanan.delete')->name('arsip-file.destroy');

    // Kegiatan Usaha Routes
    Route::get('/kegiatan-usaha', [KegiatanUsahaController::class, 'index'])->middleware('permission:simpanan.read')->name('kegiatan-usaha.index');
    Route::post('/kegiatan-usaha', [KegiatanUsahaController::class, 'store'])->middleware('permission:simpanan.create')->name('kegiatan-usaha.store');
    Route::get('/kegiatan-usaha/{id}', [KegiatanUsahaController::class, 'show'])->middleware('permission:simpanan.read')->name('kegiatan-usaha.show');
    Route::put('/kegiatan-usaha/{id}', [KegiatanUsahaController::class, 'update'])->middleware('permission:simpanan.update')->name('kegiatan-usaha.update');
    Route::delete('/kegiatan-usaha/{id}', [KegiatanUsahaController::class, 'destroy'])->middleware('permission:simpanan.delete')->name('kegiatan-usaha.destroy');

    // Transaksi Kegiatan Routes
    Route::get('/transaksi-kegiatan', [TransaksiKegiatanController::class, 'index'])->middleware('permission:simpanan.read')->name('transaksi-kegiatan.index');
    Route::get('/transaksi-kegiatan/{id}', [TransaksiKegiatanController::class, 'detail'])->middleware('permission:simpanan.read')->name('transaksi-kegiatan.detail');
    Route::post('/transaksi-kegiatan', [TransaksiKegiatanController::class, 'store'])->middleware('permission:simpanan.create')->name('transaksi-kegiatan.store');
    Route::delete('/transaksi-kegiatan/{id}', [TransaksiKegiatanController::class, 'destroy'])->middleware('permission:simpanan.delete')->name('transaksi-kegiatan.destroy');

    // Manajemen Berita Routes (Admin)
    Route::post('/berita/upload-image', [BeritaController::class, 'uploadImage'])->name('berita.upload-image');
    Route::get('/manajemen-berita', [BeritaController::class, 'index'])->middleware('permission:berita.read')->name('manajemen-berita.index');
    Route::get('/manajemen-berita/create', [BeritaController::class, 'create'])->middleware('permission:berita.create')->name('manajemen-berita.create');
    Route::post('/manajemen-berita', [BeritaController::class, 'store'])->middleware('permission:berita.create')->name('manajemen-berita.store');
    Route::get('/manajemen-berita/{berita}', [BeritaController::class, 'show'])->middleware('permission:berita.read')->name('manajemen-berita.show');
    Route::get('/manajemen-berita/{berita}/edit', [BeritaController::class, 'edit'])->middleware('permission:berita.edit')->name('manajemen-berita.edit');
    Route::put('/manajemen-berita/{berita}', [BeritaController::class, 'update'])->middleware('permission:berita.edit')->name('manajemen-berita.update');
    Route::delete('/manajemen-berita/{berita}', [BeritaController::class, 'destroy'])->middleware('permission:berita.delete')->name('manajemen-berita.destroy');

    // User Management Routes
    Route::resource('users', UserController::class);

    // Role & Permission Management Routes
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);

    // User Role Management Routes
    Route::get('/user-roles', [UserRoleController::class, 'index'])->name('user-roles.index');
    Route::get('/user-roles/{user}', [UserRoleController::class, 'show'])->name('user-roles.show');
    Route::post('/user-roles/{user}', [UserRoleController::class, 'update'])->name('user-roles.update');

    // Role Permission Management Routes
    Route::get('/role-permissions', [RolePermissionController::class, 'index'])->name('role-permissions.index');
    Route::get('/role-permissions/{role}', [RolePermissionController::class, 'show'])->name('role-permissions.show');
    Route::post('/role-permissions/{role}', [RolePermissionController::class, 'update'])->name('role-permissions.update');
});

require __DIR__.'/auth.php';
