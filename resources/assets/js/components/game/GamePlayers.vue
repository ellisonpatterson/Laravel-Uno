<template>
    <div class="row players bg-light">
        <div class="col players text-center py-2" :class="game_user.game_user_id != game.active_game_user_id ? 'bg-lightish' : game_user.forfeited ? 'bg-forfeited' : ''" data-toggle="tooltip" data-placement="bottom" :title="game_user.user.username" v-for="game_user in game.game_users">
            <ul class="player list-inline m-0" >
                <img class="avatar-s rounded-circle mr-2" :src="game_user.user.avatar" />

                <template v-if="game.status == 'active' && game_user.hand">
                    <li class="list-inline-item">{{ game_user.hand.card_count}} Card(s)</li>
                </template>
            </ul>
        </div>
    </div>
</template>

<script>
    export default {
        props: [
            'game',
        ]
    };
</script>