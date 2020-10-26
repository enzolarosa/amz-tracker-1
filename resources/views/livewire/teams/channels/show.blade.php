<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __(':name\'s detail',['name'=>$channel->name]) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div>
                    {{-- A good traveler has no fixed plans and is not intent upon arriving. --}}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
