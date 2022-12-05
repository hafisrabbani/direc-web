@extends('admin.layout.main')
@section('title', 'Halaman Dashboard')
@section('sub-title', 'Overview')
@section('css')
<link rel="stylesheet" href="{{ asset('assets/css/pages/fontawesome.css') }}" />
@endsection
@section('card')
<div class="row">
    <div class="col-6 col-lg-4 col-md-6">
        <div class="card">
            <div class="card-body px-4 py-4-5">
                <div class="row">
                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                        <div class="stats-icon purple mb-2">
                            <i class="fas fa-money-bill"></i>
                        </div>
                    </div>
                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                        <h6 class="text-muted font-semibold">
                            Profit Todays
                        </h6>
                        <h6 class="font-extrabold mb-0">Rp.{{ number_format($profitToday->total) }}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-4 col-md-6">
        <div class="card">
            <div class="card-body px-4 py-4-5">
                <div class="row">
                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                        <div class="stats-icon blue mb-2">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                        <h6 class="text-muted font-semibold">Total Pasien</h6>
                        <h6 class="font-extrabold mb-0">{{ $pasien }}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-4 col-md-6">
        <div class="card">
            <div class="card-body px-4 py-4-5">
                <div class="row">
                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                        <div class="stats-icon green mb-2">
                            <i class="fas fa-notes-medical"></i>
                        </div>
                    </div>
                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                        <h6 class="text-muted font-semibold">Rekam Medis</h6>
                        <h6 class="font-extrabold mb-0">{{ $rekamMedis }}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('content')
<div class="row">
    <div class="col-12 col-lg-12 col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Grafik Penyakit</h4>
            </div>
            <div class="card-body">
                <canvas id="chart"></canvas>
            </div>
        </div>
    </div>
    @endsection
    @push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js">
    </script>
    <script src="{{ asset('assets/extensions/jquery/jquery.min.js') }}"></script>
    <script>
        var chart = new Chart(document.getElementById("chart"),
            {
                type: 'doughnut',
                data: {
                    labels: [
                        @foreach($groupRekam as $item)
        "{{ $item->disiase->name }}",
                        @endforeach

                    ],
        datasets: [{
            label: 'Jumlah Pasien',
            data: [
                @foreach($groupRekam as $item)
                    {{ $item->total }},
                @endforeach
            ],

        backgroundColor: [
            'rgba(255, 99, 132, 0.2)',
            'rgba(54, 162, 235, 0.2)',
            'rgba(255, 206, 86, 0.2)',
            'rgba(75, 192, 192, 0.2)',
            'rgba(153, 102, 255, 0.2)',
            'rgba(255, 159, 64, 0.2)'
        ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
                borderWidth: 1
        }]
                },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
            });
    </script>
    @endpush
