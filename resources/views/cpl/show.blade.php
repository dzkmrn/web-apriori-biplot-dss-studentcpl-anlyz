@extends('layouts.app')

@section('title', 'Detail CPL - SiAC')
@section('page-title', 'Analisis CPL')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Detail CPL</h4>
    <div>
        <a href="{{ route('cpl.edit', $cpl) }}" class="btn btn-warning me-2">
            <i class="fas fa-edit"></i> Edit
        </a>
        <a href="{{ route('cpl.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-white">
                    <i class="fas fa-info-circle"></i> Informasi CPL
                </h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td width="20%"><strong>Kode CPL:</strong></td>
                        <td>
                            <span class="fw-bold text-primary fs-5">{{ $cpl->kode_cpl }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Kategori:</strong></td>
                        <td>
                            @php
                                $badgeClass = match($cpl->kategori) {
                                    'Sikap' => 'bg-success',
                                    'Pengetahuan' => 'bg-info',
                                    'Keterampilan Umum' => 'bg-warning',
                                    'Keterampilan Khusus' => 'bg-danger',
                                    default => 'bg-secondary'
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }} fs-6">{{ $cpl->kategori }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Status:</strong></td>
                        <td>
                            <span class="badge {{ $cpl->is_active ? 'bg-success' : 'bg-secondary' }} fs-6">
                                {{ $cpl->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Dibuat:</strong></td>
                        <td>{{ $cpl->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Diperbarui:</strong></td>
                        <td>{{ $cpl->updated_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-white">
                    <i class="fas fa-file-text"></i> Deskripsi CPL
                </h6>
            </div>
            <div class="card-body">
                <div class="p-3 bg-light rounded">
                    {{ $cpl->deskripsi }}
                </div>
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
                    <a href="{{ route('cpl.edit', $cpl) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit CPL
                    </a>
                    
                    <form id="toggleForm" action="{{ route('cpl.toggle-active', $cpl) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn {{ $cpl->is_active ? 'btn-secondary' : 'btn-success' }} w-100">
                            <i class="fas fa-toggle-{{ $cpl->is_active ? 'off' : 'on' }}"></i> 
                            {{ $cpl->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                        </button>
                    </form>
                    
                    <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                        <i class="fas fa-trash"></i> Hapus CPL
                    </button>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-white">
                    <i class="fas fa-chart-bar"></i> Statistik
                </h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="border rounded p-2">
                            <h4 class="text-primary mb-0">{{ $mahasiswaCount }}</h4>
                            <small class="text-muted">Mahasiswa</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border rounded p-2">
                            <h4 class="text-success mb-0">{{ $analysisCount }}</h4>
                            <small class="text-muted">Analisis</small>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <small class="text-muted">
                        <i class="fas fa-info-circle"></i> 
                        CPL ini digunakan dalam {{ $mahasiswaCount }} data mahasiswa 
                        dan {{ $analysisCount }} analisis.
                    </small>
                </div>
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
                <p>Apakah Anda yakin ingin menghapus CPL: <strong>{{ $cpl->kode_cpl }}</strong>?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Peringatan:</strong>
                    <ul class="mb-0 mt-2">
                        <li>Data nilai mahasiswa untuk CPL ini akan terpengaruh</li>
                        <li>Analisis yang menggunakan CPL ini mungkin tidak valid</li>
                        <li>Data ini tidak dapat dikembalikan setelah dihapus</li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('cpl.destroy', $cpl) }}" method="POST" style="display: inline;">
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

document.addEventListener('DOMContentLoaded', function() {
    // Toggle form confirmation
    document.getElementById('toggleForm').addEventListener('submit', function(e) {
        const isActive = {{ $cpl->is_active ? 'true' : 'false' }};
        const action = isActive ? 'menonaktifkan' : 'mengaktifkan';
        
        if (!confirm(`Apakah Anda yakin ingin ${action} CPL ini?`)) {
            e.preventDefault();
        }
    });
});
</script>
@endpush 