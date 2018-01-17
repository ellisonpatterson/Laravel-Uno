@extends('layouts.app')

@section('title')
    {{ $game->title }}
@endsection

@section('main-content-classes', 'px-0')
@section('side-content-classes', 'py-3')

@section('main-content')
    <div class="content-wrapper d-flex flex-column flex-grow">
        <game-players
            :game="game"
        ></game-players>

        <game-arena
            v-on:gameupdated="updateGame"
            :game="game"
            :game_user="game_user"
        ></game-arena>

        <game-cards
            v-on:gameupdated="updateGame"
            :game="game"
            :game_user="game_user"
        ></game-cards>

        <game-status
            v-on:gameupdated="updateGame"
            :game="game"
            :game_user="game_user"
        ></game-status>
    </div>
@endsection

@section('side-content')
    <ul class="nav nav-pills nav-fill chat-tabs">
        <li class="px-0 nav-item">
            <a id="game-chat-tab" class="flex-sm-fill text-sm-center nav-link active" data-game="true" data-toggle="tab" role="tab" aria-controls="game-chat" aria-selected="true" href="#game-chat">Game Chat</a>
        </li>

        <li class="px-0 nav-item">
            <a id="public-chat-tab" class="flex-sm-fill text-sm-center nav-link" data-game="false" data-toggle="tab" role="tab" aria-controls="public-chat" aria-selected="false" href="#public-chat">Public Chat</a>
        </li>
    </ul>

    <div class="side-wrapper d-flex flex-column flex-grow">
        <div class="chat-messages tab-content pt-3 flex-grow">
            <div class="tab-pane fade show active" id="game-chat" role="tabpanel" aria-labelledby="game-chat-tab">
                <chat-messages :messages="gameMessages"></chat-messages>
            </div>

            <div class="tab-pane fade" id="public-chat" role="tabpanel" aria-labelledby="public-chat-tab">
                <chat-messages :messages="publicMessages"></chat-messages>
            </div>
        </div>

        <chat-form
            v-on:messagesent="sendMessage"
            :game="game"
            :user="user"
        ></chat-form>
    </div>
@endsection