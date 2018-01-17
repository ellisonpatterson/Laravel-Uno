@extends('layouts.app')

@section('title', 'Dashboard')

@section('main-content-classes', 'py-3')
@section('side-content-classes', 'py-3')

@section('main-content')
    <games
        v-on:gamecreated="gameCreated"
        :user="user"
        :available_games="available_games"
        :participating_games="participating_games"
        :active_created_game="active_created_game"
        :previous_games="previous_games"
    ></games>
@endsection

@section('side-content')
    <div class="chat-wrapper d-flex flex-column flex-grow">
        <div class="chat-messages flex-grow">
            <chat-messages :messages="publicMessages"></chat-messages>
        </div>

        @if (Auth::check())
            <chat-form
                v-on:messagesent="sendMessage"
                :user="user"
            ></chat-form>
        @endif
    </div>
@endsection