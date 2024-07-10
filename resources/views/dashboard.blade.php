@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <h2 class="ps-2">Dashboard</h2>
        <div class="row">
            <div class="col">

            </div>
        </div>
        <h2 class="ps-2">Grafici</h2>
        <div class="row m-0">
            <div class="col-md-6">
                <div class="card h-100 text-center">
                    <div class="card-header bg-white">Iscrizione clienti per mese</div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="barChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100 text-center">
                    <div class="card-header bg-white">Email inviate a {{ $current_month }}</div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas style="width: 100%" id="emailsDonutChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>







    <script>
        let ctx = document.getElementById('barChart').getContext('2d');
        let myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($data_bar['labels']),
                datasets: [{
                    label: 'Clienti',
                    data: @json($data_bar['data']),
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });



        let dataDonut = @json($data_donut);
        let ctxDonut = document.getElementById('emailsDonutChart').getContext('2d');
        let donutChart = new Chart(ctxDonut, {
            type: 'doughnut',
            data: {
                labels: dataDonut.labels,
                datasets: [{
                    data: dataDonut.data,
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.2)', // Colore per le email inviate
                        'rgba(211, 211, 211, 0.2)' // Colore per le email rimanenti
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)',
                        'rgba(211, 211, 211, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw + ' email';
                            }
                        }
                    }
                }
            }
        });
    </script>
@endsection
