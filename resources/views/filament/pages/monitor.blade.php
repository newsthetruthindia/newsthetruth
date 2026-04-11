<x-filament-panels::page>
    <div wire:init="loadData" class="monitor-tv-root bg-black min-h-screen text-white overflow-hidden">
        
        <!-- TV-Optimized Stealth Header -->
        <div class="h-[6vh] flex items-center justify-between px-6 bg-neutral-900/50 border-b border-white/5">
            <h2 class="text-[1.5vh] font-black uppercase italic tracking-widest flex items-center gap-4">
                <span class="w-1 h-4 bg-primary-600"></span>
                NTT Command <span class="opacity-40 italic">[TV-MATRIX]</span>
            </h2>
            <div class="flex items-center gap-4">
                <button onclick="window.location.href='/admin'" class="px-5 py-1 bg-neutral-800 hover:bg-neutral-700 text-neutral-400 text-[1.2vh] font-bold uppercase rounded transition-colors">
                    Dashboard
                </button>
                <button wire:click="mountAction('configure')" class="px-5 py-1 bg-primary-700 hover:bg-primary-600 text-white text-[1.2vh] font-bold uppercase rounded shadow-lg transition-colors">
                    Source Config
                </button>
            </div>
        </div>

        <!-- High-Performance Grid (Fixed Viewport Height) -->
        <div class="monitor-grid h-[74vh] p-2 bg-black overflow-hidden">
            @for ($i = 0; $i < 12; $i++)
                @php
                    $urlConfig = $this->youtube_urls[$i] ?? null;
                    $url = is_array($urlConfig) ? ($urlConfig['url'] ?? null) : $urlConfig;
                    $id = $this->getYoutubeId($url);
                @endphp

                <div class="video-cell relative bg-neutral-900 border border-white/5 group shadow-inner">
                    <!-- Aspect Ratio Wrapper (For Old TV Browsers) -->
                    <div class="aspect-ratio-fallback" style="padding-top: 56.25%; position: relative;">
                        @if ($id)
                            <iframe 
                                src="https://www.youtube.com/embed/{{ $id }}?autoplay=1&mute=1&controls=1&modestbranding=1&rel=0&showinfo=0&iv_load_policy=3&enablejsapi=1" 
                                class="absolute inset-0 w-full h-full" 
                                frameborder="0" 
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                allowfullscreen>
                            </iframe>
                            <!-- HUD for TV -->
                            <div class="absolute top-2 left-2 flex items-center gap-2 px-2 py-0.5 bg-black/80 rounded opacity-0 group-hover:opacity-100 transition-opacity">
                                <span class="w-1.5 h-1.5 bg-red-600 rounded-full animate-pulse"></span>
                                <span class="text-[0.8vh] font-mono font-bold tracking-widest uppercase">CAM_{{ sprintf('%02d', $i + 1) }}</span>
                            </div>
                        @else
                            <div class="absolute inset-0 flex items-center justify-center opacity-20">
                                 <span class="text-[1vh] font-mono tracking-[0.5em] uppercase">No Signal</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endfor
        </div>

        <!-- 6-Line RSS Matrix for TV -->
        <div class="h-[20vh] space-y-[0.2vh] px-1 bg-black">
            @php $feedsHeadlines = $this->getRssHeadlines(); @endphp
            @for ($f = 0; $f < 6; $f++)
                @php $headlines = $feedsHeadlines[$f] ?? []; @endphp
                <div class="h-[3.1vh] bg-[#dadada] flex items-center overflow-hidden rounded-sm">
                    <div class="flex-shrink-0 h-full bg-[#222] flex items-center px-4 min-w-[120px] border-r border-black/20">
                        <span class="text-[0.9vh] font-black text-white uppercase tracking-tighter">FEED 0{{ $f + 1 }}</span>
                    </div>
                    <div class="flex-grow overflow-hidden whitespace-nowrap bg-white/40 h-full flex items-center">
                        <div class="inline-block animate-tv-rss-{{ $f }} flex items-center">
                            @if(count($headlines) > 0)
                                @foreach (array_merge($headlines, $headlines) as $h)
                                     <span class="text-[1.2vh] font-bold text-neutral-800 mx-10 flex items-center gap-3">
                                        <span class="w-1 h-3 bg-neutral-400 rounded-full"></span>
                                        {{ $h['title'] }}
                                     </span>
                                @endforeach
                            @else
                                <span class="text-[0.8vh] font-mono text-neutral-500 italic px-4 uppercase tracking-[0.3em]">Syncing Feed 0{{ $f + 1 }}...</span>
                            @endif
                        </div>
                    </div>
                </div>

                <style>
                    @keyframes tv-rss-{{ $f }} {
                        0% { transform: translateX(0); }
                        100% { transform: translateX(-50%); }
                    }
                    .animate-tv-rss-{{ $f }} {
                        animation: tv-rss-{{ $f }} {{ 50 + ($f * 6) }}s linear infinite;
                    }
                </style>
            @endfor
        </div>

        <style>
            /* 🖥️ TV FULL-SCREEN OVERRIDES */
            .fi-main { max-width: none !important; padding: 0 !important; margin: 0 !important; }
            .fi-main-ctn { max-width: none !important; margin: 0 !important; padding: 0 !important; }
            .fi-sidebar, .fi-topbar, header.fi-header, .fi-breadcrumbs { display: none !important; }
            section.fi-section { padding: 0 !important; margin: 0 !important; border: 0 !important; box-shadow: none !important; background: transparent !important; }
            
            /* Hidden scrollbars for clean TV feed */
            html, body { overflow: hidden !important; background: black !important; }
            ::-webkit-scrollbar { width: 0px; height: 0px; }

            /* Grid Logic */
            .monitor-grid {
                display: grid !important;
                grid-template-columns: repeat(4, 1fr) !important;
                grid-template-rows: repeat(3, 1fr) !important;
                gap: 2px !important;
            }
            .video-cell {
                height: 100% !important;
                width: 100% !important;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .aspect-ratio-fallback {
                width: 100% !important;
            }
        </style>

    </div>
</x-filament-panels::page>
