<x-filament-panels::page>
    <form wire:submit="save" class="space-y-6">
        <x-filament::section>
            <x-slot name="heading">Connection</x-slot>
            <div class="grid gap-4">
                <label class="grid gap-2"><span>BruteBank API URL</span><input class="fi-input block w-full rounded-lg border-gray-300" wire:model="apiUrl" type="url" required></label>
                <label class="grid gap-2"><span>Public key</span><input class="fi-input block w-full rounded-lg border-gray-300" wire:model="publicKey" autocomplete="off"></label>
                <label class="grid gap-2"><span>Secret key</span><input class="fi-input block w-full rounded-lg border-gray-300" wire:model="secretKey" type="password" autocomplete="new-password" placeholder="Leave blank to keep the saved key"></label>
                <label class="flex items-center gap-2"><input type="checkbox" wire:model="enabled"> Enable BruteBank firewall</label>
                <label class="flex items-center gap-2"><input type="checkbox" wire:model="twoFactorEnabled"> Require email two-factor verification</label>
            </div>
        </x-filament::section>
        <x-filament::button type="submit">Save settings</x-filament::button>
    </form>
</x-filament-panels::page>
