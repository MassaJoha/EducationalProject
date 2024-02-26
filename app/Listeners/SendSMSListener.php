<?php

namespace App\Listeners;

use App\Events\FirstEvent;
use GuzzleHttp\Client;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendSMSListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(FirstEvent $event)
    {
        $client = new Client();

        $response = $client->post('https://jsonplaceholder.typicode.com/posts', [
            'json' => [
                'title'  => $event->title,
                'body'   => $event->body,
                'userId' => $event->userId,
            ],
        ]);

        // Process the response       
        $statusCode = $response->getStatusCode();
        $responseData = $response->getBody()->getContents();

        return response()->json([
            'status' => $statusCode,
            'data' => $responseData,
        ]);
    }
}
