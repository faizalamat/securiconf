<?php

namespace App\Models;

use App\Enums\Nationality;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Attendee extends Model
{
    use HasFactory;

    protected $casts = [
        'ticket_cost' => 'integer',
        'nationality' => 'string', 
        'photo' => 'string',
        'passport_photo' => 'string',
              
    ];

    public function conference()
    {
        return $this->belongsTo(Conference::class);
    }

    public static function getForm(): array
    {
        
        return [
            Group::make()
                ->columns(2)
                ->schema([
                    TextInput::make('name')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('email')
                        ->email()
                        ->required()
                        ->maxLength(255),
                    FileUpload::make('photo')
                        ->directory('idcards')
                        ->hidden(fn ($get) => $get('nationality') !== 'Malaysian'),
                    Select::make('nationality')
                        ->options([
                            'Malaysian' => 'Malaysian',
                            'Non-Malaysian' => 'Non-Malaysian',
                        ])
                        ->reactive()
                        ->afterStateHydrated(fn ($state, $set) => $set('nationality', (string) $state)),
                    FileUpload::make('passport_photo')
                        ->directory('passport_photos')
                        ->hidden(fn ($get) => $get('nationality') !== 'Non-Malaysian'),
                ]),
        ];
    }

}
