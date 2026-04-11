<x-filament-panels::page>
    <div wire:init="loadData" class="monitor-container pb-20">
        
        <!-- News Monitor Grid (12 Screens, 4x3 on large desktops) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-3 bg-gray-50 dark:bg-black/20 p-4 rounded-3xl border border-gray-200 dark:border-white/5 shadow-inner">
            @for ($i = 0; $i < 12; $i++)
                @php
                    $urlConfig = $this->youtube_urls[$i] ?? null;
                    // Handle both array from repeater and direct string if manually formatted
                    $url = is_array($urlConfig) ? ($urlConfig['url'] ?? null) : $urlConfig;
                    $id = $this->getYoutubeId($url);
                @endphp

                <div class="relative aspect-video rounded-xl overflow-hidden bg-gray-200 dark:bg-gray-900 border border-gray-300 dark:border-white/5 shadow-xl group transition-all hover:scale-[1.03] hover:z-10 hover:shadow-2xl hover:shadow-primary-500/10">
                    @if ($id)
                        <iframe 
                            src="https://www.youtube.com/embed/{{ $id }}?autoplay=0&mute=1&controls=1&modestbranding=1&rel=0&showinfo=0" 
                            class="absolute inset-0 w-full h-full opacity-90 group-hover:opacity-100 transition-opacity" 
                            frameborder="0" 
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                            allowfullscreen>
                        </iframe>
                        <div class="absolute top-3 left-3 flex items-center gap-1.5 px-3 py-1 bg-red-600/90 backdrop-blur rounded-full text-[10px] font-black text-white uppercase tracking-tighter shadow-lg pointer-events-none">
                            <span class="w-1.5 h-1.5 bg-white rounded-full animate-pulse shadow-[0_0_8px_white]"></span>
                            CAM {{ sprintf('%02d', $i + 1) }}
                        </div>
                    @else
                        <div class="flex items-center justify-center w-full h-full text-gray-400 dark:text-gray-600 text-[10px] uppercase font-bold tracking-widest bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-900 dark:to-black">
                            <div class="text-center group-hover:text-primary-500 transition-colors opacity-40 group-hover:opacity-100">
                                <svg class="w-8 h-8 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.414a4 4 0 00-5.656-5.656l-6.415 6.414a6 6 0 108.486 8.486L20.5 13" />
                                </svg>
                                No Feed Signal
                            </div>
                        </div>
                    @endif
                    
                    <!-- Hover Control Layer -->
                    <div class="absolute inset-0 bg-primary-600/10 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none border-2 border-primary-500/50 rounded-xl"></div>
                </div>
            @endfor
        </div>

        <!-- 6-Line Compact RSS Scrolling Ticker (Desktop Only) -->
        <div class="hidden lg:block fixed bottom-6 right-8 z-[100]">
            <div class="bg-white/80 dark:bg-gray-950/90 backdrop-blur-2xl border border-gray-200 dark:border-white/10 rounded-2xl shadow-[0_20px_50px_rgba(0,0,0,0.3)] overflow-hidden w-80 ring-1 ring-black/5">
                <div class="px-5 py-3 border-b border-gray-200 dark:border-white/5 bg-gray-50/50 dark:bg-white/5 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 rounded-full bg-primary-500 animate-pulse"></div>
                        <span class="text-[11px] font-black uppercase tracking-[0.2em] text-gray-900 dark:text-gray-100">Live News Ticker</span>
                    </div>
                </div>
                
                <div class="h-64 overflow-hidden relative">
                    <div class="rss-scroller space-y-4 p-5">
                        @php $headlines = $this->getRssHeadlines(); @endphp
                        @forelse ($headlines as $h)
                            <a href="{{ $h['link'] }}" target="_blank" class="block group border-l-2 border-transparent hover:border-primary-500 pl-3 transition-all">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-[9px] font-black text-primary-500 uppercase tracking-widest">{{ $h['source'] }}</span>
                                    <span class="w-1 h-1 rounded-full bg-gray-300 dark:bg-gray-700"></span>
                                    <span class="text-[8px] font-bold text-gray-400 dark:text-gray-500 uppercase">Just Now</span>
                                </div>
                                <p class="text-[12px] leading-relaxed text-gray-700 dark:text-gray-300 font-semibold group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors line-clamp-2">
                                    {{ $h['title'] }}
                                </p>
                            </a>
                        @empty
                            <div class="flex flex-col items-center justify-center h-full text-center py-12 px-6">
                                <svg class="w-8 h-8 text-gray-300 dark:text-gray-700 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.114 5.636a9 9 0 010 12.728M16.463 8.288a5.25 5.25 0 010 7.424M6.75 8.25l4.72-4.72a.75.75 0 011.28.53v15.88a.75.75 0 01-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.01 9.01 0 012.25 12c0-.83.112-1.633.322-2.396C2.806 8.756 3.63 8.25 4.51 8.25H6.75z" />
                                </svg>
                                <span class="text-[10px] font-bold text-gray-500 uppercase">Waiting for feeds...</span>
                            </div>
                        @endforelse
                        
                        <!-- Duplicate for Seamless Scroll if enough items -->
                        @if(count($headlines) > 5)
                            @foreach(array_slice($headlines, 0, 5) as $h)
                                <div class="opacity-20 pointer-events-none">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-[9px] font-black text-primary-500 uppercase tracking-widest">{{ $h['source'] }}</span>
                                    </div>
                                    <p class="text-[12px] leading-relaxed text-gray-700 dark:text-gray-300 font-semibold line-clamp-2">
                                        {{ $h['title'] }}
                                    </p>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <style>
            .rss-scroller {
                animation: rss-scroll 60s linear infinite;
            }
            .rss-scroller:hover {
                animation-play-state: paused;
            }
            @keyframes rss-scroll {
                0% { transform: translateY(0); }
                100% { transform: translateY(-50%); }
            }
            /* Hide scrolling for mobile as requested */
            @media (max-width: 1024px) {
                .monitor-container { padding-bottom: 2rem; }
            }
        </style>

    </div>
</x-filament-panels::page>
