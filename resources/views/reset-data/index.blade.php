@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-trash-restore"></i> Reset Data CPL
        </h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Mahasiswa
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalMahasiswa) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Mahasiswa dengan Data CPL
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($mahasiswaWithCPL) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Histori Analisis
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalAnalysis) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-history fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Persentase Data CPL
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $totalMahasiswa > 0 ? number_format(($mahasiswaWithCPL / $totalMahasiswa) * 100, 1) : 0 }}%
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-percentage fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reset Data Card -->
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-danger">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-exclamation-triangle"></i> Reset Semua Data Mahasiswa
                    </h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-danger" role="alert">
                        <h6 class="alert-heading"><i class="fas fa-exclamation-triangle"></i> Peringatan Penting</h6>
                        <p class="mb-2">Fitur ini akan menghapus <strong>SEMUA DATA MAHASISWA</strong> dari database, termasuk:</p>
                        <ul class="mb-2">
                            <li><strong>Nama, NIM, dan angkatan mahasiswa</strong></li>
                            <li><strong>Semua nilai CPL mahasiswa</strong></li>
                            <li>Tabel mahasiswa akan menjadi kosong total</li>
                        </ul>
                        <p class="mb-2"><strong>Yang tetap tersimpan:</strong></p>
                        <ul class="mb-2">
                            <li><strong>Data histori analisis akan tetap tersimpan</strong></li>
                            <li><strong>Master data CPL tidak akan terhapus</strong></li>
                            <li><strong>Data profil lulusan tidak akan terhapus</strong></li>
                        </ul>
                        <p class="mb-0">Setelah reset, Anda dapat mengimpor file data mahasiswa yang baru.</p>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="border rounded p-3 bg-light">
                                <h6 class="text-success"><i class="fas fa-check"></i> Yang Akan Dipertahankan:</h6>
                                <ul class="mb-0">
                                    <li>Histori analisis apriori</li>
                                    <li>Master data CPL</li>
                                    <li>Data profil lulusan</li>
                                    <li>Struktur database</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded p-3 bg-light">
                                <h6 class="text-danger"><i class="fas fa-times"></i> Yang Akan Dihapus:</h6>
                                <ul class="mb-0">
                                    <li><strong>SEMUA data mahasiswa</strong></li>
                                    <li>Nama, NIM, angkatan</li>
                                    <li>Semua nilai CPL</li>
                                    <li>Tabel mahasiswa menjadi kosong</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    @if($totalMahasiswa > 0)
                        <div class="text-center">
                            <a href="{{ route('reset-data.confirm') }}" class="btn btn-danger btn-lg">
                                <i class="fas fa-trash-alt"></i> Reset Semua Data Mahasiswa
                            </a>
                        </div>
                    @else
                        <div class="text-center">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> Tidak ada data mahasiswa yang perlu direset.
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 