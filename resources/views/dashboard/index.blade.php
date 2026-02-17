@extends('layouts.app')

@section('title', 'Dashboard - SiAC')
@section('page-title', 'Selamat Datang di SiAC')

@section('content')
<div class="row mb-4">
    <!-- Statistics Cards -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2" style="border-left: 4px solid #4CAF50;">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Mahasiswa
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalMahasiswa }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2" style="border-left: 4px solid #1cc88a;">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Total CPL
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalCPL }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-list-alt fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2" style="border-left: 4px solid #36b9cc;">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Total Analisis
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalAnalisis }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2" style="border-left: 4px solid #f6c23e;">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Total Rules
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalRules }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-project-diagram fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Chart Area -->
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-white">Nilai CPL Mahasiswa</h6>
                <div class="dropdown no-arrow">
                    <select id="chartType" class="form-select form-select-sm" style="width: auto;">
                        <option value="bar">Bar Chart</option>
                        <option value="pie">Pie Chart</option>
                        <option value="line">Line Chart</option>
                    </select>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="myAreaChart" style="height: 320px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Pie Chart -->
    <div class="col-xl-4 col-lg-5">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-white">Profil Lulusan Mahasiswa Prodi SI Teknik Industri</h6>
            </div>
            <div class="card-body">
                <div class="chart-pie pt-4 pb-2">
                    <canvas id="myPieChart"></canvas>
                </div>
                <div class="mt-4 text-center small">
                    <span class="mr-2">
                        <i class="fas fa-circle" style="color: #4e73df;"></i> Studi Lanjut
                    </span>
                    <span class="mr-2">
                        <i class="fas fa-circle" style="color: #1cc88a;"></i> Wirausaha
                    </span>
                    <span class="mr-2">
                        <i class="fas fa-circle" style="color: #36b9cc;"></i> Pegawai Profesional
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Analysis Table -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-white">Analisis Terbaru</h6>
    </div>
    <div class="card-body">
        @if($recentAnalysis->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Angkatan</th>
                            <th>Deskripsi</th>
                            <th>Total Rules</th>
                            <th>Min Support</th>
                            <th>Min Confidence</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentAnalysis as $analysis)
                        <tr>
                            <td>{{ $analysis->tanggal->format('d/m/Y') }}</td>
                            <td>{{ $analysis->angkatan }}</td>
                            <td>{{ $analysis->deskripsi }}</td>
                            <td>
                                <span class="badge bg-primary">{{ $analysis->total_rules }}</span>
                            </td>
                            <td>{{ $analysis->min_support }}</td>
                            <td>{{ $analysis->min_confidence }}</td>
                            <td>
                                <a href="{{ route('analisis.show', $analysis) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> Lihat Detail
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-4">
                <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                <p class="text-muted">Belum ada analisis yang dilakukan</p>
                <a href="{{ route('analisis.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Buat Analisis Baru
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Chart.js configuration
    const ctx1 = document.getElementById('myAreaChart').getContext('2d');
    const ctx2 = document.getElementById('myPieChart').getContext('2d');
    
    // Area Chart Data (real CPL distribution)
    const areaChart = new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: [],
            datasets: [{
                label: 'Jumlah Data CPL',
                data: [],
                backgroundColor: 'rgba(229, 62, 62, 0.8)',
                borderColor: 'rgba(229, 62, 62, 1)',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                title: {
                    display: true,
                    text: 'Distribusi Kategori Nilai CPL'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: function(context) {
                            const max = Math.max(...context.chart.data.datasets[0].data);
                            if (max > 3000) return 500;
                            if (max > 1000) return 200;
                            if (max > 500) return 100;
                            if (max > 100) return 20;
                            return 10;
                        }
                    }
                }
            }
        }
    });

    // Pie Chart Data (Graduate Profile)
    const pieChart = new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: ['Studi Lanjut', 'Wirausaha', 'Pegawai Profesional'],
            datasets: [{
                data: [45, 25, 30],
                backgroundColor: ['#E53E3E', '#718096', '#3182CE'],
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    // Chart type switcher
    document.getElementById('chartType').addEventListener('change', function() {
        const newType = this.value;
        areaChart.config.type = newType;
        areaChart.update();
    });

    // Fetch real CPL distribution data
    fetch('{{ route("dashboard.chart-data") }}?type=cpl_distribution')
        .then(response => response.json())
        .then(data => {
            if (data && Object.keys(data).length > 0) {
                areaChart.data.labels = Object.keys(data);
                areaChart.data.datasets[0].data = Object.values(data);
                areaChart.update();
            }
        })
        .catch(error => console.error('Error fetching chart data:', error));
});
</script>
@endpush 