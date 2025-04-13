@extends('layouts.app')

@section('content')
    <style>
        @keyframes whoopIn {
            0% { transform: scale(0); opacity: 0; }
            25% { transform: scale(1.1); opacity: 1; }
            100% { transform: scale(1); opacity: 1; }
        }
        @keyframes whoopOut {
            0% { transform: scale(1); opacity: 1; }
            25% { transform: scale(0.9); opacity: 0.7; }
            100% { transform: scale(0); opacity: 0; }
        }
        .animate-whoopIn { animation: whoopIn 0.5s ease-out forwards; }
        .animate-whoopOut { animation: whoopOut 0.3s ease-in forwards; }

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
        @keyframes spin { to { transform: rotate(360deg); } }

        #calcModal, #investModal {
            transition: opacity 0.3s ease-in-out;
        }

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

    <div class="flex flex-col gap-16 w-full items-center">
        <h1 class="text-4xl font-bold my-14">My Investments</h1>
        @if($investments->isEmpty())
            <p class="text-gray-600">You don't have any investments yet.</p>
        @else
            @foreach ($investments->sortByDesc('created_at') as $investment)
                <div class="flex flex-col shadow-lg w-[60%] border-2 rounded-xl overflow-hidden bg-gradient-to-br from-white via-gray-50 to-white">
                    <div class="bg-white p-6 text-black text-2xl border-b-2 flex items-center gap-5">
                        <img src="/svgs/black-crypto-exchange.svg" class="w-6 h-6" alt="Project Icon">
                        <span class="font-bold">{{ $investment->project->name ?? '—' }}</span>
                    </div>
                    <div class="flex flex-col sm:flex-row p-6 bg-white justify-between">
                        <div class="flex flex-col gap-2">
                            <div><span class="font-bold text-black">Investment ID:</span> <span>{{ $investment->id }}</span></div>
                            <div><span class="font-bold text-black">Tokens Count:</span> <span>{{ $investment->tokens->count() }}</span></div>
                            <div><span class="font-bold text-black">Separate Token Price:</span> <span>{{ $investment->project->price }}€</span></div>
                            <div><span class="font-bold text-black">Investment Date:</span> <span>{{ $investment->created_at->format('Y-m-d H:i') }}</span></div>
                        </div>
                        <div class="flex flex-col gap-4 mt-4 sm:mt-0">
                            <button class="list-tokens-button inline-flex text-center items-center gap-2 py-3 px-4 rounded-md bg-white hover:bg-gray-200 text-green-800 border-2 border-green-800 font-bold transition"
                                    data-investment-id="{{ $investment->id }}"
                                    data-project-id="{{ $investment->project_id }}"
                                    data-price="{{ $investment->project->price }}">
                                <i class="fa-solid fa-file-alt text-green-800"></i> Sell Tokens
                            </button>
                            <button class="invest-button inline-flex items-center gap-2 py-3 px-4 rounded-md bg-green-800 hover:bg-green-700 text-white font-bold transition"
                                    data-project-id="{{ $investment->project->id }}"
                                    data-price="{{ $investment->project->price }}">
                                <i class="fa-solid fa-solid fa-money-bill-1-wave text-white"></i>
                                Invest More
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <div id="investModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black backdrop-blur-sm bg-opacity-50 hidden" aria-hidden="true">
        <div id="investContent" role="dialog" aria-modal="true" aria-labelledby="investModalTitle"
             class="bg-white p-8 rounded-2xl shadow-2xl w-11/12 max-w-3xl transform transition-all duration-300 scale-0" tabindex="-1">
            <button id="closeInvestModal" aria-label="Close invest modal" class="absolute top-4 right-4 text-gray-600 text-3xl focus:outline-none">&times;</button>
            <div class="p-6 w-full">
                <h2 id="investModalTitle" class="text-xl font-bold">Invest in Project</h2>
                <form id="investForm" class="mt-6">
                    <input type="hidden" id="modalProjectId" name="project_id" value="">
                    <div class="mb-4">
                        <label for="investTreeCount" class="block font-bold">Number of Trees to Invest</label>
                        <input type="number" id="investTreeCount" name="investTreeCount" class="w-full p-2 border rounded" placeholder="Enter number of trees" required min="0">
                        <div id="investTreeCountError" class="text-red-600 text-sm hidden">Please enter a valid number.</div>
                    </div>
                    <div class="mb-4">
                        <p id="calculatedInvestment" class="text-lg font-semibold text-gray-700">Calculated Investment: €--</p>
                    </div>
                    <button type="submit" class="w-full py-2 bg-green-800 hover:bg-green-700 text-white font-bold rounded mt-6">
                        Submit Investment
                    </button>
                </form>
                <div id="investSuccessMessage" class="success-message"></div>
            </div>
            </div>
        </div>
    </div>

    <div id="tokenModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-70 backdrop-blur-sm hidden" role="dialog" aria-modal="true" aria-labelledby="tokenModalTitle">
        <div id="tokenModalContent" class="bg-white p-8 rounded-2xl shadow-2xl w-11/12 max-w-3xl transform transition-all duration-300 scale-0" tabindex="-1">
            <button id="closeTokenModal" aria-label="Close token modal" class="absolute top-4 right-4 text-gray-600 text-3xl focus:outline-none">&times;</button>
            <h2 id="tokenModalTitle" class="text-2xl font-bold mb-6 text-gray-800">
                Transfer Tokens for Investment <span id="modalInvestmentId"></span>
            </h2>
            <p id="tokensCountDisplay" class="mb-4 text-lg text-gray-700">You own 0 tokens</p>
            <form id="transferForm" class="mt-4">
                <div class="mb-4">
                    <label for="tokensToSell" class="block text-lg font-bold text-gray-800">Tokens to Sell</label>
                    <input type="number" id="tokensToSell" name="tokensToSell" class="w-full p-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Enter number of tokens to sell" required>
                    <div id="tokenSellError" class="text-red-600 text-sm mt-1 hidden">You cannot sell more tokens than you own.</div>
                </div>
                <div class="mb-4">
                    <p id="totalValueDisplay" class="text-lg font-semibold text-gray-700">Total Value: €--</p>
                </div>
                <div class="mb-4">
                    <label for="recipientEmail" class="block text-lg font-bold text-gray-800">Recipient Email</label>
                    <input type="email" id="recipientEmail" name="recipientEmail" class="w-full p-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Enter recipient email" required>
                    <div id="emailError" class="text-red-600 text-sm mt-1 hidden">Please enter a valid email address.</div>
                </div>
                <input type="hidden" id="projectId" name="projectId" value="">
                <button type="submit" id="transferButton" class="w-full py-3 bg-green-800 hover:bg-green-700 text-white font-bold rounded transition disabled:bg-gray-400" disabled>Sell Tokens</button>
            </form>
        </div>
    </div>

    <div id="toastContainer" class="fixed bottom-8 left-1/2 transform -translate-x-1/2 z-50"></div>
