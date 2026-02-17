@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-exclamation-triangle text-danger"></i> Konfirmasi Reset Data Mahasiswa
        </h1>
        <a href="{{ route('reset-data.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- Confirmation Card -->
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow mb-4 border-danger">
                <div class="card-header py-3 bg-danger">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-exclamation-triangle"></i> PERINGATAN: Konfirmasi Reset Data
                    </h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-danger" role="alert">
                        <h5 class="alert-heading"><i class="fas fa-skull-crossbones"></i> TINDAKAN TIDAK DAPAT DIBATALKAN!</h5>
                        <p class="mb-0">Anda akan menghapus <strong>SEMUA {{ number_format($totalMahasiswa) }} DATA MAHASISWA</strong> dari database. Tabel mahasiswa akan menjadi kosong total. Pastikan Anda benar-benar yakin sebelum melanjutkan.</p>
                    </div>

                    <!-- Statistics -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h3 class="text-primary">{{ number_format($totalMahasiswa) }}</h3>
                                    <p class="mb-0">Total Mahasiswa</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h3 class="text-danger">{{ number_format($totalMahasiswa) }}</h3>
                                    <p class="mb-0">Data Mahasiswa<br><small>(akan dihapus semua)</small></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Detailed Information -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6><i class="fas fa-info-circle text-info"></i> Detail Proses Reset:</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Item</th>
                                            <th>Status</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><i class="fas fa-users text-danger"></i> Data Mahasiswa</td>
                                            <td><span class="badge bg-danger">AKAN DIHAPUS</span></td>
                                            <td><strong>SEMUA data mahasiswa akan dihapus: nama, NIM, angkatan, nilai CPL</strong></td>
                                        </tr>
                                        <tr>
                                            <td><i class="fas fa-database text-danger"></i> Tabel Mahasiswa</td>
                                            <td><span class="badge bg-danger">AKAN KOSONG</span></td>
                                            <td>Tabel akan menjadi kosong total, siap untuk import data baru</td>
                                        </tr>
                                        <tr>
                                            <td><i class="fas fa-history text-success"></i> Histori Analisis</td>
                                            <td><span class="badge bg-success">AMAN</span></td>
                                            <td>Semua hasil analisis apriori tetap tersimpan</td>
                                        </tr>
                                        <tr>
                                            <td><i class="fas fa-list text-success"></i> Master CPL</td>
                                            <td><span class="badge bg-success">AMAN</span></td>
                                            <td>Daftar CPL dan kategori tidak berubah</td>
                                        </tr>
                                        <tr>
                                            <td><i class="fas fa-graduation-cap text-success"></i> Profil Lulusan</td>
                                            <td><span class="badge bg-success">AMAN</span></td>
                                            <td>Data profil lulusan tetap tersimpan</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Confirmation Form -->
                    <form method="POST" action="{{ route('reset-data.reset') }}" id="resetForm">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="confirmReset" required>
                                    <label class="form-check-label" for="confirmReset">
                                        <strong>Saya memahami konsekuensi dari tindakan ini dan yakin ingin menghapus SEMUA data mahasiswa</strong>
                                    </label>
                                </div>
                                
                                <div class="form-check mb-4">
                                    <input class="form-check-input" type="checkbox" id="confirmBackup" required>
                                    <label class="form-check-label" for="confirmBackup">
                                        <strong>Saya telah memastikan backup data tersedia jika diperlukan</strong>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="text-center">
                            <button type="button" class="btn btn-secondary btn-lg me-3" onclick="window.history.back()">
                                <i class="fas fa-times"></i> Batal
                            </button>
                            <button type="submit" class="btn btn-danger btn-lg" id="resetButton" disabled>
                                <i class="fas fa-trash-alt"></i> Ya, Hapus Semua Data Mahasiswa
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const confirmReset = document.getElementById('confirmReset');
    const confirmBackup = document.getElementById('confirmBackup');
    const resetButton = document.getElementById('resetButton');
    
    function toggleResetButton() {
        resetButton.disabled = !(confirmReset.checked && confirmBackup.checked);
    }
    
    confirmReset.addEventListener('change', toggleResetButton);
    confirmBackup.addEventListener('change', toggleResetButton);
    
    document.getElementById('resetForm').addEventListener('submit', function(e) {
        if (!confirm('PERINGATAN TERAKHIR!\n\nAnda akan menghapus SEMUA ' + {{ $totalMahasiswa }} + ' data mahasiswa dari database.\n\nTabel mahasiswa akan menjadi kosong total!\n\nApakah Anda benar-benar yakin?')) {
            e.preventDefault();
        }
    });
});
</script>
@endsection 