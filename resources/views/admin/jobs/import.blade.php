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
                    <h3 class="font-semibold text-gray-800 dark:text-gray-200">{{ __('Process staging (for DBeaver imports)') }}</h3>
                    <ol class="list-decimal ms-6 text-sm text-gray-600 dark:text-gray-300 space-y-1">
                        <li>{{ __('Run migrations to create table') }}: <code class="px-1 py-0.5 bg-gray-100 rounded">job_imports</code>.</li>
                        <li>{{ __('In DBeaver, import your Excel/CSV into the') }} <code>job_imports</code> {{ __('table (map headers to columns).') }}</li>
                        <li>{{ __('Click the button below to transform into normalized tables.') }}</li>
                    </ol>
                    <form method="POST" action="{{ route('admin.jobs.import.process') }}">
                        @csrf
                        <button class="px-4 py-2 rounded bg-emerald-600 hover:bg-emerald-500 text-white font-semibold">{{ __('Process Staging Data') }}</button>
                    </form>
                    <form method="POST" action="{{ route('admin.jobs.truncate') }}" class="mt-3" onsubmit="return confirm('{{ __('This will delete jobs, companies, locations, categories, skills, applications and saved jobs. Continue?') }}')">
                        @csrf
                        <button class="px-4 py-2 rounded bg-red-600 hover:bg-red-500 text-white font-semibold">{{ __('Truncate Related Tables') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


