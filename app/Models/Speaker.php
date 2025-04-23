<?php

namespace App\Models;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Filament\Forms\Components\TextInput; // Adjusted namespace to Filament package
use Illuminate\Database\Eloquent\Relations\HasMany;

class Speaker extends Model
{
    use HasFactory;

    const QUALIFICATIONS = [
        'business-leader' => 'Business Leader',
        'charisma' => 'Charisma Speaker',
        'first-time' => 'First Time Speaker',
        'hometown-hero' => 'Hometown Hero',
        'industry-expert' => 'Industry Expert',
        'laracast-contributor' => 'Laracast Contributor',
        'open-source' => 'Open Source Contributor / Maintainer',
    ];

    protected $casts = [
        'id' => 'integer',
        'qualifications' => 'array',
    ];

    public function conferences(): BelongsToMany
    {
        return $this->belongsToMany(Conference::class);
    }

    public function talks(): HasMany
    {
        return $this->hasMany(Talk::class);
    }

    public static function getForm(): array
    {
        return [
                    TextInput::make('name')
                        ->label('Name')
                        ->required()
                        ->maxLength(60),
                    FileUpload::make('avatar')
                        ->avatar()
                        ->directory('avatars')
                        ->imageEditor()
                        ->image()
                        ->maxSize(1024*1024*10),
                    TextInput::make('email')
                        ->label('Email')
                        ->required()
                        ->email()
                        ->maxLength(255),
                    Textarea::make('bio')
                        ->columnSpanFull()
                        ->required()
                        ->maxLength(65535),
                    TextInput::make('twitter_handle')
                        ->required()
                        ->maxLength(255),
                    CheckboxList::make('qualifications')
                        ->columnSpanFull()
                        ->searchable()
                        ->bulkToggleable()
                        ->options(self::QUALIFICATIONS)
                        ->descriptions([
                            'business leader' => 'Here is a nice description for Business Leader',
                            'charisma' => 'Charismatic Speaker',
                        ])
                        ->columns(3),
                    ];
        
    }
}
