@extends('layouts.app')

@section('title', 'Data CPL - SiAC')
@section('page-title', 'Analisis CPL')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Data CPL</h4>
    <a href="{{ route('cpl.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah CPL
    </a>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2" style="border-left: 4px solid #4CAF50;">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total CPL
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalCpl }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-list-alt fa-2x text-gray-300"></i>
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
                            CPL Aktif
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activeCpl }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
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
                            Total Kategori
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalKategori }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-tags fa-2x text-gray-300"></i>
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
                            CPL Non-Aktif
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $inactiveCpl }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row mb-4">
    <!-- CPL by Category Chart -->
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-white">
                    <i class="fas fa-chart-bar"></i> Distribusi CPL per Kategori
                </h6>
                <div class="btn-group btn-group-sm" role="group">
                    <button type="button" class="btn btn-sm btn-outline-light" onclick="switchChartType('horizontal')" id="btnHorizontal">
                        <i class="fas fa-bars"></i> Horizontal
                    </button>
                    <button type="button" class="btn btn-sm btn-light" onclick="switchChartType('doughnut')" id="btnDoughnut">
                        <i class="fas fa-chart-pie"></i> Doughnut
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="cplCategoryChart" style="height: 350px;"></canvas>
                </div>
                <!-- Legend untuk kategori -->
                <div class="mt-3" id="categoryLegend">
                    <h6 class="mb-2">Kategori CPL:</h6>
                    <div class="row">
                        @foreach($chartData['categories']['labels'] as $index => $kategori)
                        <div class="col-12 mb-2">
                            <div class="d-flex align-items-center">
                                <div class="legend-color me-2" style="width: 20px; height: 20px; border-radius: 3px; background-color: 
                                    @if($index == 0) #4CAF50
                                    @elseif($index == 1) #2196F3
                                    @elseif($index == 2) #FFC107
                                    @elseif($index == 3) #F44336
                                    @elseif($index == 4) #9C27B0
                                    @else #FF9800
                                    @endif; border: 1px solid rgba(0,0,0,0.1);"></div>
                                <small class="text-muted">
                                    <strong>Kategori {{ $index + 1 }}:</strong> 
                                    {{ Str::limit($kategori, 80) }}
                                    @if(strlen($kategori) > 80)
                                        <span class="text-info" data-bs-toggle="tooltip" title="{{ $kategori }}">[...]</span>
                                    @endif
                                </small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CPL Status Chart -->
    <div class="col-xl-4 col-lg-5">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-white">
                    <i class="fas fa-chart-pie"></i> Status CPL
                </h6>
            </div>
            <div class="card-body">
                <div class="chart-pie pt-4 pb-2">
                    <canvas id="cplStatusChart"></canvas>
                </div>
                <div class="mt-4 text-center small">
                    <span class="mr-2">
                        <i class="fas fa-circle" style="color: #1cc88a;"></i> Aktif
                    </span>
                    <span class="mr-2">
                        <i class="fas fa-circle" style="color: #e74a3b;"></i> Non-Aktif
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h6 class="m-0 font-weight-bold text-white">
            <i class="fas fa-list-alt"></i> Daftar Capaian Pembelajaran Lulusan (CPL)
        </h6>
    </div>
    <div class="card-body p-0">
        @if($cpls->count() > 0)
            <div class="table-responsive" style="max-height: 600px; overflow-x: auto;">
                <table class="table table-bordered table-hover mb-0">
                    <thead class="table-light sticky-top">
                        <tr>
                            <th style="width: 60px; min-width: 60px;" class="text-center">No</th>
                            <th style="width: 120px; min-width: 120px;">Kode CPL</th>
                            <th style="width: 200px; min-width: 200px;">Kategori</th>
                            <th style="min-width: 300px;">Deskripsi</th>
                            <th style="width: 100px; min-width: 100px;" class="text-center">Status</th>
                            <th style="width: 140px; min-width: 140px;" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cpls as $index => $cpl)
                        <tr>
                            <td class="text-center">{{ $cpls->firstItem() + $index }}</td>
                            <td>
                                <span class="fw-bold text-primary">{{ $cpl->kode_cpl }}</span>
                            </td>
                            <td>
                                @php
                                    $badgeStyle = match($cpl->kategori) {
                                        // 6 kategori utama sesuai dengan chart colors
                                        'Kemampuan penguasaan teknik umum dan TIK dalam upaya implementasi keilmuaan Teknik Industri' => 'background-color: #4CAF50; color: white;',
                                        'Kemampuan perancangan dan penelitian pada objek sistem integrasian TIK dalam upaya implementasi keilmuaan Teknik Industri' => 'background-color: #2196F3; color: white;',
                                        'Kemampuan perumusan solusi permasalahan pada objek Teknik Industri' => 'background-color: #FFC107; color: black;',
                                        'Penguasaan aspek non-akademis pendukung' => 'background-color: #F44336; color: white;',
                                        'Penguasaan dan penerapan ilmu dasar sains dan matematik' => 'background-color: #9C27B0; color: white;',
                                        'Penguasaan keilmuaan pendukung kewirausahaan' => 'background-color: #FF9800; color: white;',
                                        // Legacy categories
                                        'Sikap' => 'background-color: #4CAF50; color: white;',
                                        'Pengetahuan' => 'background-color: #2196F3; color: white;',
                                        'Keterampilan Umum' => 'background-color: #FFC107; color: black;',
                                        'Keterampilan Khusus' => 'background-color: #F44336; color: white;',
                                        default => 'background-color: #9E9E9E; color: white;'
                                    };
                                @endphp
                                <span class="badge" style="font-size: 0.75rem; white-space: normal; line-height: 1.3; {{ $badgeStyle }}">
                                    {{ $cpl->kategori }}
                                </span>
                            </td>
                            <td style="padding: 12px 8px;">
                                <div style="max-width: 400px; word-wrap: break-word; line-height: 1.4;">
                                    {{ $cpl->deskripsi }}
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="form-check form-switch d-flex justify-content-center">
                                    <input class="form-check-input" type="checkbox" 
                                           id="switch{{ $cpl->id }}" 
                                           {{ $cpl->is_active ? 'checked' : '' }}
                                           onchange="toggleActive({{ $cpl->id }})">
                                </div>
                                <span class="badge {{ $cpl->is_active ? 'bg-success' : 'bg-secondary' }} mt-1" 
                                      id="badge{{ $cpl->id }}" style="font-size: 0.7rem;">
                                    {{ $cpl->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group-vertical" role="group">
                                    <a href="{{ route('cpl.show', $cpl) }}" 
                                       class="btn btn-sm btn-info mb-1" title="Lihat Detail" style="font-size: 0.75rem;">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('cpl.edit', $cpl) }}" 
                                       class="btn btn-sm btn-warning mb-1" title="Edit" style="font-size: 0.75rem;">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger" 
                                            onclick="confirmDelete({{ $cpl->id }}, '{{ $cpl->kode_cpl }}')" 
                                            title="Hapus" style="font-size: 0.75rem;">
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
            <div class="d-flex justify-content-between align-items-center mt-3 px-3 pb-3">
                <div>
                    <small class="text-muted">
                        Menampilkan {{ $cpls->firstItem() }} sampai {{ $cpls->lastItem() }} 
                        dari {{ $cpls->total() }} CPL
                    </small>
                </div>
                <div>
                    {{ $cpls->links() }}
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-list-alt fa-3x text-muted mb-3"></i>
                <h5>Belum ada data CPL</h5>
                <p class="text-muted">Tambahkan CPL untuk memulai analisis capaian pembelajaran</p>
                <a href="{{ route('cpl.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah CPL Pertama
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
                <p>Apakah Anda yakin ingin menghapus CPL: <strong id="deleteCpl"></strong>?</p>
                <p class="text-danger"><i class="fas fa-exclamation-triangle"></i> Data nilai mahasiswa untuk CPL ini akan terpengaruh!</p>
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

