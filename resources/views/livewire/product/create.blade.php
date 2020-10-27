<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Create Product') }}
    </h2>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div>
                <form>
                    <x-jet-input type="text" wire:model="product.asin" placeholder="Product Asin"/>
                    @error('product.asin') <span class="error">{{ $message }}</span> @enderror

                    @foreach($trackers as $tracker)
                        <x-jet-button type="button"
                                      wire:click="saveTracker({{$tracker->id}},'{{get_class($tracker)}}')">Add to
                            `{{$tracker->name}}`
                            channel
                        </x-jet-button>
                    @endforeach
                </form>
            </div>
        </div>
    </div>
</div>
