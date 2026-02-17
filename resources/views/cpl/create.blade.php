@extends('layouts.app')

@section('title', 'Tambah CPL - SiAC')
@section('page-title', 'Analisis CPL')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 text-white">
                    <i class="fas fa-plus"></i> Tambah CPL Baru
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('cpl.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="kode_cpl" class="form-label">Kode CPL <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('kode_cpl') is-invalid @enderror" 
                               id="kode_cpl" name="kode_cpl" value="{{ old('kode_cpl') }}" 
                               placeholder="Contoh: CPL 1, CPL 2, dst." required>
                        @error('kode_cpl')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Masukkan kode CPL yang unik, misalnya: CPL 1, CPL 2, atau CPL-01
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="kategori" class="form-label">Kategori CPL <span class="text-danger">*</span></label>
                        <select class="form-select @error('kategori') is-invalid @enderror" 
                                id="kategori" name="kategori" required>
                            <option value="">Pilih Kategori</option>
                            <option value="Penguasaan dan penerapan ilmu dasar sains dan matematik" {{ old('kategori') == 'Penguasaan dan penerapan ilmu dasar sains dan matematik' ? 'selected' : '' }}>
                                1. Penguasaan dan penerapan ilmu dasar sains dan matematik
                            </option>
                            <option value="Kemampuan perumusan solusi permasalahan pada objek Teknik Industri" {{ old('kategori') == 'Kemampuan perumusan solusi permasalahan pada objek Teknik Industri' ? 'selected' : '' }}>
                                2. Kemampuan perumusan solusi permasalahan pada objek Teknik Industri
                            </option>
                            <option value="Kemampuan perancangan dan penelitian pada objek sistem integrasian TIK dalam upaya implementasi keilmuaan Teknik Industri" {{ old('kategori') == 'Kemampuan perancangan dan penelitian pada objek sistem integrasian TIK dalam upaya implementasi keilmuaan Teknik Industri' ? 'selected' : '' }}>
                                3. Kemampuan perancangan dan penelitian pada objek sistem integrasian TIK dalam upaya implementasi keilmuaan Teknik Industri
                            </option>
                            <option value="Kemampuan penguasaan teknik umum dan TIK dalam upaya implementasi keilmuaan Teknik Industri" {{ old('kategori') == 'Kemampuan penguasaan teknik umum dan TIK dalam upaya implementasi keilmuaan Teknik Industri' ? 'selected' : '' }}>
                                4. Kemampuan penguasaan teknik umum dan TIK dalam upaya implementasi keilmuaan Teknik Industri
                            </option>
                            <option value="Penguasaan aspek non-akademis pendukung" {{ old('kategori') == 'Penguasaan aspek non-akademis pendukung' ? 'selected' : '' }}>
                                5. Penguasaan aspek non-akademis pendukung
                            </option>
                            <option value="Penguasaan keilmuaan pendukung kewirausahaan" {{ old('kategori') == 'Penguasaan keilmuaan pendukung kewirausahaan' ? 'selected' : '' }}>
                                6. Penguasaan keilmuaan pendukung kewirausahaan
                            </option>
                        </select>
                        @error('kategori')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi CPL <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                  id="deskripsi" name="deskripsi" rows="5" 
                                  placeholder="Masukkan deskripsi lengkap CPL..." required>{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Berikan deskripsi yang jelas dan detail tentang CPL ini
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" checked>
                            <label class="form-check-label" for="is_active">
                                CPL Aktif
                            </label>
                        </div>
                        <div class="form-text">
                            CPL yang aktif akan muncul dalam form input nilai mahasiswa
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('cpl.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <div>
                            <button type="reset" class="btn btn-outline-secondary me-2">
                                <i class="fas fa-undo"></i> Reset
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan CPL
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
                    <i class="fas fa-eye"></i> Preview CPL
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <strong>Kode CPL:</strong>
                        <div id="previewKode" class="text-muted">-</div>
                    </div>
                    <div class="col-md-3">
                        <strong>Kategori:</strong>
                        <div id="previewKategori" class="text-muted">-</div>
                    </div>
                    <div class="col-md-6">
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
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const inputs = {
        kode_cpl: document.getElementById('kode_cpl'),
        kategori: document.getElementById('kategori'),
        deskripsi: document.getElementById('deskripsi'),
        is_active: document.getElementById('is_active')
    };
    
    const preview = {
        card: document.getElementById('previewCard'),
        kode: document.getElementById('previewKode'),
        kategori: document.getElementById('previewKategori'),
        status: document.getElementById('previewStatus'),
        deskripsi: document.getElementById('previewDeskripsi')
    };
    
    function updatePreview() {
        const hasContent = inputs.kode_cpl.value || inputs.kategori.value || inputs.deskripsi.value;
        
        if (hasContent) {
            preview.card.style.display = 'block';
            preview.kode.textContent = inputs.kode_cpl.value || '-';
            preview.kategori.textContent = inputs.kategori.value || '-';
            preview.status.innerHTML = inputs.is_active.checked 
                ? '<span class="badge bg-success">Aktif</span>' 
                : '<span class="badge bg-secondary">Tidak Aktif</span>';
            preview.deskripsi.textContent = inputs.deskripsi.value || '-';
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
        const requiredFields = ['kode_cpl', 'kategori', 'deskripsi'];
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
        
        if (!isValid) {
            e.preventDefault();
            alert('Harap lengkapi semua field yang wajib diisi!');
        }
    });
    
    // Character counter for description
    const deskripsiInput = inputs.deskripsi;
    const counterDiv = document.createElement('div');
    counterDiv.className = 'form-text text-end';
    deskripsiInput.parentNode.appendChild(counterDiv);
    
    function updateCounter() {
        const length = deskripsiInput.value.length;
        counterDiv.textContent = `${length}/1000 karakter`;
        
        if (length > 1000) {
            counterDiv.classList.add('text-danger');
            deskripsiInput.classList.add('is-invalid');
        } else {
            counterDiv.classList.remove('text-danger');
            deskripsiInput.classList.remove('is-invalid');
        }
    }
    
    deskripsiInput.addEventListener('input', updateCounter);
    updateCounter();
});
</script>
@endpush 