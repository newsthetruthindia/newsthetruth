<x-filament-panels::page>
    <div wire:init="loadData" class="monitor-dashboard bg-[#0a0c10] min-h-screen p-4 rounded-xl shadow-2xl overflow-hidden border border-white/5">
        
        <!-- Header Controls -->
        <div class="flex items-center justify-between mb-4 px-2">
            <h2 class="text-xl font-black text-white tracking-tight uppercase italic flex items-center gap-3">
                <span class="w-2 h-6 bg-primary-600 rounded-full"></span>
                News Monitor <span class="text-xs font-mono text-primary-500 font-bold opacity-50 tracking-widest">[COMMAND CENTER]</span>
            </h2>
            <div class="flex items-center gap-3">
                <button onclick="window.location.href='/admin'" class="px-4 py-1.5 bg-gray-800/50 hover:bg-gray-700 text-gray-300 text-[10px] font-bold uppercase tracking-widest rounded-md transition-all border border-white/5">
                    Dashboard
                </button>
                <button wire:click="mountAction('configure')" class="px-4 py-1.5 bg-primary-600 hover:bg-primary-500 text-white text-[10px] font-bold uppercase tracking-widest rounded-md transition-all shadow-lg shadow-primary-900/20">
                    Source Config
                </button>
            </div>
        </div>

        <!-- 4x3 High-Performance Video Matrix -->
        <div class="grid grid-cols-4 gap-2 mb-6">
            @for ($i = 0; $i < 12; $i++)
                @php
                    $urlConfig = $this->youtube_urls[$i] ?? null;
                    $url = is_array($urlConfig) ? ($urlConfig['url'] ?? null) : $urlConfig;
                    $id = $this->getYoutubeId($url);
                @endphp

                <div class="relative aspect-video bg-[#12141a] group rounded-lg overflow-hidden border border-white/5 shadow-2xl transition-all hover:border-primary-500/50">
                    @if ($id)
                        <iframe 
                            src="https://www.youtube.com/embed/{{ $id }}?autoplay=1&mute=1&controls=0&modestbranding=1&rel=0&showinfo=0&iv_load_policy=3&enablejsapi=1" 
                            class="absolute inset-0 w-full h-full grayscale-[0.1] hover:grayscale-0 transition-all duration-500" 
                            frameborder="0" 
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                            allowfullscreen>
                        </iframe>
                        <!-- Mini HUD -->
                        <div class="absolute top-2 left-2 flex items-center gap-1.5 px-2 py-0.5 bg-black/60 backdrop-blur-md rounded text-[9px] font-mono text-white/50 tracking-tighter opacity-0 group-hover:opacity-100 transition-opacity">
                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span>
                            CH_{{ sprintf('%02d', $i + 1) }}
                        </div>
                    @else
                        <!-- Standby Screen -->
                        <div class="absolute inset-0 flex flex-col items-center justify-center opacity-30">
                            <svg class="w-8 h-8 text-gray-600 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.414a4 4 0 00-5.656-5.656l-6.415 6.414a6 6 0 108.486 8.486L20.5 13" />
                            </svg>
                            <span class="text-[8px] font-mono text-gray-700 uppercase tracking-widest leading-none">Signal Lost</span>
                        </div>
                    @endif
                </div>
            @endfor
        </div>

        <!-- 6-Line Independent RSS Ticker Matrix -->
        <div class="space-y-1.5">
            @php $feedsHeadlines = $this->getRssHeadlines(); @endphp
            @for ($f = 0; $f < 6; $f++)
                @php $headlines = $feedsHeadlines[$f] ?? []; @endphp
                <div class="h-9 bg-[#dadada] flex items-center overflow-hidden rounded-md shadow-inner border-b border-black/10">
                    <!-- Feed Label (Left Sticky) -->
                    <div class="flex-shrink-0 h-full bg-[#333] flex items-center px-4 border-r border-black/20 mr-4 shadow-xl">
                        <span class="text-[10px] font-black text-white uppercase tracking-tighter w-20">RSS FEED {{ $f + 1 }}</span>
                    </div>
                    
                    <!-- Scrolling Headlines -->
                    <div class="flex-grow overflow-hidden whitespace-nowrap bg-white/40 h-full flex items-center">
                        <div class="inline-block animate-rss-row-{{ $f }} flex items-center">
                            @if(count($headlines) > 0)
                                @foreach (array_merge($headlines, $headlines) as $h)
                                     <a href="{{ $h['link'] }}" target="_blank" class="text-[12px] font-bold text-gray-800 mx-8 flex items-center gap-3 hover:text-primary-600 transition-colors">
                                        <span class="w-1 h-3 bg-gray-400 rounded-full"></span>
                                        {{ $h['title'] }}
                                     </a>
                                @endforeach
                            @else
                                <span class="text-[10px] font-mono text-gray-400 italic px-4 uppercase tracking-widest">Awaiting live synchronization signal for feed {{ $f + 1 }}...</span>
                            @endif
                        </div>
                    </div>
                </div>

                <style>
                    @keyframes rss-scroll-{{ $f }} {
                        0% { transform: translateX(0); }
                        100% { transform: translateX(-50%); }
                    }
                    .animate-rss-row-{{ $f }} {
                        animation: rss-scroll-{{ $f }} {{ 40 + ($f * 5) }}s linear infinite;
                    }
                    .animate-rss-row-{{ $f }}:hover {
                        animation-play-state: paused;
                    }
                </style>
            @endfor
        </div>

    </div>

    @push('styles')
    <style>
        /* Filament Override for Dashboard Look */
        .fi-main { max-width: none !important; padding: 0.5rem !important; }
        .fi-topbar { box-shadow: none !important; border-bottom: 1px solid rgba(255,255,255,0.05) !important; }
        ::-webkit-scrollbar { width: 4px; height: 4px; }
        ::-webkit-scrollbar-thumb { background: #333; border-radius: 10px; }
    </style>
    @endpush
</x-filament-panels::page>
