<?php

namespace App\Http\Controllers\Chat;

use App\Models\Chat\Message;
use App\Models\Game\Game;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\Chat\SendMessage;
use App\Events\Chat\MessageSent;
use Exception;

class ChatController extends Controller
{
    public function fetchMessages($gameId = false)
    {
        $message = Message::with('user')->where('game_id', null);
        if ($gameId && Auth::check()) {
            $message->orWhere('game_id', $gameId);
        }

        return $message->orderBy('created_at', 'asc')->get();
    }

    public function sendMessage(SendMessage $request)
    {
        $user = Auth::user();

        if (!empty($request->game_id)) {
            $game = Game::where('game_id', $request->game_id)
            ->whereHas('gameUsers', function($query) use($user) {
                return $query->where('user_id', $user->user_id);
            })->firstOrFail();

            if ($game->status == Game::ENDED_STATUS || $game->status == Game::COMPLETE_STATUS) {
                throw new Exception('The game can no longer accept messages.');
            }
        }

        $message = Message::create([
            'message' => $request->message,
            'game_id' => $request->game_id,
            'user_id' => $user->user_id
        ]);

        broadcast(new MessageSent($message, $user))->toOthers();

        return response()->json($message->toArray() + ['user' => $user->toArray()]);
    }
}