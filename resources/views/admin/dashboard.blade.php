@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start mb-4 gap-3">
        <div>
            <h1 class="h3 fw-bold text-uppercase mb-1">Dashboard Admin</h1>
            <p class="text-muted mb-0">Kelola dan pantau kondisi toko anda</p>
        </div>

        <div class="d-flex align-items-center gap-3 fw-semibold text-secondary small pt-1">
            <div class="d-flex align-items-center gap-2">
                <i class="far fa-calendar"></i>
                <div id="date"></div>
            </div>
            <div class="vr bg-secondary" style="height: 24px;"></div>
            <div id="clock"></div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12 col-md-4">
            <div class="card stat-card h-100 border p-3 bg-white shadow-sm">
                <div class="card-body d-flex align-items-center gap-4">
                    <div class="icon-circle bg-primary-subtle">
                        <i class="fas fa-thermometer-half text-primary fs-3"></i>
                    </div>
                    <div>
                        <p class="text-secondary small fw-semibold mb-1">Suhu Freezer saat ini</p>
                        <h2 class="fw-bold mb-1">-20 °C</h2>
                        <span class="badge bg-success-subtle text-success rounded-pill px-3 py-1 fw-bold">Normal</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card stat-card h-100 border p-3 bg-white shadow-sm">
                <div class="card-body d-flex align-items-center gap-4">
                    <div class="icon-circle bg-success-subtle">
                        <i class="fas fa-box text-success fs-3"></i>
                    </div>
                    <div>
                        <p class="text-secondary small fw-semibold mb-1">Total Produk</p>
                        <h2 class="fw-bold mb-1">{{ $totalProduk }}</h2>
                        <p class="text-secondary small fw-bold mb-0">Produk</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card stat-card h-100 border p-3 bg-white shadow-sm">
                <div class="card-body d-flex align-items-center gap-4">
                    <div class="icon-circle bg-warning-subtle">
                        <i class="fas fa-shopping-basket text-warning fs-3"></i>
                    </div>
                    <div>
                        <p class="text-secondary small fw-semibold mb-1">Produk Promo</p>
                        <h2 class="fw-bold mb-1">{{ $totalDiskon }}</h2>
                        <p class="text-secondary small fw-bold mb-0">Produk</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12 col-md-4">
            <div class="card stat-card border p-3 bg-white shadow-sm">
                <div class="card-body d-flex align-items-center gap-4">
                    <div class="icon-circle bg-purple-subtle" style="background-color: #f3e5f5;">
                        <i class="fas fa-thermometer-half fs-3" style="color: #8e24aa;"></i>
                    </div>
                    <div>
                        <p class="text-secondary small fw-semibold mb-1">Rerata Suhu Freezer</p>
                        <h2 class="fw-bold mb-1">-19 °C</h2>
                        <span class="badge bg-success-subtle text-success rounded-pill px-3 py-1 fw-bold">Normal</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-lg-8">
            <div class="card border p-4 bg-white shadow-sm h-100">
                <h3 class="h5 fw-bold mb-4">Grafik Suhu (Real-Time)</h3>
                <div class="w-100 bg-secondary-subtle rounded d-flex align-items-center justify-content-center" style="height: 256px;">
                    <span class="text-muted fw-semibold fst-italic">Area Grafik</span>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="card border p-4 bg-white shadow-sm h-100 d-flex flex-column">
                <h3 class="h5 fw-bold mb-3 pb-2 border-b">Status Sistem</h3>
                <div class="d-flex flex-column flex-grow-1 justify-content-between">
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom border-light">
                        <span class="fw-bold small">Mode</span>
                        <span class="badge bg-success-subtle text-success rounded-pill px-3 py-1 fw-bold">Arduino</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom border-light">
                        <span class="fw-bold small">Koneksi</span>
                        <span class="badge bg-success-subtle text-success rounded-pill px-3 py-1 fw-bold">Connected</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom border-light">
                        <span class="fw-bold small">Sumber Data</span>
                        <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-1 fw-bold">Serial COM3</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-2">
                        <span class="fw-bold small">Update Terakhir</span>
                        <span class="small fw-bold">15:56:32</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border p-3 bg-white shadow-sm mt-4">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-dark text-white rounded-circle d-flex align-items-center justify-content-center text-xs" style="width: 24px; height: 24px;">
                <i class="fas fa-info" style="font-size: 11px;"></i>
            </div>
            <p class="small fw-bold mb-0">Sistem berjalan normal. Data suhu diperbarui setiap 5 menit.</p>
        </div>
    </div>
@endsection

@push('scripts')
<script>
function updateTime(){
    const now = new Date();
    document.getElementById("clock").innerHTML = now.toLocaleTimeString();
    document.getElementById("date").innerHTML = now.toLocaleDateString('id-ID', {
        day: '2-digit', month: 'long', year: 'numeric'
    });
}
setInterval(updateTime, 1000);
updateTime();
</script>
@endpush