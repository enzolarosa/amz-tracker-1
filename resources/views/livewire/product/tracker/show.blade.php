<div class="p-6 sm:px-20 bg-white border-b border-gray-200">
    <label>
        <input wire:model="search" type="text" placeholder="Filter"
               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
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
        @foreach($products as $product)
            <tr>
                <td class="border px-4 py-2">
                    <img src="{{\Illuminate\Support\Arr::first($product->images)}}" title="{{$product->title}}"
                         width="50px" alt="{{$product->description}}"/>
                </td>
                <td class="border px-4 py-2">
                    {{$product->title}}
                </td>
                <td class="border px-4 py-2">
                    {{number_format($product->start_price ,2, ',', '.')}}€
                </td>
                <td class="border px-4 py-2">
                    {{number_format($product->previous_price,2, ',', '.')}}€
                </td>
                <td class="border px-2 py-w">
                    {{number_format($product->current_price,2, ',', '.')}}€
                </td>
                <td class="border px-4 py-2">
                    {{number_format($product->min_price,2, ',', '.')}}€
                </td>
                <td class="border px-2 py-2">
                    {{\Carbon\Carbon::parse($product->min_price_at)->format('M-d h:i:s')}}
                </td>
                <td class="border px-2 py-2">
                    {{\Carbon\Carbon::parse($product->updated_at)->format('M-d h:i:s')}}
                </td>
                <td class="border px-4 py-2">
                    <button wire:click="showProduct({{$product->id}})"
                        class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-2 border border-gray-400 rounded shadow">
                        Show
                    </button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <br/>
    {{$products->links()}}
</div>
