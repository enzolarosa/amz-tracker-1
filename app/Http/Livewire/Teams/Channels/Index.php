<?php

namespace App\Http\Livewire\Teams\Channels;

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

    public function addChannel()
    {
        return redirect()->route('teams.channels.add');
    }
}
