@extends('layouts.app')

@section('title', 'Edit Data Mahasiswa - SiAC')
@section('page-title', 'Analisis CPL')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 text-white">
                    <i class="fas fa-user-edit"></i> Edit Data Mahasiswa
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('data-mahasiswa.update', $mahasiswa) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nim" class="form-label">NIM <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nim') is-invalid @enderror" 
                                       id="nim" name="nim" value="{{ old('nim', $mahasiswa->nim) }}" 
                                       placeholder="Contoh: 1201210417" required>
                                @error('nim')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama Mahasiswa <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nama') is-invalid @enderror" 
                                       id="nama" name="nama" value="{{ old('nama', $mahasiswa->nama) }}" 
                                       placeholder="Contoh: Hanifa Nurhaliza" required>
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="angkatan" class="form-label">Angkatan <span class="text-danger">*</span></label>
                                <select class="form-select @error('angkatan') is-invalid @enderror" 
                                        id="angkatan" name="angkatan" required>
                                    <option value="">Pilih Angkatan</option>
                                    @php
                                        $currentYear = date('Y');
                                        $startYear = 2015;
                                    @endphp
                                    @for($year = $currentYear; $year >= $startYear; $year--)
                                        <option value="{{ $year }}" {{ old('angkatan', $mahasiswa->angkatan) == $year ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                    @endfor
                                </select>
                                @error('angkatan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" 
                                        id="status" name="status" required>
                                    <option value="">Pilih Status</option>
                                    <option value="Aktif" {{ old('status', $mahasiswa->status) === 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="Lulus" {{ old('status', $mahasiswa->status) === 'Lulus' ? 'selected' : '' }}>Lulus</option>
                                    <option value="Cuti" {{ old('status', $mahasiswa->status) === 'Cuti' ? 'selected' : '' }}>Cuti</option>
                                    <option value="DO" {{ old('status', $mahasiswa->status) === 'DO' ? 'selected' : '' }}>Drop Out</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <hr>
                    <h6><i class="fas fa-list-alt"></i> Nilai CPL</h6>
                    <div class="row">
                        @php
                            $cplValues = is_array($mahasiswa->nilai_cpl) ? $mahasiswa->nilai_cpl : json_decode($mahasiswa->nilai_cpl, true) ?? [];
                        @endphp
                        @foreach($cpls as $index => $cpl)
                            <div class="col-md-6 col-lg-4 mb-3">
                                <label for="cpl_{{ $cpl->id }}" class="form-label">
                                    {{ $cpl->kode_cpl }}
                                    @php
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
                                    <span class="badge" style="font-size: 0.7rem; {{ $badgeStyle }}">{{ Str::limit($cpl->kategori, 20) }}</span>
                                </label>
                                <input type="number" class="form-control @error('nilai_cpl.' . $cpl->id) is-invalid @enderror" 
                                       id="cpl_{{ $cpl->id }}" name="nilai_cpl[{{ $cpl->id }}]" 
                                       value="{{ old('nilai_cpl.' . $cpl->id, $cplValues[$cpl->id] ?? '') }}" 
                                       min="0" max="100" step="0.01"
                                       placeholder="0-100">
                                @error('nilai_cpl.' . $cpl->id)
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">{{ Str::limit($cpl->deskripsi, 50) }}</div>
                            </div>
                        @endforeach
                    </div>

                    @if($cpls->isEmpty())
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            Belum ada CPL yang aktif. <a href="{{ route('cpl.create') }}">Tambah CPL</a> terlebih dahulu.
                        </div>
                    @endif

                    <hr>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('data-mahasiswa.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <div>
                            <a href="{{ route('data-mahasiswa.show', $mahasiswa) }}" class="btn btn-info me-2">
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

        <!-- Info Card -->
        <div class="card mt-4 border-info">
            <div class="card-header bg-light">
                <h6 class="mb-0 text-info">
                    <i class="fas fa-info-circle"></i> Informasi Pengisian
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-primary">Nilai CPL:</h6>
                        <ul class="small mb-3">
                            <li>Rentang nilai: 0 - 100</li>
                            <li>Kosongkan jika belum ada nilai</li>
                            <li>Gunakan titik (.) untuk desimal</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-primary">Kategori Nilai:</h6>
                        <ul class="small mb-0">
                            <li><span class="badge bg-success">Baik</span> > 75</li>
                            <li><span class="badge bg-warning">Cukup</span> 60 - 75</li>
                            <li><span class="badge bg-danger">Kurang</span> < 60</li>
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
    const nimInput = document.getElementById('nim');
    const namaInput = document.getElementById('nama');
    
    form.addEventListener('submit', function(e) {
        let isValid = true;
        let errorMsg = '';
        
        // Validate NIM
        if (!nimInput.value.trim()) {
            errorMsg += 'NIM wajib diisi.\n';
            isValid = false;
        } else if (!/^\d+$/.test(nimInput.value.trim())) {
            errorMsg += 'NIM harus berupa angka.\n';
            isValid = false;
        }
        
        // Validate Nama
        if (!namaInput.value.trim()) {
            errorMsg += 'Nama mahasiswa wajib diisi.\n';
            isValid = false;
        }
        
        if (!isValid) {
            e.preventDefault();
            alert(errorMsg);
        }
    });
    
    // Real-time validation for numeric inputs
    const cplInputs = document.querySelectorAll('input[name^="nilai_cpl"]');
    cplInputs.forEach(input => {
        input.addEventListener('input', function() {
            const value = parseFloat(this.value);
            if (this.value && (value < 0 || value > 100)) {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
            }
        });
    });
    
    // Auto-uppercase NIM
    nimInput.addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
    
    // Auto-capitalize nama
    namaInput.addEventListener('input', function() {
        let words = this.value.split(' ');
        for (let i = 0; i < words.length; i++) {
            if (words[i]) {
                words[i] = words[i][0].toUpperCase() + words[i].substr(1).toLowerCase();
            }
        }
        this.value = words.join(' ');
    });
});
</script>
@endpush 