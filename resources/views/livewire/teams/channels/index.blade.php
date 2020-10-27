<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Channels') }}
    </h2>
</x-slot>

<div class="p-6 sm:px-20 bg-white border-b border-gray-200">
    <div>
        @if (session()->has('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif
    </div>

    <x-jet-button wire:click="addChannel">
        Add new channel
    </x-jet-button>
</div>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div>
                {{$channels}}
            </div>
        </div>
    </div>
</div>
