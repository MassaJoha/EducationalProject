<?php

namespace App\Listeners;

use App\Events\FirstEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Kreait\Laravel\Firebase\Facades\Firebase;

class SendInfoForFirebaseDBListener
{
    /**
     * Create the event listener.
     */
    protected $database;

    public function __construct()
    {
        $this->database = app('firebase.database');
    }

    /**
     * Handle the event.
     */
    public function handle(FirstEvent $event): void
    {
        $postData = [
            "message"  => 'Data sent to Firebase DB successfully!!',
        ];

        $this->database->getReference('message')->push($postData);
    }
}
