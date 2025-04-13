@extends('layouts.app')

@section('head')
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
          integrity="sha512-papNM8P8DlHOq1ARFaQOjYlpH80YAF06lmqzKwbI94uIg/NSWVhhaQcGh2Qs5kn3Z5d9vSB2+9Yaj8Qbx9Zfkw=="
          crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection

@section('content')
    <div class="flex flex-col w-full items-center relative">
        <div class="w-full h-[250px] overflow-x-hidden [&::-webkit-scrollbar]:hidden pointer-events-none">
            <img src="/images/almonds.png" class="w-full">
        </div>
        <div class="w-[160px] h-[160px] mb-8 mt-[-80px]">
            <img src="/images/professional-investor-profile-picture.png" class="h-full w-full rounded-full">
        </div>
        <h1 class="text-2xl font-bold">{{ $investor->name }}</h1>
        <h2 class="text-lg font-semibold text-green-800 mb-16">High Ranking Investor</h2>
        <div class="flex flex-col gap-2 shadow-lg p-8 rounded-2xl border-2 w-[40%]">
            @if($investor->user)
                <div class="flex justify-between">
                    <span class="font-bold">Invested:</span>
                    <span class="font-bold text-green-800">{{ $investor->total_invested }}€</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-bold">Earned back:</span>
                    <span class="font-bold text-green-800">{{ $investor->earned_price }}€</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-bold">Most profitable project so far:</span>
                    <span class="font-bold text-green-800">{{ $investor->most_profitable_project?->name }}</span>
                </div>
            @else
                <p>No associated user found.</p>
            @endif
        </div>
    </div>

    <div class="mt-16 w-full flex flex-col items-center">
        <h3 class="text-xl font-bold mb-4">Your Investment Per Year</h3>
        <div class="w-[40%] p-6 shadow-lg rounded-2xl border-2 bg-white">
            <canvas id="annualInvestmentChart"></canvas>
        </div>
    </div>

    <div class="mt-16 w-full flex flex-col items-center">
        <h3 class="text-xl font-bold mb-4">Your Allocations</h3>
        <div class="w-[40%] p-6 shadow-lg rounded-2xl border-2 bg-white flex justify-center">
            <div class="w-80 h-80">
                <canvas id="allocationChart"></canvas>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            /***************************************
             * Investment Per Year - Line Chart
             ***************************************/
            const annualInvestments = @json($investor->investments_per_year);
            const annualLabels = Object.keys(annualInvestments);
            const investmentData = Object.values(annualInvestments);

            const ctxAnnual = document.getElementById('annualInvestmentChart').getContext('2d');
            new Chart(ctxAnnual, {
                type: 'line',
                data: {
                    labels: annualLabels,
                    datasets: [{
                        label: 'Investment Per Year (€)',
                        data: investmentData,
                        borderColor: 'rgba(34, 197, 94, 1)',
                        backgroundColor: 'rgba(34, 197, 94, 0.2)',
                        tension: 0.4,
                        fill: true,
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Amount (€)'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Year'
                            }
                        }
                    }
                }
            });

            /***************************************
             * Investment Allocations - Pie Chart
             ***************************************/
            const investmentAllocations = @json($investor->investment_allocation);
            const allocationLabels = Object.keys(investmentAllocations);
            const allocationData = Object.values(investmentAllocations);

            const ctxAllocation = document.getElementById('allocationChart').getContext('2d');
            new Chart(ctxAllocation, {
                type: 'pie',
                data: {
                    labels: allocationLabels,
                    datasets: [{
                        label: 'Your Allocations',
                        data: allocationData,
                        backgroundColor: [
                            'rgba(34, 197, 94, 0.7)',  // green-600
                            'rgba(59, 130, 246, 0.7)', // blue-500
                            'rgba(234, 88, 12, 0.7)',  // orange-600
                            'rgba(229, 62, 62, 0.7)'   // red-600; add more colors if needed
                        ],
                        borderColor: [
                            'rgba(34, 197, 94, 1)',
                            'rgba(59, 130, 246, 1)',
                            'rgba(234, 88, 12, 1)',
                            'rgba(229, 62, 62, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        });
    </script>
@endsection
