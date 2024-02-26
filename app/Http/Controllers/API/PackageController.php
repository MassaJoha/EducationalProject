<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\PackageRequest;
use App\Http\Resources\PackageResource;
use App\Models\Package;
use App\Services\PackageService;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    private $packageService;

    public function __construct(PackageService $packageService)
    {
        $this->packageService = $packageService;
    }

    public function index(Request $request){
        $includeDeleted = $request->query('include_deleted', false);
        $searchName = $request->query('search_name');
    
        $packages = $includeDeleted ? Package::withTrashed()->get() : Package::all();
    
        $filteredPackages = $packages->filter(function ($package) use ($searchName) {
            return !$searchName || stripos($package->name, $searchName) !== false;
        });
    
        return PackageResource::collection($filteredPackages);
    }

    public function show($id)
    {
        $package = $this->packageService->showPackage($id);

        return new PackageResource($package);
    }

      // Add Media function to specific Package
      public function addMedia(Package $package){
        $this->packageService->uploadMedia($package);

        return response()->json(['message' => 'Media uploaded successfully']);
    }

    // Get all media for a specific Package
    public function getAllMediaForPackage(Package $package){
        $media = $this->packageService->getMedia($package);

        return response()->json($media);
    }

    public function edit($id)
    {
        $package = $this->packageService->editPackage($id);

        return new PackageResource($package);
    }

    public function store(PackageRequest $request)
    {
        $package = $this->packageService->createPackage($request->validated());

        return new PackageResource($package);
    }

    public function update(PackageRequest $request, $id)
    {
        $package = Package::findOrFail($id);

        $updatedPackage = $this->packageService->updatePackage($package, $request->validated());

        return new PackageResource($updatedPackage);
    }

    public function destroy(Package $package)
    {
        $this->packageService->deletePackage($package);

        return response()->json(['message' => 'Package deleted successfully']);
    }
}
