<?php

namespace App\Http\Livewire\Teams\Channels;

use App\Models\Channels;
use Livewire\Component;

class Show extends Component
{
    public $channel;

    public function mount(Channels $channel)
    {
        $this->channel = $channel;
    }

    public function render()
    {
        return view('livewire.teams.channels.show');
    }

    public function index()
    {
        return redirect()->route('channels.index');
    }
}
