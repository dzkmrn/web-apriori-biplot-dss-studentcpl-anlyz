@extends('layouts.app')

@section('title', 'Edit CPL - SiAC')
@section('page-title', 'Analisis CPL')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 text-white">
                    <i class="fas fa-edit"></i> Edit CPL
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('cpl.update', $cpl) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="kode_cpl" class="form-label">Kode CPL <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('kode_cpl') is-invalid @enderror" 
                               id="kode_cpl" name="kode_cpl" value="{{ old('kode_cpl', $cpl->kode_cpl) }}" 
                               placeholder="Contoh: CPL01" required>
                        @error('kode_cpl')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Kode unik untuk mengidentifikasi CPL (format: CPL01, CPL02, dst)
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="kategori" class="form-label">Kategori CPL <span class="text-danger">*</span></label>
                        <select class="form-select @error('kategori') is-invalid @enderror" 
                                id="kategori" name="kategori" required>
                            <option value="">Pilih Kategori</option>
                            <option value="Penguasaan dan penerapan ilmu dasar sains dan matematik" {{ old('kategori', $cpl->kategori) === 'Penguasaan dan penerapan ilmu dasar sains dan matematik' ? 'selected' : '' }}>
                                1. Penguasaan dan penerapan ilmu dasar sains dan matematik
                            </option>
                            <option value="Kemampuan perumusan solusi permasalahan pada objek Teknik Industri" {{ old('kategori', $cpl->kategori) === 'Kemampuan perumusan solusi permasalahan pada objek Teknik Industri' ? 'selected' : '' }}>
                                2. Kemampuan perumusan solusi permasalahan pada objek Teknik Industri
                            </option>
                            <option value="Kemampuan perancangan dan penelitian pada objek sistem integrasian TIK dalam upaya implementasi keilmuaan Teknik Industri" {{ old('kategori', $cpl->kategori) === 'Kemampuan perancangan dan penelitian pada objek sistem integrasian TIK dalam upaya implementasi keilmuaan Teknik Industri' ? 'selected' : '' }}>
                                3. Kemampuan perancangan dan penelitian pada objek sistem integrasian TIK dalam upaya implementasi keilmuaan Teknik Industri
                            </option>
                            <option value="Kemampuan penguasaan teknik umum dan TIK dalam upaya implementasi keilmuaan Teknik Industri" {{ old('kategori', $cpl->kategori) === 'Kemampuan penguasaan teknik umum dan TIK dalam upaya implementasi keilmuaan Teknik Industri' ? 'selected' : '' }}>
                                4. Kemampuan penguasaan teknik umum dan TIK dalam upaya implementasi keilmuaan Teknik Industri
                            </option>
                            <option value="Penguasaan aspek non-akademis pendukung" {{ old('kategori', $cpl->kategori) === 'Penguasaan aspek non-akademis pendukung' ? 'selected' : '' }}>
                                5. Penguasaan aspek non-akademis pendukung
                            </option>
                            <option value="Penguasaan keilmuaan pendukung kewirausahaan" {{ old('kategori', $cpl->kategori) === 'Penguasaan keilmuaan pendukung kewirausahaan' ? 'selected' : '' }}>
                                6. Penguasaan keilmuaan pendukung kewirausahaan
                            </option>
                        </select>
                        @error('kategori')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Pilih kategori sesuai dengan jenis capaian pembelajaran
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi CPL <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                  id="deskripsi" name="deskripsi" rows="4" 
                                  placeholder="Masukkan deskripsi lengkap CPL..." required>{{ old('deskripsi', $cpl->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Jelaskan secara detail apa yang harus dicapai dalam CPL ini
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" 
                                   id="is_active" name="is_active" value="1"
                                   {{ old('is_active', $cpl->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                CPL Aktif
                            </label>
                        </div>
                        <div class="form-text">
                            CPL yang tidak aktif tidak akan digunakan dalam analisis
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('cpl.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <div>
                            <a href="{{ route('cpl.show', $cpl) }}" class="btn btn-info me-2">
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
                    <i class="fas fa-eye"></i> Preview CPL
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-3">
                        <strong>Kode:</strong>
                    </div>
                    <div class="col-9">
                        <span class="fw-bold text-primary" id="preview-kode">{{ $cpl->kode_cpl }}</span>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-3">
                        <strong>Kategori:</strong>
                    </div>
                    <div class="col-9">
                        <span class="badge bg-secondary" id="preview-kategori">{{ $cpl->kategori }}</span>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-3">
                        <strong>Deskripsi:</strong>
                    </div>
                    <div class="col-9">
                        <div id="preview-deskripsi">{{ $cpl->deskripsi }}</div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-3">
                        <strong>Status:</strong>
                    </div>
                    <div class="col-9">
                        <span class="badge" id="preview-status">
                            {{ $cpl->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
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
    const kodeCplInput = document.getElementById('kode_cpl');
    const kategoriSelect = document.getElementById('kategori');
    const deskripsiTextarea = document.getElementById('deskripsi');
    const isActiveCheckbox = document.getElementById('is_active');
    
    const previewKode = document.getElementById('preview-kode');
    const previewKategori = document.getElementById('preview-kategori');
    const previewDeskripsi = document.getElementById('preview-deskripsi');
    const previewStatus = document.getElementById('preview-status');
    
    // Category badge colors
    const categoryColors = {
        'Sikap': 'bg-success',
        'Pengetahuan': 'bg-info',
        'Keterampilan Umum': 'bg-warning',
        'Keterampilan Khusus': 'bg-danger'
    };
    
    function updatePreview() {
        // Update kode
        previewKode.textContent = kodeCplInput.value || '{{ $cpl->kode_cpl }}';
        
        // Update kategori
        const selectedKategori = kategoriSelect.value || '{{ $cpl->kategori }}';
        previewKategori.textContent = selectedKategori;
        previewKategori.className = 'badge ' + (categoryColors[selectedKategori] || 'bg-secondary');
        
        // Update deskripsi
        previewDeskripsi.textContent = deskripsiTextarea.value || '{{ $cpl->deskripsi }}';
        
        // Update status
        const isActive = isActiveCheckbox.checked;
        previewStatus.textContent = isActive ? 'Aktif' : 'Nonaktif';
        previewStatus.className = 'badge ' + (isActive ? 'bg-success' : 'bg-secondary');
    }
    
    kodeCplInput.addEventListener('input', updatePreview);
    kategoriSelect.addEventListener('change', updatePreview);
    deskripsiTextarea.addEventListener('input', updatePreview);
    isActiveCheckbox.addEventListener('change', updatePreview);
    
    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        let isValid = true;
        
        if (!kodeCplInput.value.trim()) {
            isValid = false;
            kodeCplInput.classList.add('is-invalid');
        }
        
        if (!kategoriSelect.value) {
            isValid = false;
            kategoriSelect.classList.add('is-invalid');
        }
        
        if (!deskripsiTextarea.value.trim()) {
            isValid = false;
            deskripsiTextarea.classList.add('is-invalid');
        }
        
        if (!isValid) {
            e.preventDefault();
            alert('Harap lengkapi semua field yang wajib diisi!');
        }
    });
    
    // Remove validation classes on input
    [kodeCplInput, kategoriSelect, deskripsiTextarea].forEach(element => {
        element.addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });
        
        if (element.tagName === 'SELECT') {
            element.addEventListener('change', function() {
                this.classList.remove('is-invalid');
            });
        }
    });
});
</script>
@endpush 