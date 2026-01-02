@extends('admin.layouts.app')

@section('title', 'Metode Pembayaran')

@section('header')
<div class="flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Metode Pembayaran</h1>
        <p class="mt-1 text-sm text-gray-500">Kelola akun bank dan e-wallet untuk pembayaran</p>
    </div>
    <a href="{{ route('admin.payment-methods.create') }}" 
       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
        </svg>
        Tambah Metode
    </a>
</div>
@endsection

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Metode</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rekening</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($paymentMethods as $method)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            @if($method->logo_url)
                                <div class="flex-shrink-0 h-10 w-10 bg-gray-100 rounded-lg p-1 flex items-center justify-center">
                                    <img class="max-h-8 max-w-8 object-contain" src="{{ $method->logo_url }}" alt="{{ $method->name }}">
                                </div>
                            @else
                                <div class="flex-shrink-0 h-10 w-10 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                </div>
                            @endif
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ $method->name }}</div>
                                <div class="text-sm text-gray-500">{{ Str::limit($method->description, 30) }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $method->account_number }}</div>
                        <div class="text-sm text-gray-500">{{ $method->account_holder }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($method->is_active)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Aktif
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Nonaktif
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('admin.payment-methods.edit', $method) }}" class="text-primary-600 hover:text-primary-900 mr-3">Edit</a>
                        <form action="{{ route('admin.payment-methods.destroy', $method) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus metode pembayaran ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-10 text-center text-gray-500">
                        Belum ada metode pembayaran. Silakan tambahkan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($paymentMethods->hasPages())
    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
        {{ $paymentMethods->links() }}
    </div>
    @endif
</div>
@endsection
