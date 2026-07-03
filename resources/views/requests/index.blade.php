<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                {{ __('Modul Manajemen Pengajuan (SDRF Tickets)') }}
            </h2>
            @if (auth()->user()->role === 'user')
                <a href="{{ route('requests.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-primary hover:bg-primary/95 text-white shadow-soft-sm rounded-lg font-semibold text-xs uppercase tracking-widest disabled:opacity-25 transition ease-in-out duration-150">
                    + Buat Request Baru
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-soft rounded-2xl border-0">
                <div class="p-6 text-slate-700">

                    <h3 class="text-lg font-bold text-slate-700 mb-4">Daftar Tiket Masuk</h3>

                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-50 border-0 text-success rounded-xl text-sm font-semibold">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-slate-500">
                            <thead
                                class="text-xs uppercase bg-gray-50 text-slate-600 font-bold">
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
                            <tbody class="divide-y divide-gray-100">
                                @forelse($requests as $req)
                                    <tr
                                        class="bg-white hover:bg-gray-50/50 transition-colors">
                                        <td class="px-6 py-4 font-mono font-bold text-slate-800">
                                            {{ $req->ticket_number }}
                                        </td>
                                        <td class="px-6 py-4 font-medium text-slate-800">
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
                                                class="capitalize text-xs font-semibold px-2.5 py-1 rounded-1.8 bg-slate-100 text-slate-600">
                                                {{ str_replace('_', ' ', $req->request_type) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            @php
                                                $statusColors = [
                                                    'submitted' =>
                                                        'bg-yellow-100 text-yellow-800',
                                                    'analysis_scheduled' =>
                                                        'bg-blue-100 text-blue-800',
                                                    'approved' =>
                                                        'bg-green-100 text-green-800',
                                                    'rejected' =>
                                                        'bg-red-100 text-red-800',
                                                ];
                                                $color = $statusColors[$req->status] ?? 'bg-gray-100 text-gray-800';
                                            @endphp
                                            <span
                                                class="text-xs font-bold px-3 py-1 rounded-1.8 {{ $color }}">
                                                {{ strtoupper($req->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <a href="{{ route('requests.show', $req->id) }}"
                                                class="font-semibold text-primary hover:text-primary-light transition-colors">Detail</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="bg-white">
                                        <td colspan="7" class="px-6 py-4 text-center text-slate-400">
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
