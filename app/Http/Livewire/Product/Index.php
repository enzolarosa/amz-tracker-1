<?php

namespace App\Http\Livewire\Product;

use App\Models\AmzProduct;
use App\Models\AmzProductUser;
use App\Models\ShortUrl;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.product.index', [
            'products' => auth()->user()->products()
                ->where('title', 'like', '%' . $this->search . '%')
                //    ->orWhere('asin', 'like', '%' . $this->search . '%')
                ->paginate(20),
        ]);
    }

    public function disable(int $productId)
    {
        $this->changeEnabledFlag($productId, false);
    }

    public function showProduct(int $productId)
    {
        $product = AmzProduct::query()->where('id', $productId)->first();
        $url = ShortUrl::hideLink($product->itemDetailUrl . '?tag=' . env('AMZ_PARTNER'));

        return redirect()->away($url);
    }

    public function enable(int $productId)
    {
        $this->changeEnabledFlag($productId);
    }

    public function changeEnabledFlag(int $productId, bool $flag = true)
    {
        AmzProductUser::query()->where([
            'amz_product_id' => $productId,
            'user_id' => auth()->user()->id,
        ])->update([
            'enabled' => $flag,
        ]);
    }
}
