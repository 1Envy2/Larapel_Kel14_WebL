@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h1 class="text-3xl font-extrabold text-slate-900 mb-8">Notifikasi</h1>

    @if($notifications->count() > 0)
        <div class="space-y-4">
            @foreach($notifications as $notification)
                @if($notification->title && $notification->message)
                    <a href="{{ route('notifications.show', $notification->id) }}" class="bg-white rounded-xl shadow-sm p-6 border-l-4 {{ $notification->read_at ? 'border-slate-200' : 'border-cyan-500' }} {{ !$notification->read_at ? 'bg-cyan-50' : '' }} transition hover:shadow-md hover:scale-[1.01] block cursor-pointer">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="w-3 h-3 {{ !$notification->read_at ? 'bg-cyan-500' : 'bg-slate-300' }} rounded-full flex-shrink-0"></div>
                                    <h3 class="font-semibold text-slate-900">
                                        @if($notification->type === 'donation_success')
                                            ✓ Donasi Berhasil
                                        @elseif($notification->type === 'donation_failed')
                                            ✕ Donasi Ditolak
                                        @elseif($notification->type === 'campaign_update')
                                            📰 Update Kampanye
                                        @elseif($notification->type === 'thank_you')
                                            <svg class="w-5 h-5 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"></path>
                                            </svg>
                                            Terima Kasih
                                        @else
                                            Pemberitahuan
                                        @endif
                                    </h3>
                                </div>

                                <p class="text-slate-700 mb-2">
                                    {{ $notification->message ?? 'Anda menerima pemberitahuan baru' }}
                                </p>

                                @if(isset($notification->data['campaign_title']))
                                    <p class="text-sm text-slate-500 mb-3">
                                        Kampanye: <strong>{{ $notification->data['campaign_title'] }}</strong>
                                    </p>
                                @endif

                                <div class="text-xs text-slate-500 mt-3">
                                    {{ $notification->created_at->diffForHumans() }}
                                    @if(!$notification->read_at)
                                        <span class="ml-2 px-2 py-1 bg-cyan-100 text-cyan-700 rounded-full text-xs">Belum Dibaca</span>
                                    @else
                                        <span class="ml-2 px-2 py-1 bg-slate-100 text-slate-500 rounded-full text-xs">Sudah Dibaca</span>
                                    @endif
                                </div>
                            </div>

                            <div class="flex-shrink-0 ml-4">
                                @if(!$notification->read_at)
                                    <form method="POST" action="{{ route('notifications.mark-read', $notification->id) }}" class="inline" onclick="event.stopPropagation();">
                                        @csrf
                                        <button type="submit" class="text-slate-400 hover:text-slate-600 text-sm">
                                            Tandai Sudah Dibaca
                                        </button>
                                    </form>
                                @endif

                                <form method="POST" action="{{ route('notifications.delete', $notification->id) }}" class="inline ml-2" onclick="event.stopPropagation();">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-slate-400 hover:text-red-600 text-sm">
                                        ✕
                                    </button>
                                </form>
                            </div>
                        </div>
                    </a>
                @endif
            @endforeach

            <!-- Pagination -->
            @if($notifications->hasPages())
                <div class="mt-8">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    @else
        <div class="text-center py-16 bg-white rounded-xl shadow-sm">
            <svg class="w-16 h-16 mx-auto text-slate-300 mb-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"></path></svg>
            <p class="text-slate-500 text-lg mb-4">Tidak ada notifikasi</p>
            <p class="text-slate-400 text-sm">Anda akan menerima pemberitahuan ketika ada update kampanye atau donasi</p>
        </div>
    @endif
</div>
@endsection
