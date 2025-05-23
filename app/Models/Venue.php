<?php

namespace App\Models;

use App\Enums\Region;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Venue extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $casts = [
        'id' => 'integer',
        'region' => Region::class,
    ];

    public function conferences(): HasMany
    {
        return $this->hasMany(Conference::class);
    }

    public static function getForm()
    {
        return [
            Section::make('Venue Details')
                ->schema([
                    TextInput::make('name')
                        ->label('Venue')
                        ->required()
                        ->maxLength(60),
                    TextInput::make('city')
                        ->required()
                        ->maxLength(60),
                    TextInput::make('country')
                        ->required()
                        ->maxLength(60),
                    TextInput::make('postal_code')
                        ->required()
                        ->maxLength(60),
                    Select::make('region')
                        ->enum(Region::class)
                        ->options(Region::class),
                    SpatieMediaLibraryFileUpload::make('images')
                        ->collection('venue-images')
                        ->multiple()
                        ->image(),
                ]),
        ];
    }
}
