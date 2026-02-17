@extends('layouts.app')

@section('title', 'Buat Analisis Baru - SiAC')
@section('page-title', 'Analisis CPL')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 text-white">
                    <i class="fas fa-chart-line"></i> Buat Analisis Apriori Baru
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('analisis.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="angkatan" class="form-label">Angkatan <span class="text-danger">*</span></label>
                        <select class="form-select @error('angkatan') is-invalid @enderror" 
                                id="angkatan" name="angkatan" required>
                            <option value="">Pilih Angkatan</option>
                            @foreach($angkatans as $angkatan)
                                <option value="{{ $angkatan }}" {{ old('angkatan') == $angkatan ? 'selected' : '' }}>
                                    {{ $angkatan }}
                                </option>
                            @endforeach
                        </select>
                        @error('angkatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Pilih angkatan mahasiswa yang akan dianalisis
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="min_support" class="form-label">Minimum Support <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('min_support') is-invalid @enderror" 
                                       id="min_support" name="min_support" value="{{ old('min_support', '0.05') }}" 
                                       step="0.01" min="0.01" max="1" required>
                                @error('min_support')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    Nilai antara 0.01 - 1.0 (contoh: 0.05 = 5%)
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="min_confidence" class="form-label">Minimum Confidence <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('min_confidence') is-invalid @enderror" 
                                       id="min_confidence" name="min_confidence" value="{{ old('min_confidence', '0.3') }}" 
                                       step="0.01" min="0.01" max="1" required>
                                @error('min_confidence')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    Nilai antara 0.01 - 1.0 (contoh: 0.3 = 30%)
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi Analisis <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                  id="deskripsi" name="deskripsi" rows="3" 
                                  placeholder="Masukkan deskripsi analisis..." required>{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Berikan deskripsi singkat tentang tujuan analisis ini
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('analisis.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <div>
                            <button type="reset" class="btn btn-outline-secondary me-2">
                                <i class="fas fa-undo"></i> Reset
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-play"></i> Jalankan Analisis
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Info Card -->
        <div class="card mt-4 border-info">
            <div class="card-header bg-light">
                <h6 class="mb-0 text-info">
                    <i class="fas fa-info-circle"></i> Informasi Algoritma Apriori
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-primary">Support:</h6>
                        <p class="small mb-3">Mengukur seberapa sering itemset muncul dalam data. Semakin tinggi nilai support, semakin sering kombinasi CPL tersebut muncul.</p>
                        
                        <h6 class="text-primary">Confidence:</h6>
                        <p class="small mb-0">Mengukur kepercayaan rule. Jika A maka B dengan confidence 80%, artinya 80% mahasiswa yang memiliki CPL A juga memiliki CPL B.</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-primary">Lift:</h6>
                        <p class="small mb-3">Mengukur kekuatan asosiasi. Lift > 1 menunjukkan asosiasi positif, lift < 1 menunjukkan asosiasi negatif.</p>
                        
                        <h6 class="text-primary">Rekomendasi:</h6>
                        <ul class="small mb-0">
                            <li>Support: 0.05 - 0.2 (5% - 20%)</li>
                            <li>Confidence: 0.3 - 0.8 (30% - 80%)</li>
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
    // Form validation
    const form = document.querySelector('form');
    
    form.addEventListener('submit', function(e) {
        const angkatan = document.getElementById('angkatan').value;
        const minSupport = parseFloat(document.getElementById('min_support').value);
        const minConfidence = parseFloat(document.getElementById('min_confidence').value);
        
        let isValid = true;
        let errorMsg = '';
        
        if (!angkatan) {
            errorMsg += 'Harap pilih angkatan.\n';
            isValid = false;
        }
        
        if (minSupport < 0.01 || minSupport > 1) {
            errorMsg += 'Minimum Support harus antara 0.01 dan 1.0.\n';
            isValid = false;
        }
        
        if (minConfidence < 0.01 || minConfidence > 1) {
            errorMsg += 'Minimum Confidence harus antara 0.01 dan 1.0.\n';
            isValid = false;
        }
        
        if (!isValid) {
            e.preventDefault();
            alert(errorMsg);
        } else {
            // Show loading message
            const submitBtn = form.querySelector('button[type="submit"]');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
            submitBtn.disabled = true;
        }
    });
    
    // Real-time validation for numeric inputs
    ['min_support', 'min_confidence'].forEach(id => {
        const input = document.getElementById(id);
        input.addEventListener('input', function() {
            const value = parseFloat(this.value);
            if (value < 0.01 || value > 1) {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
            }
        });
    });
});
</script>
@endpush 