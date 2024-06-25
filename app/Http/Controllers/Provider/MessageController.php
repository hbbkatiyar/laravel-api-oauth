<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;
use GuzzleHttp\Client;

class MessageController extends Controller
{
    public function index()
    {
        $messages = Message::get();

        return response()->json($messages);
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'recipient' => 'required|string',
            'message' => 'required|string',
        ]);

        $recipient = $request->input('recipient');
        $messageContent = $request->input('message');

        $client = new Client();
        $response = $client->post(env('VALUEFIRST_API_URL'), [
            'auth' => [env('VALUEFIRST_USERNAME'), env('VALUEFIRST_PASSWORD')],
            'json' => [
                'recipient' => $recipient,
                'message' => $messageContent,
            ],
        ]);

        if ($response->getStatusCode() == 200) {
            $message = new Message();
            $message->recipient = $recipient;
            $message->message = $messageContent;
            $message->save();

            return response()->json(['message' => 'Message sent successfully!']);
        }

        return response()->json(['error' => 'Failed to send message.'], 500);
    }
}



