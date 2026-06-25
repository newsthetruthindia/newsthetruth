<x-filament-widgets::widget class="fi-wi-quick-create">
    <div class="flex items-center justify-between gap-x-4 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 h-[104px]">
        <div class="flex items-center gap-x-4">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-[#e63946]/10 text-[#e63946] border border-[#e63946]/20">
                <svg class="h-6 w-6 text-[#e63946]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                </svg>
            </div>
            <div>
                <h2 class="text-base font-bold tracking-tight text-gray-950 dark:text-white leading-tight">
                    Publish New Story
                </h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    Write & publish breaking news
                </p>
            </div>
        </div>

        <a href="{{ \App\Filament\Resources\PostResource::getUrl('create') }}"
           class="inline-flex shrink-0 items-center justify-center gap-2 rounded-lg bg-[#e63946] px-5 py-3 text-sm font-extrabold tracking-wide text-white shadow-lg shadow-[#e63946]/25 transition hover:bg-[#d62839] hover:scale-[1.02] active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-[#e63946]/50">
            <svg class="h-5 w-5 stroke-[3]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            <span>CREATE POST</span>
        </a>
    </div>
</x-filament-widgets::widget>
