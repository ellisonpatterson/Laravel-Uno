<template>
    <div class="arena d-flex flex-row flex-grow justify-content-between p-3">
        <template v-if="game.status == 'active'">
            <div class="d-flex justify-content-start" v-if="!game_user.forfeited">

            </div>

            <div class="d-flex justify-content-end" v-if="!game_user.forfeited">
                <div class="deck" v-html="game.top_card != null ? game.top_card.element : ''" v-on:click="performDraw()"></div>
            </div>
        </template>

        <template v-else>
        
        </template>
    </div>
</template>

<script>
    export default {
        props: [
            'game',
            'game_user'
        ],

        methods: {
            performDraw() {
                axios.post('/games/' + this.game.game_id + '/draw').then(response => {
                    this.$emit('gameupdated', {
                        game: response.data.game,
                        game_user: response.data.gameUser
                    });
                });
            }
        }
    };
</script>