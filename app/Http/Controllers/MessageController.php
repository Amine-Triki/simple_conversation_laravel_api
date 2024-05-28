<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

use App\Models\userMess;



class MessageController extends Controller
{

    // send message

    public function sendMessage(Request $request, $id)
    {
        $validatedData = $request->validate([
            'text_msg' => 'required|string',
        ]);
        // Verify the existence of the receive
        $receiver = userMess::find($id);
        if (!$receiver) {
            return response()->json(['message' => 'Recipient not found'], 404);
        }
        // Save the message in the database
        $message = Message::create([
            'id_user_sender' => $request->user()->id,
            'id_user_receiver' => $id,
            'text_msg' => $validatedData['text_msg'],
        ]);
        return response()->json(['status' => 'Message has been sent', 'message' => $message], 201);
    }

    //seen message
    function seenMessage(Request $request, $id)
    {
        // Check
        $messages = Message::where('id_user_sender', $id)
            ->where('id_user_receiver', $request->user()->id)
            ->get();

        if ($messages->isEmpty()) {
            return response()->json(['message' => 'No messages found'], 404);
        }

        $allMessagesSeen = $messages->where('etat', 1)->count() === $messages->count();
        if ($allMessagesSeen) {
            return response()->json(['status' => 'Messages have been seen previously'], 200);
        }

        // Update
        $firstUnseenMessage = $messages->where('etat', 0)->first();
        $firstUnseenMessage->etat = 1;
        $firstUnseenMessage->save();

        return response()->json(['status' => 'Message status updated successfully (seen)'], 200);
    }




    public function getallmessages($id)
    {
        $user = auth()->user();
        $receiver = userMess::find($id);
        if (!$receiver) {
            return response()->json(['message' => 'The recipient user does not exist '], 404);
        }
        //get all messages
        $messages = Message::where(function ($query) use ($user, $receiver) {
            $query->where('id_user_sender', $user->id)
                ->where('id_user_receiver', $receiver->id);
        })
            ->orWhere(function ($query) use ($user, $receiver) {
                $query->where('id_user_sender', $receiver->id)
                    ->where('id_user_receiver', $user->id);
            })
            ->orderBy('date_envoie', 'asc')
            ->get();
        return response()->json(['conversations' => $messages], 200);
    }
}
