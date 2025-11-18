<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Distribute Data From Staging Table') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold mb-2">{{ __('Overview') }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ __('This tool will distribute data from the staging table job_imports into companies, locations, categories, and job listings.') }}
                        </p>
                    </div>

                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium">
                                {{ __('Total rows in staging table') }}:
                                <span id="total-rows" class="font-semibold">{{ $progress['total'] ?? 0 }}</span>
                            </p>
                            <p class="text-sm mt-1">
                                {{ __('Status') }}:
                                <span id="status-label" class="font-semibold">
                                    @if(!empty($progress['running']))
                                        {{ __('Running') }}
                                    @elseif(($progress['processed'] ?? 0) > 0)
                                        {{ __('Completed') }}
                                    @else
                                        {{ __('Idle') }}
                                    @endif
                                </span>
                            </p>
                        </div>

                        <button id="start-distribute"
                                type="button"
                                class="inline-flex items-center px-4 py-2 bg-violet-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-violet-700 disabled:opacity-50 disabled:cursor-not-allowed">
                            {{ __('Start Distribute Data') }}
                        </button>
                    </div>

                    <div>
                        <p class="text-sm font-medium mb-2">
                            {{ __('Progress') }}:
                            <span id="progress-text">
                                {{ $progress['processed'] ?? 0 }} / {{ $progress['total'] ?? 0 }}
                            </span>
                        </p>
                        <div class="w-full bg-gray-200 rounded-full h-3 dark:bg-gray-700">
                            @php
                                $total = max((int) ($progress['total'] ?? 0), 1);
                                $processed = (int) ($progress['processed'] ?? 0);
                                $percentage = min(100, (int) floor(($processed / $total) * 100));
                            @endphp
                            <div id="progress-bar"
                                 class="bg-violet-600 h-3 rounded-full transition-all duration-300"
                                 style="width: {{ $percentage }}%;">
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-sm font-semibold mb-2">{{ __('Errors (if any)') }}</h3>
                        <div id="errors-container" class="text-xs space-y-1 max-h-40 overflow-y-auto">
                            @foreach(($progress['errors'] ?? []) as $error)
                                <p class="text-red-500">• {{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const startButton = document.getElementById('start-distribute');
            const statusLabel = document.getElementById('status-label');
            const totalRowsEl = document.getElementById('total-rows');
            const progressText = document.getElementById('progress-text');
            const progressBar = document.getElementById('progress-bar');
            const errorsContainer = document.getElementById('errors-container');

            let pollInterval = null;

            function updateUI(data) {
                const total = data.total ?? 0;
                const processed = data.processed ?? 0;
                const running = !!data.running;
                const errors = Array.isArray(data.errors) ? data.errors : [];

                totalRowsEl.textContent = total;
                progressText.textContent = processed + ' / ' + total;

                const denom = total > 0 ? total : 1;
                const percentage = Math.min(100, Math.floor((processed / denom) * 100));
                progressBar.style.width = percentage + '%';

                if (running) {
                    statusLabel.textContent = 'Running';
                } else if (processed > 0) {
                    statusLabel.textContent = 'Completed';
                } else if (errors.length > 0) {
                    statusLabel.textContent = 'Error';
                } else {
                    statusLabel.textContent = 'Idle';
                }

                errorsContainer.innerHTML = '';
                errors.forEach(function (err) {
                    const p = document.createElement('p');
                    p.className = 'text-red-500';
                    p.textContent = '• ' + err;
                    errorsContainer.appendChild(p);
                });
            }

            function fetchProgress() {
                fetch('{{ route('admin.jobs.import.progress') }}', {
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        updateUI(data);

                        if (!data.running) {
                            if (pollInterval) {
                                clearInterval(pollInterval);
                                pollInterval = null;
                            }
                            startButton.disabled = false;
                        }
                    })
                    .catch(() => {
                        if (pollInterval) {
                            clearInterval(pollInterval);
                            pollInterval = null;
                        }
                        startButton.disabled = false;
                    });
            }

            startButton.addEventListener('click', function () {
                startButton.disabled = true;
                statusLabel.textContent = 'Starting...';

                fetch('{{ route('admin.jobs.import.start') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({})
                })
                    .then(async (response) => {
                        const data = await response.json().catch(() => ({}));

                        if (!response.ok) {
                            updateUI({
                                total: data.total ?? 0,
                                processed: data.processed ?? 0,
                                running: false,
                                errors: [data.message || 'Failed to start distribution.'],
                            });
                            startButton.disabled = false;
                            return;
                        }

                        statusLabel.textContent = 'Running';
                        pollInterval = setInterval(fetchProgress, 2000);
                        fetchProgress();
                    })
                    .catch(() => {
                        updateUI({
                            total: 0,
                            processed: 0,
                            running: false,
                            errors: ['Unexpected error starting distribution.'],
                        });
                        startButton.disabled = false;
                    });
            });

            // If a job is already running, start polling immediately
            @if(!empty($progress['running']))
                pollInterval = setInterval(fetchProgress, 2000);
            @endif
        })();
    </script>
</x-app-layout>


