<?php

namespace App\Http\Livewire\Teams\Channels;

use App\Models\Channels;
use Livewire\Component;

class Index extends Component
{
    public $channels;
    public $channel;

    public function mount()
    {
        $this->channels = auth()->user()->currentTeam->channels;
    }

    public function render()
    {
        return view('livewire.teams.channels.index');
    }

    public function create()
    {
        return redirect()->route('channels.create');
    }

    public function show(int $id)
    {
        return redirect()->route('channels.show', ['channel' => Channels::find($id)]);
    }
}
