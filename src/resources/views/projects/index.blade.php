@extends('layouts.app')

@section('content')
    <style>
        @keyframes whoopIn {
            0% {
                transform: scale(0);
                opacity: 0;
            }
            40% {
                transform: scale(1.1);
                opacity: 1;
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        @keyframes whoopOut {
            0% {
                transform: scale(1);
                opacity: 1;
            }
            40% {
                transform: scale(0.9);
                opacity: 0.7;
            }
            100% {
                transform: scale(0);
                opacity: 0;
            }
        }

        .animate-whoopIn {
            animation: whoopIn 0.5s ease-out forwards;
        }
        .animate-whoopOut {
            animation: whoopOut 0.3s ease-in forwards;
        }
    </style>

    <div class="p-10">
        @if($projects->isEmpty())
            <p>No projects available.</p>
        @else
            <ul class="flex flex-col gap-10">
                @foreach($projects as $project)
                    <li data-project-id="{{ $project->id }}" data-price="{{ $project->price }}" class="project-item flex flex-row gap-10 shadow-lg rounded-xl bg-white p-8">
                        <div class="flex-shrink-0 flex items-center">
                            <div class="w-[200px] h-auto">
                                <img class="rounded-lg" src="/images/barn.png" alt="Project Image">
                            </div>
                        </div>
                        <div class="flex flex-col gap-4 flex-grow text-black">
                            <h2 class="text-2xl font-extrabold">{{ $project->name }}</h2>
                            <div class="flex flex-row p-3 rounded-md gap-2">
                                <span class="font-bold">Landlord name:</span>
                                <span>{{ $project->landlord->name }}</span>
                            </div>
                            <div class="flex flex-row p-3 rounded-md gap-2">
                                <span class="font-bold">Description:</span>
                                <span>{{ $project->description }}</span>
                            </div>
                            <div class="flex flex-row p-3 rounded-md gap-2">
                                <span class="font-bold">Broj dostupnih sadnic:</span>
                                <span>{{ $project->amount }}</span>
                            </div>
                            <div class="flex flex-row p-3 rounded-md gap-2">
                                <span class="font-bold">Cena jedne sadnice:</span>
                                <span>{{ $project->price }}</span>
                            </div>
                        </div>
                        <div class="flex flex-col flex-shrink-0 justify-evenly gap-4 w-[200px]">
                            <button class="calc-button py-3 rounded-md bg-green-800 hover:bg-green-700 text-white font-bold">Calculate</button>
                            <button class="py-3 rounded-md bg-green-800 hover:bg-green-700 text-white font-bold">Invest</button>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <div id="calcModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden transition-opacity duration-300 ease-out">
        <div id="modalContent" class="bg-white relative rounded-lg shadow-lg flex w-3/4 max-w-7xl transform scale-0">
            <button id="closeCalcModal" class="absolute top-0 z-10 text-gray-600 text-2xl" style="right: 1rem">&times;</button>
            <div class="p-6 w-1/2 border-r">
                <h2 class="text-xl font-bold">Calculate Investment</h2>
                <form id="calcForm" class="mt-8">
                    <div class="mb-4">
                        <label for="treeCount" class="block font-bold">Number of Trees</label>
                        <input type="number" id="treeCount" name="treeCount" class="w-full p-2 border rounded" placeholder="Enter number of trees" required>
                    </div>
                    <div class="mb-4">
                        <label for="investmentYears" class="block font-bold">Years</label>
                        <input type="number" id="investmentYears" name="years" class="w-full p-2 border rounded" placeholder="Enter years" required>
                    </div>
                    <input type="hidden" name="project_id" id="modalProjectId" value="">
                    <div class="mb-4">
                        <div class="text-lg font-semibold text-gray-700">
                            Average retail cost: <span id="retailCostValue">--</span> €/kg
                        </div>
                        <div class="text-sm italic text-gray-500">
                            Data extracted from official statistics in Slovenia
                        </div>
                    </div>
                    <button type="submit" class="w-full py-2 bg-green-800 hover:bg-green-700 text-white font-bold rounded mt-8">Calculate</button>
                </form>
            </div>
            <div class="p-6 w-1/2">
                <canvas id="incomeChart" class="w-full h-64"></canvas>
                <div id="investmentResults" class="mt-4">
                    <p class="text-black text-lg">
                        The total cost is expected to be paid back in <span class="text-green-800" id="paybackPeriod">--</span> years.
                    </p>
                    <p class="text-black text-lg">
                        Net profit after <span class="text-green-800" id="profitPeriod">--</span> years is <span class="text-green-800" id="netProfit">--</span> €.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const calcModal = document.getElementById('calcModal');
            const modalContent = document.getElementById('modalContent');
            const closeCalcModal = document.getElementById('closeCalcModal');
            const calcButtons = document.querySelectorAll('.calc-button');
            const modalProjectId = document.getElementById('modalProjectId');
            const calcForm = document.getElementById('calcForm');
            const retailCostValue = document.getElementById('retailCostValue');
            const incomeChartCanvas = document.getElementById('incomeChart');
            const paybackPeriodElem = document.getElementById('paybackPeriod');
            const profitPeriodElem = document.getElementById('profitPeriod');
            const netProfitElem = document.getElementById('netProfit');

            let averageRetailCost = null;
            let selectedProjectPrice = null;
            const yieldPerTree = 20;

            let incomeChart = new Chart(incomeChartCanvas.getContext('2d'), {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Cumulative Net Profit',
                        data: [],
                        borderColor: 'blue',
                        fill: false
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: { beginAtZero: false }
                    }
                }
            });

            const openModal = () => {
                calcModal.classList.remove('hidden');
                modalContent.classList.remove('scale-0');
                modalContent.classList.remove('animate-whoopOut');
                modalContent.classList.add('animate-whoopIn');

                fetch('/average-retail-cost', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if(data.average_retail_cost) {
                            averageRetailCost = parseFloat(data.average_retail_cost);
                            retailCostValue.textContent = averageRetailCost.toFixed(2);
                        }
                    })
                    .catch(console.error);
            };

            const closeModal = () => {
                modalContent.classList.add('animate-whoopOut');
                setTimeout(() => {
                    calcModal.classList.add('hidden');
                    calcForm.reset();
                    retailCostValue.textContent = '--';
                    averageRetailCost = null;
                    selectedProjectPrice = null;
                    paybackPeriodElem.textContent = '--';
                    profitPeriodElem.textContent = '--';
                    netProfitElem.textContent = '--';
                    if (incomeChart) { incomeChart.destroy(); }
                    incomeChart = new Chart(incomeChartCanvas.getContext('2d'), {
                        type: 'line',
                        data: {
                            labels: [],
                            datasets: [{
                                label: 'Cumulative Net Profit',
                                data: [],
                                borderColor: 'blue',
                                fill: false
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: { y: { beginAtZero: false } }
                        }
                    });
                    modalContent.classList.remove('animate-whoopOut');
                    modalContent.classList.add('scale-0');
                }, 300);
            };

            calcButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const li = this.closest('li');
                    modalProjectId.value = li ? li.dataset.projectId : '';
                    selectedProjectPrice = li ? parseFloat(li.dataset.price) : null;
                    openModal();
                });
            });

            closeCalcModal.addEventListener('click', closeModal);

            calcForm.addEventListener('submit', function(e) {
                e.preventDefault();

                if (averageRetailCost === null) {
                    alert('Average retail cost is not available yet. Please try again shortly.');
                    return;
                }
                if (selectedProjectPrice === null) {
                    alert('Project price is not available.');
                    return;
                }

                const treeCount = parseFloat(document.getElementById('treeCount').value);
                const investmentYears = parseInt(document.getElementById('investmentYears').value);

                const totalCost = treeCount * selectedProjectPrice;
                const annualIncome = treeCount * yieldPerTree * averageRetailCost;

                const cumulativeNetProfit = [];
                const labels = [];
                for (let year = 1; year <= investmentYears; year++) {
                    let cumIncome = annualIncome * year;
                    let netProfit = cumIncome - totalCost;
                    labels.push('Year ' + year);
                    cumulativeNetProfit.push(netProfit);
                }

                if (incomeChart) { incomeChart.destroy(); }
                incomeChart = new Chart(incomeChartCanvas.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Cumulative Net Profit',
                            data: cumulativeNetProfit,
                            borderColor: 'blue',
                            fill: false
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: { beginAtZero: false }
                        }
                    }
                });

                let payback;
                if (annualIncome <= 0) {
                    payback = 'Never';
                } else if (totalCost <= annualIncome) {
                    payback = 'Less than 1 year';
                } else {
                    payback = (totalCost / annualIncome).toFixed(1);
                }

                const finalNetProfit = (annualIncome * investmentYears) - totalCost;

                paybackPeriodElem.textContent = payback;
                profitPeriodElem.textContent = investmentYears;
                netProfitElem.textContent = finalNetProfit.toFixed(2);
            });
        });
    </script>
@endsection
