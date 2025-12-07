@props(['campaign'])

@php
    $collected = (float)($campaign->collected_amount ?? 0);
    $target = (float)($campaign->target_amount ?? 1);
    $percentage = (int) min(100, $target > 0 ? round(($collected / $target) * 100) : 0);
    $donors = $campaign->donations_count ?? 0;
    
    // Calculate days left properly
    if ($campaign->end_date) {
        $daysLeft = now()->diffInDays($campaign->end_date, false);
        $daysLeft = $daysLeft < 0 ? null : ($daysLeft === 0 && now()->lessThan($campaign->end_date) ? 1 : (int)$daysLeft);
    } else {
        $daysLeft = null;
    }
    
    $organizer = $campaign->organizer->name ?? 'Penyelenggara';
    $imageUrl = $campaign->image ? asset('storage/' . $campaign->image) : null;
    
    // Determine if campaign is completed based on database status
    $isCompleted = $campaign->status === 'completed';

    // Indonesian currency formatting helper
    $idr = function($amount) {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    };
@endphp

<div class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-md transition-shadow group">
    <!-- Image Container with Edit Button -->
    <div class="relative h-40 md:h-44 lg:h-36">
        @if($imageUrl)
            <img src="{{ $imageUrl }}" alt="{{ $campaign->title }}" class="w-full h-full object-cover">
        @else
            <div class="w-full h-full bg-gradient-to-r from-slate-100 to-slate-200 flex items-center justify-center text-slate-400 text-sm">
                Gambar Kampanye
            </div>
        @endif

        <!-- Status Badge (Top Left) -->
        @if($isCompleted)
            <span class="absolute top-3 left-3 inline-block bg-green-100 text-green-700 text-xs px-3 py-1 rounded-full shadow font-semibold">Selesai</span>
        @else
            <span class="absolute top-3 left-3 inline-block bg-blue-100 text-blue-700 text-xs px-3 py-1 rounded-full shadow font-semibold">Aktif</span>
        @endif

        <!-- Category Badge (Top Right) -->
        @if(!empty($campaign->category->name))
            @php
                $categoryName = $campaign->category->name === 'Bencana Alam' ? 'Bencana' : $campaign->category->name;
            @endphp
            <span class="absolute top-3 right-3 inline-block bg-cyan-500 text-white text-xs px-3 py-1 rounded-full shadow">{{ $categoryName }}</span>
        @endif

        <!-- Edit Button (Hover) -->
        <a href="{{ route('admin.campaigns.edit', $campaign->id) }}" class="absolute top-3 right-3 opacity-0 group-hover:opacity-100 transition-opacity bg-white rounded-full p-2 shadow-lg hover:bg-cyan-50">
            <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
        </a>
    </div>

    <div class="p-4">
        <a href="{{ route('admin.campaigns.show', $campaign->id) }}" class="block text-lg font-semibold text-slate-800 hover:text-slate-900 leading-tight">{{ $campaign->title }}</a>
        <div class="text-sm text-slate-500 mt-1">{{ $organizer }}</div>

        <div class="mt-4">
            <div class="flex items-center justify-between">
                <div class="text-slate-800 font-semibold">{{ $idr($collected) }}</div>
                <div class="text-sm text-slate-400">dari {{ $idr($target) }}</div>
            </div>

            <div class="mt-2">
                <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                    <div class="h-2 bg-gradient-to-r from-cyan-400 to-blue-500 rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                </div>
                <div class="flex items-center justify-between text-xs text-slate-400 mt-2">
                    <div>{{ $percentage }}%</div>
                    <div>{{ $donors }} donatur</div>
                </div>
            </div>
        </div>

        <!-- View Button -->
        <div class="mt-4">
            <a href="{{ route('admin.campaigns.show', $campaign->id) }}" class="inline-block w-full text-center bg-slate-100 text-slate-700 font-bold py-3 rounded-lg hover:opacity-95 transition-opacity">
                Lihat Detail Kampanye
            </a>
        </div>
    </div>
</div>
