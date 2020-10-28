<?php

namespace App\Http\Livewire\Teams\Channels;

use App\Models\Channels;
use Livewire\Component;

class Create extends Component
{
    public Channels  $channel;

    protected $rules = [
        'channel.name' => 'required|string|max:250',
        'channel.team_id' => 'required|exists:teams,id',
        'channel.configuration' => 'sometimes|json|nullable',
    ];

    public function mount()
    {
        $this->channel = new Channels([
            'team_id' => auth()->user()->current_team_id,
        ]);
    }

    public function render()
    {
        return view('livewire.teams.channels.create');
    }

    public function save()
    {
        $this->validate();
        $this->channel->save();

        session()->flash('message', 'Channel successfully updated.');

        return redirect()->route('channels.index');
    }
}
