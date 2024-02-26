<?php

namespace App\Http\Controllers\API;

use App\Exceptions\PurchaseSureException;
use App\Http\Controllers\Controller;
use App\Http\Requests\PurchaseRequest;
use App\Http\Resources\PurchaseResource;
use App\Models\Course;
use App\Models\Package;
use App\Models\User;
use App\Services\UserPurchaseService;
use Illuminate\Support\Facades\Log;


class PurchaseController extends Controller
{
    private $purchaseService;

    public function __construct(UserPurchaseService $purchaseService)
    {
        $this->purchaseService = $purchaseService;
    }

 public function index(User $user)
    {
        $purchases = $this->purchaseService->getUserPurchases($user);

        return PurchaseResource::collection($purchases);
    }

    public function store(PurchaseRequest $request)
    {
        $user = $request->user();
        $purchasableType = $request->input('purchasable_type');
        $purchasableId = $request->input('purchasable_id');
        $data = $request->validated();

        try {
            // Check if the user already has the same purchase
            if ($this->purchaseService->hasExistingPurchase($user, $purchasableType, $purchasableId)) {
                throw new PurchaseSureException('Error', ['error' => 'User already has this purchase, Do you want to purchasing Course/Package again?'], 400);
            }
        
            // Determine the purchasable model based on the purchasable type
            if ($purchasableType === 'course') {
                $purchasable = Course::findOrFail($purchasableId);
            } elseif ($purchasableType === 'package') {
                $purchasable = Package::findOrFail($purchasableId);
            } else {
                // Handle the case when the purchasable type is not supported
                return response()->json(['error' => 'Invalid purchasable type'], 400);
            }

            $purchase = $this->purchaseService->createPurchase($user, $purchasableType, $purchasableId, $data);
            
            return new PurchaseResource($purchase);
        } catch (PurchaseSureException $e) {
            // Handle the custom exception
            $message = $e->getMessage();
            $data    = $e->getData();
            $code    = $e->getCode();
        
            // Log the exception or perform any other necessary actions
            Log::error($message, ['data' => $data, 'code' => $code]);
        
            // Return an appropriate response to the user
            return response()->json(['error' => $message, 'data' => $data], $code);
        }
    }
}
