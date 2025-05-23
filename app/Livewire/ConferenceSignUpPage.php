<?php

namespace App\Livewire;

use App\Models\Attendee;
use App\Models\Conference;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Illuminate\Support\HtmlString;
use Livewire\Component;



class ConferenceSignUpPage extends Component implements HasForms, HasActions
{
    use InteractsWithActions, InteractsWithForms;

    public int $conferenceId;
    public int $price = 50000;
    public array $conferences = [];
    public string $selectedNationality = '';
    public string $nationality = '';
    public string $passportPhoto = '';
    public array $attendees = [];

    public function mount()
    {
        $this->conferences = Conference::all()->pluck('name', 'id')->toArray();

        $this->conferenceId = array_key_first($this->conferences) ?? 0;

        // $this->selectedNationality = 'Malaysian'; // Initialize the selected

        // $this->nationality = is_array($attendee->nationality)
        // ? implode(',', $attendee->nationality) // Convert array to comma-separated string
        // : (string) $attendee->nationality; // Cast to string if not already
        
    //     $attendee = Attendee::first();
    //     $this->nationality = $attendee ? (is_array($attendee->nationality)
    // ? implode(',', $attendee->nationality)
    // : (string) $attendee->nationality) : '';

    }

    public function signUpAction(): Action
    {
        return Action::make('signUp')
            
            ->openUrlInNewTab() // Ensures the URL opens in a new tab
            ->form([
                Placeholder::make('total_price')
                    ->hiddenLabel()
                    ->content(function (Get $get) {
                        return '$' . count($get('attendees')) * 500;
                    }),
                Select::make('conference_id')
                    ->label('Select Conference')
                    ->options($this->conferences)
                    ->reactive()
                    ->afterStateUpdated( fn ($state) => $this->conferenceId = $state),
                Repeater::make('attendees')
                    ->schema(Attendee::getForm()),
            ])
            ->action(function (array $data) {
                collect($data['attendees'])->each(function ($data) {
                    Attendee::create([
                        'conference_id' => $this->conferenceId,
                        'ticket_cost' => $this->price,
                        'name' => $data['name'],
                        'email' => $data['email'],
                        'nationality' => $data['nationality'],
                        'photo' => isset($data['photo']) ? (is_array($data['photo']) ? json_encode($data['photo']) : (string) $data['photo']) : '',
                        'passport_photo' => isset($data['passport_photo']) ? (is_array($data['passport_photo']) ? json_encode($data['passport_photo']) : (string) $data['passport_photo']) : '',
                        'is_paid' => true,
                    ]);
                    
                });
            })
            ->after(function () {
                Notification::make()->success()->title('Success!')
                    ->body(new HtmlString('You have successfully signed up for the conference.'))->send();
            });
    }

    public function render()
    {
        return view('livewire.conference-sign-up-page');
    }
}
