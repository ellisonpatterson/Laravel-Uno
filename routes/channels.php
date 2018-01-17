<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('chat', function($user) {
    return true;
});

Broadcast::channel('chat.{game_id}', function($user, $gameId) {
    return !empty($user) && \App\Models\Game\GameUser::where([
        ['user_id', '=', $user->user_id],
        ['game_id', '=', $gameId]
    ])->firstOrFail();
});

Broadcast::channel('game.{game_user_id}', function($user, $gameUserId) {
    return !empty($user) && \App\Models\Game\GameUser::where([
        ['game_user_id', '=', $gameUserId],
        ['user_id', '=', $user->user_id],
    ])->firstOrFail();
});

Broadcast::channel('dashboard', function($user) {
    return true;
});