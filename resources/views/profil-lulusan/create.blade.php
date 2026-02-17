@extends('layouts.app')

@section('title', 'Tambah Profil Lulusan - SiAC')
@section('page-title', 'Analisis CPL')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 text-white">
                    <i class="fas fa-graduation-cap"></i> Tambah Profil Lulusan Baru
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('profil-lulusan.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="nama_profil" class="form-label">Nama Profil Lulusan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nama_profil') is-invalid @enderror" 
                               id="nama_profil" name="nama_profil" value="{{ old('nama_profil') }}" 
                               placeholder="Contoh: Studi Lanjut, Wirausaha, Pegawai Profesional" required>
                        @error('nama_profil')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Masukkan nama profil lulusan yang akan dijadikan kategori analisis
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="jumlah" class="form-label">Jumlah Lulusan<span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('jumlah') is-invalid @enderror" 
                               id="jumlah" name="jumlah" value="{{ old('jumlah') }}" 
                               placeholder="0" min="0" required>
                        @error('jumlah')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Target jumlah mahasiswa untuk profil lulusan ini
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi Profil Lulusan</label>
                        <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                  id="deskripsi" name="deskripsi" rows="4" 
                                  placeholder="Masukkan deskripsi profil lulusan (opsional)...">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Berikan deskripsi singkat tentang profil lulusan ini
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" checked>
                            <label class="form-check-label" for="is_active">
                                Profil Aktif
                            </label>
                        </div>
                        <div class="form-text">
                            Profil yang aktif akan muncul dalam analisis dan laporan
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('profil-lulusan.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <div>
                            <button type="reset" class="btn btn-outline-secondary me-2">
                                <i class="fas fa-undo"></i> Reset
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Profil
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Preview Card -->
        <div class="card mt-4" id="previewCard" style="display: none;">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0">
                    <i class="fas fa-eye"></i> Preview Profil Lulusan
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <strong>Nama Profil:</strong>
                        <div id="previewNama" class="text-muted">-</div>
                    </div>
                    <div class="col-md-3">
                        <strong>Target Jumlah:</strong>
                        <div id="previewJumlah" class="text-muted">-</div>
                    </div>
                    <div class="col-md-5">
                        <strong>Status:</strong>
                        <div id="previewStatus" class="text-muted">-</div>
                    </div>
                </div>
                <div class="mt-3">
                    <strong>Deskripsi:</strong>
                    <div id="previewDeskripsi" class="text-muted">-</div>
                </div>
            </div>
        </div>

        <!-- Quick Tips -->
        <div class="card mt-4 border-info">
            <div class="card-header bg-light">
                <h6 class="mb-0 text-info">
                    <i class="fas fa-lightbulb"></i> Tips Profil Lulusan
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-primary">Contoh Profil Lulusan:</h6>
                        <ul class="mb-0">
                            <li>Studi Lanjut (S2/S3)</li>
                            <li>Wirausaha</li>
                            <li>Pegawai Profesional</li>
                            <li>Peneliti</li>
                            <li>Konsultan</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-primary">Catatan Penting:</h6>
                        <ul class="mb-0">
                            <li>Nama profil harus unik</li>
                            <li>Target jumlah dapat disesuaikan</li>
                            <li>Deskripsi membantu pemahaman</li>
                            <li>Status aktif untuk analisis</li>
                        </ul>
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
    const inputs = {
        nama_profil: document.getElementById('nama_profil'),
        jumlah: document.getElementById('jumlah'),
        deskripsi: document.getElementById('deskripsi'),
        is_active: document.getElementById('is_active')
    };
    
    const preview = {
        card: document.getElementById('previewCard'),
        nama: document.getElementById('previewNama'),
        jumlah: document.getElementById('previewJumlah'),
        status: document.getElementById('previewStatus'),
        deskripsi: document.getElementById('previewDeskripsi')
    };
    
    function updatePreview() {
        const hasContent = inputs.nama_profil.value || inputs.jumlah.value || inputs.deskripsi.value;
        
        if (hasContent) {
            preview.card.style.display = 'block';
            preview.nama.textContent = inputs.nama_profil.value || '-';
            preview.jumlah.textContent = inputs.jumlah.value ? `${inputs.jumlah.value} mahasiswa` : '-';
            preview.status.innerHTML = inputs.is_active.checked 
                ? '<span class="badge bg-success">Aktif</span>' 
                : '<span class="badge bg-secondary">Tidak Aktif</span>';
            preview.deskripsi.textContent = inputs.deskripsi.value || 'Tidak ada deskripsi';
        } else {
            preview.card.style.display = 'none';
        }
    }
    
    // Add event listeners
    Object.values(inputs).forEach(input => {
        input.addEventListener('input', updatePreview);
        input.addEventListener('change', updatePreview);
    });
    
    // Initial preview update
    updatePreview();
    
    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const requiredFields = ['nama_profil', 'jumlah'];
        let isValid = true;
        
        requiredFields.forEach(field => {
            const input = document.getElementById(field);
            if (!input.value.trim()) {
                input.classList.add('is-invalid');
                isValid = false;
            } else {
                input.classList.remove('is-invalid');
            }
        });
        
        // Additional validation for jumlah
        const jumlahInput = inputs.jumlah;
        if (jumlahInput.value && (parseInt(jumlahInput.value) < 0 || parseInt(jumlahInput.value) > 1000)) {
            jumlahInput.classList.add('is-invalid');
            isValid = false;
            alert('Target jumlah harus antara 0 dan 1000');
        }
        
        if (!isValid) {
            e.preventDefault();
            alert('Harap lengkapi semua field yang wajib diisi dengan benar!');
        }
    });
    
    // Number input formatting
    inputs.jumlah.addEventListener('input', function() {
        // Remove non-digit characters
        this.value = this.value.replace(/\D/g, '');
        
        // Limit to reasonable number
        if (parseInt(this.value) > 1000) {
            this.value = '1000';
        }
        
        updatePreview();
    });
    
    // Character counter for description
    const deskripsiInput = inputs.deskripsi;
    const counterDiv = document.createElement('div');
    counterDiv.className = 'form-text text-end';
    deskripsiInput.parentNode.appendChild(counterDiv);
    
    function updateCounter() {
        const length = deskripsiInput.value.length;
        counterDiv.textContent = `${length}/500 karakter`;
        
        if (length > 500) {
            counterDiv.classList.add('text-danger');
            deskripsiInput.classList.add('is-invalid');
        } else {
            counterDiv.classList.remove('text-danger');
            deskripsiInput.classList.remove('is-invalid');
        }
    }
    
    deskripsiInput.addEventListener('input', updateCounter);
    updateCounter();
    
    // Auto-capitalize first letter of each word in nama_profil
    inputs.nama_profil.addEventListener('input', function() {
        const words = this.value.split(' ');
        const capitalizedWords = words.map(word => {
            if (word.length > 0) {
                return word.charAt(0).toUpperCase() + word.slice(1).toLowerCase();
            }
            return word;
        });
        this.value = capitalizedWords.join(' ');
        updatePreview();
    });
});
</script>
@endpush 