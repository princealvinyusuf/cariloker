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

                    <div class="flex items-center justify-between gap-3">
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

                        <div class="flex items-center gap-2">
                            <button id="start-distribute"
                                    type="button"
                                    class="inline-flex items-center px-4 py-2 bg-violet-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-violet-700 disabled:opacity-50 disabled:cursor-not-allowed">
                                {{ __('Start Distribute Data') }}
                            </button>
                            <button id="clean-related-data"
                                    type="button"
                                    class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed">
                                {{ __('Clean Relatable Database') }}
                            </button>
                        </div>
                    </div>

                    <div>
                        <p class="text-sm font-medium mb-2">
                            {{ __('Progress') }}:
                            <span id="progress-text">
                                {{ $progress['processed'] ?? 0 }} / {{ $progress['total'] ?? 0 }}
                            </span>
                        </p>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mb-2">
                            {{ __('Succeeded') }}: <span id="succeeded-count">{{ $progress['succeeded'] ?? 0 }}</span>
                            • {{ __('Failed') }}: <span id="failed-count">{{ $progress['failed'] ?? 0 }}</span>
                            • {{ __('Skipped') }}: <span id="skipped-count">{{ $progress['skipped'] ?? 0 }}</span>
                        </p>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mb-2">
                            {{ __('Rows/s') }}: <span id="rows-per-second">{{ $progress['rows_per_second'] ?? 0 }}</span>
                            • {{ __('Chunk Rows/s') }}: <span id="chunk-rows-per-second">{{ $progress['chunk_rows_per_second'] ?? 0 }}</span>
                            • {{ __('Elapsed') }}: <span id="elapsed-seconds">{{ $progress['elapsed_seconds'] ?? 0 }}</span>s
                            • {{ __('ETA') }}: <span id="eta-seconds">{{ $progress['eta_seconds'] ?? '-' }}</span>
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
                        <h3 class="text-sm font-semibold mb-2">{{ __('Result') }}</h3>
                        <div id="result-container" class="text-xs space-y-1 max-h-40 overflow-y-auto"></div>
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
            const cleanButton = document.getElementById('clean-related-data');
            const statusLabel = document.getElementById('status-label');
            const totalRowsEl = document.getElementById('total-rows');
            const progressText = document.getElementById('progress-text');
            const succeededCount = document.getElementById('succeeded-count');
            const failedCount = document.getElementById('failed-count');
            const skippedCount = document.getElementById('skipped-count');
            const rowsPerSecond = document.getElementById('rows-per-second');
            const chunkRowsPerSecond = document.getElementById('chunk-rows-per-second');
            const elapsedSeconds = document.getElementById('elapsed-seconds');
            const etaSeconds = document.getElementById('eta-seconds');
            const progressBar = document.getElementById('progress-bar');
            const resultContainer = document.getElementById('result-container');
            const errorsContainer = document.getElementById('errors-container');

            let pollInterval = null;

            function setButtonsDisabled(disabled) {
                startButton.disabled = disabled;
                cleanButton.disabled = disabled;
            }

            function updateUI(data) {
                const total = data.total ?? 0;
                const processed = data.processed ?? 0;
                const running = !!data.running;
                const errors = Array.isArray(data.errors) ? data.errors : [];
                const succeeded = data.succeeded ?? 0;
                const failed = data.failed ?? 0;
                const skipped = data.skipped ?? 0;
                const rps = data.rows_per_second ?? 0;
                const chunkRps = data.chunk_rows_per_second ?? 0;
                const elapsed = data.elapsed_seconds ?? 0;
                const eta = data.eta_seconds;
                const successMessage = data.success_message ?? '';

                totalRowsEl.textContent = total;
                progressText.textContent = processed + ' / ' + total;
                succeededCount.textContent = succeeded;
                failedCount.textContent = failed;
                skippedCount.textContent = skipped;
                rowsPerSecond.textContent = rps;
                chunkRowsPerSecond.textContent = chunkRps;
                elapsedSeconds.textContent = elapsed;
                etaSeconds.textContent = eta === null ? '-' : eta + 's';

                resultContainer.innerHTML = '';
                if (successMessage) {
                    const p = document.createElement('p');
                    p.className = 'text-emerald-500';
                    p.textContent = '• ' + successMessage;
                    resultContainer.appendChild(p);
                }

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
                            setButtonsDisabled(false);
                        }
                    })
                    .catch(() => {
                        if (pollInterval) {
                            clearInterval(pollInterval);
                            pollInterval = null;
                        }
                        setButtonsDisabled(false);
                    });
            }

            startButton.addEventListener('click', function () {
                setButtonsDisabled(true);
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
                                succeeded: data.succeeded ?? 0,
                                failed: data.failed ?? 0,
                                skipped: data.skipped ?? 0,
                                rows_per_second: data.rows_per_second ?? 0,
                                chunk_rows_per_second: data.chunk_rows_per_second ?? 0,
                                elapsed_seconds: data.elapsed_seconds ?? 0,
                                eta_seconds: data.eta_seconds ?? null,
                                running: false,
                                success_message: '',
                                errors: [data.message || 'Failed to start distribution.'],
                            });
                            setButtonsDisabled(false);
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
                            succeeded: 0,
                            failed: 1,
                            skipped: 0,
                            rows_per_second: 0,
                            chunk_rows_per_second: 0,
                            elapsed_seconds: 0,
                            eta_seconds: null,
                            running: false,
                            success_message: '',
                            errors: ['Unexpected error starting distribution.'],
                        });
                        setButtonsDisabled(false);
                    });
            });

            cleanButton.addEventListener('click', function () {
                const confirmed = window.confirm('Clean ALL job listings and related orphaned companies/categories/locations? This will NOT delete rows in job_imports.');
                if (!confirmed) {
                    return;
                }

                setButtonsDisabled(true);
                statusLabel.textContent = 'Cleaning...';

                fetch('{{ route('admin.jobs.import.clean') }}', {
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
                                processed: 0,
                                succeeded: 0,
                                failed: 1,
                                skipped: 0,
                                rows_per_second: 0,
                                chunk_rows_per_second: 0,
                                elapsed_seconds: 0,
                                eta_seconds: null,
                                running: false,
                                success_message: '',
                                errors: [data.message || 'Failed to clean related data.'],
                            });
                            setButtonsDisabled(false);
                            return;
                        }

                        const counts = data.counts || {};
                        updateUI({
                            total: Number(document.getElementById('total-rows').textContent || 0),
                            processed: 0,
                            succeeded: 0,
                            failed: 0,
                            skipped: 0,
                            rows_per_second: 0,
                            chunk_rows_per_second: 0,
                            elapsed_seconds: 0,
                            eta_seconds: null,
                            running: false,
                            success_message:
                                (data.message || 'Cleanup completed.') +
                                    ' jobs=' + (counts.jobs_deleted ?? 0) +
                                    ', companies=' + (counts.companies_deleted ?? 0) +
                                    ', categories=' + (counts.categories_deleted ?? 0) +
                                    ', locations=' + (counts.locations_deleted ?? 0),
                            errors: [],
                        });
                        statusLabel.textContent = 'Idle';
                        setButtonsDisabled(false);
                    })
                    .catch(() => {
                        updateUI({
                            total: Number(document.getElementById('total-rows').textContent || 0),
                            processed: 0,
                            succeeded: 0,
                            failed: 1,
                            skipped: 0,
                            rows_per_second: 0,
                            chunk_rows_per_second: 0,
                            elapsed_seconds: 0,
                            eta_seconds: null,
                            running: false,
                            success_message: '',
                            errors: ['Unexpected error while cleaning related data.'],
                        });
                        setButtonsDisabled(false);
                    });
            });

            // If a job is already running, start polling immediately
            @if(!empty($progress['running']))
                pollInterval = setInterval(fetchProgress, 2000);
                setButtonsDisabled(true);
            @endif
        })();
    </script>
</x-app-layout>


