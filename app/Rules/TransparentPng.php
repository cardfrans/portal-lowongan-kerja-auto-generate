<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;

class TransparentPng implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! $value instanceof UploadedFile || ! $value->isValid()) {
            $fail('Logo perusahaan tidak dapat dibaca.');
            return;
        }

        if (strtolower($value->getClientOriginalExtension()) !== 'png' || $value->getMimeType() !== 'image/png') {
            $fail('Logo perusahaan harus berupa file PNG.');
            return;
        }

        if (! function_exists('imagecreatefrompng')) {
            $fail('Validasi transparansi logo belum tersedia di server.');
            return;
        }

        $dimensions = @getimagesize($value->getRealPath());
        if ($dimensions === false || $dimensions[0] > 2000 || $dimensions[1] > 2000) {
            $fail('Ukuran logo maksimal 2000 x 2000 piksel.');
            return;
        }

        $image = @imagecreatefrompng($value->getRealPath());
        if ($image === false) {
            $fail('File logo PNG tidak valid.');
            return;
        }

        $hasTransparentPixel = false;
        $width = imagesx($image);
        $height = imagesy($image);

        for ($x = 0; $x < $width && ! $hasTransparentPixel; $x++) {
            for ($y = 0; $y < $height; $y++) {
                if ((imagecolorat($image, $x, $y) & 0x7F000000) !== 0) {
                    $hasTransparentPixel = true;
                    break;
                }
            }
        }

        imagedestroy($image);

        if (! $hasTransparentPixel) {
            $fail('Logo harus memiliki latar transparan. Simpan logo sebagai PNG transparan.');
        }
    }
}
