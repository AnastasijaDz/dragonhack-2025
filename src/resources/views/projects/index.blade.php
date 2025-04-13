@extends('layouts.app')

@section('content')
    <style>
        /*========================================
          Modal Animations
        ========================================*/
        @keyframes whoopIn {
            0% {
                transform: scale(0);
                opacity: 0;
            }
            25% {
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
            25% {
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

        /*========================================
          Spinner CSS
        ========================================*/
        .spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, 0.6);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
            vertical-align: middle;
            margin-left: 8px;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /*========================================
          Modal Overlay Fade
        ========================================*/
        #calcModal,
        #investModal {
            transition: opacity 0.3s ease-in-out;
        }

        /*========================================
          Toast Styles
        ========================================*/
        #toastContainer {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1000;
        }
        .toast {
            background: #333;
            color: #fff;
            padding: 12px 20px;
            border-radius: 4px;
            margin-top: 10px;
            opacity: 0.9;
            font-size: 0.9rem;
        }
    </style>

    <!-- Toast Container -->
    <div id="toastContainer"></div>

    <div class="p-10">
        @if($projects->isEmpty())
            <p class="text-center text-xl">No projects available.</p>
        @else
            <!-- Projects Listing -->
            <ul class="flex flex-col gap-10 items-center">
                @foreach($projects as $project)
                    <li data-project-id="{{ $project->id }}" data-price="{{ $project->price }}"
                        class="project-item flex flex-col sm:flex-row gap-6 shadow-lg rounded-xl bg-gradient-to-br from-white via-gray-50 to-white p-6 w-full max-w-5xl">

                        <!-- Project Image -->
                        <div class="flex-shrink-0">
                            <div class="w-48 h-48 overflow-hidden rounded-lg">
                                <img class="object-cover w-full h-full" src="/images/barn.png"
                                     alt="Project Image" loading="lazy">
                            </div>
                        </div>

                        <!-- Project Details -->
                        <div class="flex flex-col justify-center flex-grow">
                            <h2 class="text-3xl font-extrabold text-gray-800">{{ $project->name }}</h2>
                            <div class="mt-2 space-y-1">
                                <p><span class="font-bold text-gray-700">Landlord name:</span> {{ $project->landlord->name }}</p>
                                <p><span class="font-bold text-gray-700">Description:</span> {{ $project->description }}</p>
                                <p><span class="font-bold text-gray-700">Available Trees:</span> {{ $project->amount }}</p>
                                <p><span class="font-bold text-gray-700">Price per Tree:</span> €{{ $project->price }}</p>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col justify-evenly gap-4">
                            <button class="calc-button py-3 px-4 rounded-md bg-white hover:bg-gray-200 text-green-800 border-2 border-green-800 font-bold transition">
                                Calculate
                            </button>
                            <button class="invest-button gap-2 py-3 px-4 rounded-md bg-green-800 hover:bg-green-700 text-white font-bold transition">
                                Invest
                                <i class="fa-solid fa-money-bill-1-wave text-white ml-1"></i>
                            </button>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <!--========================================
      Calculation Modal
    ========================================-->
    <div id="calcModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden backdrop-blur-sm" aria-hidden="true">
        <div id="modalContent" role="dialog" aria-modal="true" aria-labelledby="modalTitle"
             class="bg-white relative rounded-lg shadow-lg flex w-3/4 max-w-7xl transform scale-0">

            <!-- Close Button -->
            <button id="closeCalcModal" aria-label="Close modal" class="absolute top-0 z-10 text-gray-600 text-2xl" style="right: 1rem">
                &times;
            </button>

            <!-- Left Pane: Investment Calculation Form -->
            <div class="p-6 w-1/2 border-r">
                <h2 id="modalTitle" class="text-xl font-bold">Calculate Investment</h2>
                <form id="calcForm" class="mt-6">
                    <!-- Number of Trees Input -->
                    <div class="mb-4">
                        <label for="treeCount" class="block font-bold">Number of Trees</label>
                        <input type="number" id="treeCount" name="treeCount"
                               class="w-full p-2 border rounded" placeholder="Enter number of trees"
                               required aria-invalid="false" min="0">
                        <div id="treeCountError" class="text-red-600 text-sm hidden">
                            Please enter a valid number.
                        </div>
                    </div>

                    <!-- Investment Years Input -->
                    <div class="mb-4">
                        <label for="investmentYears" class="block font-bold">Years</label>
                        <input type="number" id="investmentYears" name="years"
                               class="w-full p-2 border rounded" placeholder="Enter years"
                               required aria-invalid="false" min="0">
                        <div id="investmentYearsError" class="text-red-600 text-sm hidden">
                            Please enter a valid number of years.
                        </div>
                    </div>

                    <!-- Hidden Project ID -->
                    <input type="hidden" name="project_id" id="modalProjectId" value="">

                    <!-- Average Retail Cost Display -->
                    <div class="mb-4">
                        <div class="text-lg font-semibold text-gray-700">
                            Average retail cost: <span id="retailCostValue">--</span> €/kg
                        </div>
                        <div class="text-sm italic text-gray-500">
                            Data extracted from official statistics in Slovenia
                        </div>
                    </div>

                    <!-- Calculation Button with Spinner -->
                    <button type="submit" class="w-full py-2 bg-white hover:bg-gray-200 text-green-800 font-bold border-2 border-green-800 rounded mt-2">
                        Calculate
                    </button>
                </form>

                <!-- Button to Open Investment Modal -->
                <button id="goToInvestBtn" class="w-full py-2 bg-green-800 hover:bg-green-700 text-white font-bold rounded mt-4">
                    <i class="fa-solid fa-money-bill-1-wave text-white mr-1"></i>
                    Go To Invest
                </button>
            </div>

            <!-- Right Pane: Chart and Results -->
            <div class="p-6 w-1/2">
                <canvas id="incomeChart" class="w-full h-64 mt-5"></canvas>
                <div id="investmentResults" class="mt-4" aria-live="polite">
                    <p class="text-black text-lg">
                        The total cost is expected to be paid back in
                        <span class="text-green-800 font-bold" id="paybackPeriod">--</span> years.
                    </p>
                    <p class="text-black text-lg">
                        Net profit after
                        <span class="text-green-800 font-bold" id="profitPeriod">--</span> years is
                        <span class="text-green-800 font-bold" id="netProfit">--</span> €.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!--========================================
      Investment Modal
    ========================================-->
    <div id="investModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden backdrop-blur-sm" aria-hidden="true">
        <div id="investContent" role="dialog" aria-modal="true" aria-labelledby="investModalTitle"
             class="bg-white relative rounded-lg shadow-lg flex w-3/4 max-w-2xl transform scale-0">

            <!-- Close Button -->
            <button id="closeInvestModal" aria-label="Close invest modal" class="absolute top-0 z-10 text-gray-600 text-2xl" style="right: 1rem">
                &times;
            </button>

            <div class="p-6 w-full">
                <h2 id="investModalTitle" class="text-xl font-bold">Invest in Project</h2>
                <form id="investForm" class="mt-6">
                    <!-- Number of Trees for Investment -->
                    <div class="mb-4">
                        <label for="investTreeCount" class="block font-bold">Number of Trees to Invest</label>
                        <input type="number" id="investTreeCount" name="investTreeCount"
                               class="w-full p-2 border rounded" placeholder="Enter number of trees" required>
                        <div id="investTreeCountError" class="text-red-600 text-sm hidden">
                            Please enter a valid number.
                        </div>
                    </div>

                    <!-- Calculated Investment Display -->
                    <div class="mb-4">
                        <p id="calculatedInvestment" class="text-lg font-semibold text-gray-700">
                            Calculated Investment: €--
                        </p>
                    </div>

                    <!-- Investment Submission Button -->
                    <button type="submit" class="w-full py-2 bg-green-800 hover:bg-green-700 text-white font-bold rounded mt-6">
                        Submit Investment
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            /*----------------------------------------
              DOM Elements
            ----------------------------------------*/
            const calcModal         = document.getElementById('calcModal');
            const modalContent      = document.getElementById('modalContent');
            const closeCalcModal    = document.getElementById('closeCalcModal');
            const calcButtons       = document.querySelectorAll('.calc-button');
            const modalProjectId    = document.getElementById('modalProjectId');
            const calcForm          = document.getElementById('calcForm');
            const retailCostValue   = document.getElementById('retailCostValue');
            const incomeChartCanvas = document.getElementById('incomeChart');
            const paybackPeriodElem = document.getElementById('paybackPeriod');
            const profitPeriodElem  = document.getElementById('profitPeriod');
            const netProfitElem     = document.getElementById('netProfit');
            const goToInvestBtn     = document.getElementById('goToInvestBtn');
            const calcSpinner       = document.getElementById('calcSpinner');

            const investModal       = document.getElementById('investModal');
            const investContent     = document.getElementById('investContent');
            const closeInvestModal  = document.getElementById('closeInvestModal');
            const investForm        = document.getElementById('investForm');
            const investTreeCount   = document.getElementById('investTreeCount');
            const calculatedInvestment = document.getElementById('calculatedInvestment');

            /*----------------------------------------
              Error Message Elements
            ----------------------------------------*/
            const treeCountError    = document.getElementById('treeCountError');
            const investmentYearsError = document.getElementById('investmentYearsError');
            const investTreeCountError = document.getElementById('investTreeCountError');

            /*----------------------------------------
              Variables
            ----------------------------------------*/
            let averageRetailCost   = null;
            let selectedProjectPrice = null;
            const yieldPerTree      = 20;

            /*----------------------------------------
              Toast Notification Setup
            ----------------------------------------*/
            const toastContainer = document.getElementById('toastContainer');
            const showToast = (message, duration = 3000) => {
                const toast = document.createElement('div');
                toast.className = 'toast';
                toast.textContent = message;
                toastContainer.appendChild(toast);
                setTimeout(() => {
                    toastContainer.removeChild(toast);
                }, duration);
            };

            /*----------------------------------------
              Chart Setup
            ----------------------------------------*/
            let incomeChart = new Chart(incomeChartCanvas.getContext('2d'), {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Cumulative Net Profit',
                        data: [],
                        borderColor: 'green',
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

            /*----------------------------------------
              Utility Functions
            ----------------------------------------*/
            // Focus on the first input/button of a modal for accessibility
            const setFocusToModal = (modal) => {
                const firstInput = modal.querySelector('input, button');
                if (firstInput) {
                    firstInput.focus();
                }
            };

            /*----------------------------------------
              Modal Control Functions
            ----------------------------------------*/
            const openCalcModal = () => {
                calcModal.classList.remove('hidden');
                calcModal.style.opacity = 0;
                setTimeout(() => { calcModal.style.opacity = 1; }, 10);
                modalContent.classList.remove('scale-0', 'animate-whoopOut');
                modalContent.classList.add('animate-whoopIn');
                calcModal.setAttribute('aria-hidden', 'false');
                setFocusToModal(modalContent);

                // Fetch average retail cost
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
                    .catch(error => {
                        console.error(error);
                        showToast('Failed to fetch average retail cost. Please try again.');
                    });
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

                    // Reset the chart
                    if (incomeChart) { incomeChart.destroy(); }
                    incomeChart = new Chart(incomeChartCanvas.getContext('2d'), {
                        type: 'line',
                        data: {
                            labels: [],
                            datasets: [{
                                label: 'Cumulative Net Profit',
                                data: [],
                                borderColor: 'green',
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
                investModal.style.opacity = 0;
                setTimeout(() => { investModal.style.opacity = 1; }, 10);
                investContent.classList.remove('scale-0', 'animate-whoopOut');
                investContent.classList.add('animate-whoopIn');
                investModal.setAttribute('aria-hidden', 'false');
                setFocusToModal(investContent);
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

            /*----------------------------------------
              Global Event Listeners for Modal Dismissal
            ----------------------------------------*/
            // Click outside modal to close
            document.addEventListener('click', (e) => {
                if (e.target === calcModal) {
                    closeCalcModalFunc();
                }
                if (e.target === investModal) {
                    closeInvestModalFunc();
                }
            });

            // Escape key to close modal
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

            /*----------------------------------------
              Button Handlers
            ----------------------------------------*/
            // Open calculation modal from project listing
            calcButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const li = this.closest('li');
                    modalProjectId.value = li ? li.dataset.projectId : '';
                    selectedProjectPrice = li ? parseFloat(li.dataset.price) : null;
                    openCalcModal();
                });
            });
            closeCalcModal.addEventListener('click', () => { closeCalcModalFunc(); });

            // Transition to investment modal from calc modal
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

            // Open investment modal directly
            document.querySelectorAll('.invest-button').forEach(button => {
                button.addEventListener('click', function() {
                    const li = this.closest('li');
                    modalProjectId.value = li ? li.dataset.projectId : '';
                    selectedProjectPrice = li ? parseFloat(li.dataset.price) : null;
                    openInvestModal();
                    investTreeCount.value = '';
                    calculatedInvestment.textContent = 'Calculated Investment: €--';
                });
            });

            /*----------------------------------------
              Real-Time Calculation for Investment Form
            ----------------------------------------*/
            investTreeCount.addEventListener('input', () => {
                const numTrees = parseFloat(investTreeCount.value);
                if (!isNaN(numTrees) && selectedProjectPrice) {
                    const totalCost = numTrees * selectedProjectPrice;
                    calculatedInvestment.textContent = `Calculated Investment: €${totalCost.toFixed(2)}`;
                } else {
                    calculatedInvestment.textContent = 'Calculated Investment: €--';
                }
            });

            /*----------------------------------------
              Calculation Form Submission Handler
            ----------------------------------------*/
            calcForm.addEventListener('submit', function(e) {
                e.preventDefault();

                // Inline validation
                let valid = true;
                const treeCount     = parseFloat(document.getElementById('treeCount').value);
                const investmentYears = parseInt(document.getElementById('investmentYears').value);

                if (isNaN(treeCount) || treeCount <= 0) {
                    treeCountError.classList.remove('hidden');
                    valid = false;
                } else {
                    treeCountError.classList.add('hidden');
                }
                if (isNaN(investmentYears) || investmentYears <= 0) {
                    investmentYearsError.classList.remove('hidden');
                    valid = false;
                } else {
                    investmentYearsError.classList.add('hidden');
                }
                if (!valid) {
                    calcSpinner.classList.add('hidden');
                    showToast('Please correct the errors in the form.');
                    return;
                }
                if (averageRetailCost === null) {
                    calcSpinner.classList.add('hidden');
                    showToast('Average retail cost is not available yet. Please try again shortly.');
                    return;
                }
                if (selectedProjectPrice === null) {
                    calcSpinner.classList.add('hidden');
                    showToast('Project price is not available.');
                    return;
                }

                // Calculation Logic
                const totalCost    = treeCount * selectedProjectPrice;
                const annualIncome = treeCount * yieldPerTree * averageRetailCost;
                const cumulativeNetProfit = [];
                const labels       = [];

                for (let year = 1; year <= investmentYears; year++) {
                    let cumIncome = annualIncome * year;
                    let netProfit = cumIncome - totalCost;
                    labels.push('Year ' + year);
                    cumulativeNetProfit.push(netProfit);
                }

                // Recreate Chart with new data
                if (incomeChart) { incomeChart.destroy(); }
                incomeChart = new Chart(incomeChartCanvas.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Cumulative Net Profit',
                            data: cumulativeNetProfit,
                            borderColor: 'green',
                            fill: false
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: { y: { beginAtZero: false } }
                    }
                });

                // Calculate payback period and final net profit
                let payback;
                if (annualIncome <= 0) {
                    payback = 'Never';
                } else if (totalCost <= annualIncome) {
                    payback = 'Less than 1 year';
                } else {
                    payback = (totalCost / annualIncome).toFixed(1);
                }
                const finalNetProfit = (annualIncome * investmentYears) - totalCost;

                // Update UI with results
                paybackPeriodElem.textContent = payback;
                profitPeriodElem.textContent  = investmentYears;
                netProfitElem.textContent     = finalNetProfit.toFixed(2);

                // Hide spinner after calculation
                calcSpinner.classList.add('hidden');
            });

            /*----------------------------------------
              Investment Form Submission Handler
            ----------------------------------------*/
            investForm.addEventListener('submit', function(e) {
                e.preventDefault();
                let valid = true;
                const numInvestTrees = parseFloat(investTreeCount.value);
                if (isNaN(numInvestTrees) || numInvestTrees <= 0) {
                    investTreeCountError.classList.remove('hidden');
                    valid = false;
                } else {
                    investTreeCountError.classList.add('hidden');
                }
                if (!valid) {
                    showToast('Please enter a valid number for trees.');
                    return;
                }
                const projectId = modalProjectId.value;
                const payload   = {
                    project_id: projectId,
                    number_of_trees: numInvestTrees
                };

                // Disable submit button to avoid duplicates
                const submitButton = investForm.querySelector('button[type="submit"]');
                submitButton.disabled = true;
                submitButton.textContent = "Submitting...";

                // Investment submission via API
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
                        investForm.innerHTML = '<p class="text-xl text-green-800 font-bold text-center">Success! Your investment has been submitted.</p>';
                        showToast('Investment submitted successfully!', 4000);
                        setTimeout(() => {
                            closeInvestModalFunc();
                            investForm.reset();
                            submitButton.disabled = false;
                            submitButton.textContent = "Submit Investment";
                        }, 3500);
                    })
                    .catch(error => {
                        console.error('Error submitting investment:', error);
                        showToast('An error occurred while submitting your investment.');
                        submitButton.disabled = false;
                        submitButton.textContent = "Submit Investment";
                    });
            });
        });
    </script>
@endsection
