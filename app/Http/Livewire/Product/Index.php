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
    public bool $showDisabled = false;
    public int $perPage = 20;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.product.index', [
            'trackers' => auth()->user()->products()
                ->join('amz_products', 'amz_products.id', '=', 'amz_product_user.amz_product_id')
                ->where('amz_product_user.enabled', !$this->showDisabled)
                //  ->where('amz_product_user.trackable_type', Channels::class)
                //  ->whereIn('amz_product_user.trackable_id', auth()->user()->current_team_id)
                ->where('amz_products.title', 'like', '%' . $this->search . '%')
                ->select('amz_product_user.*')
                ->paginate($this->perPage),
        ]);
    }

    public function navigate(int $productId)
    {
        $product = AmzProduct::query()->where('id', $productId)->first();
        $url = ShortUrl::hideLink($product->itemDetailUrl . '?tag=' . env('AMZ_PARTNER'));

        return redirect()->away($url);
    }

    public function show(int $productId)
    {
        return redirect()->route('products.show', ['product' => AmzProduct::find($productId)]);
    }

    public function disable(int $trackerId)
    {
        $this->changeEnabledFlag($trackerId, false);
    }

    public function enable(int $trackerId)
    {
        $this->changeEnabledFlag($trackerId);
    }

    public function changeEnabledFlag(int $trackerId, bool $flag = true)
    {
        AmzProductUser::query()->where([
            'id' => $trackerId,
        ])->update([
            'enabled' => $flag,
        ]);

        $this->resetPage();
    }

    public function create()
    {
        return redirect()->route('products.create');
    }
}
