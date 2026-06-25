<div class="fi-wi-widget fi-wi-quick-create">
    <a href="{{ \App\Filament\Resources\PostResource::getUrl('create') }}"
       style="background: linear-gradient(135deg, #e63946 0%, #d62839 50%, #b81d24 100%); display: flex; align-items: center; justify-content: space-between; height: 104px; width: 100%; padding: 0 28px; border-radius: 12px; text-decoration: none; box-shadow: 0 10px 25px -5px rgba(230, 57, 70, 0.4); border: 1px solid rgba(255, 255, 255, 0.25); position: relative; overflow: hidden; transition: all 0.2s ease-in-out;"
       onmouseover="this.style.transform='scale(1.01)'; this.style.boxShadow='0 20px 30px -10px rgba(230, 57, 70, 0.6)';"
       onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 10px 25px -5px rgba(230, 57, 70, 0.4)';">
        
        <!-- Decorative subtle light circle -->
        <div style="position: absolute; right: -20px; top: -20px; height: 140px; width: 140px; border-radius: 50%; background: rgba(255, 255, 255, 0.1); pointer-events: none;"></div>

        <!-- Left Content: Icon & Title -->
        <div style="display: flex; align-items: center; gap: 18px; position: relative; z-index: 2;">
            <div style="height: 54px; width: 54px; flex-shrink: 0; border-radius: 14px; background: rgba(255, 255, 255, 0.2); display: flex; align-items: center; justify-content: center; border: 1px solid rgba(255, 255, 255, 0.35); box-shadow: inset 0 2px 4px rgba(255, 255, 255, 0.2);">
                <svg style="height: 30px; width: 30px; color: #ffffff;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
            </div>
            <div>
                <h2 style="font-size: 20px; font-weight: 900; color: #ffffff; margin: 0; letter-spacing: 0.5px; line-height: 1.2; font-family: inherit;">
                    CREATE NEW POST
                </h2>
                <p style="font-size: 12px; font-weight: 700; color: rgba(255, 255, 255, 0.9); margin: 4px 0 0 0; letter-spacing: 1px; text-transform: uppercase;">
                    ⚡ Launch Story Editor
                </p>
            </div>
        </div>

        <!-- Right Content: White Action Button -->
        <div style="position: relative; z-index: 2; display: flex; align-items: center; gap: 8px; background: #ffffff; color: #d62839; padding: 12px 22px; border-radius: 10px; font-size: 13px; font-weight: 900; letter-spacing: 1px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15); text-transform: uppercase;">
            <span>WRITE STORY</span>
            <svg style="height: 16px; width: 16px; color: #d62839;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
            </svg>
        </div>
    </a>
</div>
