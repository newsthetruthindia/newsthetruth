<x-filament-panels::page>
    <div wire:init="loadData" class="monitor-pure-matrix fixed inset-0 z-[100] bg-black overflow-hidden flex flex-col">
        
        <!-- Stealth Control Overlay (Hidden by default, shows on hover) -->
        <div class="absolute top-6 right-6 z-[200] opacity-0 hover:opacity-100 transition-opacity flex items-center gap-3">
            <button onclick="window.location.href='/admin'" class="px-4 py-2 bg-neutral-900 border border-neutral-700 text-neutral-400 text-[10px] font-mono uppercase tracking-widest hover:text-white transition-colors">
                Control Panel
            </button>
            <button wire:click="mountAction('configure')" class="px-4 py-2 bg-neutral-900 border border-neutral-700 text-neutral-400 text-[10px] font-mono uppercase tracking-widest hover:text-white transition-colors">
                Source Config
            </button>
        </div>

        <!-- 3x3 Pure Video Matrix -->
        <div class="flex-grow grid grid-cols-3 gap-0 bg-black h-full">
            @for ($i = 0; $i < 9; $i++)
                @php
                    $urlConfig = $this->youtube_urls[$i] ?? null;
                    $url = is_array($urlConfig) ? ($urlConfig['url'] ?? null) : $urlConfig;
                    $id = $this->getYoutubeId($url);
                @endphp

                <div class="relative bg-black border-[0.5px] border-neutral-900 overflow-hidden">
                    @if ($id)
                        <iframe 
                            src="https://www.youtube.com/embed/{{ $id }}?autoplay=1&mute=1&controls=0&modestbranding=1&rel=0&showinfo=0&iv_load_policy=3&enablejsapi=1" 
                            class="absolute inset-0 w-full h-full pointer-events-none" 
                            frameborder="0" 
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                            allowfullscreen>
                        </iframe>
                    @else
                        <!-- Blank Signal -->
                        <div class="absolute inset-0 flex items-center justify-center opacity-20">
                             <div class="text-[10px] font-mono text-neutral-700 uppercase tracking-widest">No Signal</div>
                        </div>
                    @endif
                </div>
            @endfor
        </div>

        <style>
            /* Force Ultra-Fullscreen */
            .fi-main { padding: 0 !important; max-width: none !important; }
            .fi-main-ctn { margin: 0 !important; padding: 0 !important; }
            .fi-topbar, .fi-sidebar, .fi-header, .fi-breadcrumbs { display: none !important; }
            section.fi-section { padding: 0 !important; margin: 0 !important; border: 0 !important; box-shadow: none !important; background: transparent !important; }
            
            /* Hide scrollbars */
            html, body { overflow: hidden !important; }
            ::-webkit-scrollbar { width: 0px; }
        </style>

    </div>
</x-filament-panels::page>
