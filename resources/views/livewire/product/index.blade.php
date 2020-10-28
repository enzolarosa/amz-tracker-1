<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Products') }}
    </h2>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                <div>
                    @if (session()->has('message'))
                        <div class="alert alert-success">
                            {{ session('message') }}
                        </div>
                    @endif
                </div>

                <x-jet-button wire:click="create">Add product</x-jet-button>

                <label class="md:w-2/3 block text-gray-500 font-bold">
                    <input wire:model="trackerId" value="0" class="mr-2 leading-tight" type="radio">
                    <span class="text-sm">Show all product</span>
                </label>
                @foreach($channels as $channel)
                    <label class="md:w-2/3 block text-gray-500 font-bold">
                        <input wire:model="trackerId" value="{{$channel->id}}" class="mr-2 leading-tight" type="radio">
                        <span class="text-sm">Show `{{$channel->name}}` channel product</span>
                    </label>
                @endforeach
            </div>
            <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                <label>
                    <input wire:model="search" type="text" placeholder="Filter"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </label>

                <label class="md:w-2/3 block text-gray-500 font-bold">
                    <input wire:model="showDisabled" class="mr-2 leading-tight" type="checkbox">
                    <span class="text-sm">Show disabled product</span>
                </label>
            </div>

            <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                <table class="table-auto">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Title</th>
                        <th>Start Price</th>
                        <th>Previous Price</th>
                        <th>Price</th>
                        <th>Min Price</th>
                        <th>Min Price Date</th>
                        <th>Checked at</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody wire:poll.5s>
                    @foreach($trackers as $tracker)
                        <tr>
                            <td class="border px-4 py-2"><img
                                    src="{{\Illuminate\Support\Arr::first($tracker->product->images)}}"
                                    title="{{$tracker->product->title}}"
                                    style="max-width: 80px;max-height: 80px;"
                                    alt="{{$tracker->product->description}}"/></td>
                            <td class="border px-4 py-2">
                                <span style="font-size: 12px;">
                                    Channel: {{$tracker->trackable->name}}
                                </span>
                                <br/>
                                {{$tracker->product->title}}
                            </td>
                            <td class="border px-4 py-2">{{number_format($tracker->product->start_price ,2, ',', '.')}}
                                €
                            </td>
                            <td class="border px-4 py-2">{{number_format($tracker->product->previous_price,2, ',', '.')}}
                                €
                            </td>
                            <td class="border px-2 py-w">{{number_format($tracker->product->current_price,2, ',', '.')}}
                                €
                            </td>
                            <td class="border px-4 py-2">{{number_format($tracker->product->min_price,2, ',', '.')}}€
                            </td>
                            <td class="border px-2 py-2">{{\Carbon\Carbon::parse($tracker->product->min_price_at)->format('M-d h:i:s')}}</td>
                            <td class="border px-2 py-2">{{\Carbon\Carbon::parse($tracker->product->updated_at)->format('M-d h:i:s')}}</td>
                            <td class="border px-4 py-2">
                                @if ($tracker->enabled)
                                    <x-jet-button wire:click="disable({{$tracker->id}})">Disable</x-jet-button>
                                @else
                                    <x-jet-button wire:click="enable({{$tracker->id}})">Enable</x-jet-button>
                                @endif

                                <x-jet-button wire:click="show({{$tracker->product->id}})">Show</x-jet-button>
                                <x-jet-button wire:click="navigate({{$tracker->product->id}})">Navigate</x-jet-button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <br/>
                {{$trackers->links()}}
            </div>
        </div>
    </div>
</div>
