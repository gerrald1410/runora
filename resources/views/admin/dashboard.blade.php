@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<div id="main-wrapper">
    <div class="mb-4">
        <h4 class="fw-bold mb-1" style="letter-spacing: -0.5px;">DASHBOARD ADMIN</h4>
        <p class="text-muted small">Kelola dan pantau kondisi toko anda</p>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-md-4">
            <div class="card stat-card p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-blue-soft"><i class="bi bi-wallet2"></i></div>
                    <div>
                        <span class="text-muted small d-block">Total Penjualan</span>
                        <h5 class="fw-bold mb-0">Rp 120.000.000</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card stat-card p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-green-soft"><i class="bi bi-archive"></i></div>
                    <div>
                        <span class="text-muted small d-block">Total Pesanan</span>
                        <h5 class="fw-bold mb-0">{{ number_format($totalPesanan) }} <span class="text-muted fs-6 fw-normal">Produk</span></h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card stat-card p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-orange-soft"><i class="bi bi-bag"></i></div>
                    <div>
                        <span class="text-muted small d-block">Total Produk</span>
                        <h5 class="fw-bold mb-0">{{ number_format($totalProduk) }} <span class="text-muted fs-6 fw-normal">Produk</span></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card stat-card p-4 mb-4">
        <h6 class="fw-bold mb-3">Top 5 Produk Terlaris</h6>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th width="60">No</th>
                        <th>Produk</th>
                        <th class="text-center">Terjual</th>
                        <th class="text-end">Pendapatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topProduk as $index => $item)
                    <tr>
                        <td class="text-muted">{{ $index + 1 }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-light rounded" style="width: 40px; height: 40px; display:flex; align-items:center; justify-content:center;">
                                    <i class="bi bi-image text-muted"></i>
                                </div>
                                <span class="fw-medium">{{ $item->nama_produk }}</span>
                            </div>
                        </td>
                        <td class="text-center fw-medium text-muted">{{ $item->total_terjual }}</td>
                        <td class="text-end fw-bold">Rp {{ number_format($item->total_pendapatan, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12 col-md-5">
            <div class="card stat-card p-4 h-100">
                <h6 class="fw-bold mb-3">Stok Produk</h6>
                <div class="d-flex flex-column gap-2">
                    <div class="indicator-box d-flex justify-content-between align-items-center">
                        <span class="small text-muted"><i class="bi bi-exclamation-triangle text-warning me-2"></i> Stok Menipis (&lt;10)</span>
                        <span class="fw-bold small">{{ $stokMenipis }} Produk</span>
                    </div>
                    <div class="indicator-box d-flex justify-content-between align-items-center">
                        <span class="small text-muted"><i class="bi bi-box-seam text-success me-2"></i> Stok Tersedia</span>
                        <span class="fw-bold small">{{ $stokTersedia }} Produk</span>
                    </div>
                    <div class="indicator-box d-flex justify-content-between align-items-center">
                        <span class="small text-muted"><i class="bi bi-x-circle text-danger me-2"></i> Stok Habis</span>
                        <span class="fw-bold small">{{ $stokHabis }} Produk</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-7">
            <div class="card stat-card p-4 h-100">
                <h6 class="fw-bold mb-3">Aktivitas Terbaru</h6>
                <div class="d-flex flex-column gap-3">
                    @foreach($aktivitasTerbaru as $act)
                    <div class="d-flex align-items-start justify-content-between border-bottom pb-2">
                        <div class="d-flex gap-3">
                            <div class="stat-icon {{ $act->tipe == 'produk_ditambah' ? 'bg-green-soft' : 'bg-orange-soft' }}" style="width: 36px; height: 36px;">
                                <i class="bi {{ $act->tipe == 'produk_ditambah' ? 'bi-plus-circle' : 'bi-bag-dash' }} fs-6"></i>
                            </div>
                            <div>
                                <span class="d-block fw-medium small">{{ $act->deskripsi }}</span>
                                <small class="text-muted text-xs">Oleh: {{ $act->user_name }}</small>
                            </div>
                        </div>
                        <small class="text-muted" style="font-size: 0.75rem;">{{ $act->waktu }}</small>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-md-8">
            <div class="card stat-card p-4">
                <h6 class="fw-bold mb-3">Grafik Penjualan</h6>
                <div style="height: 250px; position: relative;">
                    <canvas id="chartPenjualan"></canvas>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card stat-card p-4">
                <h6 class="fw-bold mb-3">Status Pesanan</h6>
                <div class="d-flex flex-column align-items-center" style="position: relative;">
                    <div style="max-height: 180px; max-width: 180px;">
                        <canvas id="chartStatus"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // ── DATA GRAPH FROM CONTROLLER ──
    const salesData = @json($grafikPenjualan);
    const orderStatus = @json($statusPesanan);

    // 1. Chart Penjualan (Bar Chart Line Style Soft)
    new Chart(document.getElementById('chartPenjualan'), {
        type: 'bar',
        data: {
            labels: salesData.map(d => d.bulan),
            datasets: [{
                data: salesData.map(d => d.total),
                backgroundColor: 'rgba(192, 57, 43, 0.1)',
                borderColor: '#C0392B',
                borderWidth: 2,
                borderRadius: 4,
                barPercentage: 0.5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: '#F2F4F4' }, ticks: { font: { size: 10 } } },
                x: { grid: { display: false }, ticks: { font: { size: 10 } } }
            }
        }
    });

    // 2. Chart Status Pesanan (Doughnut Chart)
    new Chart(document.getElementById('chartStatus'), {
        type: 'doughnut',
        data: {
            labels: ['Menunggu Pembayaran', 'Diproses', 'Dikirim', 'Selesai'],
            datasets: [{
                data: [
                    orderStatus.menunggu_pembayaran || 0,
                    orderStatus.diproses || 0,
                    orderStatus.dikirim || 0,
                    orderStatus.selesai || 0
                ],
                backgroundColor: ['#F1C40F', '#3498DB', '#2ECC71', '#9B59B6'],
                borderWidth: 4,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 11 } } }
            }
        }
    });
});
</script>
@endpush