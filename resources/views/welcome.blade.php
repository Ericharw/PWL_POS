@extends('layouts.template')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Card 1 - Total Pengguna -->
        <div class="col-lg-3 col-md-6 col-12 mb-4">
            <div class="small-box bg-info shadow rounded">
                <div class="inner">
                    <h3>{{ $totalPengguna }}</h3>
                    <p>Total Pengguna</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <a href="{{ url('/user') }}" class="small-box-footer">
                    Selengkapnya <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Card 2 - Total Barang -->
        <div class="col-lg-3 col-md-6 col-12 mb-4">
            <div class="small-box bg-success shadow rounded">
                <div class="inner">
                    <h3>{{ $totalBarang }}</h3>
                    <p>Total Barang</p>
                </div>
                <div class="icon">
                    <i class="fas fa-boxes"></i>
                </div>
                <a href="{{ url('/barang') }}" class="small-box-footer">
                    Selengkapnya <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Card 3 - Total Stok -->
        <div class="col-lg-3 col-md-6 col-12 mb-4">
            <div class="small-box bg-primary shadow rounded">
                <div class="inner">
                    <h3>{{ $totalStok }}</h3>
                    <p>Total Stok</p>
                </div>
                <div class="icon">
                    <i class="fas fa-warehouse"></i>
                </div>
                <a href="{{ url('/stok') }}" class="small-box-footer">
                    Selengkapnya <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Card 4 - Total Penjualan -->
        <div class="col-lg-3 col-md-6 col-12 mb-4">
            <div class="small-box bg-danger shadow rounded">
                <div class="inner">
                    <h3>{{ $totalPenjualan }}</h3>
                    <p>Total Penjualan</p>
                </div>
                <div class="icon">
                    <i class="fas fa-cash-register"></i>
                </div>
                <a href="{{ url('/penjualan') }}" class="small-box-footer">
                    Selengkapnya <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Grafik Statistik -->
    <div class="card shadow mt-4">
        <div class="card-header bg-light">
            <h3 class="card-title mb-0">
                <i class="fas fa-chart-bar"></i> Statistik Pengguna (Per Bulan)
            </h3>
        </div>
        <div class="card-body">
            <canvas id="userChart" style="max-height: 400px;"></canvas>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('userChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni'],
            datasets: [
                {
                    label: 'Pengguna Aktif',
                    data: [1200, 1300, 1250, 1400, 1350, 1450],
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Pengguna Tidak Aktif',
                    data: [300, 350, 400, 450, 500, 550],
                    backgroundColor: 'rgba(255, 99, 132, 0.7)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 200
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top'
                }
            }
        }
    });
</script>
@endpush
