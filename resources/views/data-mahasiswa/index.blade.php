@extends('layouts.app')

@section('title', 'Data Mahasiswa - SiAC')
@section('page-title', 'Analisis CPL')

@push('styles')
<style>
.compact-table {
    font-size: 0.8rem;
}
.compact-table th,
.compact-table td {
    padding: 4px 6px !important;
    vertical-align: middle;
}
.compact-table .btn {
    padding: 2px 6px;
    font-size: 0.7rem;
}
.compact-table .badge {
    font-size: 0.65rem;
    padding: 2px 4px;
}
.table-responsive {
    border-radius: 8px;
}
/* Ensure table fits better on smaller screens */
@media (max-width: 1400px) {
    .table-responsive table {
        font-size: 0.75rem !important;
    }
    .table-responsive th,
    .table-responsive td {
        padding: 4px 2px !important;
    }
}

/* Optimize table layout */
.compact-table thead th {
    white-space: nowrap;
    border-bottom: 2px solid #dee2e6;
}

.compact-table tbody td {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Fix dropdown in table */
.table .dropdown-menu {
    font-size: 0.8rem;
    min-width: 120px;
}

/* Responsive adjustments */
@media (max-width: 1200px) {
    .compact-table .badge {
        font-size: 0.55rem !important;
        padding: 1px 2px !important;
    }
    .compact-table th,
    .compact-table td {
        padding: 3px 1px !important;
    }
}

/* Pagination styling to match Telkom theme */
.pagination {
    margin-bottom: 0;
    display: flex;
    padding-left: 0;
    list-style: none;
}

.page-link {
    color: #E53E3E !important;
    background-color: #fff;
    border: 1px solid #dee2e6;
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
    text-decoration: none;
    position: relative;
    display: block;
    transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out;
}

.page-link:hover {
    color: #C53030 !important;
    background-color: #f8f9fa;
    border-color: #C53030;
    text-decoration: none;
}

.page-link:focus {
    z-index: 3;
    outline: 0;
    box-shadow: 0 0 0 0.2rem rgba(229, 62, 62, 0.25);
}

.page-item.active .page-link {
    background-color: #E53E3E !important;
    border-color: #E53E3E !important;
    color: white !important;
    z-index: 3;
}

.page-item.disabled .page-link {
    color: #6c757d !important;
    background-color: #fff;
    border-color: #dee2e6;
    pointer-events: none;
}

/* Fix pagination spacing */
.pagination .page-item {
    margin: 0 1px;
}

.pagination .page-item:first-child .page-link {
    margin-left: 0;
    border-top-left-radius: 0.375rem;
    border-bottom-left-radius: 0.375rem;
}

.pagination .page-item:last-child .page-link {
    border-top-right-radius: 0.375rem;
    border-bottom-right-radius: 0.375rem;
}

.pagination .page-link {
    border-radius: 0.375rem;
    margin: 0 1px;
}

/* Ensure pagination is visible */
.pagination-wrapper {
    background-color: transparent;
    padding: 10px 0;
}

/* Additional pagination fixes */
nav[aria-label="pagination"] {
    display: block;
}

.pagination li {
    display: inline-block;
}

/* Override any conflicting styles */
.card-body .pagination {
    margin: 0;
    padding: 0;
}

.card-body .pagination .page-link {
    border: 1px solid #dee2e6 !important;
    background-color: #fff !important;
}

/* Responsive pagination */
@media (max-width: 768px) {
    .pagination {
        justify-content: center;
        flex-wrap: wrap;
    }
    
    .pagination .page-item {
        margin: 2px;
    }
    
    .pagination .page-link {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
    
    /* Hide some pagination items on mobile */
    .pagination .page-item:not(.active):not(:first-child):not(:last-child):not(:nth-child(2)):not(:nth-last-child(2)) {
        display: none;
    }
}
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Data Mahasiswa</h4>
    <div>
        <button type="button" class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#uploadModal">
            <i class="fas fa-upload"></i> Choose file
        </button>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2" style="border-left: 4px solid #4CAF50;">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Mahasiswa
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalMahasiswa }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2" style="border-left: 4px solid #1cc88a;">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Data Lengkap
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $dataLengkap }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2" style="border-left: 4px solid #36b9cc;">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Total Angkatan
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalAngkatan }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-graduation-cap fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2" style="border-left: 4px solid #f6c23e;">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Rata-rata CPL
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($avgCpl, 1) }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row mb-4">
    <!-- Angkatan Distribution Chart -->
    <div class="col-xl-6 col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-white">
                    <i class="fas fa-chart-bar"></i> Distribusi Mahasiswa per Angkatan
                </h6>
            </div>
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="angkatanChart" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- CPL Performance Chart -->
    <div class="col-xl-6 col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-white">
                    <i class="fas fa-chart-line"></i> Kinerja CPL Keseluruhan
                </h6>
            </div>
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="cplPerformanceChart" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('data-mahasiswa.index') }}" class="row g-3">
            <div class="col-md-3">
                <label for="angkatan" class="form-label">Filter Angkatan</label>
                <select name="angkatan" id="angkatan" class="form-select">
                    <option value="">Semua Angkatan</option>
                    @foreach($angkatans as $angkatan)
                        <option value="{{ $angkatan }}" {{ request('angkatan') == $angkatan ? 'selected' : '' }}>
                            {{ $angkatan }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label for="search" class="form-label">Cari Mahasiswa</label>
                <input type="text" name="search" id="search" class="form-control" 
                       placeholder="NIM atau Nama..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">&nbsp;</label>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Filter
                    </button>
                    <a href="{{ route('data-mahasiswa.index') }}" class="btn btn-secondary">
                        <i class="fas fa-refresh"></i> Reset
                    </a>
                </div>
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <div class="dropdown">
                    <button class="btn btn-info dropdown-toggle w-100" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-tools"></i> Tools
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <button class="dropdown-item" onclick="normalizeData()">
                                <i class="fas fa-calculator"></i> Normalisasikan
                            </button>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('data-mahasiswa.download') }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}">
                                <i class="fas fa-download"></i> Download Data
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Data Table -->
<div class="card">
    <div class="card-header">
        <h6 class="m-0 font-weight-bold text-white">
            Daftar Mahasiswa
            @if($mahasiswas->total() > 0)
                <span class="badge bg-light text-dark ms-2">{{ $mahasiswas->total() }} mahasiswa</span>
            @endif
        </h6>
    </div>
    <div class="card-body p-0">
        @if($mahasiswas->count() > 0)
            <div class="table-responsive table-container" style="max-height: 600px; overflow-x: auto; overflow-y: visible;">
                <table class="table table-bordered table-hover mb-0 compact-table">
                    <thead class="table-light sticky-top">
                        <tr>
                            <th style="width: 35px; min-width: 35px;" class="text-center">No</th>
                            <th style="width: 120px; min-width: 120px;">Mahasiswa</th>
                            @foreach($cpls->sortBy(function($cpl) { return (int)str_replace('CPL ', '', $cpl->kode_cpl); }) as $cpl)
                                @php
                                    $headerStyle = match($cpl->kategori) {
                                        'Kemampuan penguasaan teknik umum dan TIK dalam upaya implementasi keilmuaan Teknik Industri' => 'background-color: #4CAF50; color: white;',
                                        'Kemampuan perancangan dan penelitian pada objek sistem integrasian TIK dalam upaya implementasi keilmuaan Teknik Industri' => 'background-color: #2196F3; color: white;',
                                        'Kemampuan perumusan solusi permasalahan pada objek Teknik Industri' => 'background-color: #FFC107; color: black;',
                                        'Penguasaan aspek non-akademis pendukung' => 'background-color: #F44336; color: white;',
                                        'Penguasaan dan penerapan ilmu dasar sains dan matematik' => 'background-color: #9C27B0; color: white;',
                                        'Penguasaan keilmuaan pendukung kewirausahaan' => 'background-color: #FF9800; color: white;',
                                        default => 'background-color: #9E9E9E; color: white;'
                                    };
                                @endphp
                                <th style="width: 45px; min-width: 45px; {{ $headerStyle }}" class="text-center" title="{{ $cpl->kode_cpl }} - {{ $cpl->kategori }}">
                                    <div style="font-size: 0.65rem; font-weight: bold;">{{ str_replace('CPL ', '', $cpl->kode_cpl) }}</div>
                                </th>
                            @endforeach
                            <th style="width: 50px; min-width: 50px;" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mahasiswas as $index => $mahasiswa)
                        <tr>
                            <td class="text-center">{{ $mahasiswas->firstItem() + $index }}</td>
                            <td>
                                <div class="fw-bold text-truncate" style="max-width: 100px; font-size: 0.75rem;" title="{{ $mahasiswa->nim }}">{{ $mahasiswa->nim }}</div>
                                <small class="text-muted text-truncate d-block" style="max-width: 100px; font-size: 0.65rem;" title="{{ $mahasiswa->nama }}">{{ $mahasiswa->nama }}</small>
                                @if($mahasiswa->angkatan)
                                    <span class="badge bg-secondary" style="font-size: 0.55rem; padding: 1px 3px;">{{ $mahasiswa->angkatan }}</span>
                                @endif
                            </td>
                            @foreach($cpls->sortBy(function($cpl) { return (int)str_replace('CPL ', '', $cpl->kode_cpl); }) as $cpl)
                                @php
                                    $nilai = $mahasiswa->nilai_cpl[$cpl->kode_cpl] ?? null;
                                    $kategoriNilai = $nilai ? $mahasiswa->categorizeNilai($nilai) : 'Missing';
                                    
                                    // Warna berdasarkan kategori CPL, bukan kategori nilai
                                    $badgeStyle = match($cpl->kategori) {
                                        'Kemampuan penguasaan teknik umum dan TIK dalam upaya implementasi keilmuaan Teknik Industri' => 'background-color: #4CAF50; color: white;',
                                        'Kemampuan perancangan dan penelitian pada objek sistem integrasian TIK dalam upaya implementasi keilmuaan Teknik Industri' => 'background-color: #2196F3; color: white;',
                                        'Kemampuan perumusan solusi permasalahan pada objek Teknik Industri' => 'background-color: #FFC107; color: black;',
                                        'Penguasaan aspek non-akademis pendukung' => 'background-color: #F44336; color: white;',
                                        'Penguasaan dan penerapan ilmu dasar sains dan matematik' => 'background-color: #9C27B0; color: white;',
                                        'Penguasaan keilmuaan pendukung kewirausahaan' => 'background-color: #FF9800; color: white;',
                                        default => 'background-color: #9E9E9E; color: white;'
                                    };
                                @endphp
                                <td class="text-center">
                                    @if($nilai)
                                        <span class="badge" style="font-size: 0.6rem; padding: 1px 3px; {{ $badgeStyle }}" title="{{ $cpl->kode_cpl }} - {{ $kategoriNilai }} ({{ $nilai }})">{{ number_format($nilai, 0) }}</span>
                                    @else
                                        <span class="badge" style="font-size: 0.6rem; padding: 1px 3px; background-color: #9E9E9E; color: white;" title="{{ $cpl->kode_cpl }} - Missing">-</span>
                                    @endif
                                </td>
                            @endforeach
                                        <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('data-mahasiswa.show', $mahasiswa) }}" class="btn btn-sm btn-outline-info" 
                                       title="Lihat Detail" style="font-size: 0.7rem; padding: 4px 8px;">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('data-mahasiswa.edit', $mahasiswa) }}" class="btn btn-sm btn-outline-warning" 
                                       title="Edit" style="font-size: 0.7rem; padding: 4px 8px;">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button class="btn btn-sm btn-outline-danger" 
                                            title="Hapus" style="font-size: 0.7rem; padding: 4px 8px;"
                                            onclick="confirmDelete({{ $mahasiswa->id }}, '{{ $mahasiswa->nim }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="row mt-3 px-3 pb-3">
                <div class="col-md-6 d-flex align-items-center">
                    <small class="text-muted">
                        Menampilkan {{ $mahasiswas->firstItem() }} sampai {{ $mahasiswas->lastItem() }} 
                        dari {{ $mahasiswas->total() }} mahasiswa
                    </small>
                </div>
                <div class="col-md-6 d-flex justify-content-end mt-2 mt-md-0">
                    <div class="pagination-wrapper">
                        {{ $mahasiswas->appends(request()->query())->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                <h5>Belum ada data mahasiswa</h5>
                <p class="text-muted">Mulai dengan mengupload file Excel</p>
                <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#uploadModal">
                    <i class="fas fa-upload"></i> Upload Excel
                </button>
                <a href="{{ route('data-mahasiswa.create') }}" class="btn btn-success">
                    <i class="fas fa-plus"></i> Tambah Manual
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Upload Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-white" id="uploadModalLabel">Upload File Excel</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('data-mahasiswa.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="file" class="form-label">Pilih File Excel (.xlsx, .xls)</label>
                        <input type="file" class="form-control" id="file" name="file" 
                               accept=".xlsx,.xls" required>
                        <div class="form-text">
                            File harus berformat Excel dengan kolom: No, NIM, CPL 1, CPL 2, dst.
                        </div>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Format File:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Kolom pertama: No</li>
                            <li>Kolom kedua: NIM</li>
                            <li>Kolom ketiga: Nama (opsional)</li>
                            <li>Kolom selanjutnya: CPL 1, CPL 2, dst.</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload"></i> Upload
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus data mahasiswa dengan NIM: <strong id="deleteNim"></strong>?</p>
                <p class="text-danger"><i class="fas fa-exclamation-triangle"></i> Tindakan ini tidak dapat dibatalkan!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Table styling improvements */
.table-container {
    position: relative;
    overflow-x: auto;
}

.table-container .table {
    margin-bottom: 0;
}

.compact-table td {
    vertical-align: middle;
    padding: 0.5rem 0.25rem;
}

.compact-table th {
    vertical-align: middle;
    padding: 0.75rem 0.25rem;
}

/* Button group spacing */
.btn-group-actions {
    display: flex;
    gap: 2px;
    justify-content: center;
}

/* Button styling for table actions */
.btn-sm {
    transition: all 0.15s ease-in-out;
}

.btn-sm:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
</style>
@endpush

@push('scripts')
<script>
function confirmDelete(id, nim) {
    document.getElementById('deleteNim').textContent = nim;
    document.getElementById('deleteForm').action = `/data-mahasiswa/${id}`;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

function normalizeData() {
    const angkatan = document.getElementById('angkatan').value;
    
    if (confirm('Apakah Anda yakin ingin menormalisasi data? Proses ini akan mengkategorikan nilai CPL.')) {
        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        if (angkatan) {
            formData.append('angkatan', angkatan);
        }
        
        fetch('{{ route("data-mahasiswa.normalize") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Normalisasi berhasil: ' + data.message);
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi error saat normalisasi data');
        });
    }
}

// Auto-submit filter form on select change
document.getElementById('angkatan').addEventListener('change', function() {
    this.form.submit();
});

// Charts
document.addEventListener('DOMContentLoaded', function() {
    // Angkatan Distribution Chart
    const ctx1 = document.getElementById('angkatanChart').getContext('2d');
    const angkatanChart = new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartData['angkatan']['labels']) !!},
            datasets: [{
                label: 'Jumlah Mahasiswa',
                data: {!! json_encode($chartData['angkatan']['data']) !!},
                backgroundColor: 'rgba(76, 175, 80, 0.8)',
                borderColor: 'rgba(76, 175, 80, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                title: {
                    display: true,
                    text: 'Distribusi Mahasiswa per Angkatan'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // CPL Performance Chart
    const ctx2 = document.getElementById('cplPerformanceChart').getContext('2d');
    const cplChart = new Chart(ctx2, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartData['cpl']['labels']) !!},
            datasets: [{
                label: 'Rata-rata Nilai',
                data: {!! json_encode($chartData['cpl']['data']) !!},
                backgroundColor: 'rgba(33, 150, 243, 0.2)',
                borderColor: 'rgba(33, 150, 243, 1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                title: {
                    display: true,
                    text: 'Kinerja Rata-rata CPL'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        stepSize: 10
                    }
                }
            }
        }
    });
});
</script>
@endpush 