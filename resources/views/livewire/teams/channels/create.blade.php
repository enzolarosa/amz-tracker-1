<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Create Channels') }}
    </h2>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div>
                <form wire:submit.prevent="save">
                    <x-jet-input type="text" wire:model="channel.name" placeholder="Channel Name"/>
                    @error('channel.name') <span class="error">{{ $message }}</span> @enderror

                    <label>
                        <textarea wire:model="channel.configuration"></textarea>
                    </label>
                    @error('channel.configuration') <span class="error">{{ $message }}</span> @enderror

                    <x-jet-button type="submit">
                        Save
                    </x-jet-button>
                </form>
            </div>
        </div>
    </div>
</div>
