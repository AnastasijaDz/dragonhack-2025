@extends('layouts.app')

@section('content')
    <div class="flex flex-col gap-8 w-full items-center">
        <h1 class="text-3xl font-bold my-10">My Investments</h1>

        @if($investments->isEmpty())
            <p class="text-gray-600">You don't have any investments yet.</p>
        @else
            @foreach ($investments as $investment)
                <div class="flex flex-col shadow-lg w-[60%] border-2 rounded-xl overflow-hidden">
                    <div class="bg-green-800 font-bold p-6 text-white text-2xl flex flex-row gap-3"><img src="/svgs/white-crypto-exchange.svg">{{ $investment->project->name ?? '—' }}</div>
                    <div class="flex flex-row p-6 bg-white justify-between">
                        <div class="flex flex-col justify-between">
                            <div><span class="font-bold text-black">Investment ID: </span><span>{{ $investment->id }}</span></div>
                            <div><span class="font-bold text-black">Tokens Count:
                                </span><span>{{ $investment->tokens->count() }}</span></div>
                            <div><span class="font-bold text-black">Separate Token Price:
                                </span><span>{{ $investment->project->price }}</span></div>
                            <div><span class="font-bold text-black">Investment Date:
                                </span><span>{{ $investment->created_at->format('Y-m-d H:i') }}</span></div>
                        </div>
                        <div class="flex flex-col justify-between gap-8">
                            <button class="inline-flex p-3 hover:underline font-bold gap-2 text-black">
                                Invest More
                                <div><img src="/svgs/moneys.svg"></div>
                            </button>
                            <button class="list-tokens-button inline-flex p-3 hover:underline font-bold gap-2 text-black" 
                                    data-investment-id="{{ $investment->id }}" 
                                    data-project-id="{{ $investment->project_id }}">
                                List Tokens<div><img src="/svgs/paper.svg"></div>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

=    <div id="tokenModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden" aria-hidden="true">
        <div id="tokenModalContent" role="dialog" aria-modal="true" aria-labelledby="tokenModalTitle"
             class="bg-white relative rounded-lg shadow-lg flex flex-col w-3/4 max-w-2xl transform scale-0">

=            <button id="closeTokenModal" aria-label="Close token modal" class="absolute top-0 z-10 text-gray-600 text-2xl" style="right: 1rem">
                &times;
            </button>

            <div class="p-6 w-full">
                <h2 id="tokenModalTitle" class="text-xl font-bold">Transfer Tokens</h2>
                
                <div class="my-4">
                    <div class="flex items-center mb-2">
                        <input type="checkbox" id="selectAllTokens" class="mr-2">
                        <label for="selectAllTokens" class="font-bold">Select All Tokens</label>
                    </div>
                    <div id="tokensList" class="max-h-60 overflow-y-auto border rounded-md p-2">
                        <div class="text-center py-8 text-gray-500">Loading tokens...</div>
                    </div>
                </div>

                <form id="transferForm" class="mt-4">
                    <div class="mb-4">
                        <label for="recipientEmail" class="block font-bold">Recipient Email</label>
                        <input type="email" id="recipientEmail" name="recipientEmail"
                               class="w-full p-2 border rounded" placeholder="Enter recipient email" required>
                        <div id="emailError" class="text-red-600 text-sm hidden">
                            Please enter a valid email address.
                        </div>
                    </div>

                    <input type="hidden" id="projectId" name="projectId" value="">

                    <div class="flex justify-between mt-6">
                        <span id="selectedCount" class="text-gray-700 self-center">0 tokens selected</span>
                        <button type="submit" id="transferButton" class="py-2 px-4 bg-green-800 hover:bg-green-700 text-white font-bold rounded disabled:bg-gray-400" disabled>
                            Transfer Tokens
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="toastContainer" class="fixed bottom-20 left-1/2 transform -translate-x-1/2 z-1000"></div>
@endsection

