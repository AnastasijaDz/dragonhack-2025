@extends('layouts.app')

@section('content')
    <style>
        @keyframes whoopIn {
            0% {
                transform: scale(0);
                opacity: 0;
            }
            30% {
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
            30% {
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
            <ul class="flex flex-col gap-10 items-center">
                @foreach($projects as $project)
                    <li data-project-id="{{ $project->id }}" data-price="{{ $project->price }}" class="project-item flex flex-row gap-10 shadow-lg border-2 rounded-xl bg-white p-8 w-[80%]">
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
                            <button class="invest-button py-3 rounded-md bg-green-800 hover:bg-green-700 text-white font-bold">Invest</button>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <div id="calcModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden transition-opacity duration-300 ease-out" aria-hidden="true">
        <div id="modalContent" role="dialog" aria-modal="true" aria-labelledby="modalTitle" class="bg-white relative rounded-lg shadow-lg flex w-3/4 max-w-7xl transform scale-0">
            <button id="closeCalcModal" aria-label="Close modal" class="absolute top-0 z-10 text-gray-600 text-2xl" style="right: 1rem">&times;</button>
            <div class="p-6 w-1/2 border-r">
                <h2 id="modalTitle" class="text-xl font-bold">Calculate Investment</h2>
                <form id="calcForm" class="mt-6">
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
                    <button type="submit" class="w-full py-2 bg-green-800 hover:bg-green-700 text-white font-bold rounded mt-2">Calculate</button>
                </form>
                <button id="goToInvestBtn" class="w-full py-2 bg-blue-800 hover:bg-blue-700 text-white font-bold rounded mt-4">Go to Invest</button>
            </div>
            <div class="p-6 w-1/2">
                <canvas id="incomeChart" class="w-full h-64 mt-5"></canvas>
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

    <div id="investModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden transition-opacity duration-300 ease-out" aria-hidden="true">
        <div id="investContent" role="dialog" aria-modal="true" aria-labelledby="investModalTitle" class="bg-white relative rounded-lg shadow-lg flex w-3/4 max-w-7xl transform scale-0">
            <button id="closeInvestModal" aria-label="Close invest modal" class="absolute top-0 z-10 text-gray-600 text-2xl" style="right: 1rem">&times;</button>
            <div class="p-6 w-full">
                <h2 id="investModalTitle" class="text-xl font-bold">Invest in Project</h2>
                <form id="investForm" class="mt-6">
                    <div class="mb-4">
                        <label for="investTreeCount" class="block font-bold">Number of Trees to Invest</label>
                        <input type="number" id="investTreeCount" name="investTreeCount" class="w-full p-2 border rounded" placeholder="Enter number of trees" required>
                    </div>
                    <div class="mb-4">
                        <p id="calculatedInvestment" class="text-lg font-semibold text-gray-700">
                            Calculated Investment: €--
                        </p>
                    </div>
                    <button type="submit" class="w-full py-2 bg-green-800 hover:bg-green-700 text-white font-bold rounded mt-6">Submit Investment</button>
                </form>
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
            const goToInvestBtn = document.getElementById('goToInvestBtn');

            const investModal = document.getElementById('investModal');
            const investContent = document.getElementById('investContent');
            const closeInvestModal = document.getElementById('closeInvestModal');
            const investForm = document.getElementById('investForm');
            const investTreeCount = document.getElementById('investTreeCount');
            const calculatedInvestment = document.getElementById('calculatedInvestment');

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

            const openCalcModal = () => {
                calcModal.classList.remove('hidden');
                modalContent.classList.remove('scale-0');
                modalContent.classList.remove('animate-whoopOut');
                modalContent.classList.add('animate-whoopIn');
                calcModal.setAttribute('aria-hidden', 'false');

                fetch('/average-retail-cost', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.average_retail_cost) {
                            averageRetailCost = parseFloat(data.average_retail_cost);
                            retailCostValue.textContent = averageRetailCost.toFixed(2);
                        }
                    })
                    .catch(console.error);
            };

            const closeCalcModalFunc = (callback) => {
                modalContent.classList.remove('animate-whoopIn');
                modalContent.classList.add('animate-whoopOut');
                setTimeout(() => {
                    calcModal.classList.add('hidden');
                    calcForm.reset();
                    retailCostValue.textContent = '--';
                    averageRetailCost = null;
                    if (!callback) {
                        selectedProjectPrice = null;
                    }
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
                    calcModal.setAttribute('aria-hidden', 'true');
                    if (callback && typeof callback === 'function') {
                        callback();
                    }
                }, 300);
            };

            const openInvestModal = () => {
                investModal.classList.remove('hidden');
                investContent.classList.remove('scale-0');
                investContent.classList.remove('animate-whoopOut');
                investContent.classList.add('animate-whoopIn');
                investModal.setAttribute('aria-hidden', 'false');
            };

            const closeInvestModalFunc = () => {
                investContent.classList.remove('animate-whoopIn');
                investContent.classList.add('animate-whoopOut');
                setTimeout(() => {
                    investModal.classList.add('hidden');
                    investContent.classList.remove('animate-whoopOut');
                    investContent.classList.add('scale-0');
                    investModal.setAttribute('aria-hidden', 'true');
                }, 300);
            };

            document.addEventListener('click', (e) => {
                if (e.target === calcModal) {
                    closeCalcModalFunc();
                }
                if (e.target === investModal) {
                    closeInvestModalFunc();
                }
            });

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    if (!calcModal.classList.contains('hidden')) {
                        closeCalcModalFunc();
                    }
                    if (!investModal.classList.contains('hidden')) {
                        closeInvestModalFunc();
                    }
                }
            });

            calcButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const li = this.closest('li');
                    console.log("Project Data: ", li ? li.dataset : "LI not found");
                    modalProjectId.value = li ? li.dataset.projectId : '';
                    selectedProjectPrice = li ? parseFloat(li.dataset.price) : null;
                    openCalcModal();
                });
            });
            closeCalcModal.addEventListener('click', () => { closeCalcModalFunc(); });

            goToInvestBtn.addEventListener('click', () => {
                closeCalcModalFunc(() => {
                    openInvestModal();
                    investTreeCount.value = '';
                    calculatedInvestment.textContent = 'Calculated Investment: €--';
                });
            });

            closeInvestModal.addEventListener('click', () => {
                closeInvestModalFunc();
            });

            investTreeCount.addEventListener('input', () => {
                console.log("investTreeCount changed:", investTreeCount.value, "selectedProjectPrice:", selectedProjectPrice);
                const numTrees = parseFloat(investTreeCount.value);
                if (!isNaN(numTrees) && selectedProjectPrice) {
                    const totalCost = numTrees * selectedProjectPrice;
                    calculatedInvestment.textContent = `Calculated Investment: €${totalCost.toFixed(2)}`;
                } else {
                    calculatedInvestment.textContent = 'Calculated Investment: €--';
                }
            });

            document.querySelectorAll('.invest-button').forEach(button => {
                button.addEventListener('click', function() {
                    const li = this.closest('li');
                    console.log("Invest Project Data:", li ? li.dataset : "LI not found");
                    modalProjectId.value = li ? li.dataset.projectId : '';
                    selectedProjectPrice = li ? parseFloat(li.dataset.price) : null;
                    openInvestModal();
                    investTreeCount.value = '';
                    calculatedInvestment.textContent = 'Calculated Investment: €--';
                });
            });

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

            investForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const numInvestTrees = investTreeCount.value;
                const projectId = modalProjectId.value;
                const payload = {
                    project_id: projectId,
                    number_of_trees: numInvestTrees
                };
                fetch('/invest', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(payload)
                })
                    .then(response => response.json())
                    .then(data => {
                        alert('Investment submitted successfully!');
                        closeInvestModalFunc();
                    })
                    .catch(error => {
                        console.error('Error submitting investment:', error);
                        alert('An error occurred while submitting your investment.');
                    });
            });
        });
    </script>
@endsection
