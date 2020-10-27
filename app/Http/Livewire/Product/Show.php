<?php

namespace App\Http\Livewire\Product;

use App\Models\AmzProduct;
use Livewire\Component;

class Show extends Component
{
    public $product;

    public function mount(AmzProduct $product)
    {
        $this->product = $product;
    }

    public function render()
    {
        return view('livewire.product.show');
    }
}