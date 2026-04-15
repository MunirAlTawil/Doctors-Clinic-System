<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

final class InterventionPublicImage
{
    /**
     * Save an uploaded image via Intervention on the public disk (e.g. doctor-profiles, pages, specialties).
     */
    public static function store(UploadedFile $file, string $directory): string
    {
        $directory = trim($directory, '/');

        $manager = new ImageManager(new Driver());
        $nameGen = hexdec(uniqid()).'.'.$file->getClientOriginalExtension();
        $relative = $directory.'/'.$nameGen;

        Storage::disk('public')->makeDirectory($directory);

        $img = $manager->read($file);
        $img->save(Storage::disk('public')->path($relative));

        return $relative;
    }
}
