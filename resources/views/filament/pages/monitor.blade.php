<x-filament-panels::page>
    <div wire:init="loadData" class="ntt-monitor-system" style="background: #000; min-height: 800px; padding: 10px;">
        
        <!-- UI OVERRIDE CSS (Aggressive for TVs) -->
        <style>
            /* Hide Sidebar & UI Elements (Aggressive) */
            .fi-sidebar, .fi-topbar, .fi-header, .fi-breadcrumbs { 
                display: none !important; 
                width: 0 !important; 
                height: 0 !important; 
                visibility: hidden !important; 
            }
            .fi-main { 
                padding: 0 !important; 
                margin: 0 !important; 
                max-width: none !important; 
                background: #000 !important;
            }
            .fi-main-ctn { 
                margin: 0 !important; 
                padding: 0 !important; 
            }
            
            /* Root Container */
            .ntt-monitor-system {
                color: white;
                font-family: monospace;
            }

            /* 4x3 Grid (3 columns across) */
            .video-grid {
                display: grid !important;
                grid-template-columns: repeat(4, 1fr) !important;
                gap: 5px !important;
                margin-bottom: 20px;
            }

            /* Responsive Cell with 16:9 Fallback */
            .video-box {
                position: relative;
                width: 100%;
                background: #111;
                border: 1px solid #333;
                padding-top: 56.25%; /* 16:9 Ratio */
            }

            .video-box iframe {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
            }

            /* RSS Rows */
            .rss-row {
                background: #dadada;
                margin-bottom: 2px;
                height: 35px;
                display: flex;
                align-items: center;
                overflow: hidden;
                border-radius: 3px;
            }

            .rss-label {
                background: #222;
                color: white;
                padding: 0 15px;
                height: 100%;
                display: flex;
                align-items: center;
                font-size: 11px;
                font-weight: bold;
                min-width: 100px;
                border-right: 2px solid rgba(0,0,0,0.1);
            }

            /* Marquee */
            .rss-marquee {
                display: inline-block;
                white-space: nowrap;
                padding-left: 50px;
                animation: marquee 60s linear infinite;
            }

            @keyframes marquee {
                from { transform: translateX(0); }
                to { transform: translateX(-50%); }
            }

            /* Stealth Buttons */
            .stealth-bar {
                display: flex;
                justify-content: flex-end;
                gap: 10px;
                padding: 10px;
                opacity: 0.1;
                transition: 0.3s;
            }
            .stealth-bar:hover { opacity: 1; }
        </style>

        <!-- Configuration Bar -->
        <div class="stealth-bar">
            <button onclick="window.location.href='/admin'" style="background:#222; color:#888; border:1px solid #444; padding:5px 15px; font-size:10px; cursor:pointer;">
                DASHBOARD
            </button>
            <button wire:click="mountAction('configure')" style="background:#e63946; color:#fff; border:none; padding:5px 15px; font-size:10px; cursor:pointer;">
                SOURCE CONFIG
            </button>
        </div>

        <!-- 4x3 Grid -->
        <div class="video-grid">
            @for ($i = 0; $i < 12; $i++)
                @php
                    $urlConfig = $this->youtube_urls[$i] ?? null;
                    $url = is_array($urlConfig) ? ($urlConfig['url'] ?? null) : $urlConfig;
                    $id = $this->getYoutubeId($url);
                @endphp
                <div class="video-box">
                    @if ($id)
                        <iframe 
                            src="https://www.youtube.com/embed/{{ $id }}?autoplay=1&mute=1&controls=1&modestbranding=1&rel=0&showinfo=0&iv_load_policy=3&enablejsapi=1" 
                            frameborder="0" 
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                            allowfullscreen>
                        </iframe>
                    @else
                        <div style="position:absolute; inset:0; display:flex; align-items:center; justify-content:center; color:#333; font-size:10px; text-transform:uppercase;">
                            No Signal
                        </div>
                    @endif
                </div>
            @endfor
        </div>

        <!-- 6 RSS Lines -->
        <div class="rss-strip-matrix">
            @php $feedsHeadlines = $this->getRssHeadlines(); @endphp
            @for ($f = 0; $f < 6; $f++)
                @php $headlines = $feedsHeadlines[$f] ?? []; @endphp
                <div class="rss-row">
                    <div class="rss-label">RSS FEED {{ $f + 1 }}</div>
                    <div style="flex-grow:1; overflow:hidden;">
                        <div class="rss-marquee" style="animation-duration: {{ 60 + ($f * 10) }}s;">
                            @if(count($headlines) > 0)
                                @foreach (array_merge($headlines, $headlines) as $h)
                                     <span style="color:#000; font-weight:bold; font-size:13px; margin-right:60px;">
                                        ● {{ $h['title'] }}
                                     </span>
                                @endforeach
                            @else
                                <span style="color:#666; font-size:11px; font-style:italic;">Initializing feed synchronization for port {{ $f + 1 }} ...</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endfor
        </div>

    </div>
</x-filament-panels::page>
