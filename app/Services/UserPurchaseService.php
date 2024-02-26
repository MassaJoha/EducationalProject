<?php
namespace App\Services;

use App\Events\AfterUserPurchasingEvent;
use App\Helpers\PurchaseLogger;
use App\Models\User;

class UserPurchaseService
{
    public function getUserPurchases(User $user)
    {
        return $user->purchases()->get();
    }

    public function hasExistingPurchase(User $user, $purchasableType, $purchasableId)
    {
        $existingPurchase = $user->purchases()
            ->where('purchasable_type', $purchasableType)
            ->where('purchasable_id', $purchasableId)
            ->exists();

        return $existingPurchase;
    }

    public function createPurchase($user, $purchasableType, $purchasableId, array $data)
    {
        // Create a purchase
        $purchase = $user->purchases()->create([
            'purchasable_type' => $purchasableType,
            'purchasable_id' => $purchasableId,
            'amount' => $data['amount'],
        ]);
    
        // Fire the event, that sending email after signup
        event(new AfterUserPurchasingEvent($user));
        
        // Log the purchase operation using the PurchaseLogger helper
        PurchaseLogger::afterPurchaseOperationLog($purchase);

        return $purchase;
    }
}