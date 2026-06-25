<section class="fi-wi-widget fi-wi-quick-create" style="display: flex; height: 100%; width: 100%;">
    <a href="{{ \App\Filament\Resources\PostResource::getUrl('create') }}"
       style="background: #e63946; color: #ffffff; display: flex; align-items: center; justify-content: center; gap: 12px; width: 100%; height: 100%; border-radius: 12px; font-size: 18px; font-weight: 800; letter-spacing: 1px; text-decoration: none; box-shadow: 0 4px 15px rgba(230, 57, 70, 0.35); border: 1px solid rgba(255, 255, 255, 0.15); transition: background 0.2s;"
       onmouseover="this.style.background='#d62839';"
       onmouseout="this.style.background='#e63946';">
        <svg style="height: 24px; width: 24px; color: #ffffff;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
        </svg>
        <span>CREATE ARTICLE</span>
    </a>
</section>
