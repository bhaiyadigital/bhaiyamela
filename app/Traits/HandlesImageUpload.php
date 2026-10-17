<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait HandlesImageUpload
{
    protected int $maxSizeBytes = 1 * 1024 * 1024; // 1MB default limit
    protected int $maxDimension = 1920;             // max width or height

    protected function storeAsWebp(UploadedFile|string $input, string $directory): string
    {
        if ($input instanceof UploadedFile) {

            $extension = strtolower($input->getClientOriginalExtension());

            if ($extension === 'svg') {
                return $this->svgUpload($input, $directory);
            }
        }

        $filename = 'img_' . uniqid() . '_' . time() . '.webp';
        $fullPath = $directory . '/' . $filename;

        // Base64 ডিকোড হ্যান্ডলিং
        $source = $input;
        if (is_string($input)) {
            $data   = preg_replace('/^data:image\/\w+;base64,/', '', $input);
            $source = base64_decode($data);
        } else {
            $source = $input->getRealPath();
        }

        // ফাসাদ ক্লাসের নাম নির্ধারণ
        $v3Facade = 'Intervention\Image\Laravel\Facades\Image';
        $v2Facade = 'Intervention\Image\Facades\Image';

        // ১. রান-টাইমে Intervention v3/v4 ডিটেক্ট করা হচ্ছে
        $isV3 = false;
        if (class_exists($v3Facade)) {
            try {
                $root = $v3Facade::getFacadeRoot();
                if ($root && method_exists($root, 'read')) {
                    $isV3 = true;
                }
            } catch (\Throwable $e) {
                $isV3 = false;
            }
        }

        if ($isV3) {
            // ── INTERVENTION IMAGE V3/V4 ──
            $image = $v3Facade::read($source);

            // সাইজ বেশি বড় হলে রিলিজ/ডাউনস্কেল করা
            $image->scaleDown(width: $this->maxDimension, height: $this->maxDimension);

            // ফাইল সাইজ ১ মেগাবাইটের নিচে না আসা পর্যন্ত কমপ্রেস করা
            $quality = 85;
            do {
                $encoded = $image->toWebp(quality: $quality);
                $quality -= 5;
            } while (strlen((string) $encoded) > $this->maxSizeBytes && $quality >= 20);

            Storage::disk('public')->put($fullPath, (string) $encoded);
        } elseif (class_exists($v2Facade) && method_exists($v2Facade, 'make')) {
            // ── INTERVENTION IMAGE V2 (ব্যাকআপ সাপোর্ট) ──
            $image = $v2Facade::make($source);

            $image->resize($this->maxDimension, $this->maxDimension, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            $quality = 85;
            do {
                $encoded = $image->encode('webp', $quality);
                $quality -= 5;
            } while (strlen((string) $encoded) > $this->maxSizeBytes && $quality >= 20);

            Storage::disk('public')->put($fullPath, (string) $encoded);
        } else {
            // ── নেটিভ ব্যাকআপ (প্যাকেজে সমস্যা থাকলে নরমাল সেভ হবে) ──
            if ($input instanceof UploadedFile) {
                $fullPath = $input->storeAs($directory, $filename, 'public');
            } else {
                Storage::disk('public')->put($fullPath, $source);
            }
        }

        return $fullPath;
    }
    public function svgUpload(UploadedFile $file, string $directory): string
    {
        $filename = 'img_' . uniqid() . '_' . time() . '.svg';
        $fullPath = $directory . '/' . $filename;

        Storage::disk('public')->putFileAs($directory, $file, $filename);

        return $fullPath;
    }
}
