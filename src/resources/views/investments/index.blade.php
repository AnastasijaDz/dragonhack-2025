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
        .success-message {
            margin-top: 1rem;
            color: green;
            font-size: 1.1rem;
            font-weight: bold;
        }
    </style>

    <div class="flex flex-col gap-8 w-full items-center">
        <h1 class="text-3xl font-bold my-10">My Investments</h1>
        @if($investments->isEmpty())
            <p class="text-gray-600">You don't have any investments yet.</p>
        @else
            @foreach ($investments->sortByDesc('created_at') as $investment)
                <div class="flex flex-col shadow-lg w-[60%] border-2 rounded-xl overflow-hidden bg-gradient-to-br from-white via-gray-50 to-white">
                    <div class="bg-green-800 p-6 text-white text-2xl flex items-center gap-3">
                        <img src="/svgs/white-crypto-exchange.svg" class="w-6 h-6" alt="Project Icon">
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
                            <button class="invest-button inline-flex items-center gap-2 py-3 px-4 rounded-md bg-green-800 hover:bg-green-700 text-white font-bold transition"
                                    data-project-id="{{ $investment->project->id }}"
                                    data-price="{{ $investment->project->price }}">
                                <i class="fa-solid fa-money-bill-1-wave text-white mr-1"></i> Invest More
                            </button>
                            <button class="list-tokens-button inline-flex items-center gap-2 py-3 px-4 rounded-md bg-green-800 hover:bg-green-700 text-white font-bold transition"
                                    data-investment-id="{{ $investment->id }}"
                                    data-project-id="{{ $investment->project_id }}">
                                <i class="fa-solid fa-file-alt text-white"></i> List Tokens
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

    <div id="tokenModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-70 backdrop-blur-sm hidden" role="dialog" aria-modal="true" aria-labelledby="tokenModalTitle">
        <div id="tokenModalContent" class="bg-white p-8 rounded-2xl shadow-2xl w-11/12 max-w-3xl transform transition-all duration-300 scale-0" tabindex="-1">
            <button id="closeTokenModal" aria-label="Close token modal" class="absolute top-4 right-4 text-gray-600 text-3xl focus:outline-none">&times;</button>
            <h2 id="tokenModalTitle" class="text-2xl font-bold mb-6 text-gray-800">
                Transfer Tokens for Investment <span id="modalInvestmentId"></span>
            </h2>
            <div class="mb-6">
                <div class="flex items-center mb-3">
                    <input type="checkbox" id="selectAllTokens" class="mr-3">
                    <label for="selectAllTokens" class="font-bold text-gray-700">Select All Tokens</label>
                </div>
                <div id="tokensList" class="max-h-64 overflow-y-auto border border-gray-200 rounded-md p-4">
                    <div class="text-center py-6 text-gray-500">Loading tokens...</div>
                </div>
            </div>
            <form id="transferForm" class="mt-4">
                <div class="mb-4">
                    <label for="recipientEmail" class="block text-lg font-bold text-gray-800">Recipient Email</label>
                    <input type="email" id="recipientEmail" name="recipientEmail" class="w-full p-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Enter recipient email" required>
                    <div id="emailError" class="text-red-600 text-sm mt-1 hidden">Please enter a valid email address.</div>
                </div>
                <input type="hidden" id="projectId" name="projectId" value="">
                <div class="flex items-center justify-between mt-6">
                    <span id="selectedCount" class="text-gray-700 text-lg">0 tokens selected</span>
                    <button type="submit" id="transferButton" class="py-3 px-6 bg-green-800 hover:bg-green-700 text-white font-bold rounded transition disabled:bg-gray-400" disabled>Transfer Tokens</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        (function() {
            document.addEventListener('DOMContentLoaded', () => {
                const tokenModal = document.getElementById('tokenModal');
                const tokenModalContent = document.getElementById('tokenModalContent');
                const closeTokenModal = document.getElementById('closeTokenModal');
                const listTokensButtons = document.querySelectorAll('.list-tokens-button');
                const tokensList = document.getElementById('tokensList');
                const selectAllTokens = document.getElementById('selectAllTokens');
                const recipientEmail = document.getElementById('recipientEmail');
                const emailError = document.getElementById('emailError');
                const selectedCount = document.getElementById('selectedCount');
                const transferButton = document.getElementById('transferButton');
                const transferForm = document.getElementById('transferForm');
                const projectIdInput = document.getElementById('projectId');
                const modalInvestmentId = document.getElementById('modalInvestmentId');

                const investModal = document.getElementById('investModal');
                const investContent = document.getElementById('investContent');
                const closeInvestModal = document.getElementById('closeInvestModal');
                const investForm = document.getElementById('investForm');
                const investTreeCount = document.getElementById('investTreeCount');
                const calculatedInvestment = document.getElementById('calculatedInvestment');
                const modalProjectId = document.getElementById('modalProjectId');
                const investSuccessMessage = document.getElementById('investSuccessMessage');
                let selectedProjectPrice = null;
                let tokens = [];
                let selectedTokenIds = [];

                const openModal = (modal, content) => {
                    modal.classList.remove('hidden');
                    modal.style.opacity = 0;
                    setTimeout(() => { modal.style.opacity = 1; }, 10);
                    content.classList.remove('scale-0', 'animate-whoopOut');
                    content.classList.add('animate-whoopIn');
                    modal.setAttribute('aria-hidden', 'false');
                    const firstFocusable = content.querySelector('input, button');
                    if (firstFocusable) firstFocusable.focus();
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

                const openInvestModal = () => {
                    investSuccessMessage.textContent = '';
                    openModal(investModal, investContent);
                };

                const closeInvestModalFunc = () => {
                    closeModal(investModal, investContent, () => {
                        investForm.reset();
                        calculatedInvestment.textContent = 'Calculated Investment: €--';
                        investSuccessMessage.textContent = '';
                    });
                };

                const openTokenModal = () => { openModal(tokenModal, tokenModalContent); };

                const closeTokenModalFunc = () => {
                    closeModal(tokenModal, tokenModalContent, () => {
                        transferForm.reset();
                        tokensList.innerHTML = '<div class="text-center py-6 text-gray-500">Loading tokens...</div>';
                        emailError.classList.add('hidden');
                        selectedCount.textContent = '0 tokens selected';
                        transferButton.disabled = true;
                        selectedTokenIds = [];
                        selectAllTokens.checked = false;
                    });
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
                        calculatedInvestment.textContent = `Calculated Investment: €${(numTrees * selectedProjectPrice).toFixed(2)}`;
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
                            investSuccessMessage.textContent = 'Investment submitted successfully!';
                            setTimeout(() => {
                                investForm.reset();
                                modalProjectId.value = '';
                                calculatedInvestment.textContent = 'Calculated Investment: €--';
                                investSuccessMessage.textContent = '';
                                Array.from(investForm.elements).forEach(el => el.disabled = false);
                            }, 3500);
                        })
                        .catch(error => {
                            investSuccessMessage.textContent = 'An error occurred while submitting your investment.';
                            Array.from(investForm.elements).forEach(el => el.disabled = false);
                        });
                });

                listTokensButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const investmentId = this.dataset.investmentId;
                        const projId = this.dataset.projectId;
                        projectIdInput.value = projId;
                        modalInvestmentId.textContent = investmentId;
                        selectedTokenIds = [];
                        selectAllTokens.checked = false;
                        updateSelectedCount();
                        openTokenModal();
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
                                renderTokensList();
                            })
                            .catch(error => {
                                tokensList.innerHTML = '<div class="text-center py-4 text-red-600">Failed to load tokens. Please try again.</div>';
                            });
                    });
                });

                const renderTokensList = () => {
                    if (tokens.length === 0) {
                        tokensList.innerHTML = '<div class="text-center py-4 text-gray-600">No tokens available for this investment.</div>';
                        return;
                    }
                    let html = '';
                    tokens.forEach(token => {
                        const isChecked = selectedTokenIds.includes(token.id) ? 'checked' : '';
                        html += `
                            <div class="flex items-center p-2 hover:bg-gray-100 rounded">
                                <input type="checkbox" id="token-${token.id}" class="token-checkbox mr-3" data-token-id="${token.id}" ${isChecked}>
                                <label for="token-${token.id}" class="cursor-pointer flex-grow">
                                    Token #${token.id}${token.name ? ' - ' + token.name : ''}
                                </label>
                            </div>
                        `;
                    });
                    tokensList.innerHTML = html;
                    document.querySelectorAll('.token-checkbox').forEach(cb => {
                        cb.addEventListener('change', handleTokenSelection);
                    });
                    updateSelectAllState();
                };

                const handleTokenSelection = (e) => {
                    const tokenId = parseInt(e.target.dataset.tokenId);
                    if (e.target.checked) {
                        if (!selectedTokenIds.includes(tokenId)) selectedTokenIds.push(tokenId);
                    } else {
                        selectedTokenIds = selectedTokenIds.filter(id => id !== tokenId);
                    }
                    updateSelectedCount();
                    updateSelectAllState();
                };

                selectAllTokens.addEventListener('change', () => {
                    const checkboxes = document.querySelectorAll('.token-checkbox');
                    if (selectAllTokens.checked) {
                        selectedTokenIds = tokens.map(token => token.id);
                        checkboxes.forEach(cb => cb.checked = true);
                    } else {
                        selectedTokenIds = [];
                        checkboxes.forEach(cb => cb.checked = false);
                    }
                    updateSelectedCount();
                });

                const updateSelectedCount = () => {
                    selectedCount.textContent = `${selectedTokenIds.length} tokens selected`;
                    const email = recipientEmail.value.trim();
                    const validEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
                    transferButton.disabled = selectedTokenIds.length === 0 || !validEmail;
                };

                const updateSelectAllState = () => {
                    selectAllTokens.checked = tokens.length && selectedTokenIds.length === tokens.length;
                };

                recipientEmail.addEventListener('input', () => {
                    const email = recipientEmail.value.trim();
                    const validEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
                    emailError.classList.toggle('hidden', validEmail || email === '');
                    updateSelectedCount();
                });

                transferForm.addEventListener('submit', (e) => {
                    e.preventDefault();
                    const email = recipientEmail.value.trim();
                    if (selectedTokenIds.length === 0 || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) return;
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
                            token_ids: selectedTokenIds,
                            recipient_email: email,
                            project_id: projId
                        })
                    })
                        .then(response => {
                            if (!response.ok) return response.json().then(data => { throw new Error(data.message || 'Transfer failed'); });
                            return response.json();
                        })
                        .then(data => { closeTokenModalFunc(); })
                        .catch(error => {
                            transferButton.disabled = false;
                            transferButton.textContent = 'Transfer Tokens';
                        })
                        .finally(() => {
                            transferButton.disabled = false;
                            transferButton.textContent = 'Transfer Tokens';
                        });
                });
            });
        })();
    </script>
@endsection
