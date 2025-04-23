<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Conference;
use App\Models\Attendee;

class ConferenceSignUp extends Component
{
    public $conferences;
    public $selectedConference;
    public $name;
    public $email;

    public function mount()
    {
        $this->conferences = Conference::all();
    }

    public function submit()
    {
        $this->validate([
            'selectedConference' => 'required|exists:conferences,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);

        Attendee::create([
            'conference_id' => $this->selectedConference,
            'name' => $this->name,
            'email' => $this->email,
        ]);

        session()->flash('message', 'You have successfully signed up for the conference!');

        $this->reset(['selectedConference', 'name', 'email']);
    }

    public function render()
    {
        return view('livewire.conference-sign-up');
    }
}
