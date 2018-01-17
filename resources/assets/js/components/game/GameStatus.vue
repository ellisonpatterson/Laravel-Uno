<template>
    <div class="game-bar bg-light p-2 d-flex justify-content-between align-items-center">
        <div class="status" v-html="game.status_message"></div>

        <div class="action">
            <template v-if="game_user.position == 'creator'">
                <template v-if="game.has_enough_players && game.status == 'pending'">
                    <button class="btn btn-success" v-on:click="updateGameStatus('active')" role="button">Start Game</button>
                </template>

                <template v-if="game.status == 'pending' || game.status == 'active'">
                    <button class="btn btn-danger" v-on:click="updateGameStatus('ended')" role="button">End Game</button>
                </template>
            </template>

            <template v-else-if="game.status == 'pending'">
                <button class="btn btn-warning" v-on:click="updateGameStatus('leave')" role="button">Leave Game</button>
            </template>

            <template v-else-if="!game_user.forfeited && game.status == 'active'">
                <button class="btn btn-danger" v-on:click="updateGameStatus('forfeit')" role="button">Forfeit</button>
            </template>
        </div>
    </div>
</template>

<script>
    export default {
        props: [
            'game',
            'game_user'
        ],

        methods: {
            updateGameStatus(action) {
                axios.post('/games/' + this.game.game_id + '/status', { action: action }).then(response => {
                    this.$emit('gameupdated', {
                        game: response.data.game,
                        game_user: response.data.gameUser
                    });

                    if (action == 'leave') {
                        window.location.href = "/";
                    }
                });
            }
        }
    };
</script>