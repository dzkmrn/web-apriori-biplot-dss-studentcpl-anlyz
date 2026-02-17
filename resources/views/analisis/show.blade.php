@extends('layouts.app')

@section('title', 'Detail Analisis - SiAC')
@section('page-title', 'Analisis CPL')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Detail Analisis</h4>
    <a href="{{ route('analisis.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<!-- Analysis Info Card -->
<div class="card mb-4">
    <div class="card-header">
        <h6 class="m-0 font-weight-bold text-white">
            <i class="fas fa-info-circle"></i> Informasi Analisis
        </h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <td width="40%"><strong>Tanggal Analisis:</strong></td>
                        <td>{{ $dataHistori->tanggal->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Deskripsi:</strong></td>
                        <td>{{ $dataHistori->deskripsi }}</td>
                    </tr>
                    <tr>
                        <td><strong>Angkatan:</strong></td>
                        <td><span class="badge bg-info">{{ $dataHistori->angkatan }}</span></td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <td width="40%"><strong>Total Rules:</strong></td>
                        <td><span class="badge bg-primary">{{ $dataHistori->total_rules }}</span></td>
                    </tr>
                    <tr>
                        <td><strong>Min Support:</strong></td>
                        <td>{{ number_format($dataHistori->min_support * 100, 1) }}%</td>
                    </tr>
                    <tr>
                        <td><strong>Min Confidence:</strong></td>
                        <td>{{ number_format($dataHistori->min_confidence * 100, 1) }}%</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Profil Lulusan Recommendations -->
<div class="card mb-4">
    <div class="card-header">
        <h6 class="m-0 font-weight-bold text-white">
            <i class="fas fa-graduation-cap"></i> Rekomendasi Profil Lulusan
        </h6>
    </div>
    <div class="card-body">
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            <strong>Berdasarkan Analisis Association Rules CPL:</strong> Sistem merekomendasikan profil lulusan yang paling sesuai berdasarkan pola asosiasi CPL yang ditemukan.
        </div>
        
        <div class="row">
            @foreach($profilLulusanRecommendations as $profil => $data)
            <div class="col-md-4 mb-3">
                <div class="card border-left-{{ $loop->first ? 'success' : ($loop->iteration == 2 ? 'warning' : 'info') }} shadow h-100" style="border-left: 4px solid {{ $loop->first ? '#38A169' : ($loop->iteration == 2 ? '#D69E2E' : '#3182CE') }};">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="font-weight-bold text-{{ $loop->first ? 'success' : ($loop->iteration == 2 ? 'warning' : 'info') }}">
                                {{ $data['name'] }}
                            </h6>
                            @if($loop->first)
                                <span class="badge bg-success">Rekomendasi #1</span>
                            @elseif($loop->iteration == 2)
                                <span class="badge bg-warning">Rekomendasi #2</span>
                            @else
                                <span class="badge bg-info">Rekomendasi #3</span>
                            @endif
                        </div>
                        
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <small class="text-muted">Skor Kesesuaian:</small>
                                <strong>{{ number_format($data['score'], 3) }}</strong>
                            </div>
                            <div class="progress mt-1" style="height: 6px;">
                                <div class="progress-bar bg-{{ $loop->first ? 'success' : ($loop->iteration == 2 ? 'warning' : 'info') }}" 
                                     style="width: {{ min(100, ($data['score'] / max(array_column($profilLulusanRecommendations, 'score'))) * 100) }}%"></div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <small class="text-muted">Jumlah Rules Relevan:</small>
                            <span class="badge bg-secondary">{{ $data['rule_count'] }}</span>
                        </div>
                        
                        <!-- Category Breakdown -->
                        <div class="mb-3">
                            <h6 class="text-sm font-weight-bold">Kategori CPL:</h6>
                            @foreach($data['categories'] as $categoryKey => $category)
                                @if($category['rule_count'] > 0)
                                <div class="mb-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">{{ $categoryKey }}:</small>
                                        <span class="badge bg-primary">{{ $category['rule_count'] }} rules</span>
                                    </div>
                                    <small class="text-muted d-block">{{ $category['name'] }}</small>
                                    <div class="text-sm">
                                        Avg Confidence: <strong>{{ number_format($category['avg_confidence'] * 100, 1) }}%</strong>
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        </div>
                        
                        <!-- Top Relevant Rules -->
                        @if(count($data['relevant_rules']) > 0)
                        <div class="mt-3">
                            <h6 class="text-sm font-weight-bold">Top Rules:</h6>
                            @foreach(array_slice($data['relevant_rules'], 0, 3) as $rule)
                            <div class="mb-1">
                                <small class="text-muted">
                                    Confidence: <span class="badge bg-success">{{ number_format($rule->confidence * 100, 1) }}%</span>
                                    Lift: <span class="badge bg-info">{{ number_format($rule->lift, 2) }}</span>
                                </small>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
                 <!-- Explanation -->
         <div class="alert alert-light mt-4">
             <h6 class="font-weight-bold">Penjelasan Kategori CPL:</h6>
             <div class="row">
                 <div class="col-md-12">
                     <div class="row">
                         <div class="col-md-6">
                             <ol class="mb-3">
                                 <li><small><strong>Penguasaan dan penerapan ilmu dasar sains dan matematik</strong></small></li>
                                 <li><small><strong>Kemampuan perumusan solusi permasalahan pada objek Teknik Industri</strong></small></li>
                                 <li><small><strong>Kemampuan perancangan dan penelitian pada objek sistem integrasi</strong></small></li>
                             </ol>
                         </div>
                         <div class="col-md-6">
                             <ol start="4" class="mb-3">
                                 <li><small><strong>Kemampuan penguasaan teknik umum dan TIK dalam upaya implementasi keilmuaan Teknik Industri</strong></small></li>
                                 <li><small><strong>Penguasaan aspek non-akademis pendukung</strong></small></li>
                                 <li><small><strong>Penguasaan keilmuaan pendukung kewirausahaan</strong></small></li>
                             </ol>
                         </div>
                     </div>
                     <div class="alert alert-info mt-3 mb-0">
                         <small><i class="fas fa-info-circle me-1"></i> 
                         <strong>Catatan:</strong> Setiap CPL dikategorikan berdasarkan 6 kategori utama di atas untuk memudahkan analisis pola asosiasi dan rekomendasi profil lulusan.</small>
                     </div>
                 </div>
             </div>
         </div>
    </div>
</div>

<!-- Insight dan Rekomendasi untuk Dosen -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-white">
                    <i class="fas fa-brain"></i> Insight Analisis & Rekomendasi Aksi
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="bg-light p-3 rounded mb-3">
                            <h6 class="text-primary"><i class="fas fa-eye"></i> Temuan Utama:</h6>
                            <div id="mainFindings">
                                <!-- Will be populated by JavaScript -->
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="bg-light p-3 rounded mb-3">
                            <h6 class="text-success"><i class="fas fa-tasks"></i> Rekomendasi Aksi:</h6>
                            <div id="actionRecommendations">
                                <!-- Will be populated by JavaScript -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="alert alert-info">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0"><i class="fas fa-info-circle"></i> Panduan Penggunaan Hasil Analisis:</h6>
                        <button class="btn btn-sm btn-outline-info" type="button" data-bs-toggle="collapse" data-bs-target="#panduanPenggunaan" aria-expanded="false">
                            <i class="fas fa-chevron-down"></i> Detail
                        </button>
                    </div>
                    <div class="collapse mt-3" id="panduanPenggunaan">
                        <ol class="mb-0 small">
                            <li><strong>Identifikasi CPL Kunci:</strong> Fokus pada CPL yang sering muncul dalam hubungan kuat</li>
                            <li><strong>Perbaiki Pembelajaran:</strong> Tingkatkan metode pembelajaran untuk CPL dengan hubungan lemah</li>
                            <li><strong>Desain Kurikulum:</strong> Susun mata kuliah berdasarkan pola hubungan yang ditemukan</li>
                            <li><strong>Evaluasi Berkala:</strong> Lakukan analisis ulang setiap semester untuk memantau perkembangan</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Association Rules Cards -->
<div class="row">
    <!-- One-to-One Rules -->
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-white">
                    <i class="fas fa-arrow-right"></i> Association Rules (1→1)
                </h6>
            </div>
            <div class="card-body">
                @if($oneToOneRules->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th width="8%">#</th>
                                    <th>Rule</th>
                                    <th width="18%">Support</th>
                                    <th width="20%">Confidence</th>
                                    <th width="15%">Lift</th>
                                    <th width="18%">Profil</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($oneToOneRules as $index => $rule)
                                <tr>
                                    <td class="text-center"><strong>{{ $index + 1 }}</strong></td>
                                    <td>
                                        @if(is_array($rule->lhs))
                                            @foreach($rule->lhs as $lhsItem)
                                                <span class="badge bg-success">{{ $lhsItem }}</span>
                                            @endforeach
                                        @else
                                            <span class="badge bg-success">{{ $rule->lhs }}</span>
                                        @endif
                                        <i class="fas fa-arrow-right mx-1"></i>
                                        @if(is_array($rule->rhs))
                                            @foreach($rule->rhs as $rhsItem)
                                                <span class="badge bg-warning">{{ $rhsItem }}</span>
                                            @endforeach
                                        @else
                                            <span class="badge bg-warning">{{ $rule->rhs }}</span>
                                        @endif
                                    </td>
                                    <td>{{ number_format($rule->support * 100, 1) }}%</td>
                                    <td>{{ number_format($rule->confidence * 100, 1) }}%</td>
                                    <td>
                                        <span class="badge {{ $rule->lift > 1 ? 'bg-success' : 'bg-danger' }}">
                                            {{ number_format($rule->lift, 2) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge 
                                            @if($rule->profil == 'Studi Lanjut') bg-primary
                                            @elseif($rule->profil == 'Pegawai Profesional') bg-info
                                            @elseif($rule->profil == 'Kewirausahaan') bg-warning
                                            @else bg-secondary
                                            @endif">
                                            {{ $rule->profil ?? 'Pegawai Profesional' }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-3">
                        <i class="fas fa-search text-muted mb-2"></i>
                        <p class="text-muted mb-0">Tidak ada rules 1→1 yang memenuhi kriteria</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Two-to-One Rules -->
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-white">
                    <i class="fas fa-arrows-alt-h"></i> Association Rules (2→1)
                </h6>
            </div>
            <div class="card-body">
                @if($twoToOneRules->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th width="8%">#</th>
                                    <th>Rule</th>
                                    <th width="18%">Support</th>
                                    <th width="20%">Confidence</th>
                                    <th width="15%">Lift</th>
                                    <th width="18%">Profil</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($twoToOneRules as $index => $rule)
                                <tr>
                                    <td class="text-center"><strong>{{ $index + 1 }}</strong></td>
                                    <td>
                                        @if(is_array($rule->lhs))
                                            @foreach($rule->lhs as $lhsItem)
                                                <span class="badge bg-success">{{ $lhsItem }}</span>
                                            @endforeach
                                        @else
                                            @php
                                                $lhsItems = explode(',', $rule->lhs);
                                            @endphp
                                            @foreach($lhsItems as $item)
                                                <span class="badge bg-success">{{ trim($item) }}</span>
                                            @endforeach
                                        @endif
                                        <i class="fas fa-arrow-right mx-1"></i>
                                        @if(is_array($rule->rhs))
                                            @foreach($rule->rhs as $rhsItem)
                                                <span class="badge bg-warning">{{ $rhsItem }}</span>
                                            @endforeach
                                        @else
                                            <span class="badge bg-warning">{{ $rule->rhs }}</span>
                                        @endif
                                    </td>
                                    <td>{{ number_format($rule->support * 100, 1) }}%</td>
                                    <td>{{ number_format($rule->confidence * 100, 1) }}%</td>
                                    <td>
                                        <span class="badge {{ $rule->lift > 1 ? 'bg-success' : 'bg-danger' }}">
                                            {{ number_format($rule->lift, 2) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge 
                                            @if($rule->profil == 'Studi Lanjut') bg-primary
                                            @elseif($rule->profil == 'Pegawai Profesional') bg-info
                                            @elseif($rule->profil == 'Kewirausahaan') bg-warning
                                            @else bg-secondary
                                            @endif">
                                            {{ $rule->profil ?? 'Pegawai Profesional' }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-3">
                        <i class="fas fa-search text-muted mb-2"></i>
                        <p class="text-muted mb-0">Tidak ada rules 2→1 yang memenuhi kriteria</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Three-to-One Rules -->
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-white">
                    <i class="fas fa-project-diagram"></i> Association Rules (3→1)
                </h6>
            </div>
            <div class="card-body">
                @if($threeToOneRules->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th width="8%">#</th>
                                    <th>Rule</th>
                                    <th width="18%">Support</th>
                                    <th width="20%">Confidence</th>
                                    <th width="15%">Lift</th>
                                    <th width="18%">Profil</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($threeToOneRules as $index => $rule)
                                <tr>
                                    <td class="text-center"><strong>{{ $index + 1 }}</strong></td>
                                    <td>
                                        @if(is_array($rule->lhs))
                                            @foreach($rule->lhs as $lhsItem)
                                                <span class="badge bg-success">{{ $lhsItem }}</span>
                                            @endforeach
                                        @else
                                            @php
                                                $lhsItems = explode(',', $rule->lhs);
                                            @endphp
                                            @foreach($lhsItems as $item)
                                                <span class="badge bg-success">{{ trim($item) }}</span>
                                            @endforeach
                                        @endif
                                        <i class="fas fa-arrow-right mx-1"></i>
                                        @if(is_array($rule->rhs))
                                            @foreach($rule->rhs as $rhsItem)
                                                <span class="badge bg-warning">{{ $rhsItem }}</span>
                                            @endforeach
                                        @else
                                            <span class="badge bg-warning">{{ $rule->rhs }}</span>
                                        @endif
                                    </td>
                                    <td>{{ number_format($rule->support * 100, 1) }}%</td>
                                    <td>{{ number_format($rule->confidence * 100, 1) }}%</td>
                                    <td>
                                        <span class="badge {{ $rule->lift > 1 ? 'bg-success' : 'bg-danger' }}">
                                            {{ number_format($rule->lift, 2) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge 
                                            @if($rule->profil == 'Studi Lanjut') bg-primary
                                            @elseif($rule->profil == 'Pegawai Profesional') bg-info
                                            @elseif($rule->profil == 'Kewirausahaan') bg-warning
                                            @else bg-secondary
                                            @endif">
                                            {{ $rule->profil ?? 'Pegawai Profesional' }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-3">
                        <i class="fas fa-search text-muted mb-2"></i>
                        <p class="text-muted mb-0">Tidak ada rules 3→1 yang memenuhi kriteria</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Visualizations for Non-IT Users -->
<div class="row mb-4">
    <!-- Rules Distribution Chart -->
    <div class="col-xl-6 col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3" data-bs-toggle="collapse" data-bs-target="#distribusiPolaCard" aria-expanded="false" style="cursor: pointer;">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-chart-pie"></i> Distribusi Pola Hubungan CPL
                    </h6>
                    <i class="fas fa-chevron-down text-white"></i>
                </div>
            </div>
            <div class="collapse" id="distribusiPolaCard" style="display: none;">
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="rulesDistributionChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        <span class="mr-2">
                            <i class="fas fa-circle" style="color: #E53E3E;"></i> Hubungan Langsung (1→1)
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle" style="color: #3182CE;"></i> Hubungan Kombinasi (2→1)
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle" style="color: #D69E2E;"></i> Hubungan Kompleks (3→1)
                        </span>
                    </div>
                    <div class="alert alert-info mt-3">
                        <h6><i class="fas fa-lightbulb"></i> Interpretasi untuk Dosen:</h6>
                        <ul class="mb-0 small">
                            <li><strong>Hubungan Langsung (1→1):</strong> Jika mahasiswa menguasai satu CPL, maka akan menguasai CPL lainnya</li>
                            <li><strong>Hubungan Kombinasi (2→1):</strong> Kombinasi dua CPL yang dikuasai akan menghasilkan penguasaan CPL ketiga</li>
                            <li><strong>Hubungan Kompleks (3→1):</strong> Tiga CPL yang dikuasai bersama mengindikasikan penguasaan CPL lainnya</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Confidence Distribution Chart -->
    <div class="col-xl-6 col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3" data-bs-toggle="collapse" data-bs-target="#tingkatKepastianCard" aria-expanded="false" style="cursor: pointer;">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-chart-bar"></i> Tingkat Kepastian Hubungan CPL
                    </h6>
                    <i class="fas fa-chevron-down text-white"></i>
                </div>
            </div>
            <div class="collapse" id="tingkatKepastianCard" style="display: none;">
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="confidenceChart" style="height: 300px;"></canvas>
                    </div>
                    <div class="alert alert-success mt-3">
                        <h6><i class="fas fa-graduation-cap"></i> Panduan Interpretasi:</h6>
                        <div class="row small">
                            <div class="col-md-6">
                                <p><span class="badge bg-success">80-100%</span> <strong>Sangat Kuat:</strong> Hubungan yang sangat dapat diandalkan untuk prediksi</p>
                                <p><span class="badge bg-info">60-79%</span> <strong>Kuat:</strong> Hubungan yang cukup dapat diandalkan</p>
                            </div>
                            <div class="col-md-6">
                                <p><span class="badge bg-warning">40-59%</span> <strong>Sedang:</strong> Hubungan yang perlu perhatian khusus</p>
                                <p><span class="badge bg-danger">0-39%</span> <strong>Lemah:</strong> Hubungan yang tidak konsisten</p>
                            </div>
                        </div>
                        <div class="bg-light p-2 rounded mt-2">
                            <small><strong>Cara Membaca Chart:</strong><br>
                            <strong>Sumbu X:</strong> Rentang tingkat kepastian (confidence) dalam persen<br>
                            <strong>Sumbu Y:</strong> Jumlah rules yang memiliki tingkat kepastian dalam rentang tersebut</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Rules Quality Metrics -->
<div class="row mb-4">
    <!-- Support vs Confidence Scatter -->
    <div class="col-xl-8 col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3" data-bs-toggle="collapse" data-bs-target="#pemetaanKualitasCard" aria-expanded="false" style="cursor: pointer;">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-chart-line"></i> Pemetaan Kualitas Hubungan CPL
                    </h6>
                    <i class="fas fa-chevron-down text-white"></i>
                </div>
            </div>
            <div class="collapse" id="pemetaanKualitasCard" style="display: none;">
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="qualityChart" style="height: 350px;"></canvas>
                    </div>
                    <div class="alert alert-warning mt-3">
                        <h6><i class="fas fa-compass"></i> Cara Membaca Grafik:</h6>
                        <div class="row small">
                            <div class="col-md-6">
                                <p><strong>Sumbu X (Support):</strong> Seberapa sering pola ini muncul dalam data (0-100%)</p>
                                <p><strong>Sumbu Y (Confidence):</strong> Seberapa pasti pola ini terjadi (0-100%)</p>
                                <p><strong>Warna Titik:</strong> Menunjukkan jenis hubungan (1→1, 2→1, 3→1)</p>
                            </div>
                            <div class="col-md-6">
                                <div class="bg-light p-2 rounded">
                                    <strong>Zona Prioritas:</strong><br>
                                    <i class="fas fa-star text-warning"></i> <strong>Kanan Atas:</strong> Pola terbaik (sering & pasti)<br>
                                    <i class="fas fa-exclamation-triangle text-danger"></i> <strong>Kiri Bawah:</strong> Pola lemah (jarang & tidak pasti)<br>
                                    <i class="fas fa-info-circle text-info"></i> <strong>Kanan Bawah:</strong> Sering tapi tidak pasti<br>
                                    <i class="fas fa-question-circle text-secondary"></i> <strong>Kiri Atas:</strong> Jarang tapi pasti
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lift Distribution -->
    <div class="col-xl-4 col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3" data-bs-toggle="collapse" data-bs-target="#kekuatanHubunganCard" aria-expanded="false" style="cursor: pointer;">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-chart-area"></i> Kekuatan Hubungan CPL
                    </h6>
                    <i class="fas fa-chevron-down text-white"></i>
                </div>
            </div>
            <div class="collapse" id="kekuatanHubunganCard" style="display: none;">
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="liftChart" style="height: 350px;"></canvas>
                    </div>
                    <div class="alert alert-primary mt-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0"><i class="fas fa-balance-scale"></i> Interpretasi Kekuatan:</h6>
                            <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#interpretasiKekuatan" aria-expanded="false">
                                <i class="fas fa-chevron-down"></i> Detail
                            </button>
                        </div>
                        <div class="bg-light p-2 rounded mt-2 mb-3">
                            <small><strong>Cara Membaca Chart:</strong><br>
                            <strong>Sumbu X:</strong> Rentang nilai lift (kekuatan hubungan)<br>
                            <strong>Sumbu Y:</strong> Jumlah rules dalam setiap kategori kekuatan</small>
                        </div>
                        <div class="collapse mt-3" id="interpretasiKekuatan">
                            <div class="small">
                                <p><span class="badge bg-success">Lift > 1.5:</span> <strong>Sangat Kuat</strong> - Hubungan yang sangat signifikan</p>
                                <p><span class="badge bg-info">Lift 1.1-1.5:</span> <strong>Kuat</strong> - Hubungan yang bermakna</p>
                                <p><span class="badge bg-warning">Lift 0.9-1.1:</span> <strong>Netral</strong> - Hubungan independen</p>
                                <p><span class="badge bg-danger">Lift < 0.9:</span> <strong>Negatif</strong> - Hubungan berlawanan</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Summary Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2" style="border-left: 4px solid #E53E3E;">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Rata-rata Confidence
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="avgConfidence">-</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-percentage fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2" style="border-left: 4px solid #38A169;">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Rata-rata Support
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="avgSupport">-</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2" style="border-left: 4px solid #3182CE;">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Rata-rata Lift
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="avgLift">-</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-balance-scale fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2" style="border-left: 4px solid #D69E2E;">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Rules Berkualitas
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="qualityRules">-</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-star fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Interpretations -->
@if($dataHistori->interpretasi)
<div class="card">
    <div class="card-header">
        <h6 class="m-0 font-weight-bold text-white">
            <i class="fas fa-lightbulb"></i> Interpretasi Hasil
        </h6>
    </div>
    <div class="card-body">
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            <strong>Interpretasi Otomatis:</strong>
        </div>
        <div class="interpretasi-content">
            {!! nl2br(e($dataHistori->interpretasi)) !!}
        </div>
    </div>
</div>
@endif
@endsection

@push('styles')
<style>
.interpretasi-content {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 0.375rem;
    border-left: 4px solid #20c997;
}
</style>
@endpush

@push('scripts')
<script>
// Wait for Chart.js to be fully loaded
function initializeCharts() {
    // Check if Chart.js is loaded
    if (typeof Chart === 'undefined') {
        console.error('Chart.js is not loaded!');
        setTimeout(initializeCharts, 100); // Retry after 100ms
        return;
    }
    
    // Plugin untuk menampilkan teks di tengah doughnut chart
    Chart.register({
        id: 'centerText',
        beforeDraw: function(chart) {
            if (chart.config.options.elements && chart.config.options.elements.center) {
                const ctx = chart.ctx;
                const centerConfig = chart.config.options.elements.center;
                const fontStyle = centerConfig.fontStyle || 'Arial';
                const txt = centerConfig.text;
                const color = centerConfig.color || '#000';
                const maxFontSize = centerConfig.maxFontSize || 75;
                const sidePadding = centerConfig.sidePadding || 20;
                const sidePaddingCalculated = (sidePadding / 100) * (chart.innerRadius * 2);
                
                ctx.font = "16px " + fontStyle;
                ctx.fillStyle = color;
                
                const stringWidth = ctx.measureText(txt).width;
                const elementWidth = (chart.innerRadius * 2) - sidePaddingCalculated;
                
                const widthRatio = elementWidth / stringWidth;
                const newFontSize = Math.floor(30 * widthRatio);
                const fontSizeToUse = Math.min(newFontSize, maxFontSize, 16);
                
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                
                const centerX = ((chart.chartArea.left + chart.chartArea.right) / 2);
                const centerY = ((chart.chartArea.top + chart.chartArea.bottom) / 2);
                
                ctx.font = fontSizeToUse + "px " + fontStyle;
                ctx.fillStyle = color;
                
                if (txt.includes('\n')) {
                    const lines = txt.split('\n');
                    lines.forEach((line, index) => {
                        ctx.fillText(line, centerX, centerY + (index - (lines.length - 1) / 2) * fontSizeToUse);
                    });
                } else {
                    ctx.fillText(txt, centerX, centerY);
                }
            }
        }
    });
    
    // Collect all rules data with safety checks
    const rules1to1 = @json($oneToOneRules) || [];
    const rules2to1 = @json($twoToOneRules) || [];
    const rules3to1 = @json($threeToOneRules) || [];
    
    // Ensure all are arrays before spreading
    const safeRules1to1 = Array.isArray(rules1to1) ? rules1to1 : [];
    const safeRules2to1 = Array.isArray(rules2to1) ? rules2to1 : [];
    const safeRules3to1 = Array.isArray(rules3to1) ? rules3to1 : [];
    
    const allRules = [...safeRules1to1, ...safeRules2to1, ...safeRules3to1];
    
    // Calculate statistics
    const avgConfidence = allRules.length > 0 ? 
        (allRules.reduce((sum, rule) => sum + rule.confidence, 0) / allRules.length * 100).toFixed(1) : 0;
    const avgSupport = allRules.length > 0 ? 
        (allRules.reduce((sum, rule) => sum + rule.support, 0) / allRules.length * 100).toFixed(1) : 0;
    const avgLift = allRules.length > 0 ? 
        (allRules.reduce((sum, rule) => sum + rule.lift, 0) / allRules.length).toFixed(2) : 0;
    const qualityRules = allRules.filter(rule => rule.confidence > 0.8 && rule.lift > 1.1).length;
    
    // Check if we have any data
    const hasData = allRules.length > 0;
    
    // Update statistics cards
    document.getElementById('avgConfidence').textContent = hasData ? avgConfidence + '%' : 'Tidak ada data';
    document.getElementById('avgSupport').textContent = hasData ? avgSupport + '%' : 'Tidak ada data';
    document.getElementById('avgLift').textContent = hasData ? avgLift : 'Tidak ada data';
    document.getElementById('qualityRules').textContent = hasData ? qualityRules : 'Tidak ada data';
    
    // Generate insights and recommendations
    if (hasData) {
        generateInsights(allRules, safeRules1to1, safeRules2to1, safeRules3to1, avgConfidence, avgSupport, avgLift, qualityRules);
    }
    
    // 1. Rules Distribution Chart
    const canvas1 = document.getElementById('rulesDistributionChart');
    if (!canvas1) return;
    const ctx1 = canvas1.getContext('2d');
    if (hasData) {
        new Chart(ctx1, {
            type: 'doughnut',
            data: {
                labels: ['Rules 1→1', 'Rules 2→1', 'Rules 3→1'],
                datasets: [{
                    data: [safeRules1to1.length, safeRules2to1.length, safeRules3to1.length],
                    backgroundColor: ['#E53E3E', '#3182CE', '#D69E2E'],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    title: { display: true, text: 'Distribusi Pola Hubungan CPL' }
                }
            }
        });
    } else {
        new Chart(ctx1, {
            type: 'doughnut',
            data: {
                labels: ['Tidak ada data'],
                datasets: [{
                    data: [1],
                    backgroundColor: ['#E2E8F0'],
                    borderColor: ['#CBD5E0'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    title: { display: true, text: 'Distribusi Jenis Association Rules' },
                    tooltip: { enabled: false }
                },
                elements: {
                    center: {
                        text: 'Tidak ada\ndata rules',
                        color: '#6c757d',
                        fontStyle: 'Arial',
                        sidePadding: 20,
                        minFontSize: 12,
                        lineHeight: 25
                    }
                }
            }
        });
    }
    
    // 2. Confidence Distribution Chart
    const canvas2 = document.getElementById('confidenceChart');
    if (!canvas2) return;
    const ctx2 = canvas2.getContext('2d');
    if (hasData) {
        const confidenceRanges = { '80-100%': 0, '60-79%': 0, '40-59%': 0, '0-39%': 0 };
        allRules.forEach(rule => {
            const conf = rule.confidence * 100;
            if (conf >= 80) confidenceRanges['80-100%']++;
            else if (conf >= 60) confidenceRanges['60-79%']++;
            else if (conf >= 40) confidenceRanges['40-59%']++;
            else confidenceRanges['0-39%']++;
        });
        
        const maxConfidenceValue = Math.max(...Object.values(confidenceRanges));
        const confidenceStepSize = maxConfidenceValue <= 5 ? 1 : Math.ceil(maxConfidenceValue / 10);
        
        new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: Object.keys(confidenceRanges),
                datasets: [{
                    label: 'Jumlah Rules',
                    data: Object.values(confidenceRanges),
                    backgroundColor: ['#38A169', '#68D391', '#D69E2E', '#E53E3E'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    title: { display: true, text: 'Tingkat Kepastian Hubungan CPL' }
                },
                scales: {
                    x: {
                        title: { display: true, text: 'Rentang Tingkat Kepastian (%)' }
                    },
                    y: { 
                        title: { display: true, text: 'Jumlah Rules' },
                        beginAtZero: true, 
                        ticks: { 
                            stepSize: confidenceStepSize 
                        }
                    }
                }
            }
        });
    } else {
        new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: ['Tidak ada data'],
                datasets: [{
                    label: 'Tidak ada data',
                    data: [0],
                    backgroundColor: ['#E2E8F0'],
                    borderColor: ['#CBD5E0'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    title: { display: true, text: 'Distribusi Tingkat Kepercayaan' },
                    tooltip: { enabled: false }
                },
                scales: {
                    y: { 
                        beginAtZero: true,
                        ticks: { display: false }
                    },
                    x: { ticks: { display: false } }
                }
            }
        });
    }
    
    // 3. Quality Scatter Chart (Support vs Confidence)
    const canvas3 = document.getElementById('qualityChart');
    if (!canvas3) return;
    const ctx3 = canvas3.getContext('2d');
    if (hasData) {
        const scatterData = {
            '1→1': allRules.filter(r => r.rule_type === '1to1').map(r => ({x: r.support * 100, y: r.confidence * 100})),
            '2→1': allRules.filter(r => r.rule_type === '2to1').map(r => ({x: r.support * 100, y: r.confidence * 100})),
            '3→1': allRules.filter(r => r.rule_type === '3to1').map(r => ({x: r.support * 100, y: r.confidence * 100}))
        };
        
        new Chart(ctx3, {
            type: 'scatter',
            data: {
                datasets: [
                    {
                        label: 'Hubungan Langsung (1→1)',
                        data: scatterData['1→1'],
                        backgroundColor: '#E53E3E',
                        borderColor: '#E53E3E'
                    },
                    {
                        label: 'Hubungan Kombinasi (2→1)',
                        data: scatterData['2→1'],
                        backgroundColor: '#3182CE',
                        borderColor: '#3182CE'
                    },
                    {
                        label: 'Hubungan Kompleks (3→1)',
                        data: scatterData['3→1'],
                        backgroundColor: '#D69E2E',
                        borderColor: '#D69E2E'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: { display: true, text: 'Pemetaan Kualitas Hubungan CPL' }
                },
                scales: {
                    x: { 
                        title: { display: true, text: 'Frekuensi Kemunculan (Support %)' },
                        beginAtZero: true
                    },
                    y: { 
                        title: { display: true, text: 'Tingkat Kepastian (Confidence %)' },
                        beginAtZero: true
                    }
                }
            }
        });
    } else {
        new Chart(ctx3, {
            type: 'scatter',
            data: {
                datasets: [{
                    label: 'Tidak ada data',
                    data: [],
                    backgroundColor: '#E2E8F0'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: { display: true, text: 'Kualitas Rules: Support vs Confidence' },
                    tooltip: { enabled: false },
                    legend: { display: false }
                },
                scales: {
                    x: { 
                        title: { display: true, text: 'Support (%)' },
                        beginAtZero: true
                    },
                    y: { 
                        title: { display: true, text: 'Confidence (%)' },
                        beginAtZero: true
                    }
                },
                elements: {
                    point: { radius: 0 }
                }
            }
        });
        
        // Add text overlay
        ctx3.save();
        ctx3.fillStyle = '#6c757d';
        ctx3.font = '16px Arial';
        ctx3.textAlign = 'center';
        ctx3.fillText('Tidak ada data rules untuk ditampilkan', ctx3.canvas.width/2, ctx3.canvas.height/2);
        ctx3.restore();
    }
    
    // 4. Lift Distribution Chart
    const canvas4 = document.getElementById('liftChart');
    if (!canvas4) return;
    const ctx4 = canvas4.getContext('2d');
    if (hasData) {
        const liftRanges = { '>1.5': 0, '1.1-1.5': 0, '0.9-1.1': 0, '<0.9': 0 };
        allRules.forEach(rule => {
            const lift = rule.lift;
            if (lift > 1.5) liftRanges['>1.5']++;
            else if (lift >= 1.1) liftRanges['1.1-1.5']++;
            else if (lift >= 0.9) liftRanges['0.9-1.1']++;
            else liftRanges['<0.9']++;
        });
        
        const maxLiftValue = Math.max(...Object.values(liftRanges));
        const liftStepSize = maxLiftValue <= 5 ? 1 : Math.ceil(maxLiftValue / 10);
        
        new Chart(ctx4, {
            type: 'bar',
            data: {
                labels: Object.keys(liftRanges),
                datasets: [{
                    label: 'Jumlah Rules',
                    data: Object.values(liftRanges),
                    backgroundColor: ['#38A169', '#68D391', '#D69E2E', '#E53E3E'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    title: { display: true, text: 'Kekuatan Hubungan CPL' }
                },
                scales: {
                    x: {
                        title: { display: true, text: 'Rentang Nilai Lift' }
                    },
                    y: { 
                        title: { display: true, text: 'Jumlah Rules' },
                        beginAtZero: true, 
                        ticks: { 
                            stepSize: liftStepSize 
                        }
                    }
                }
            }
        });
    } else {
        new Chart(ctx4, {
            type: 'bar',
            data: {
                labels: ['Tidak ada data'],
                datasets: [{
                    label: 'Tidak ada data',
                    data: [0],
                    backgroundColor: ['#E2E8F0'],
                    borderColor: ['#CBD5E0'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    title: { display: true, text: 'Distribusi Lift Values' },
                    tooltip: { enabled: false }
                },
                scales: {
                    y: { 
                        beginAtZero: true,
                        ticks: { display: false }
                    },
                    x: { ticks: { display: false } }
                }
            }
        });
    }
}

// Function to generate insights and recommendations
function generateInsights(allRules, rules1to1, rules2to1, rules3to1, avgConfidence, avgSupport, avgLift, qualityRules) {
    const findings = [];
    const recommendations = [];
    
    // Analyze rule distribution
    const totalRules = allRules.length;
    const strongRules = allRules.filter(rule => rule.confidence > 0.8 && rule.lift > 1.2).length;
    const weakRules = allRules.filter(rule => rule.confidence < 0.6 || rule.lift < 1.0).length;
    
    // Main findings
    if (strongRules > totalRules * 0.5) {
        findings.push(`<div class="alert alert-success p-2 mb-2"><i class="fas fa-check-circle"></i> <strong>Kualitas Tinggi:</strong> ${strongRules} dari ${totalRules} hubungan CPL menunjukkan kekuatan yang sangat baik</div>`);
    } else if (strongRules > totalRules * 0.3) {
        findings.push(`<div class="alert alert-warning p-2 mb-2"><i class="fas fa-exclamation-circle"></i> <strong>Kualitas Sedang:</strong> ${strongRules} dari ${totalRules} hubungan CPL cukup kuat, masih ada ruang perbaikan</div>`);
    } else {
        findings.push(`<div class="alert alert-danger p-2 mb-2"><i class="fas fa-times-circle"></i> <strong>Perlu Perhatian:</strong> Hanya ${strongRules} dari ${totalRules} hubungan CPL yang kuat</div>`);
    }
    
    // Confidence analysis
    if (avgConfidence > 80) {
        findings.push(`<div class="alert alert-success p-2 mb-2"><i class="fas fa-thumbs-up"></i> <strong>Kepastian Tinggi:</strong> Rata-rata tingkat kepercayaan ${avgConfidence}% menunjukkan pola yang konsisten</div>`);
    } else if (avgConfidence > 60) {
        findings.push(`<div class="alert alert-warning p-2 mb-2"><i class="fas fa-balance-scale"></i> <strong>Kepastian Sedang:</strong> Rata-rata tingkat kepercayaan ${avgConfidence}% masih dapat ditingkatkan</div>`);
    } else {
        findings.push(`<div class="alert alert-danger p-2 mb-2"><i class="fas fa-question-circle"></i> <strong>Kepastian Rendah:</strong> Rata-rata tingkat kepercayaan ${avgConfidence}% memerlukan evaluasi mendalam</div>`);
    }
    
    // Rule type distribution analysis
    const dominantType = rules1to1.length > rules2to1.length && rules1to1.length > rules3to1.length ? 'Langsung (1→1)' :
                        rules2to1.length > rules3to1.length ? 'Kombinasi (2→1)' : 'Kompleks (3→1)';
    findings.push(`<div class="alert alert-info p-2 mb-2"><i class="fas fa-chart-pie"></i> <strong>Pola Dominan:</strong> Hubungan ${dominantType} paling banyak ditemukan dalam data</div>`);
    
    // Generate recommendations based on findings
    if (strongRules < totalRules * 0.3) {
        recommendations.push(`<div class="alert alert-warning p-2 mb-2"><i class="fas fa-tools"></i> <strong>Perbaiki Metode Pembelajaran:</strong> Fokus pada peningkatan kualitas pembelajaran untuk memperkuat hubungan antar CPL</div>`);
    }
    
    if (avgConfidence < 70) {
        recommendations.push(`<div class="alert alert-info p-2 mb-2"><i class="fas fa-sync-alt"></i> <strong>Evaluasi Kurikulum:</strong> Tinjau kembali struktur kurikulum untuk meningkatkan konsistensi pencapaian CPL</div>`);
    }
    
    if (weakRules > totalRules * 0.3) {
        recommendations.push(`<div class="alert alert-danger p-2 mb-2"><i class="fas fa-exclamation-triangle"></i> <strong>Identifikasi CPL Bermasalah:</strong> ${weakRules} hubungan lemah perlu mendapat perhatian khusus</div>`);
    }
    
    // CPL-specific recommendations
    const cplFrequency = {};
    allRules.forEach(rule => {
        const allItems = [...(Array.isArray(rule.lhs) ? rule.lhs : [rule.lhs]), ...(Array.isArray(rule.rhs) ? rule.rhs : [rule.rhs])];
        allItems.forEach(item => {
            const match = item.match(/CPL (\d+)/);
            if (match) {
                const cplNum = match[1];
                cplFrequency[cplNum] = (cplFrequency[cplNum] || 0) + 1;
            }
        });
    });
    
    const mostFrequentCPL = Object.keys(cplFrequency).reduce((a, b) => cplFrequency[a] > cplFrequency[b] ? a : b, '1');
    const leastFrequentCPL = Object.keys(cplFrequency).reduce((a, b) => cplFrequency[a] < cplFrequency[b] ? a : b, '1');
    
    recommendations.push(`<div class="alert alert-success p-2 mb-2"><i class="fas fa-star"></i> <strong>CPL Kunci:</strong> CPL ${mostFrequentCPL} paling sering muncul dalam hubungan - pertahankan kualitas pembelajaran ini</div>`);
    recommendations.push(`<div class="alert alert-warning p-2 mb-2"><i class="fas fa-arrow-up"></i> <strong>Fokus Perbaikan:</strong> CPL ${leastFrequentCPL} jarang muncul dalam hubungan - tingkatkan pembelajaran untuk CPL ini</div>`);
    
    // Update the DOM
    document.getElementById('mainFindings').innerHTML = findings.join('');
    document.getElementById('actionRecommendations').innerHTML = recommendations.join('');
}

// Initialize charts when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    initializeCharts();
    
    // Wait a bit for Bootstrap to be fully loaded
    setTimeout(function() {
        initializeCollapseHandlers();
    }, 100);
});

function initializeCollapseHandlers() {
    // Handle collapse animation and chevron icon switching
    document.querySelectorAll('[data-bs-toggle="collapse"]').forEach(function(element) {
        const targetId = element.getAttribute('data-bs-target');
        const targetElement = document.querySelector(targetId);
        let chevron = element.querySelector('i.fa-chevron-down, i.fa-chevron-up');
        
        if (targetElement && chevron) {
            // Set initial chevron state based on collapse state
            if (targetElement.classList.contains('show')) {
                chevron.className = chevron.className.replace('fa-chevron-down', 'fa-chevron-up');
                element.setAttribute('aria-expanded', 'true');
            } else {
                chevron.className = chevron.className.replace('fa-chevron-up', 'fa-chevron-down');
                element.setAttribute('aria-expanded', 'false');
            }
            
            // Add event listeners for Bootstrap collapse events
            targetElement.addEventListener('show.bs.collapse', function() {
                const currentChevron = element.querySelector('i.fa-chevron-down, i.fa-chevron-up');
                if (currentChevron) {
                    currentChevron.className = currentChevron.className.replace('fa-chevron-down', 'fa-chevron-up');
                    element.setAttribute('aria-expanded', 'true');
                }
                // Remove inline style to let Bootstrap handle display
                targetElement.style.removeProperty('display');
            });
            
            targetElement.addEventListener('hide.bs.collapse', function() {
                const currentChevron = element.querySelector('i.fa-chevron-down, i.fa-chevron-up');
                if (currentChevron) {
                    currentChevron.className = currentChevron.className.replace('fa-chevron-up', 'fa-chevron-down');
                    element.setAttribute('aria-expanded', 'false');
                }
            });
            
            targetElement.addEventListener('hidden.bs.collapse', function() {
                // Set display none after collapse animation is done
                targetElement.style.display = 'none';
            });
        }
    });
}

// Also try to initialize immediately in case DOM is already loaded
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
        initializeCharts();
        setTimeout(initializeCollapseHandlers, 100);
    });
} else {
    initializeCharts();
    setTimeout(initializeCollapseHandlers, 100);
}
</script>

<style>
/* Collapsible card styles */
.card-header[data-bs-toggle="collapse"] {
    transition: all 0.2s ease;
}

.card-header[data-bs-toggle="collapse"]:hover {
    background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.card-header i.fas.fa-chevron-down,
.card-header i.fas.fa-chevron-up {
    transition: all 0.3s ease;
}

/* Additional styling for better interaction */
.card-header[data-bs-toggle="collapse"] {
    user-select: none;
}

.card-header[data-bs-toggle="collapse"]:active {
    transform: translateY(0px);
}

.collapse.show {
    animation: slideIn 0.3s ease;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Enhanced chart readability */
.bg-light {
    background-color: #f8f9fa !important;
    border: 1px solid #e9ecef;
    border-radius: 6px;
}

.alert {
    border-radius: 8px;
    border: 1px solid transparent;
}

.alert-warning {
    background-color: #fff3cd;
    border-color: #ffeaa7;
    color: #856404;
}

.alert-info {
    background-color: #d1ecf1;
    border-color: #b6d4da;
    color: #0c5460;
}

.alert-success {
    background-color: #d4edda;
    border-color: #c3e6cb;
    color: #155724;
}

.card-header h6 {
    font-size: 1rem;
    font-weight: 600;
}

.badge {
    font-size: 0.8rem;
    padding: 0.375rem 0.75rem;
}
</style>
@endpush