@extends('layouts.master')

@section('content')
    <div class="row mt-5">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header" style="background-color: #75FC51">
                    <h2>Products</h2>
                </div>
                <div class="card-body">
                    <h2>{{  $products ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-header" style="background-color: #FF89F3">
                   <h2>Orders</h2>
                </div>
                <div class="card-body">
                    <h2>{{  $orders ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-header" style="background-color: #FEFE6D">
                   <h2>Total Cost</h2>
                </div>
                <div class="card-body">
                    <h2>${{  $totalCost ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-header" style="background-color: #489DF1">
                   <h2>Total Sell</h2>
                </div>
                <div class="card-body">
                    <h2>${{  $totalSell ?? 0 }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-md-12">
            <canvas id="monthlySalesChart" width="400" height="120"></canvas>
        </div>
    </div>
@endsection

@section('custom_js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get the canvas element
        const ctx = document.getElementById('monthlySalesChart').getContext('2d');

        // Prepare data for the chart
        const data = {
            labels: @json($months),
            datasets: [{
                label: 'Monthly Sales',
                data: @json($sales),
                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        };

        // Create the chart instance
        const monthlySalesChart = new Chart(ctx, {
            type: 'bar',
            data: data,
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Sales ($)',
                        },
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Month',
                        },
                    }
                }
            }
        });
    });
</script>

@endsection