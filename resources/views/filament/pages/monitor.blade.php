<x-filament-panels::page>
    <div wire:init="loadData" class="monitor-system-root fixed inset-0 z-[100] bg-black overflow-hidden flex flex-col">
        
        <!-- Stealth Control Overlay -->
        <div class="absolute top-6 right-6 z-[200] opacity-10 hover:opacity-100 transition-opacity flex items-center gap-3">
            <button onclick="window.location.href='/admin'" class="px-4 py-2 bg-neutral-900 text-neutral-400 text-[10px] font-mono uppercase tracking-widest hover:text-white border border-neutral-800 transition-colors">
                Back to Dashboard
            </button>
            <x-filament-actions::modals />
            <x-filament-actions::action :action="$this->getAction('configure')" color="gray" />
        </div>

        <!-- 4x3 High-Density Matrix -->
        <div class="flex-grow grid grid-cols-4 grid-rows-3 gap-[1px] bg-neutral-950 p-[1px]">
            @for ($i = 0; $i < 12; $i++)
                @php
                    $urlConfig = $this->youtube_urls[$i] ?? null;
                    $url = is_array($urlConfig) ? ($urlConfig['url'] ?? null) : $urlConfig;
                    $id = $this->getYoutubeId($url);
                @endphp

                <div class="relative bg-black group overflow-hidden">
                    @if ($id)
                        <iframe 
                            src="https://www.youtube.com/embed/{{ $id }}?autoplay=1&mute=1&controls=0&modestbranding=1&rel=0&showinfo=0&iv_load_policy=3&enablejsapi=1" 
                            class="absolute inset-0 w-full h-full pointer-events-none grayscale-[0.2] hover:grayscale-0 transition-all duration-700" 
                            frameborder="0" 
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                            allowfullscreen>
                        </iframe>
                        
                        <!-- CCTV HUD -->
                        <div class="absolute inset-0 pointer-events-none p-3 flex flex-col justify-between z-10">
                            <div class="flex items-start justify-between">
                                <div class="flex flex-col">
                                    <span class="text-[11px] font-mono text-green-400 bg-black/60 px-2 py-0.5 tracking-tighter">FEED: CAM_{{ sprintf('%02d', $i + 1) }}</span>
                                    <span class="text-[9px] font-mono text-neutral-500 bg-black/60 px-2 mt-1 uppercase">SIGNAL: 1080P/60</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="flex items-center gap-1.5 bg-black/60 px-2 py-0.5 rounded-sm">
                                        <span class="w-2 h-2 rounded-full bg-red-600 animate-pulse"></span>
                                        <span class="text-[10px] font-mono text-red-500 font-black">REC</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-end justify-between">
                                <div class="bg-black/60 px-2 py-0.5 flex flex-col">
                                    <span class="text-[10px] font-mono text-neutral-400">{{ date('H:i:s') }}</span>
                                    <span class="text-[8px] font-mono text-neutral-600">{{ date('d/m/Y') }}</span>
                                </div>
                                <div class="w-12 h-12 border-r border-b border-neutral-500 opacity-20"></div>
                            </div>
                        </div>

                        <!-- Scanline Effect -->
                        <div class="absolute inset-0 bg-[linear-gradient(rgba(18,16,16,0)_50%,rgba(0,0,0,0.25)_50%),linear-gradient(90deg,rgba(255,0,0,0.06),rgba(0,255,0,0.02),rgba(0,0,255,0.06))] bg-[length:100%_2px,3px_100%] pointer-events-none opacity-20"></div>
                    @else
                        <!-- No Signal Look -->
                        <div class="absolute inset-0 flex flex-col items-center justify-center bg-neutral-950">
                            <div class="w-full h-px bg-neutral-900 absolute top-1/2 -translate-y-1/2"></div>
                            <div class="w-px h-full bg-neutral-900 absolute left-1/2 -translate-x-1/2"></div>
                            <span class="relative text-[10px] font-mono text-neutral-800 uppercase tracking-[0.5em] font-bold">Signal Lost</span>
                        </div>
                    @endif
                </div>
            @endfor
        </div>

        <!-- Bottom News Strip (Ticker Mode) -->
        <div class="h-10 bg-neutral-950 border-t border-neutral-900 flex items-center overflow-hidden">
            <div class="flex-shrink-0 h-full bg-red-900/80 flex items-center px-6 border-r border-neutral-800">
                 <span class="text-[11px] font-mono text-white font-black uppercase tracking-widest animate-pulse">Global Alert</span>
            </div>
            <div class="flex-grow overflow-hidden whitespace-nowrap bg-black h-full flex items-center">
                <div class="inline-block animate-cctv-marquee">
                    @php $allHeadlines = $this->getRssHeadlines(); @endphp
                    @foreach ($allHeadlines as $h)
                         <span class="text-[12px] font-mono text-neutral-300 mx-10 flex items-center gap-4">
                            <span class="text-neutral-600 font-bold">[{{ $h['source'] }}]</span> 
                            <span>{{ $h['title'] }}</span>
                            <span class="text-neutral-800 text-[10px] tracking-tighter">///</span>
                         </span>
                    @endforeach
                    @foreach ($allHeadlines as $h)
                         <span class="text-[12px] font-mono text-neutral-300 mx-10 flex items-center gap-4">
                            <span class="text-neutral-600 font-bold">[{{ $h['source'] }}]</span> 
                            <span>{{ $h['title'] }}</span>
                            <span class="text-neutral-800 text-[10px] tracking-tighter">///</span>
                         </span>
                    @endforeach
                </div>
            </div>
        </div>

        <style>
            @keyframes cctv-marquee {
                0% { transform: translate(0, 0); }
                100% { transform: translate(-50%, 0); }
            }
            .animate-cctv-marquee {
                display: flex;
                animation: cctv-marquee 120s linear infinite;
            }
            .animate-cctv-marquee:hover {
                animation-play-state: paused;
            }

            /* Radical UI Override for Fullscreen Monitor */
            .fi-main { padding: 0 !important; max-width: none !important; }
            .fi-sidebar { transition: transform 0.3s ease; }
            .fi-main-ctn { margin: 0 !important; padding: 0 !important; }
            .fi-topbar { display: none !important; }
            .fi-breadcrumbs { display: none !important; }
            header.fi-header { display: none !important; }
            .fi-section { border: 0 !important; margin: 0 !important; padding: 0 !important; background: transparent !important; box-shadow: none !important; }
            
            /* Custom Scrollbar for stealth */
            ::-webkit-scrollbar { width: 0px; }
        </style>

    </div>
</x-filament-panels::page>
