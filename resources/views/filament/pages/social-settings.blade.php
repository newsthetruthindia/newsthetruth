<x-filament-panels::page>
    <div class="space-y-6">
        <div class="fi-section fi-wi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 p-6">
            <h2 class="text-xl font-bold mb-2">Meta API Requirements</h2>
            <p class="text-gray-600 dark:text-gray-400 mb-6 max-w-2xl text-sm">
                To auto-publish articles, you must create an App in Meta Developers, link your Facebook Page and Instagram Professional Account, and generate a long-lived Access Token. Provide those credentials below.
            </p>

            <form wire:submit="save" class="space-y-4">
                {{ $this->form }}

                <div>
                    <label class="font-medium text-sm text-gray-700 dark:text-gray-300">Facebook Page ID</label>
                    <input type="text" wire:model="fb_page_id" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white" placeholder="e.g. 10423984572..." />
                </div>

                <div>
                    <label class="font-medium text-sm text-gray-700 dark:text-gray-300">Instagram Account ID (Linked to Page)</label>
                    <input type="text" wire:model="ig_account_id" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white" placeholder="e.g. 178414000..." />
                </div>

                <div>
                    <label class="font-medium text-sm text-gray-700 dark:text-gray-300">Long-Lived Access Token</label>
                    <textarea wire:model="fb_access_token" rows="3" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white" placeholder="EAAGm..."></textarea>
                </div>

                <div class="pt-4 border-t border-gray-100 dark:border-gray-800">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model="automatic_social_publish" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-red-300 dark:peer-focus:ring-red-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-red-600"></div>
                        <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300">Enable Automatic Social Publishing</span>
                    </label>
                    <p class="text-xs text-gray-500 mt-1">When enabled, newly published articles will automatically be posted to Facebook and Instagram.</p>
                </div>
            </form>
        </div>
    </div>
</x-filament-panels::page>
