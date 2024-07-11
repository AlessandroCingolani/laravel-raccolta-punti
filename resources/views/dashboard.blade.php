@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <h2 class="my-3 text-center">Dashboard</h2>
        <div class="row justify-content-center mb-4">
            <div class="col-md-3 mb-2">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fa-solid fa-users"></i></h5>
                        <h2 class="card-text">{{ $total_customer }}</h2>
                        <p class="card-text">Totale Clienti</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fa-solid fa-sack-dollar"></i></h5>
                        <h2 class="card-text">{{ $amount }} €</h2>
                        <p class="card-text">Incasso totale</p>
                    </div>
                </div>
            </div>
        </div>
        <h2 class="my-3 text-center">Grafici</h2>
        <div class="row p-0 m-0 my-4">
            <div class="col-12">
                <div class="card h-100 text-center">
                    <div class="card-header bg-white">Acquisti clienti per mese</div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="monthlySalesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mx-0 mb-5">
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
        // BAR
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
        // END BAR


        // DONUT
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
        // END DONUT


        // LINE
        var dataLine = @json($data_line);

        var ctxLine = document.getElementById('monthlySalesChart').getContext('2d');
        var lineChart = new Chart(ctxLine, {
            type: 'line',
            data: {
                labels: dataLine.labels,
                datasets: [{
                    label: 'Andamento Mensile degli Acquisti',
                    data: dataLine.data,
                    fill: false,
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
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
        // END LINE
    </script>
@endsection
