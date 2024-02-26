<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;

class PurchaseLogger
{
    public static function afterPurchaseOperationLog($purchaseData)
    {
        Log::info('Purchase operation logged', ['data' => $purchaseData]);

        // Custome Log for Purchase Operation:
        $logMessage = 'Purchase operation logged: ' . json_encode($purchaseData);
        Log::channel('purchaselog')->info($logMessage);
    }
}