<x-filament-panels::page>
    <div class="space-y-6">

        {{-- Token Status Banner --}}
        <div class="fi-section rounded-xl p-4 {{ $token_status === 'valid' ? 'bg-emerald-500/10 ring-1 ring-emerald-500/20' : ($token_status === 'invalid' ? 'bg-red-500/10 ring-1 ring-red-500/20' : 'bg-yellow-500/10 ring-1 ring-yellow-500/20') }}">
            <div class="flex items-center gap-3">
                @if($token_status === 'valid')
                    <div class="flex-shrink-0 w-3 h-3 rounded-full bg-emerald-500 animate-pulse"></div>
                    <div>
                        <p class="font-semibold text-emerald-400">✅ Connected — {{ $token_page_name }}</p>
                        <p class="text-xs text-emerald-400/70">Your Facebook & Instagram API token is active and working.</p>
                    </div>
                @elseif($token_status === 'invalid')
                    <div class="flex-shrink-0 w-3 h-3 rounded-full bg-red-500 animate-pulse"></div>
                    <div>
                        <p class="font-semibold text-red-400">❌ Token Invalid or Expired</p>
                        <p class="text-xs text-red-400/70">{{ $token_page_name }}</p>
                    </div>
                @else
                    <div class="flex-shrink-0 w-3 h-3 rounded-full bg-yellow-500"></div>
                    <div>
                        <p class="font-semibold text-yellow-400">⚠️ Token Status Unknown</p>
                        <p class="text-xs text-yellow-400/70">Save your settings and click "Test Connection" to verify.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Page & Account IDs --}}
        <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 p-6">
            <h2 class="text-lg font-bold mb-1">📡 Account Configuration</h2>
            <p class="text-gray-500 dark:text-gray-400 text-sm mb-5">Your Facebook Page ID and Instagram Business Account ID.</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-1">Facebook Page ID</label>
                    <input type="text" wire:model="fb_page_id" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white text-sm" placeholder="e.g. 115000625006655" />
                </div>
                <div>
                    <label class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-1">Instagram Account ID</label>
                    <input type="text" wire:model="ig_account_id" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white text-sm" placeholder="e.g. 17841460658517747" />
                </div>
            </div>
        </div>

        {{-- App Credentials --}}
        <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 p-6">
            <h2 class="text-lg font-bold mb-1">🔑 Facebook App Credentials</h2>
            <p class="text-gray-500 dark:text-gray-400 text-sm mb-5">Required for automatic token exchange. Find these at <a href="https://developers.facebook.com/apps/" target="_blank" class="text-primary-500 underline">developers.facebook.com/apps</a>.</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-1">App ID</label>
                    <input type="text" wire:model="fb_app_id" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white text-sm" placeholder="e.g. 1234567890" />
                </div>
                <div>
                    <label class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-1">App Secret</label>
                    <input type="password" wire:model="fb_app_secret" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white text-sm" placeholder="••••••••" />
                </div>
            </div>
        </div>

        {{-- Token Exchange --}}
        <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 p-6">
            <h2 class="text-lg font-bold mb-1">🔄 Generate Never-Expiring Token</h2>
            <p class="text-gray-500 dark:text-gray-400 text-sm mb-2">This tool converts a short-lived token into a permanent page token. Follow these steps:</p>

            <div class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-4 mb-5 text-sm space-y-2">
                <p class="font-medium text-gray-700 dark:text-gray-300">📋 Instructions:</p>
                <ol class="list-decimal list-inside text-gray-600 dark:text-gray-400 space-y-1.5 ml-2">
                    <li>Go to <a href="https://developers.facebook.com/tools/explorer/" target="_blank" class="text-primary-500 underline">Graph API Explorer</a></li>
                    <li>Select your NTT App from the dropdown</li>
                    <li>Click <strong>"Add a Permission"</strong> and add: <code class="bg-gray-200 dark:bg-gray-700 px-1 rounded text-xs">pages_manage_posts</code>, <code class="bg-gray-200 dark:bg-gray-700 px-1 rounded text-xs">pages_read_engagement</code>, <code class="bg-gray-200 dark:bg-gray-700 px-1 rounded text-xs">instagram_basic</code>, <code class="bg-gray-200 dark:bg-gray-700 px-1 rounded text-xs">instagram_content_publish</code></li>
                    <li>Click <strong>"Generate Access Token"</strong> and authorize</li>
                    <li>Copy the token and paste it below</li>
                    <li>Click <strong>"Exchange Token"</strong> — the system will automatically generate a permanent token</li>
                </ol>
            </div>

            <div class="space-y-3">
                <div>
                    <label class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-1">Short-Lived User Token (from Graph API Explorer)</label>
                    <textarea wire:model="short_lived_token" rows="2" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white text-sm font-mono" placeholder="EAAGm0P..."></textarea>
                </div>

                <button wire:click="exchangeToken" wire:loading.attr="disabled" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition-colors disabled:opacity-50">
                    <svg wire:loading wire:target="exchangeToken" class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span wire:loading.remove wire:target="exchangeToken">🔄 Exchange Token</span>
                    <span wire:loading wire:target="exchangeToken">Processing...</span>
                </button>
            </div>
        </div>

        {{-- Current Active Token --}}
        <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 p-6">
            <h2 class="text-lg font-bold mb-1">🎟️ Active Access Token</h2>
            <p class="text-gray-500 dark:text-gray-400 text-sm mb-5">The currently active token. This is auto-populated when you use the exchange tool above, or you can paste a token directly.</p>

            <div>
                <label class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-1">Page Access Token</label>
                <textarea wire:model="fb_access_token" rows="3" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white text-sm font-mono" placeholder="EAAL9Cx..."></textarea>
            </div>
        </div>

        {{-- Auto-Publish Toggle --}}
        <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold mb-1">⚡ Automatic Publishing</h2>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">When enabled, newly published articles will automatically be posted to Facebook and Instagram.</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" wire:model="automatic_social_publish" class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-red-300 dark:peer-focus:ring-red-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-red-600"></div>
                </label>
            </div>
        </div>

    </div>
</x-filament-panels::page>
