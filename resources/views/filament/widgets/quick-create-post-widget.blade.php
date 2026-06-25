<x-filament-widgets::widget class="fi-wi-quick-create">
    <a href="{{ \App\Filament\Resources\PostResource::getUrl('create') }}"
       class="group relative flex h-[104px] w-full items-center justify-between overflow-hidden rounded-xl bg-gradient-to-r from-[#e63946] via-[#d62839] to-[#b81d24] p-6 shadow-xl transition-all duration-300 hover:scale-[1.01] hover:shadow-2xl hover:shadow-[#e63946]/30 active:scale-[0.99] ring-1 ring-white/20">
        
        <!-- Subtle decorative background glow -->
        <div class="absolute -right-6 -top-6 h-32 w-32 rounded-full bg-white/10 blur-2xl transition-transform duration-500 group-hover:scale-150"></div>
        
        <div class="relative z-10 flex items-center gap-x-4">
            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-white/15 text-white backdrop-blur-md shadow-inner border border-white/25 group-hover:bg-white/25 transition-colors duration-300">
                <svg class="h-8 w-8 stroke-[2.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-black tracking-wide text-white drop-shadow-md flex items-center gap-2">
                    CREATE NEW POST
                </h2>
                <p class="text-xs font-semibold text-white/80 tracking-wider uppercase mt-0.5">
                    ⚡ Launch Story Editor
                </p>
            </div>
        </div>

        <div class="relative z-10 flex items-center gap-2 rounded-xl bg-white px-5 py-3 text-sm font-black tracking-wider text-[#d62839] shadow-lg transition-transform duration-300 group-hover:translate-x-1">
            <span>WRITE STORY</span>
            <svg class="h-4 w-4 stroke-[3]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
            </svg>
        </div>
    </a>
</x-filament-widgets::widget>
