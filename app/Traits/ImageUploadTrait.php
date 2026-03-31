<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

trait ImageUploadTrait
{
    // /** handle slider image file */
    // public function sliderImage(Request $request, $inputName, $path)
    // {
    //     if ($request->hasFile($inputName)) {
    //         $image = $request->{$inputName};
    //         $ext = $image->getClientOriginalExtension();
    //         $imageName = 'media_' . uniqid() . '.' . $ext;
            
    //         // Save to storage/app/public/$path
    //         $image->storeAs($path, $imageName, 'public');
            
    //         return $path . '/' . $imageName;
    //     }
    // }

    // /** image upload handle with intervention */
    // public function uploadImage($request, $imageField, $directory, $width = 400, $height = 500)
    // {
    //     if ($request->file($imageField)) {
    //         $image = $request->file($imageField);

    //         $width = $width ?: 400;
    //         $height = $height ?: 500;

    //         $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
    //         $manager = new ImageManager(new Driver());
    //         $img = $manager->read($image);
            
    //         // Resize and encode
    //         $encoded = $img->resize($width, $height)->toJpeg(); // or keep original format if needed
            
    //         $fullPath = $directory . '/' . $name_gen;
    //         Storage::disk('public')->put($fullPath, (string) $encoded);
            
    //         return $fullPath;
    //     }
    //     return null;
    // }

    public function deleteImage($path)
    {
        if ($path) {
            // Remove 'storage/' prefix if it's there (from accessor)
            if (str_starts_with($path, 'storage/')) {
                $path = substr($path, 8);
            }
            
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }
    }

    // /** multiple image upload */
    // public function uploadMultiImage(Request $request, $inputName, $directory, $width = 400, $height = 500)
    // {
    //     $imagepaths = [];
    //     if ($request->hasFile($inputName)) {
    //         $images = $request->{$inputName};
    //         foreach ($images as $image) {
    //             $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
    //             $manager = new ImageManager(new Driver());
    //             $img = $manager->read($image);
                
    //             $encoded = $img->resize($width, $height)->toJpeg();
    //             $fullPath = $directory . '/' . $name_gen;
                
    //             Storage::disk('public')->put($fullPath, (string) $encoded);
    //             $imagepaths[] = $fullPath;
    //         }
    //         return $imagepaths;
    //     }
    // }

    // /** update image */
    // public function updateImage($request, $imageField, $directory, $oldImage = null, $width = 400, $height = 500)
    // {
    //     if ($request->file($imageField)) {
    //         if ($oldImage) {
    //             $this->deleteImage($oldImage);
    //         }

    //         return $this->uploadImage($request, $imageField, $directory, $width, $height);
    //     }

    //     return $oldImage;
    // }

    /** Special image upload (SVG, WebP, GIF, etc.) */
    public function uploadSpecialImage($request, $imageField, $directory, $oldImage = null)
    {
        if ($request->hasFile($imageField)) {
            $file = $request->file($imageField);
            $extension = strtolower($file->getClientOriginalExtension());
            $allowed = ['jpg', 'jpeg', 'png', 'webp', 'svg', 'gif', 'ico', 'bmp', 'tiff'];

            if (!in_array($extension, $allowed)) {
                $extension = strtolower(pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION));
                if (!in_array($extension, $allowed)) {
                    return $oldImage;
                }
            }

            if ($oldImage) {
                $this->deleteImage($oldImage);
            }

            $name = hexdec(uniqid()) . '.' . $extension;
            $file->storeAs($directory, $name, 'public');

            return $directory . '/' . $name;
        }

        return $oldImage;
    }

    /** Normal way handle image */
    public function upload_image(Request $request, $inputName, $path)
    {
        if ($request->hasFile($inputName)) {
            $image = $request->{$inputName};
            $ext = $image->getClientOriginalExtension();
            $imageName = 'media_' . uniqid() . '.' . $ext;
            
            $image->storeAs($path, $imageName, 'public');
            
            return $path . '/' . $imageName;
        }
    }

    /** handle multi image file */
    public function upload_multiImage(Request $request, $inputName, $path)
    {
        $imagepaths = [];
        if ($request->hasFile($inputName)) {
            $images = $request->{$inputName};
            foreach ($images as $image) {
                $ext = $image->getClientOriginalExtension();
                $imageName = 'media_' . uniqid() . '.' . $ext;
                
                $image->storeAs($path, $imageName, 'public');
                $imagepaths[] = $path . '/' . $imageName;
            }
            return $imagepaths;
        }
    }

    /** handle single image update file */
    public function update_image(Request $request, $inputName, $path, $oldPath = null)
    {
        if ($request->hasFile($inputName)) {
            if ($oldPath) {
                $this->deleteImage($oldPath);
            }
            
            $image = $request->{$inputName};
            $ext = $image->getClientOriginalExtension();
            $imageName = 'media_' . uniqid() . '.' . $ext;
            
            $image->storeAs($path, $imageName, 'public');
            
            return $path . '/' . $imageName;
        }
        return $oldPath;
    }

    /** handle delete file */
    public function delete_image(string $path)
    {
        $this->deleteImage($path);
    }
}