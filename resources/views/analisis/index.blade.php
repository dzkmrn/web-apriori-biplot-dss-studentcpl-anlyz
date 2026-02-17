@extends('layouts.app')

@section('title', 'Analisis Hasil - SiAC')
@section('page-title', 'Analisis CPL')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Analisis Hasil</h4>
    <a href="{{ route('analisis.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Buat Analisis Baru
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h6 class="m-0 font-weight-bold text-white">
            <i class="fas fa-chart-line"></i> Riwayat Analisis Apriori
        </h6>
    </div>
    <div class="card-body">
        @if($dataHistori->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">No</th>
                            <th width="10%">Tanggal</th>
                            <th width="10%">Angkatan</th>
                            <th width="25%">Deskripsi</th>
                            <th width="10%">Total Rules</th>
                            <th width="10%">Min Support</th>
                            <th width="15%">Min Confidence</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dataHistori as $index => $analisis)
                        <tr>
                            <td>{{ $dataHistori->firstItem() + $index }}</td>
                            <td>{{ $analisis->tanggal->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge bg-info">{{ $analisis->angkatan }}</span>
                            </td>
                            <td>{{ $analisis->deskripsi }}</td>
                            <td>
                                <span class="badge bg-primary">{{ $analisis->total_rules }}</span>
                            </td>
                            <td>{{ number_format($analisis->min_support * 100, 1) }}%</td>
                            <td>{{ number_format($analisis->min_confidence * 100, 1) }}%</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('analisis.show', $analisis) }}" 
                                       class="btn btn-sm btn-success" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger" 
                                            onclick="confirmDelete({{ $analisis->id }}, '{{ $analisis->deskripsi }}')" 
                                            title="Hapus">
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
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    Menampilkan {{ $dataHistori->firstItem() }} sampai {{ $dataHistori->lastItem() }} 
                    dari {{ $dataHistori->total() }} analisis
                </div>
                <div>
                    {{ $dataHistori->links() }}
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                <h5>Belum ada analisis yang dilakukan</h5>
                <p class="text-muted">Buat analisis baru untuk melihat association rules dari data CPL mahasiswa</p>
                <a href="{{ route('analisis.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Buat Analisis Pertama
                </a>
            </div>
        @endif
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
                <p>Apakah Anda yakin ingin menghapus analisis: <strong id="deleteDesc"></strong>?</p>
                <p class="text-danger"><i class="fas fa-exclamation-triangle"></i> Semua data association rules akan ikut terhapus!</p>
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

@push('scripts')
<script>
function confirmDelete(id, desc) {
    document.getElementById('deleteDesc').textContent = desc;
    document.getElementById('deleteForm').action = `/analisis/${id}`;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@endpush 