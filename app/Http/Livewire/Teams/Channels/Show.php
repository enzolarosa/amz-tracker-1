<?php

namespace App\Http\Livewire\Teams\Channels;

use App\Models\Channels;
use Livewire\Component;

class Show extends Component
{
    public $channel;

    public function mount($id)
    {
        $this->channel = Channels::query()->find($id);
    }

    public function render()
    {
        return view('livewire.teams.channels.show');
    }
}