@push('styles')
<style>
/* Chart container improvements */
.chart-area {
    position: relative;
    padding: 10px;
}

/* Legend styling */
#categoryLegend {
    background-color: #f8f9fa;
    border-radius: 8px;
    padding: 15px;
    border: 1px solid #e9ecef;
}

.legend-color {
    border: 1px solid rgba(0,0,0,0.1);
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

/* Button styling */
.btn-group .btn {
    transition: all 0.2s ease-in-out;
}

.btn-group .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

/* Responsive improvements */
@media (max-width: 768px) {
    .chart-area canvas {
        height: 250px !important;
    }
    
    #categoryLegend .col-12 {
        font-size: 0.8rem;
    }
    
    .btn-group {
        flex-direction: column;
    }
    
    .btn-group .btn {
        margin-bottom: 2px;
    }
}
</style>
@endpush

@push('scripts')
<script>
function confirmDelete(id, kodeCpl) {
    document.getElementById('deleteCpl').textContent = kodeCpl;
    document.getElementById('deleteForm').action = `/cpl/${id}`;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

function toggleActive(id) {
    const switchElement = document.getElementById(`switch${id}`);
    const badgeElement = document.getElementById(`badge${id}`);
    
    fetch(`/cpl/${id}/toggle-active`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (data.is_active) {
                badgeElement.classList.remove('bg-secondary');
                badgeElement.classList.add('bg-success');
                badgeElement.textContent = 'Aktif';
            } else {
                badgeElement.classList.remove('bg-success');
                badgeElement.classList.add('bg-secondary');
                badgeElement.textContent = 'Nonaktif';
            }
            
            // Show success message
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-success alert-dismissible fade show';
            alertDiv.innerHTML = `
                <i class="fas fa-check-circle me-2"></i>
                ${data.message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            const mainContent = document.querySelector('.main-content');
            mainContent.insertBefore(alertDiv, mainContent.firstChild);
            
            // Auto dismiss after 3 seconds
            setTimeout(() => {
                alertDiv.remove();
            }, 3000);
        } else {
            // Revert switch if failed
            switchElement.checked = !switchElement.checked;
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        // Revert switch if failed
        switchElement.checked = !switchElement.checked;
        console.error('Error:', error);
        alert('Terjadi error saat mengubah status CPL');
    });
}

// Global chart variable
let categoryChart;
let currentChartType = 'horizontal';

// Chart data
const chartLabels = {!! json_encode($chartData['categories']['labels']) !!};
const chartData = {!! json_encode($chartData['categories']['data']) !!};

// Shortened labels for better display
const shortLabels = chartLabels.map((label, index) => `Kategori ${index + 1}`);

const chartColors = [
    '#4CAF50',  // Green - Kemampuan penguasaan teknik umum dan TIK
    '#2196F3',  // Blue - Kemampuan perancangan dan penelitian pada objek sistem integrasian TIK
    '#FFC107',  // Yellow - Kemampuan perumusan solusi permasalahan pada objek Teknik Industri
    '#F44336',  // Red - Penguasaan aspek non-akademis pendukung
    '#9C27B0',  // Purple - Penguasaan dan penerapan ilmu dasar sains dan matematik
    '#FF9800',  // Orange - Penguasaan keilmuaan pendukung kewirausahaan
    '#795548',  // Brown - Extra color
    '#9E9E9E'   // Gray - Extra color
];

const borderColors = [
    '#388E3C',  // Dark Green
    '#1976D2',  // Dark Blue
    '#F57F17',  // Dark Yellow
    '#D32F2F',  // Dark Red
    '#7B1FA2',  // Dark Purple
    '#F57C00',  // Dark Orange
    '#5D4037',  // Dark Brown
    '#616161'   // Dark Gray
];

function createCategoryChart(type) {
    const ctx1 = document.getElementById('cplCategoryChart').getContext('2d');
    
    if (categoryChart) {
        categoryChart.destroy();
    }
    
    let config = {};
    
    if (type === 'horizontal') {
        config = {
            type: 'bar',
            data: {
                labels: shortLabels,
                datasets: [{
                    label: 'Jumlah CPL',
                    data: chartData,
                    backgroundColor: chartColors.slice(0, chartData.length),
                    borderColor: borderColors.slice(0, chartData.length),
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y', // This makes it horizontal
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: 'Distribusi CPL berdasarkan Kategori',
                        font: {
                            size: 14,
                            weight: 'bold'
                        }
                    },
                    tooltip: {
                        callbacks: {
                            title: function(context) {
                                const index = context[0].dataIndex;
                                return chartLabels[index];
                            },
                            label: function(context) {
                                return `Jumlah CPL: ${context.parsed.x}`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    },
                    y: {
                        ticks: {
                            maxRotation: 0,
                            font: {
                                size: 12
                            }
                        }
                    }
                }
            }
        };
    } else if (type === 'doughnut') {
        config = {
            type: 'doughnut',
            data: {
                labels: shortLabels,
                datasets: [{
                    data: chartData,
                    backgroundColor: chartColors.slice(0, chartData.length),
                    borderColor: borderColors.slice(0, chartData.length),
                    borderWidth: 2,
                    hoverOffset: 4
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
                        text: 'Distribusi CPL berdasarkan Kategori',
                        font: {
                            size: 14,
                            weight: 'bold'
                        }
                    },
                    tooltip: {
                        callbacks: {
                            title: function(context) {
                                const index = context[0].dataIndex;
                                return chartLabels[index];
                            },
                            label: function(context) {
                                const total = chartData.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? ((context.raw / total) * 100).toFixed(1) : 0;
                                return `Jumlah: ${context.raw} CPL (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        };
    }
    
    categoryChart = new Chart(ctx1, config);
}

function switchChartType(type) {
    currentChartType = type;
    createCategoryChart(type);
    
    // Update button states
    document.getElementById('btnHorizontal').className = type === 'horizontal' 
        ? 'btn btn-sm btn-light' 
        : 'btn btn-sm btn-outline-light';
    document.getElementById('btnDoughnut').className = type === 'doughnut' 
        ? 'btn btn-sm btn-light' 
        : 'btn btn-sm btn-outline-light';
}

// Charts
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Initialize CPL Category Chart (default to horizontal)
    createCategoryChart('horizontal');

    // CPL Status Chart
    const ctx2 = document.getElementById('cplStatusChart').getContext('2d');
    const statusChart = new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: ['Aktif', 'Non-Aktif'],
            datasets: [{
                data: [{{ $activeCpl }}, {{ $inactiveCpl }}],
                backgroundColor: ['#1cc88a', '#e74a3b'],
                hoverOffset: 4
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
                    text: 'Status CPL'
                }
            }
        }
    });
});
</script>
@endpush 