@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')
    <h2>All Users</h2>
    <div class="row">
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-body">
                    <h5 class="card-title">Total Users</h5>
                    <p class="card-text" id="totalUsers">{{ number_format($totalUsers) }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-body">
                    <h5 class="card-title">New Messages</h5>
                    <p class="card-text">5 Unread</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-body">
                    <h5 class="card-title">Notifications</h5>
                    <p class="card-text" id="latestMessage">
                        {{ $latestMessage }} new messages
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="chart-container">
        <div class="card shadow">
            <div class="card-body">
        <canvas id="userChart"></canvas>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
 document.addEventListener("DOMContentLoaded", function () {
    const ctx = document.getElementById('userChart').getContext('2d');
    let userChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [], // Initially empty
            datasets: [
                {
                    label: 'Users Registered',
                    data: [],
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                    fill: true,
                    tension: 0.4
                },
                {
                    label: 'Users Messages',
                    data: [],
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    fill: true,
                    tension: 0.4
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true
                }
            },
            scales: {
                x: { grid: { display: false } },
                y: { beginAtZero: true }
            }
        }
    });

    function fetchLiveData() {
        fetch('/api/live-user-stats')
            .then(response => response.json())
            .then(data => {
                userChart.data.labels = data.userRegistrations.map(entry => entry.date);
                userChart.data.datasets[1].data = data.userRegistrations.map(entry => entry.count);
                userChart.data.datasets[0].data = data.userMessages.map(entry => entry.count);
                userChart.update();
            })
            .catch(error => console.error('Error fetching data:', error));
    }

    fetchLiveData();
    // setInterval(fetchLiveData, 5000); 
});

</script>
@endsection
