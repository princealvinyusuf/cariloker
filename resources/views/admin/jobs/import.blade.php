<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Bulk Import Jobs') }}
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

                <form method="POST" action="{{ route('admin.jobs.import.store') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Upload Excel/CSV') }}</label>
                        <input name="file" type="file" accept=".xlsx,.csv" class="mt-1 block w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 cursor-pointer" required />
                        @error('file')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <button class="px-4 py-2 rounded bg-indigo-600 hover:bg-indigo-500 text-white font-semibold">{{ __('Import') }}</button>
                </form>

                <div class="mt-6 text-sm text-gray-600 dark:text-gray-300">
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
            </div>
        </div>
    </div>
</x-app-layout>


