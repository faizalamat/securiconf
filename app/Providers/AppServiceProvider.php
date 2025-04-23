<?php

namespace App\Providers;

use Filament\Tables\Actions\CreateAction;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::unguard();

        CreateAction::configureUsing(function ($action) {
            return $action->slideOver();
        });

        Storage::macro('getUniqueFilename', function($disk, $originalFileName) {

    $filename = File::name($originalFileName);
    $extension = File::extension($originalFileName);

    if(Storage::disk($disk)->exists($originalFileName)) {

        $allFiles = collect(Storage::disk($disk)->allFiles())
            ->map(fn($file) => File::name($file)) //take the filenames only
            ->reject(fn($item) => $item == "") //reject empty filenames
            ->reject(fn($item) => !preg_match("/{$filename}-[0-9]*$/", $item)) //reject anything that doesnt match filename-xx
            ->sort(); 


        if($allFiles->count())
        {
            $f = $allFiles->last();
            $filename = ++$f;
        }
        else
        {
            //just add a suffix
            $filename .= "-01";
        }
    }

    $filename .= ".".$extension;

    return $filename;

    });
    }
}
