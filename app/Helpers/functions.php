<?php

use CodeItNow\BarcodeBundle\Utils\QrCode;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Spatie\Image\Image;
use Intervention\Image\ImageManagerStatic as IntImage;
use Spatie\Image\Manipulations;

function getQrCode($text)
{
    $qrCode = new QrCode();
    $qrCode
        ->setText($text)
        ->setSize(200)
        ->setPadding(10)
        ->setErrorCorrection('high')
        ->setForegroundColor(array('r' => 0, 'g' => 0, 'b' => 0, 'a' => 0))
        ->setBackgroundColor(array('r' => 255, 'g' => 255, 'b' => 255, 'a' => 0))
        ->setLabel($text)
        ->setLabelFontSize(16)
        ->setImageType(QrCode::IMAGE_TYPE_PNG);
    return $qrCode->generate();
}

if (!function_exists('uploadBase64')) {
    function uploadBase64($image_64, $toPath)
    {
        $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];   // .jpg .png .pdf
        $replace = substr($image_64, 0, strpos($image_64, ',') + 1);
        $image = str_replace($replace, '', $image_64);
        $image = str_replace(' ', '+', $image);

        $toPath .= ".{$extension}";
        Storage::put("public/{$toPath}", file_get_contents($image_64));

        return "storage/{$toPath}";
    }
}

if (!function_exists('customMaskImage')) {
    function customMaskImage($post)
    {
        $fileName = $post->media()->first()->file_name;
        $pathToGetPut = "posts/{$post->id}";

        $s3file = Storage::disk('s3')->get("{$pathToGetPut}/{$fileName}");
        $image = IntImage::make($s3file);

        $post->clearMediaCollection('Posts');

        try {
            $mask = IntImage::make('storage/temp/mask.png');
            $width = $mask->width();
            $height = $mask->height();

            $image->fit($width, $height); //->mask($mask);

            $logo = IntImage::make($post->business->logo)->resize(80, 80);

            $fonts = ['anydore', 'gladifilthefte', 'momentus', 'roboto-regular'];

            $image->insert($logo, 'top-left', 10, 10)
                ->text($post->business->name, $width - 80, $height - 15, function ($font) use ($fonts) {
                    $index = rand(0, 3);

                    $font->file(public_path("fonts/{$fonts[$index]}.ttf"));
                    $font->size(24);
                })
                ->save("storage/masked/{$fileName}");

            Storage::disk('s3')
                ->put("{$pathToGetPut}/{$fileName}", $image->__toString(), 'public');

            return Storage::disk('s3')->url("{$pathToGetPut}/{$fileName}");
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}


if (!function_exists('customOverlay')) {
    function customOverlay($post, $overlay, $divisor = 3)
    {
        $fileName = $post->media()->first()->file_name;
        $pathToGetPut = "posts/{$post->id}";

        $s3file = IntImage::make(Storage::disk('s3')->get("{$pathToGetPut}/{$fileName}"));

        $overlay = IntImage::make($overlay);
        $width = $s3file->width() - $s3file->width() / $divisor;

        $overlay->resize($width, null, function ($constraint) {
            $constraint->aspectRatio();
        });

        // $mask = IntImage::make('storage/temp/mask.png');
        // $canvas = IntImage::canvas($s3file->width(), $s3file->height())->insert($overlay, 'bottom', 0, 50)->mask($mask);


        $s3file->insert($overlay, 'bottom', 0, 50)
            ->save("storage/overlay/{$fileName}");

        $post->clearMediaCollection('Posts');

        $post->addMediaFromUrl(url("storage/overlay/{$fileName}"))
            ->sanitizingFileName(function ($fileName) {
                return strtolower(str_replace(['#', '/', '\\', ' '], '-', $fileName));
            })
            ->toMediaCollection('Posts');
    }
}

if (!function_exists('postToFacebook')) {
    function postToFacebook()
    {
    }
}
