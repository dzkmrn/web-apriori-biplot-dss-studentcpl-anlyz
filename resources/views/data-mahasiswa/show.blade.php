@extends('layouts.app')

@section('title', 'Detail Data Mahasiswa - SiAC')
@section('page-title', 'Analisis CPL')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Detail Data Mahasiswa</h4>
    <div>
        <a href="{{ route('data-mahasiswa.edit', $mahasiswa) }}" class="btn btn-warning me-2">
            <i class="fas fa-edit"></i> Edit
        </a>
        <a href="{{ route('data-mahasiswa.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-white">
                    <i class="fas fa-user"></i> Informasi Mahasiswa
                </h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td width="20%"><strong>NIM:</strong></td>
                        <td>
                            <span class="fw-bold text-primary fs-5">{{ $mahasiswa->nim }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Angkatan:</strong></td>
                        <td>
                            <span class="badge bg-info fs-6">{{ $mahasiswa->angkatan }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Dibuat:</strong></td>
                        <td>{{ $mahasiswa->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Diperbarui:</strong></td>
                        <td>{{ $mahasiswa->updated_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-white">
                    <i class="fas fa-chart-bar"></i> Nilai CPL
                </h6>
            </div>
            <div class="card-body">
                @php
                    $cplValues = is_array($mahasiswa->nilai_cpl) ? $mahasiswa->nilai_cpl : json_decode($mahasiswa->nilai_cpl, true) ?? [];
                @endphp
                
                @if(!empty($cplValues))
                    <div class="row">
                        @foreach($cpls as $cpl)
                            @if(isset($cplValues[$cpl->kode_cpl]) && $cplValues[$cpl->kode_cpl] !== null)
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card border-light">
                                        <div class="card-body p-3">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <h6 class="mb-0">{{ $cpl->kode_cpl }}</h6>
                                                @php
                                                    $nilai = $cplValues[$cpl->kode_cpl];
                                                    $kategori = $mahasiswa->kategorikanCpl($nilai);
                                                    $badgeClass = match($kategori) {
                                                        'Baik' => 'bg-success',
                                                        'Cukup' => 'bg-warning',
                                                        'Kurang' => 'bg-danger',
                                                        default => 'bg-secondary'
                                                    };
                                                @endphp
                                                <span class="badge {{ $badgeClass }}">{{ $kategori }}</span>
                                            </div>
                                            <div class="mb-2">
                                                <span class="h4 text-primary">{{ number_format($nilai, 1) }}</span>
                                                <small class="text-muted">/100</small>
                                            </div>
                                            <div class="progress" style="height: 8px;">
                                                <div class="progress-bar {{ str_replace('bg-', 'bg-', $badgeClass) }}" 
                                                     style="width: {{ $nilai }}%"></div>
                                            </div>
                                            <small class="text-muted">{{ $cpl->kategori }}</small>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                    
                    <!-- Ringkasan Nilai -->
                    <div class="mt-4">
                        <h6>Ringkasan Nilai CPL:</h6>
                        <div class="row text-center">
                            @php
                                $summary = $mahasiswa->getRingkasanNilai();
                            @endphp
                            <div class="col-3">
                                <div class="border rounded p-2">
                                    <h5 class="text-success mb-0">{{ $summary['baik'] }}</h5>
                                    <small class="text-muted">Baik</small>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="border rounded p-2">
                                    <h5 class="text-warning mb-0">{{ $summary['cukup'] }}</h5>
                                    <small class="text-muted">Cukup</small>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="border rounded p-2">
                                    <h5 class="text-danger mb-0">{{ $summary['kurang'] }}</h5>
                                    <small class="text-muted">Kurang</small>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="border rounded p-2">
                                    <h5 class="text-muted mb-0">{{ $summary['missing'] }}</h5>
                                    <small class="text-muted">Kosong</small>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-chart-bar fa-3x text-muted mb-3"></i>
                        <h5>Belum ada nilai CPL</h5>
                        <p class="text-muted">Data nilai CPL belum diinput untuk mahasiswa ini</p>
                        <a href="{{ route('data-mahasiswa.edit', $mahasiswa) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Input Nilai CPL
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-white">
                    <i class="fas fa-cogs"></i> Aksi
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('data-mahasiswa.edit', $mahasiswa) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit Data
                    </a>
                    
                    <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                        <i class="fas fa-trash"></i> Hapus Data
                    </button>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-white">
                    <i class="fas fa-chart-pie"></i> Statistik CPL
                </h6>
            </div>
            <div class="card-body">
                @php
                    $totalCpl = $cpls->count();
                    $cplTerisi = collect($cplValues)->filter(function($value) {
                        return $value !== null && $value !== '';
                    })->count();
                    $persentaseTerisi = $totalCpl > 0 ? round(($cplTerisi / $totalCpl) * 100, 1) : 0;
                @endphp
                
                <div class="text-center mb-3">
                    <h4 class="text-primary mb-0">{{ $persentaseTerisi }}%</h4>
                    <small class="text-muted">CPL Terisi</small>
                </div>
                
                <div class="progress mb-3" style="height: 10px;">
                    <div class="progress-bar bg-primary" style="width: {{ $persentaseTerisi }}%"></div>
                </div>
                
                <div class="row text-center">
                    <div class="col-6">
                        <div class="border rounded p-2">
                            <h5 class="text-success mb-0">{{ $cplTerisi }}</h5>
                            <small class="text-muted">Terisi</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border rounded p-2">
                            <h5 class="text-muted mb-0">{{ $totalCpl - $cplTerisi }}</h5>
                            <small class="text-muted">Kosong</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-4 border-info">
            <div class="card-header bg-light">
                <h6 class="mb-0 text-info">
                    <i class="fas fa-lightbulb"></i> Tips
                </h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        <small>Pastikan semua CPL sudah terisi</small>
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        <small>Update status sesuai kondisi terkini</small>
                    </li>
                    <li class="mb-0">
                        <i class="fas fa-check text-success me-2"></i>
                        <small>Periksa akurasi data secara berkala</small>
                    </li>
                </ul>
            </div>
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
                <p>Apakah Anda yakin ingin menghapus data mahasiswa: <strong>{{ $mahasiswa->nama }} ({{ $mahasiswa->nim }})</strong>?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Peringatan:</strong>
                    <ul class="mb-0 mt-2">
                        <li>Data ini tidak dapat dikembalikan setelah dihapus</li>
                        <li>Analisis yang menggunakan data ini mungkin terpengaruh</li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('data-mahasiswa.destroy', $mahasiswa) }}" method="POST" style="display: inline;">
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

@push('scripts')
<script>
function confirmDelete() {
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@endpush