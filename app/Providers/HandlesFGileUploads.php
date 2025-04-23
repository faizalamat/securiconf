<?php

namespace App\Providers

use Livewire\TemporaryUploadedFile;
use Illuminate\Support\Facades\Storage;

trait HandlesFileUploads
{
    public static function uploadFile($disk, $component, TemporaryUploadedFile $file)
    {
        $storeMethod = $component->getVisibility() === 'public' ? 'storePubliclyAs' : 'storeAs';

        $filename = str_replace(" ", "-", $file->getClientOriginalName());
        $filename = Storage::getUniqueFilename('public', $filename);

        return $file->{$storeMethod}($component->getDirectory(), $filename, $component->getDiskName());
    }

    public static function removeFile($disk, $file)
    {
        Storage::disk($disk)->delete($file);
    }

}