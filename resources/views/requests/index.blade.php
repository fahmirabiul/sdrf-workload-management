<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Modul Manajemen Pengajuan (SDRF Tickets)') }}
            </h2>
            @if (auth()->user()->role === 'user')
                <a href="{{ route('requests.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                    + Buat Request Baru
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <h3 class="text-lg font-medium mb-4">Daftar Tiket Masuk</h3>

                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-md">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">No. Tiket</th>
                                    <th scope="col" class="px-6 py-3">Judul Request</th>
                                    <th scope="col" class="px-6 py-3">Unit Kerja</th>
                                    <th scope="col" class="px-6 py-3">Aplikasi Target</th>
                                    <th scope="col" class="px-6 py-3">Tipe</th>
                                    <th scope="col" class="px-6 py-3">Status</th>
                                    <th scope="col" class="px-6 py-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($requests as $req)
                                    <tr
                                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <td class="px-6 py-4 font-mono font-bold text-gray-900 dark:text-white">
                                            {{ $req->ticket_number }}
                                        </td>
                                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                            {{ $req->title }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $req->unit->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $req->application->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <span
                                                class="capitalize text-xs font-semibold px-2 py-1 rounded bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                                {{ str_replace('_', ' ', $req->request_type) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            @php
                                                $statusColors = [
                                                    'submitted' =>
                                                        'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                                    'analysis_scheduled' =>
                                                        'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                                                    'approved' =>
                                                        'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                                    'rejected' =>
                                                        'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                                                ];
                                                $color = $statusColors[$req->status] ?? 'bg-gray-100 text-gray-800';
                                            @endphp
                                            <span
                                                class="text-xs font-bold px-2.5 py-1 rounded-full {{ $color }}">
                                                {{ strtoupper($req->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <a href="{{ route('requests.show', $req->id) }}"
                                                class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Detail</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                            Belum ada data pengajuan tiket saat ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $requests->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
