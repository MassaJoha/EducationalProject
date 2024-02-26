<?php

namespace App\Listeners;

use App\Events\AfterUserLoginOrSignupEvent;
use App\Events\AfterUserPurchasingEvent;
use App\Mail\PurchaseEmail;
use App\Mail\WelcomeEmail;
use Illuminate\Events\Dispatcher;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SubscribeListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    public function handlePurchasingOperation(AfterUserPurchasingEvent $event): void{
          // Get the user from the event
          $user = $event->user;

          // Send the email to the user
          Mail::to($user->email)->send(new PurchaseEmail($user));
    }

    public function handleLoginOrSignupOperation(AfterUserLoginOrSignupEvent $event): void
    {
        // Get the user from the event
        $otpAttemp = $event->otpAttemp;

        // Send the email to the user
        Mail::to($otpAttemp->email)->send(new WelcomeEmail($otpAttemp));
    }

    public function subscribe(Dispatcher $events): void
    {
        $events->listen(
            AfterUserPurchasingEvent::class,
            [SubscribeListener::class, 'handlePurchasingOperation']
        );
 
        $events->listen(
            AfterUserLoginOrSignupEvent::class,
            [SubscribeListener::class, 'handleLoginOrSignupOperation']
        );
    }
}
