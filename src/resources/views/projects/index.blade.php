@extends('layouts.app')

@section('content')
    <style>
        /* Define the whoopIn animation */
        @keyframes whoopIn {
            0% {
                transform: scale(0);
                opacity: 0;
            }
            60% {
                transform: scale(1.1);
                opacity: 1;
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        /* Define the whoopOut animation */
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

        /* Helper classes to trigger animations */
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
                    <li data-project-id="{{ $project->id }}" class="project-item flex flex-row gap-10 shadow-lg rounded-xl bg-white p-8">
                        <div class="flex-shrink-0 flex items-center">
                            <img class="w-[200px] h-auto rounded-lg" src="https://picsum.photos/200/200" alt="Project Image">
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
                            <!-- Trigger button for the modal remains here -->
                            <button class="calc-button py-3 rounded-md bg-green-800 hover:bg-green-700 text-white font-bold">Calculate</button>
                            <button class="py-3 rounded-md bg-green-800 hover:bg-green-700 text-white font-bold">Invest</button>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <!-- Modal for investment calculation -->
    <div id="calcModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden transition-opacity duration-300 ease-out">
        <div id="modalContent" class="bg-white relative rounded-lg shadow-lg flex w-3/4 max-w-5xl transform scale-0">
            <button id="closeCalcModal" class="absolute top-0 z-10 text-gray-600 text-2xl" style="right: 1rem">&times;</button>
            <div class="p-6 w-1/2 border-r">
                <h2 class="text-xl font-bold">Calculate Investment</h2>
                <form id="calcForm" class="mt-6">
                    <div class="mb-4">
                        <label for="investmentAmount" class="block font-bold">Amount</label>
                        <input type="number" id="investmentAmount" name="amount" class="w-full p-2 border rounded" placeholder="Enter amount" required>
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
                    <button type="submit" class="w-full py-2 bg-green-800 hover:bg-green-700 text-white font-bold rounded">Calculate</button>
                </form>
            </div>
            <div class="p-6 w-1/2">
                <canvas id="incomeChart" class="w-full h-64"></canvas>
                <div id="investmentResults" class="mt-4">
                    <p class="text-black text-lg">
                        The initial investment amount is expected to pay itself in <span class="text-green-800" id="paybackPeriod">--</span> years.
                    </p>
                    <p class="text-black text-lg">
                        Net profit gain after <span class="text-green-800" id="profitPeriod">--</span> years is <span class="text-green-800" id="netProfit">--</span> EUR.
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

            let incomeChart = new Chart(incomeChartCanvas.getContext('2d'), {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Estimated Value',
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

            const openModal = (trigger) => {
                modalContent.classList.remove('animate-whoopOut');
                calcModal.classList.remove('hidden');
                void modalContent.offsetWidth;
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
                            retailCostValue.textContent = parseFloat(data.average_retail_cost).toFixed(2);
                        }
                    })
                    .catch(console.error);
            };

            const closeModal = () => {
                modalContent.classList.remove('animate-whoopIn');
                modalContent.classList.add('animate-whoopOut');
                setTimeout(() => {
                    calcModal.classList.add('hidden');
                    calcForm.reset();
                    retailCostValue.textContent = '--';
                    if (incomeChart) { incomeChart.destroy(); }
                    incomeChart = new Chart(incomeChartCanvas.getContext('2d'), {
                        type: 'line',
                        data: {
                            labels: [],
                            datasets: [{
                                label: 'Estimated Value',
                                data: [],
                                borderColor: 'blue',
                                fill: false
                            }]
                        },
                        options: { responsive: true, scales: { y: { beginAtZero: false } } }
                    });
                    modalContent.classList.remove('animate-whoopOut');
                    modalContent.style.transform = 'scale(0)';
                    paybackPeriodElem.textContent = '--';
                    profitPeriodElem.textContent = '--';
                    netProfitElem.textContent = '--';
                }, 300);
            };

            calcButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const li = this.closest('li');
                    modalProjectId.value = li ? li.dataset.projectId : '';
                    openModal();
                });
            });

            closeCalcModal.addEventListener('click', closeModal);

            calcForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                fetch('/calculate', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        // Build the chart using the returned data (each item contains year and value)
                        const labels = data.map(item => 'Year ' + item.year);
                        const values = data.map(item => item.value);
                        if (incomeChart) { incomeChart.destroy(); }
                        incomeChart = new Chart(incomeChartCanvas.getContext('2d'), {
                            type: 'line',
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: 'Estimated Value',
                                    data: values,
                                    borderColor: 'blue',
                                    fill: false
                                }]
                            },
                            options: {
                                responsive: true,
                                scales: {
                                    y: {
                                        beginAtZero: false,
                                        suggestedMin: values[0] - Math.abs(values[0] * 0.1),
                                        suggestedMax: values[0] + Math.abs(values[0] * 0.1)
                                    }
                                }
                            }
                        });

                        const investmentAmount = parseFloat(document.getElementById('investmentAmount').value);
                        const investmentYears = parseInt(document.getElementById('investmentYears').value);

                        // Interpolate to determine the precise payback period
                        let paybackPeriod = null;
                        if (values.length > 0) {
                            // Check if the first value is already above or equal to investmentAmount
                            if (values[0] >= investmentAmount) {
                                // Estimate fraction if possible; if not, consider it less than a year
                                if (values[0] === investmentAmount) {
                                    paybackPeriod = 1;
                                } else {
                                    paybackPeriod = 0.5; // default guess or could be refined further
                                }
                            } else {
                                for (let i = 0; i < data.length - 1; i++) {
                                    const currentValue = data[i].value;
                                    const nextValue = data[i + 1].value;
                                    if (investmentAmount > currentValue && investmentAmount <= nextValue) {
                                        // Linear interpolation between the current and the next data point
                                        const fraction = (investmentAmount - currentValue) / (nextValue - currentValue);
                                        paybackPeriod = data[i].year + fraction;
                                        break;
                                    }
                                }
                            }
                        }
                        if (paybackPeriod === null) {
                            paybackPeriod = 'N/A';
                        } else if (typeof paybackPeriod === 'number') {
                            // If less than one year then note it explicitly
                            if (paybackPeriod < 1) {
                                paybackPeriod = 'Less than a year';
                            } else {
                                paybackPeriod = paybackPeriod.toFixed(1);
                            }
                        }

                        // Calculate net profit gain: final year's value minus the initial investment
                        const finalValue = data[data.length - 1].value;
                        const netProfit = finalValue - investmentAmount;

                        // Update results: static text in black, numbers in green
                        paybackPeriodElem.textContent = paybackPeriod;
                        profitPeriodElem.textContent = investmentYears;
                        netProfitElem.textContent = typeof netProfit === 'number' ? netProfit.toFixed(2) : netProfit;
                    })
                    .catch(error => console.error('Error:', error));
            });
        });
    </script>
@endsection
