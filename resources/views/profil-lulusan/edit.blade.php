@extends('layouts.app')

@section('title', 'Edit Profil Lulusan - SiAC')
@section('page-title', 'Analisis CPL')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 text-white">
                    <i class="fas fa-edit"></i> Edit Profil Lulusan
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('profil-lulusan.update', $profilLulusan) }}" method="POST">
                    @csrf
                    @method('PUT')
                    


                    <div class="mb-3">
                        <label for="nama_profil" class="form-label">Nama Profil Lulusan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nama_profil') is-invalid @enderror" 
                               id="nama_profil" name="nama_profil" value="{{ old('nama_profil', $profilLulusan->nama_profil) }}" 
                               placeholder="Contoh: Data Scientist" required>
                        @error('nama_profil')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Nama profesi atau bidang kerja yang menjadi target lulusan
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi Profil <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                  id="deskripsi" name="deskripsi" rows="5" 
                                  placeholder="Jelaskan deskripsi profil lulusan..." required>{{ old('deskripsi', $profilLulusan->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text d-flex justify-content-between">
                            <span>Deskripsikan kompetensi dan tanggung jawab profil lulusan</span>
                            <span class="text-muted" id="charCount">{{ strlen($profilLulusan->deskripsi) }} karakter</span>
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('profil-lulusan.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <div>
                            <a href="{{ route('profil-lulusan.show', $profilLulusan) }}" class="btn btn-info me-2">
                                <i class="fas fa-eye"></i> Lihat Detail
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Preview Card -->
        <div class="card mt-4 border-info">
            <div class="card-header bg-light">
                <h6 class="mb-0 text-info">
                    <i class="fas fa-eye"></i> Preview Profil Lulusan
                </h6>
            </div>
            <div class="card-body">

                <div class="row mt-2">
                    <div class="col-3">
                        <strong>Nama Profil:</strong>
                    </div>
                    <div class="col-9">
                        <div class="fw-semibold" id="preview-nama">{{ $profilLulusan->nama_profil }}</div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-3">
                        <strong>Deskripsi:</strong>
                    </div>
                    <div class="col-9">
                        <div id="preview-deskripsi" style="max-height: 150px; overflow-y: auto;">
                            {{ $profilLulusan->deskripsi }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Real-time preview
    const namaProfilInput = document.getElementById('nama_profil');
    const deskripsiTextarea = document.getElementById('deskripsi');
    
    const previewNama = document.getElementById('preview-nama');
    const previewDeskripsi = document.getElementById('preview-deskripsi');
    const charCount = document.getElementById('charCount');
    
    function updatePreview() {
        // Update nama
        previewNama.textContent = namaProfilInput.value || '{{ $profilLulusan->nama_profil }}';
        
        // Update deskripsi
        previewDeskripsi.textContent = deskripsiTextarea.value || '{{ $profilLulusan->deskripsi }}';
        
        // Update character count
        charCount.textContent = deskripsiTextarea.value.length + ' karakter';
    }
    
    namaProfilInput.addEventListener('input', updatePreview);
    deskripsiTextarea.addEventListener('input', updatePreview);
    
    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        let isValid = true;
        let errorMessages = [];
        

        
        if (!namaProfilInput.value.trim()) {
            isValid = false;
            namaProfilInput.classList.add('is-invalid');
            errorMessages.push('Nama profil wajib diisi');
        }
        
        if (!deskripsiTextarea.value.trim()) {
            isValid = false;
            deskripsiTextarea.classList.add('is-invalid');
            errorMessages.push('Deskripsi wajib diisi');
        } else if (deskripsiTextarea.value.length < 50) {
            isValid = false;
            deskripsiTextarea.classList.add('is-invalid');
            errorMessages.push('Deskripsi minimal 50 karakter');
        }
        
        if (!isValid) {
            e.preventDefault();
            alert('Kesalahan:\n' + errorMessages.join('\n'));
        }
    });
    
    // Remove validation classes on input
    [namaProfilInput, deskripsiTextarea].forEach(element => {
        element.addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });
    });
    
    // Initialize character count
    updatePreview();
});
</script>
@endpush 