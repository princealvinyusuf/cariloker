<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Job Staging & Transform') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if (session('status'))
                    <div class="mb-4 p-3 rounded bg-green-100 text-green-800">{{ session('status') }}</div>
                @endif
                @if (session('import_errors'))
                    <div class="mb-4 p-3 rounded bg-yellow-100 text-yellow-800">
                        <div class="font-semibold mb-1">{{ __('Import warnings') }}</div>
                        <ul class="list-disc ms-6 text-sm">
                            @foreach(session('import_errors') as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                    <p class="font-semibold mb-2">{{ __('Expected columns (exact headers)') }}:</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        <ul class="list-disc ms-5">
                            <li>Nama Perusahaan</li>
                            <li>Provinsi</li>
                            <li>KAB/KOTA</li>
                            <li>Sektor</li>
                            <li>jabatan</li>
                            <li>Jumlah Lowongan (orang)</li>
                            <li>Tanggal Posting</li>
                            <li>Tanggal Berakhir</li>
                            <li>URL</li>
                        </ul>
                        <ul class="list-disc ms-5">
                            <li>Jenis Kelamin</li>
                            <li>Kondisi</li>
                            <li>Tipe Pekerjaan</li>
                            <li>Tingkat pekerjaan</li>
                            <li>Pendidikan</li>
                            <li>Gaji</li>
                            <li>Bidang pekerjaan</li>
                            <li>Keahlian</li>
                            <li>Deskripsi</li>
                            <li>Pengalaman</li>
                        </ul>
                    </div>
                    <p class="mt-2">{{ __('Tips') }}: {{ __('Export as .xlsx or .csv from Excel. Dates can be d/m/Y (e.g., 03/09/2025). Gaji may be a range like "6,000,000 - 10,000,000".') }}</p>
                </div>

                <hr class="my-8 border-gray-200 dark:border-gray-700">
                <div class="space-y-3">
                    @php
                        $p = $progress['processed'] ?? 0;
                        $t = $progress['total'] ?? ($total ?? 0);
                        $running = $progress['running'] ?? false;
                        $percent = $t > 0 ? min(100, round(($p / $t) * 100)) : 0;
                        $showProgress = isset($total) && $total > 0;
                    @endphp
                    <div id="progressContainer" @if(!$showProgress) style="display: none;" @endif>
                        <div class="flex items-center justify-between text-sm text-gray-600 dark:text-gray-300">
                            <span>{{ __('Progress') }}</span>
                            <span id="progressText">{{ $p }} / {{ $t }} ({{ $percent }}%)</span>
                        </div>
                        <div class="mt-1 h-3 bg-gray-100 rounded">
                            <div id="progressBar" class="h-3 bg-emerald-500 rounded transition-all duration-300" style="width: {{ $percent }}%"></div>
                        </div>
                        @if($running)
                            <p class="mt-1 text-xs text-gray-500">{{ __('Processing is in progress or can be resumed. Click Process again to continue from the last point.') }}</p>
                        @endif
                    </div>
                    <h3 class="font-semibold text-gray-800 dark:text-gray-200">{{ __('Process staging (for DBeaver imports)') }}</h3>
                    <ol class="list-decimal ms-6 text-sm text-gray-600 dark:text-gray-300 space-y-1">
                        <li>{{ __('Run migrations to create table') }}: <code class="px-1 py-0.5 bg-gray-100 rounded">job_imports</code>.</li>
                        <li>{{ __('In DBeaver, import your Excel/CSV into the') }} <code>job_imports</code> {{ __('table (map headers to columns).') }}</li>
                        <li>{{ __('Click the button below to transform into normalized tables.') }}</li>
                    </ol>
                    <form id="processForm" method="POST" action="{{ route('admin.jobs.import.process') }}">
                        @csrf
                        <button id="processButton" type="submit" class="px-4 py-2 rounded bg-emerald-600 hover:bg-emerald-500 text-white font-semibold disabled:opacity-50 disabled:cursor-not-allowed">
                            <span id="buttonText">{{ __('Process Staging Data') }}</span>
                            <span id="buttonSpinner" class="hidden inline-block ml-2">
                                <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </span>
                        </button>
                    </form>
                    <div id="processingStatus" class="mt-3 hidden text-sm text-gray-600 dark:text-gray-300"></div>
                    <div id="errorDisplay" class="mt-3 hidden p-3 rounded bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200">
                        <div class="font-semibold mb-1">{{ __('Error') }}:</div>
                        <div id="errorMessage" class="text-sm"></div>
                        <div id="errorDetails" class="text-xs mt-2 font-mono opacity-75"></div>
                    </div>
                    <form method="POST" action="{{ route('admin.jobs.truncate') }}" class="mt-3" onsubmit="return confirm('{{ __('This will delete jobs, companies, locations, categories, skills, applications and saved jobs. Continue?') }}')">
                        @csrf
                        <button class="px-4 py-2 rounded bg-red-600 hover:bg-red-500 text-white font-semibold">{{ __('Truncate Related Tables') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const processForm = document.getElementById('processForm');
            const processButton = document.getElementById('processButton');
            const buttonText = document.getElementById('buttonText');
            const buttonSpinner = document.getElementById('buttonSpinner');
            const processingStatus = document.getElementById('processingStatus');
            const progressContainer = document.getElementById('progressContainer');
            const progressText = document.getElementById('progressText');
            const progressBar = document.getElementById('progressBar');
            const errorDisplay = document.getElementById('errorDisplay');
            const errorMessage = document.getElementById('errorMessage');
            const errorDetails = document.getElementById('errorDetails');
            
            let pollingInterval = null;
            let isProcessing = false;

            function showError(message, details = null) {
                console.error('Job Import Error:', message, details);
                if (errorDisplay && errorMessage) {
                    errorMessage.textContent = message;
                    if (errorDetails && details) {
                        errorDetails.textContent = typeof details === 'string' ? details : JSON.stringify(details, null, 2);
                    }
                    errorDisplay.classList.remove('hidden');
                    if (processingStatus) {
                        processingStatus.classList.add('hidden');
                    }
                }
            }

            function hideError() {
                if (errorDisplay) {
                    errorDisplay.classList.add('hidden');
                }
            }

            // Check if processing is already running on page load
            checkProgress();

            // Handle form submission
            if (processForm) {
                processForm.addEventListener('submit', async function(e) {
                    e.preventDefault(); // Prevent default form submission
                    
                    if (isProcessing) {
                        return;
                    }

                    isProcessing = true;
                    processButton.disabled = true;
                    buttonSpinner.classList.remove('hidden');
                    buttonText.textContent = '{{ __('Processing...') }}';
                    processingStatus.classList.remove('hidden');
                    processingStatus.textContent = '{{ __('Starting processing...') }}';
                    processingStatus.className = 'mt-3 text-sm text-blue-600 dark:text-blue-400';
                    hideError();

                    try {
                        console.log('Submitting form to:', processForm.action);
                        const formData = new FormData(processForm);
                        
                        // Start polling immediately - don't wait for response
                        startPolling();
                        
                        const response = await fetch(processForm.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                            // Don't follow redirects automatically
                            redirect: 'manual'
                        });

                        console.log('Form submission response:', response.status, response.statusText);
                        
                        // If redirect, it means processing started successfully
                        if (response.status >= 300 && response.status < 400) {
                            console.log('Processing started successfully');
                            // Continue polling - progress will update
                        } else if (response.ok) {
                            // Response was OK but no redirect
                            const data = await response.json().catch(() => null);
                            console.log('Response data:', data);
                        } else {
                            // Error response
                            const errorText = await response.text();
                            console.error('Form submission error:', response.status, errorText);
                            showError('Error starting processing: HTTP ' + response.status, errorText);
                            stopPolling();
                            resetButton();
                        }
                    } catch (error) {
                        console.error('Form submission exception:', error);
                        showError('Error submitting form: ' + error.message, error.stack);
                        stopPolling();
                        resetButton();
                    }
                });
            }

            function startPolling() {
                if (pollingInterval) {
                    clearInterval(pollingInterval);
                }

                pollingInterval = setInterval(checkProgress, 2000); // Poll every 2 seconds
                checkProgress(); // Check immediately
            }

            function stopPolling() {
                if (pollingInterval) {
                    clearInterval(pollingInterval);
                    pollingInterval = null;
                }
            }

            async function checkProgress() {
                try {
                    console.log('Checking progress...');
                    const response = await fetch('{{ route('admin.jobs.import.progress') }}', {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        }
                    });

                    console.log('Progress response status:', response.status, response.statusText);

                    if (!response.ok) {
                        const errorText = await response.text();
                        console.error('Progress fetch failed:', {
                            status: response.status,
                            statusText: response.statusText,
                            body: errorText
                        });
                        throw new Error(`HTTP ${response.status}: ${response.statusText}\n${errorText}`);
                    }

                    const data = await response.json();
                    console.log('Progress data received:', data);
                    
                    hideError(); // Hide any previous errors on successful fetch
                    
                    // Update progress display
                    if (data.total > 0) {
                        // Ensure progress container is visible
                        if (progressContainer) {
                            progressContainer.style.display = 'block';
                        }
                        
                        if (progressText && progressBar) {
                            const percent = Math.min(100, Math.round((data.processed / data.total) * 100));
                            progressText.textContent = `${data.processed} / ${data.total} (${percent}%)`;
                            progressBar.style.width = `${percent}%`;
                        }
                    }

                    // Update status message
                    if (processingStatus) {
                        if (data.running) {
                            processingStatus.textContent = `{{ __('Processing...') }} ${data.processed} / ${data.total} rows completed`;
                            processingStatus.className = 'mt-3 text-sm text-blue-600 dark:text-blue-400';
                            processingStatus.classList.remove('hidden');
                            
                            if (!isProcessing && processButton) {
                                isProcessing = true;
                                processButton.disabled = true;
                                if (buttonSpinner) buttonSpinner.classList.remove('hidden');
                                if (buttonText) buttonText.textContent = '{{ __('Processing...') }}';
                            }
                            
                            // Keep polling
                            if (!pollingInterval) {
                                startPolling();
                            }
                        } else {
                            // Not running anymore
                            if (data.processed >= data.total && data.total > 0) {
                                // Completed
                                processingStatus.textContent = '{{ __('Processing completed!') }}';
                                processingStatus.className = 'mt-3 text-sm text-green-600 dark:text-green-400';
                                stopPolling();
                                resetButton();
                                
                                // Refresh page after 2 seconds to show final status
                                setTimeout(() => {
                                    window.location.reload();
                                }, 2000);
                            } else if (data.total === 0) {
                                // No data to process
                                processingStatus.textContent = '{{ __('No data to process. Please import data first.') }}';
                                processingStatus.className = 'mt-3 text-sm text-yellow-600 dark:text-yellow-400';
                                stopPolling();
                                resetButton();
                            } else {
                                // Stopped but not complete
                                stopPolling();
                                resetButton();
                                if (processingStatus) {
                                    processingStatus.classList.add('hidden');
                                }
                            }
                        }
                    }
                } catch (error) {
                    console.error('Error checking progress:', error);
                    console.error('Error stack:', error.stack);
                    showError('Error checking progress: ' + error.message, error.stack || error.toString());
                    stopPolling();
                    resetButton();
                }
            }

            function resetButton() {
                isProcessing = false;
                if (processButton) processButton.disabled = false;
                if (buttonSpinner) buttonSpinner.classList.add('hidden');
                if (buttonText) buttonText.textContent = '{{ __('Process Staging Data') }}';
            }
        });
    </script>
</x-app-layout>


