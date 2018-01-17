<template>
    <div class="game-cards bg-light d-flex flex-row justify-content-center align-items-center" v-if="game.status == 'active' && game_user.hand && !game_user.forfeited">
        <div class="col px-0 py-2 h-100" v-for="card in game_user.hand.cards" v-on:click="performTurn(card.card_id)" v-html="card.element"></div>
    </div>
</template>

<script>
    export default {
        props: [
            'game',
            'game_user'
        ],

        methods: {
            performTurn(cardId) {
                axios.post('/games/' + this.game.game_id + '/turn', { card_id: cardId }).then(response => {
                    this.$emit('gameupdated', {
                        game: response.data.game,
                        game_user: response.data.gameUser
                    });
                });
            }
        }
    };
</script>