@section('scripts')
    <script>
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

            const toastContainer = document.getElementById('toastContainer');
            const showToast = (message, duration = 3000, isError = false) => {
                const toast = document.createElement('div');
                toast.className = `p-3 rounded-md shadow-md ${isError ? 'bg-red-600' : 'bg-green-800'} text-white m-2`;
                toast.textContent = message;
                toastContainer.appendChild(toast);
                
                setTimeout(() => {
                    toast.style.opacity = '0';
                    toast.style.transition = 'opacity 0.5s ease';
                    setTimeout(() => {
                        toastContainer.removeChild(toast);
                    }, 500);
                }, duration);
            };

            const openTokenModal = () => {
                tokenModal.classList.remove('hidden');
                tokenModal.style.opacity = 0;
                setTimeout(() => { tokenModal.style.opacity = 1; }, 10);
                tokenModalContent.classList.remove('scale-0');
                tokenModalContent.classList.add('animate-whoopIn');
                tokenModal.setAttribute('aria-hidden', 'false');
                const firstInput = tokenModalContent.querySelector('input');
                if (firstInput) firstInput.focus();
            };

            const closeTokenModalFunc = () => {
                tokenModalContent.classList.remove('animate-whoopIn');
                tokenModalContent.classList.add('animate-whoopOut');
                
                setTimeout(() => {
                    tokenModal.classList.add('hidden');
                    tokenModalContent.classList.remove('animate-whoopOut');
                    tokenModalContent.classList.add('scale-0');
                    tokenModal.setAttribute('aria-hidden', 'true');
                    
                    // Reset form
                    transferForm.reset();
                    tokensList.innerHTML = '<div class="text-center py-8 text-gray-500">Loading tokens...</div>';
                    emailError.classList.add('hidden');
                    selectedCount.textContent = '0 tokens selected';
                    transferButton.disabled = true;
                }, 300);
            };

            // Click outside modal to close
            tokenModal.addEventListener('click', (e) => {
                if (e.target === tokenModal) {
                    closeTokenModalFunc();
                }
            });

            // Escape key to close modal
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && !tokenModal.classList.contains('hidden')) {
                    closeTokenModalFunc();
                }
            });

            // Close button click
            closeTokenModal.addEventListener('click', closeTokenModalFunc);

            let tokens = [];
            let selectedTokenIds = [];

            // Open token modal and load tokens
            listTokensButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const investmentId = this.dataset.investmentId;
                    const projectId = this.dataset.projectId;
                    projectIdInput.value = projectId;
                    
                    // Reset selection state
                    selectedTokenIds = [];
                    selectAllTokens.checked = false;
                    updateSelectedCount();
                    
                    openTokenModal();
                    
                    // Load tokens for this investment
                    fetch(`/investments/${investmentId}/tokens`, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Failed to load tokens');
                        }
                        return response.json();
                    })
                    .then(data => {
                        tokens = data.tokens || [];
                        renderTokensList();
                    })
                    .catch(error => {
                        console.error('Error loading tokens:', error);
                        tokensList.innerHTML = '<div class="text-center py-4 text-red-600">Failed to load tokens. Please try again.</div>';
                        showToast('Failed to load tokens', 3000, true);
                    });
                });
            });

            // Render tokens list
            const renderTokensList = () => {
                if (tokens.length === 0) {
                    tokensList.innerHTML = '<div class="text-center py-4 text-gray-600">No tokens available for this investment</div>';
                    return;
                }

                let html = '';
                tokens.forEach(token => {
                    const isChecked = selectedTokenIds.includes(token.id) ? 'checked' : '';
                    html += `
                        <div class="flex items-center p-2 hover:bg-gray-100 rounded">
                            <input type="checkbox" id="token-${token.id}" class="token-checkbox mr-2" 
                                   data-token-id="${token.id}" ${isChecked}>
                            <label for="token-${token.id}" class="flex-grow cursor-pointer">
                                Token #${token.id} ${token.name ? `- ${token.name}` : ''}
                            </label>
                        </div>
                    `;
                });
                
                tokensList.innerHTML = html;
                
                // Add event listeners to checkboxes
                document.querySelectorAll('.token-checkbox').forEach(checkbox => {
                    checkbox.addEventListener('change', handleTokenSelection);
                });
                
                // Update select all checkbox state
                updateSelectAllState();
            };

            // Handle individual token selection
            const handleTokenSelection = (e) => {
                const tokenId = parseInt(e.target.dataset.tokenId);
                
                if (e.target.checked) {
                    if (!selectedTokenIds.includes(tokenId)) {
                        selectedTokenIds.push(tokenId);
                    }
                } else {
                    const index = selectedTokenIds.indexOf(tokenId);
                    if (index !== -1) {
                        selectedTokenIds.splice(index, 1);
                    }
                }
                
                updateSelectedCount();
                updateSelectAllState();
            };

            // Handle "Select All" checkbox
            selectAllTokens.addEventListener('change', () => {
                const checkboxes = document.querySelectorAll('.token-checkbox');
                
                if (selectAllTokens.checked) {
                    selectedTokenIds = tokens.map(token => token.id);
                    checkboxes.forEach(checkbox => {
                        checkbox.checked = true;
                    });
                } else {
                    selectedTokenIds = [];
                    checkboxes.forEach(checkbox => {
                        checkbox.checked = false;
                    });
                }
                
                updateSelectedCount();
            });

            // Update selection counter and button state
            const updateSelectedCount = () => {
                selectedCount.textContent = `${selectedTokenIds.length} tokens selected`;
                transferButton.disabled = selectedTokenIds.length === 0 || recipientEmail.value.trim() === '';
            };

            // Update "Select All" checkbox state
            const updateSelectAllState = () => {
                if (tokens.length === 0) {
                    selectAllTokens.checked = false;
                    return;
                }
                
                selectAllTokens.checked = selectedTokenIds.length === tokens.length;
            };

            // Email validation
            recipientEmail.addEventListener('input', () => {
                const email = recipientEmail.value.trim();
                const isValid = email !== '' && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
                
                if (!isValid && email !== '') {
                    emailError.classList.remove('hidden');
                } else {
                    emailError.classList.add('hidden');
                }
                
                transferButton.disabled = selectedTokenIds.length === 0 || email === '' || !isValid;
            });

            transferForm.addEventListener('submit', (e) => {
                e.preventDefault();
                
                if (selectedTokenIds.length === 0) {
                    showToast('Please select at least one token to transfer', 3000, true);
                    return;
                }
                
                const email = recipientEmail.value.trim();
                if (email === '' || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                    emailError.classList.remove('hidden');
                    return;
                }
                
                const projectId = projectIdInput.value;
                
                // Disable form during submission
                transferButton.disabled = true;
                transferButton.innerHTML = '<span class="spinner inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin mr-2"></span> Transferring...';
                
                // Send transfer request
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
                        project_id: projectId
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(data => {
                            throw new Error(data.message || 'Transfer failed');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    showToast('Tokens transferred successfully!');
                    closeTokenModalFunc();
                    
                    // Refresh the page after successful transfer
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                })
                .catch(error => {
                    console.error('Error transferring tokens:', error);
                    showToast(error.message || 'Failed to transfer tokens', 3000, true);
                    
                    // Reset button state
                    transferButton.disabled = false;
                    transferButton.textContent = 'Transfer Tokens';
                })
                .finally(() => {
                    // Ensure button is reset even if there was an error
                    transferButton.disabled = false;
                    transferButton.textContent = 'Transfer Tokens';
                });
            });
        });
    </script>

    <style>
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

        .spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, 0.6);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
            vertical-align: middle;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
@endsection