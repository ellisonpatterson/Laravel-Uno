require('./routes');
require('./bootstrap');

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

window.loadedTabs = [];
$(document).on('click', '.tab-ajax', function(e) {
    e.preventDefault();
    var url = $(this).attr('data-url');
    var params = callFunctionIfExists($(this).attr('data-params'));

    if ($.inArray(this.hash, window.loadedTabs) === -1 && typeof url !== 'undefined') {
        var pane = $(this), href = this.hash;

        $.ajax({
            url: url,
            cache: true,
            data: params
        }).done(function(response) {
            $(href).append(response);
        });

        window.loadedTabs.push(href);
        pane.tab('show');
    } else {
        $(this).tab('show');
    }
});

$(function () {
    $('[data-toggle="tooltip"]').tooltip();
});

window.Vue = require('vue');
Vue.use(require('vee-validate'));

Vue.component('chat-messages', require('./components/chat/ChatMessages.vue'));
Vue.component('chat-form', require('./components/chat/ChatForm.vue'));
Vue.component('game-status', require('./components/game/GameStatus.vue'));
Vue.component('game-players', require('./components/game/GamePlayers.vue'));
Vue.component('game-cards', require('./components/game/GameCards.vue'));
Vue.component('game-arena', require('./components/game/GameArena.vue'));
Vue.component('games', require('./components/game/Games.vue'));

var app = new Vue({
    el: '#main',

    data: {
        user: window.user || {},
        game: window.game || {},
        game_user: window.gameUser || {},

        available_games: [],
        participating_games: [],
        active_created_game: {},
        previous_games: [],

        gameMessages: [],
        publicMessages: [],
    },

    created() {
        if (_.has(this.game, 'game_id')) {
            Echo.private('chat.' + this.game.game_id).listen('Chat.MessageSent', (e) => {
                e.message.user = e.user;
                this.gameMessages.unshift(e.message);
            });
        }

        Echo.channel('chat').listen('Chat.MessageSent', (e) => {
            e.message.user = e.user;
            this.publicMessages.unshift(e.message);
        });

        if (!_.has(this.game, 'game_id')) {
            Echo.channel('dashboard').listen('Game.DashboardUpdate', (e) => {
                this.updateAvailableGames(e.availableGames);
            });

            this.fetchGames();
        } else {
            Echo.private('game.' + this.game_user.game_user_id).listen('Game.GameUpdate', (e) => {
                this.updateGame({
                    game: e.game,
                    game_user: e.gameUser
                });
            });
        }

        this.fetchMessages();
    },

    methods: {
        fetchMessages() {
            axios.get('/chat/messages/' + (_.has(this.game, 'game_id') ? this.game.game_id : '')).then(response => {
                $.each(response.data, function(key, message) {
                    if (message.game_id == null) {
                        app.publicMessages.unshift(message);
                    } else {
                        app.gameMessages.unshift(message);
                    }
                });
            });
        },

        sendMessage(message) {
            if ($('.chat-tabs a.active').attr('data-game') == 'true') {
                var game_id = this.game.game_id;
            }

            axios.post('/chat/messages/' + (game_id !== undefined ? game_id : ''), message).then(response => {
                if (game_id) {
                    this.gameMessages.unshift(response.data);
                } else {
                    this.publicMessages.unshift(response.data);
                }
            });
        },

        updateAvailableGames(availableGames) {
            this.available_games = availableGames;
        },

        fetchGames() {
            axios.get('/games').then(response => {
                this.available_games = response.data.availableGames;
                this.participating_games = response.data.participatingGames;
                this.active_created_game = response.data.activeCreatedGame;
                this.previous_games = response.data.previousGames;
            });
        },

        gameCreated(game) {
            this.active_created_game = game;
            this.participating_games.unshift(game);
        },

        updateGame(gameData) {
            if (_.has(gameData, 'game')) {
                this.game = gameData.game;
            }

            if (_.has(gameData, 'game_user')) {
                this.game_user = gameData.game_user;
            }
        }
    }
});