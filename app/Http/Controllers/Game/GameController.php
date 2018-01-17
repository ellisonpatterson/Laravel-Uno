<?php

namespace App\Http\Controllers\Game;

use App\Models\Chat\Room;
use App\Models\Game\Game;
use App\Models\Game\GameUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\CreateGame;
use App\Http\Requests\Game\GamePassword;
use App\Http\Requests\Game\UpdateStatus;
use App\Http\Requests\Game\PerformTurn;
use App\Events\Game\GameUpdated;
use App\Exceptions\GeneralException;
use JavaScript;
use GameEngine;

class GameController extends Controller
{
    public function __construct()
    {
        $this->middleware('loggedIn', ['only' => ['view', 'createGame']]);
    }

    public function view($gameId)
    {
        $user = Auth::user();
        $gameEngine = GameEngine::create($gameId);

        if (!$gameEngine->gameUser && $gameEngine->game->status == Game::PENDING_STATUS) {
            if ($gameEngine->game->scope == Game::PUBLIC_SCOPE) {
                $gameEngine->game->gameUsers()->create([
                    'game_id' => $gameEngine->game->game_id,
                    'user_id' => $user->user_id,
                    'order' => ($gameEngine->game->gameUsers()->count() + 1) * 10
                ]);

                $gameEngine->refreshRelations();
                $gameEngine->findGameUser();

                $gameEngine->events->gameUpdate();
            }
        }

        $gameEngine->finalize();

        JavaScript::put([
            'game' => $gameEngine->game,
            'gameUser' => $gameEngine->gameUser,
            'user' => $user
        ]);

        return response()->view('game.view', [
            'game' => $gameEngine->game,
            'gameUser' => $gameEngine->gameUser
        ]);
    }

    public function status($gameId, UpdateStatus $request)
    {
        $user = Auth::user();
        $gameEngine = GameEngine::create($gameId);
        $gameEngine->manager()->updateStatus($request->action);

        $gameEngine->finalize();
        $gameEngine->events->gameUpdate();

        return response()->json([
            'game' => $gameEngine->game,
            'gameUser' => $gameEngine->gameUser
        ]);
    }

    public function turn($gameId, PerformTurn $request)
    {
        $gameEngine = GameEngine::create($gameId);

        if ($gameEngine->game->status == Game::PENDING_STATUS) {
            throw new GeneralException('You cannot perform a turn until the game begins.');
        }

        if ($gameEngine->game->status != Game::ACTIVE_STATUS) {
            throw new GeneralException('You cannot perform a turn on an inactive game.');
        }

        if ($gameEngine->gameUser->forfeited) {
            throw new GeneralException('You forfeited and can no longer play.');
        }

        if (!$gameEngine->manager->isTurn()) {
            throw new GeneralException('It is not your turn.');
        }

        if (!$gameEngine->hand->checkIfCardInHand($request->card_id)) {
            throw new GeneralException('The specified card is not in your hand.');
        }

        if (!$gameEngine->discard->isCardValidOnTurn($request->card_id)) {
            throw new GeneralException('You cannot play that card.');
        }

        $gameEngine->hand->removeCardFromHand($request->card_id);
        $gameEngine->card->performCardTypeAction($request->card_id);
        $gameEngine->discard->addTopCard($request->card_id);

        if ($gameEngine->manager->isEndingTurn()) {
            $gameEngine->manager->completeGame();
        } else {
            $gameEngine->manager->gotoNextUser();
        }

        $gameEngine->finalize();
        $gameEngine->events->gameUpdate();

        return response()->json([
            'game' => $gameEngine->game,
            'gameUser' => $gameEngine->gameUser
        ]);
    }

    public function draw($gameId)
    {
        $gameEngine = GameEngine::create($gameId);

        if ($gameEngine->game->status == Game::PENDING_STATUS) {
            throw new GeneralException('You cannot draw until the game begins.');
        }

        if ($gameEngine->game->status != Game::ACTIVE_STATUS) {
            throw new GeneralException('You cannot draw on an inactive game.');
        }

        if (!$gameEngine->manager->isTurn()) {
            throw new GeneralException('It is not your turn.');
        }

        if ($gameEngine->discard->anyCardsValidOnTurn($gameEngine->gameUser->hand->cards)->count()) {
            throw new GeneralException('You have playable cards.');
        }

        $topCard = $gameEngine->draw->removeTopCard();
        $gameEngine->hand->addCardToHand($topCard);

        if (!$drawValidCard = $gameEngine->discard->isCardValidOnTurn($topCard)) {
            $gameEngine->manager->gotoNextUser();
        }

        $gameEngine->finalize();

        if (!$drawValidCard) {
            $gameEngine->events->gameUpdate();            
        }

        return response()->json([
            'game' => $gameEngine->game,
            'gameUser' => $gameEngine->gameUser
        ]);
    }

    public function fetchGames()
    {
        $gameEngine = GameEngine::games();

        return response()->json([
            'availableGames' => $gameEngine->fetchAvailableGames(),
            'participatingGames' => $gameEngine->fetchParticipatingGames(),
            'activeCreatedGame' => $gameEngine->fetchIfHasActiveOwnedGames(),
            'previousGames' => $gameEngine->fetchPreviousGames()
        ]);
    }

    public function createGame(CreateGame $request)
    {
        GameEngine::manager()->createGame(
            $request->title,
            $request->scope,
            $request->password
        );

        GameEngine::finalize();
        GameEngine::events()->dashboardUpdate();

        return response()->json(GameEngine::game());
    }
}