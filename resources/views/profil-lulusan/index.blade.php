@extends('layouts.app')

@section('title', 'Data Profil Lulusan - SiAC')
@section('page-title', 'Analisis CPL')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Data Profil Lulusan</h4>
    <a href="{{ route('profil-lulusan.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Profil Lulusan
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h6 class="m-0 font-weight-bold text-white">
            <i class="fas fa-graduation-cap"></i> Daftar Profil Lulusan
        </h6>
    </div>
    <div class="card-body">
        @if($profilLulusans->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">No</th>
                            <th width="15%">Kode Profil</th>
                            <th width="25%">Nama Profil</th>
                            <th width="40%">Deskripsi</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($profilLulusans as $index => $profil)
                        <tr>
                            <td>{{ $profilLulusans->firstItem() + $index }}</td>
                            <td>
                                <span class="fw-bold text-primary">{{ $profil->kode_profil }}</span>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $profil->nama_profil }}</div>
                            </td>
                            <td>
                                <div class="text-truncate" style="max-width: 350px;" title="{{ $profil->deskripsi }}">
                                    {{ $profil->deskripsi }}
                                </div>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('profil-lulusan.show', $profil) }}" 
                                       class="btn btn-sm btn-info" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('profil-lulusan.edit', $profil) }}" 
                                       class="btn btn-sm btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger" 
                                            onclick="confirmDelete({{ $profil->id }}, '{{ $profil->nama_profil }}')" 
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
                    Menampilkan {{ $profilLulusans->firstItem() }} sampai {{ $profilLulusans->lastItem() }} 
                    dari {{ $profilLulusans->total() }} profil lulusan
                </div>
                <div>
                    {{ $profilLulusans->links() }}
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-graduation-cap fa-3x text-muted mb-3"></i>
                <h5>Belum ada data profil lulusan</h5>
                <p class="text-muted">Tambahkan profil lulusan untuk mendefinisikan target kompetensi alumni</p>
                <a href="{{ route('profil-lulusan.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Profil Lulusan Pertama
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
                <p>Apakah Anda yakin ingin menghapus profil lulusan: <strong id="deleteProfil"></strong>?</p>
                <p class="text-danger"><i class="fas fa-exclamation-triangle"></i> Data ini tidak dapat dikembalikan setelah dihapus!</p>
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
function confirmDelete(id, namaProfil) {
    document.getElementById('deleteProfil').textContent = namaProfil;
    document.getElementById('deleteForm').action = `/profil-lulusan/${id}`;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@endpush 