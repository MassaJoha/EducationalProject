<?php
namespace App\Services;

use App\Models\Package;

class PackageService
{
    public function createPackage(array $data)
    {
        $package = Package::create($data);

        return $package;
    }
    
    public function uploadMedia(Package $package)
    {
        $media = $package->addMediaFromRequest('file')->toMediaCollection('media');

        return $media;
    }

    public function getMedia(Package $package)
    {
        $media = $package->getMedia('media');

        $mediaUrls = $media->map(function ($item) {
            return $item->getUrl();
        });

        return $mediaUrls;
    }

    public function showPackage($id)
    {
        return Package::findOrFail($id);
    }

    public function editPackage($id)
    {
        return Package::findOrFail($id);
    }
    
    public function updatePackage(Package $package, array $data)
    {
        $package->update($data);

        return $package;
    }

    public function deletePackage(Package $package)
    {
        $package->delete();
    }
}