@endsection

@section('scripts')
    <script>
        (function() {
            document.addEventListener('DOMContentLoaded', () => {
                /*----- Investment Modal Logic -----*/
                const investModal = document.getElementById('investModal');
                const investContent = document.getElementById('investContent');
                const closeInvestModal = document.getElementById('closeInvestModal');
                const investForm = document.getElementById('investForm');
                const investTreeCount = document.getElementById('investTreeCount');
                const calculatedInvestment = document.getElementById('calculatedInvestment');
                const modalProjectId = document.getElementById('modalProjectId');
                const investSuccessMessage = document.getElementById('investSuccessMessage');
                let selectedProjectPrice = null;
                const yieldPerTree = 20;

                function setFocusToModal(modal) {
                    const firstInput = modal.querySelector('input, button');
                    if (firstInput) firstInput.focus();
                }

                const openInvestModal = () => {
                    investSuccessMessage.textContent = '';
                    openModal(investModal, investContent);
                };

                const closeInvestModalFunc = () => {
                    closeModal(investModal, investContent, () => {
                        investForm.reset();
                        modalProjectId.value = '';
                        calculatedInvestment.textContent = 'Calculated Investment: €--';
                        investSuccessMessage.textContent = '';
                    });
                };

                /*----- Token Modal (for selling tokens) -----*/
                const tokenModal = document.getElementById('tokenModal');
                const tokenModalContent = document.getElementById('tokenModalContent');
                const closeTokenModal = document.getElementById('closeTokenModal');
                const tokensCountDisplay = document.getElementById('tokensCountDisplay');
                const totalValueDisplay = document.getElementById('totalValueDisplay');

                const transferForm = document.getElementById('transferForm');
                const recipientEmail = document.getElementById('recipientEmail');
                const emailError = document.getElementById('emailError');
                const transferButton = document.getElementById('transferButton');
                const projectIdInput = document.getElementById('projectId');

                // We'll store available tokens count and token price for the modal
                let availableTokensCount = 0;
                let tokenPrice = 0;

                const openTokenModal = () => { openModal(tokenModal, tokenModalContent); };

                const closeTokenModalFunc = () => {
                    closeModal(tokenModal, tokenModalContent, () => {
                        transferForm.reset();
                        tokensCountDisplay.textContent = 'You own 0 tokens';
                        totalValueDisplay.textContent = 'Total Value: €--';
                        emailError.classList.add('hidden');
                        transferButton.disabled = true;
                        availableTokensCount = 0;
                        tokenPrice = 0;
                    });
                };

                // Generic modal open/close functions
                const openModal = (modal, content) => {
                    modal.classList.remove('hidden');
                    modal.style.opacity = 0;
                    setTimeout(() => { modal.style.opacity = 1; }, 10);
                    content.classList.remove('scale-0', 'animate-whoopOut');
                    content.classList.add('animate-whoopIn');
                    modal.setAttribute('aria-hidden', 'false');
                    setFocusToModal(content);
                };

                const closeModal = (modal, content, cb) => {
                    content.classList.remove('animate-whoopIn');
                    content.classList.add('animate-whoopOut');
                    setTimeout(() => {
                        modal.classList.add('hidden');
                        content.classList.remove('animate-whoopOut');
                        content.classList.add('scale-0');
                        modal.setAttribute('aria-hidden', 'true');
                        if (cb) cb();
                    }, 300);
                };

                document.addEventListener('click', (e) => {
                    if (e.target === investModal) closeInvestModalFunc();
                    if (e.target === tokenModal) closeTokenModalFunc();
                });
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape') {
                        if (!investModal.classList.contains('hidden')) closeInvestModalFunc();
                        if (!tokenModal.classList.contains('hidden')) closeTokenModalFunc();
                    }
                });
                closeInvestModal.addEventListener('click', closeInvestModalFunc);
                closeTokenModal.addEventListener('click', closeTokenModalFunc);

                /*----- Invest More Button Handler -----*/
                document.querySelectorAll('.invest-button').forEach(button => {
                    button.addEventListener('click', function() {
                        selectedProjectPrice = parseFloat(this.dataset.price);
                        modalProjectId.value = this.dataset.projectId || '';
                        openInvestModal();
                        investTreeCount.value = '';
                        calculatedInvestment.textContent = 'Calculated Investment: €--';
                    });
                });

                investTreeCount.addEventListener('input', () => {
                    const numTrees = parseFloat(investTreeCount.value);
                    if (!isNaN(numTrees) && selectedProjectPrice) {
                        const totalCost = numTrees * selectedProjectPrice;
                        calculatedInvestment.textContent = `Calculated Investment: €${totalCost.toFixed(2)}`;
                    } else {
                        calculatedInvestment.textContent = 'Calculated Investment: €--';
                    }
                });

                investForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const numTrees = parseFloat(investTreeCount.value);
                    if (isNaN(numTrees) || numTrees <= 0) return;
                    const projectId = modalProjectId.value;
                    Array.from(investForm.elements).forEach(el => el.disabled = true);
                    fetch('/invest', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ project_id: projectId, number_of_trees: numTrees })
                    })
                        .then(response => response.json())
                        .then(data => {
                            showToast('Investment submitted successfully!', 4000);
                            closeInvestModalFunc();
                            setTimeout(() => window.location.reload(), 1500);
                        })
                        .catch(error => {
                            showToast('An error occurred while submitting your investment.', 3000, true);
                            Array.from(investForm.elements).forEach(el => el.disabled = false);
                        });
                });

                /*----- List Tokens Button Handler (for selling tokens) -----*/
                document.querySelectorAll('.list-tokens-button').forEach(button => {
                    button.addEventListener('click', function() {
                        const investmentId = this.dataset.investmentId;
                        const projId = this.dataset.projectId;
                        tokenPrice = parseFloat(this.dataset.price);
                        projectIdInput.value = projId;
                        modalInvestmentId.textContent = investmentId;
                        openTokenModal();
                        // Fetch tokens for this investment
                        fetch(`/investments/${investmentId}/tokens`, {
                            method: 'GET',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        })
                            .then(response => {
                                if (!response.ok) throw new Error('Failed to load tokens');
                                return response.json();
                            })
                            .then(data => {
                                tokens = data.tokens || [];
                                availableTokensCount = tokens.length;
                                tokensCountDisplay.textContent = `You own ${availableTokensCount} tokens`;
                            })
                            .catch(error => {
                                tokensCountDisplay.textContent = 'Failed to load tokens';
                                showToast('Failed to load tokens', 3000, true);
                            });
                    });
                });

                const tokensToSellInput = document.getElementById('tokensToSell');

                const tokenSellError = document.createElement('div');
                tokenSellError.id = 'tokenSellError';
                tokenSellError.className = 'text-red-600 text-sm mt-1 hidden';
                tokenSellError.textContent = 'You cannot sell more tokens than you own.';
                tokensToSellInput.insertAdjacentElement('afterend', tokenSellError);

                tokensToSellInput.addEventListener('input', () => {
                    const tokensToSell = parseInt(tokensToSellInput.value);

                    if (!isNaN(tokensToSell)) {
                        if (tokensToSell > availableTokensCount) {
                            tokenSellError.classList.remove('hidden');
                            transferButton.disabled = true;
                        } else {
                            tokenSellError.classList.add('hidden');
                            if (recipientEmail.value.trim() && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(recipientEmail.value.trim())) {
                                transferButton.disabled = false;
                            }
                        }
                        const totalValue = tokensToSell * tokenPrice;
                        totalValueDisplay.textContent = `Total Value: €${isNaN(totalValue) ? '--' : totalValue.toFixed(2)}`;
                    } else {
                        totalValueDisplay.textContent = 'Total Value: €--';
                        transferButton.disabled = true;
                    }
                });

                /*----- Recipient Email Validation -----*/
                recipientEmail.addEventListener('input', () => {
                    const email = recipientEmail.value.trim();
                    const validEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
                    emailError.classList.toggle('hidden', validEmail || email === '');
                    if (tokensToSellInput.value && parseInt(tokensToSellInput.value) <= availableTokensCount && validEmail) {
                        transferButton.disabled = false;
                    } else {
                        transferButton.disabled = true;
                    }
                });

                /*----- Transfer Form Submission -----*/
                transferForm.addEventListener('submit', (e) => {
                    e.preventDefault();
                    const email = recipientEmail.value.trim();
                    const tokensToSell = parseInt(tokensToSellInput.value);
                    if (isNaN(tokensToSell) || tokensToSell <= 0 || tokensToSell > availableTokensCount) return;
                    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) return;
                    const projId = projectIdInput.value;
                    transferButton.disabled = true;
                    transferButton.innerHTML = '<span class="spinner inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin mr-2"></span> Transferring...';
                    fetch('/tokens/transfer', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            tokens_to_sell: tokensToSell,
                            recipient_email: email,
                            project_id: projId
                        })
                    })
                        .then(response => {
                            if (!response.ok) return response.json().then(data => { throw new Error(data.message || 'Transfer failed'); });
                            return response.json();
                        })
                        .then(data => {
                            showToast('Tokens transferred successfully!', 4000);
                            closeTokenModalFunc();
                            setTimeout(() => window.location.reload(), 1500);
                        })
                        .catch(error => {
                            showToast(error.message || 'Failed to transfer tokens', 3000, true);
                            transferButton.disabled = false;
                            transferButton.textContent = 'Sell Tokens';
                        })
                        .finally(() => {
                            transferButton.disabled = false;
                            transferButton.textContent = 'Sell Tokens';
                        });
                });

                /*----- Toast Function (already defined earlier) -----*/
                function showToast(message, duration = 3000, isError = false) {
                    const toast = document.createElement('div');
                    toast.className = `p-3 rounded-md shadow-md mb-2 ${isError ? 'bg-red-600' : 'bg-green-800'} text-white text-lg`;
                    toast.textContent = message;
                    toastContainer.appendChild(toast);
                    setTimeout(() => {
                        toast.style.opacity = '0';
                        toast.style.transition = 'opacity 0.5s ease';
                        setTimeout(() => {
                            toastContainer.removeChild(toast);
                        }, 500);
                    }, duration);
                }
            });
        })();
    </script>
    <style>
        @keyframes whoopIn {
            0% { transform: scale(0); opacity: 0; }
            25% { transform: scale(1.1); opacity: 1; }
            100% { transform: scale(1); opacity: 1; }
        }
        @keyframes whoopOut {
            0% { transform: scale(1); opacity: 1; }
            25% { transform: scale(0.9); opacity: 0.7; }
            100% { transform: scale(0); opacity: 0; }
        }
        .animate-whoopIn { animation: whoopIn 0.5s ease-out forwards; }
        .animate-whoopOut { animation: whoopOut 0.3s ease-in forwards; }
    </style>
@endsection
