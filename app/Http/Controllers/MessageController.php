<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\ResponseFactory;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Message;
use App\User;

use App\Events\SeenMessage;
use App\Events\NewMessage;

use Carbon\Carbon;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Shows user messages.
     *
     * @return Response
     */
    public function show_messages() {
        $allPreviews = $this->getPreviews();

        return view('pages.messages', ['user' => Auth::user(), 'previews' => $allPreviews, 'selectedUser' => null]);
    }

    public function show_conversation($id) {
        $allPreviews = $this->getPreviews();

        $selectedUser = User::findOrFail($id);

        $selectedUserMessages = $this->get_user_messages($id);

        return view('pages.messages', ['user' => Auth::user(), 'previews' => $allPreviews, 'selectedUser' => $selectedUser, 'selectedUserMessages' => $selectedUserMessages]);
    }

    public function get_user_messages($id) {
        $user_id = Auth::user()->user_id;
        $other_id = $id;
        return Message::where('sender_id', $user_id)->where('receiver_id', $other_id)
            ->orWhere('sender_id', $other_id)->where('receiver_id', $user_id)
            ->orderBy('date_sent')
            ->get();
    }

    public function send_message(Request $request, $user_id) {
        $request->validate([
            'content' => 'required'
        ]);

        User::findOrFail($user_id);

        $message = new Message();
        $message->sender_id = Auth::user()->user_id;
        $message->receiver_id = $user_id;
        $message->date_sent = Carbon::now();
        $message->content = $request->input('content');
        $message->seen = false;

        $this->authorize('send', $message);
        $message->save();

        $message->author;
        $message->receiver;
        broadcast(new NewMessage($message))->toOthers();

        return response()->json($message);
    }

    public function delete_message(Request $request, $message_id) {
        $message = Message::findOrFail($message_id);
        $this->authorize('delete', $message);
        $message->delete();

        return response()->json($message);
    }

    public function see_messages($other_id) {
        $own_id = Auth::user()->user_id;

        Message::where('sender_id', '=', $other_id)
            ->where('receiver_id', '=', $own_id)
            ->update(['seen' => true]);

        broadcast(new SeenMessage($own_id, $other_id))->toOthers();

        return;
    }

    private function getPreviews() {
        $previews = DB::select(
            'SELECT sender_id, receiver_id, message.date_sent, content, seen 
                FROM 
                    (SELECT max(date_sent) AS date_sent, user_id 
                    FROM 
                        (SELECT max(date_sent) AS date_sent, sender_id AS user_id 
                        FROM message 
                        WHERE receiver_id = :user_id 
                        GROUP BY sender_id 
                        UNION 
                        SELECT max(date_sent) AS date_sent, receiver_id AS user_id 
                        FROM message WHERE sender_id = :user_id 
                        GROUP BY receiver_id) 
                        AS conversations 
                        GROUP BY user_id) 
                    AS conversations 
                    JOIN message ON 
                        (conversations.date_sent = message.date_sent 
                        AND (sender_id = user_id AND receiver_id = :user_id OR sender_id = :user_id 
                        AND receiver_id = user_id)) 
                ORDER BY conversations.date_sent DESC'
            , ['user_id' => Auth::user()->user_id]);

        $previews = Message::hydrate($previews);

        return $previews;
    }
}