<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Channels') }}
    </h2>
</x-slot>


<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div>

            <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
    <div>
        @if (session()->has('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif
    </div>

    <x-jet-button wire:click="create">Create channel</x-jet-button>
</div>

<div class="p-6 sm:px-20 bg-white border-b border-gray-200">
    <table class="table-auto">
        <thead>
        <tr>
            <th></th>
            <th>Name</th>
            <th>Status</th>
            <th></th>
        </tr>
        </thead>
        <tbody wire:poll.5s>
        @foreach($channels as $channel)
            <tr>
                <td class="border px-4 py-2">{{$channel->name}}</td>
                <td class="border px-4 py-2">
                    @if ($channel->enabled)
                        Active
                    @else
                        Disable
                    @endif
                </td>
                <td>
                    <x-jet-button wire:click="show({{$channel->id}})">Show</x-jet-button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
            </div>
        </div>
    </div>
</div>
