<?php

namespace App\Http\Livewire\Product;

use App\Models\AmzProduct;
use App\Models\AmzProductUser;
use Livewire\Component;

class Create extends Component
{
    public AmzProduct $product;
    public $trackers;

    protected $rules = [
        'product.asin' => 'required|string|max:250',
    ];

    public function mount()
    {
        $this->product = new AmzProduct();
        $this->trackers = auth()->user()->currentTeam->channels;
    }

    public function render()
    {
        return view('livewire.product.create');
    }

    public function saveTracker(int $trackerId, string $trackerType)
    {
        $this->validate();
        $this->product = AmzProduct::query()->firstOrCreate(['asin' => $this->product->asin]);

        AmzProductUser::query()->updateOrCreate([
            'trackable_id' => $trackerId,
            'trackable_type' => $trackerType,
            'amz_product_id' => $this->product->id
        ], [
            'enabled' => true
        ]);

        session()->flash('message', 'Product successfully updated.');

        return redirect()->route('products.index');
    }
}
