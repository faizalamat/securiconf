<?php

namespace App\Models;

use Filament\Forms\Components\Group;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendee extends Model
{
    use HasFactory;



    // public function conference(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    // {
    //     return $this->belongsTo(Conference::class);
    // }

    public function conference()
    {
        return $this->belongsTo(Conference::class);
    }

    public static function getForm(): array
    {
        


        return [
            Group::make()->columns(2)->schema([
                TextInput::make('name')
                    ->required()->maxLength(255),
                TextInput::make('email')
                    ->email()->required()->maxLength(255),
                FileUpload::make('photo')
                    // ->saveUploadedFileUsing(function (FileUpload $component, TemporaryUploadedFile $file): string {
                    //         return self::uploadFile('public', $component, $file);
                    // })
                    // ->deleteUploadedFileUsing(function($file) {
                    // self::removeFile('public', $file);
                    // }),
                    ->directory('idcards'),

            ])
        ];
    }

}
