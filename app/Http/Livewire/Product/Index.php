<?php

namespace App\Http\Livewire\Product;

use App\Models\AmzProduct;
use App\Models\AmzProductUser;
use App\Models\Channels;
use App\Models\ShortUrl;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $channels;
    public string $search = '';
    public int $trackerId = 0;
    public int $perPage = 20;
    public bool $showDisabled = false;
    public string $trackerType = Channels::class;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingTrackerId()
    {
        $this->resetPage();
    }

    public function mount()
    {
        $this->channels = auth()->user()->currentTeam->channels;
    }

    public function render()
    {
        $channelsId = auth()->user()->currentTeam->channels->pluck('id');

        $query = AmzProductUser::query()
            ->join('amz_products', 'amz_products.id', '=', 'amz_product_user.amz_product_id')
            ->where('amz_product_user.enabled', !$this->showDisabled)
            ->where('amz_products.title', 'like', '%' . $this->search . '%');

        if ($this->trackerId && $this->trackerType) {
            $query->where('amz_product_user.trackable_type', $this->trackerType)
                ->where('amz_product_user.trackable_id', $this->trackerId);
        } else {
            $query->where('amz_product_user.trackable_type', $this->trackerType)
                ->whereIn('amz_product_user.trackable_id', $channelsId);
        }

        return view('livewire.product.index', [
            'trackers' => $query->select('amz_product_user.*')->paginate($this->perPage)
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

    public function filterTracker(int $trackerId)
    {
        $this->trackerId = $trackerId;
        $this->resetPage();
    }
}
