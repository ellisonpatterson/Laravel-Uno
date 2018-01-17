<template>
    <div class="games">
        <template v-if="user.user_id">
            <ul class="nav nav-pills nav-fill game-tabs">
                <li class="px-0 nav-item">
                    <a id="available-tab" class="flex-sm-fill text-sm-center nav-link active" data-toggle="tab" role="tab" aria-controls="available" aria-selected="true" href="#available">Available</a>
                </li>

                <li class="px-0 nav-item">
                    <a id="participating-tab" class="flex-sm-fill text-sm-center nav-link" data-toggle="tab" role="tab" aria-controls="participating" aria-selected="false" href="#participating">Participating</a>
                </li>

                <li class="px-0 nav-item">
                    <a id="history-tab" class="flex-sm-fill text-sm-center nav-link" data-toggle="tab" role="tab" aria-controls="history" aria-selected="false" href="#history">History</a>
                </li>

                <li class="px-0 nav-item" v-if="!active_created_game">
                    <a id="create-tab" class="flex-sm-fill text-sm-center nav-link" data-toggle="tab" role="tab" aria-controls="history" aria-selected="false" href="#create">Create</a>
                </li>
            </ul>

            <div class="tab-content py-3">
                <div class="tab-pane fade show active" id="available" role="tabpanel" aria-labelledby="available-tab">
                    <template v-if="available_games.length">
                        <ul class="list-group">
                            <li class="list-group-item list-group-item-action p-0" v-for="available_game in available_games">
                                <a :href="'/games/' + available_game.game_id" class="no-hover">
                                    <div class="card border-0 bg-0">
                                        <div class="card-body">
                                            <h3 class="card-title">
                                                {{ available_game.title }}
                                            </h3>
                                            <h6 class="card-subtitle mb-2 text-muted">Created {{ available_game.created_at }}</h6>
                                            <div class="card-footer border-0 bg-0 p-0 text-muted">
                                                <ul class="users list-group flex-row align-items-center bg-0">
                                                    <li class="list-group-item p-0 mr-1 border-0 bg-0" data-toggle="tooltip" :title="game_user.user.username" v-for="game_user in available_game.game_users">
                                                        <img class="avatar-s rounded-circle" :src="game_user.user.avatar" />
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </template>

                    <template v-else>
                        <p class="lead">There are no games currently available.</p>
                    </template>
                </div>

                <div class="tab-pane fade" id="participating" role="tabpanel" aria-labelledby="participating-tab">
                    <template v-if="participating_games.length">
                        <ul class="list-group">
                            <li class="list-group-item list-group-item-action p-0" v-for="participating_game in participating_games">
                                <a :href="'/games/' + participating_game.game_id" class="no-hover">
                                    <div class="card border-0 bg-0">
                                        <div class="card-body">
                                            <h3 class="card-title">
                                                {{ participating_game.title }}
                                            </h3>
                                            <h6 class="card-subtitle mb-2 text-muted">Created {{ participating_game.created_at }}</h6>
                                            <div class="card-footer border-0 bg-0 p-0 text-muted">
                                                <ul class="users list-group flex-row align-items-center bg-0">
                                                    <li class="list-group-item p-0 mr-1 border-0 bg-0" data-toggle="tooltip" :title="game_user.user.username" v-for="game_user in participating_game.game_users">
                                                        <img class="avatar-s rounded-circle" :src="game_user.user.avatar" />
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </template>

                    <template v-else>
                        <p class="lead">You are not currently participating in any games.</p>
                    </template>
                </div>

                <div class="tab-pane fade" id="history" role="tabpanel" aria-labelledby="history-tab">
                    <template v-if="previous_games.length">
                        <ul class="list-group">
                            <li class="list-group-item list-group-item-action p-0" v-for="previous_game in previous_games">
                                <a :href="'/games/' + previous_game.game_id" class="no-hover">
                                    <div class="card border-0 bg-0">
                                        <div class="card-body">
                                            <h3 class="card-title">
                                                {{ previous_game.title }}
                                            </h3>
                                            <h6 class="card-subtitle mb-2 text-muted">Created {{ previous_game.created_at }}</h6>
                                            <div class="card-footer border-0 bg-0 p-0 text-muted">
                                                <ul class="users list-group flex-row align-items-center bg-0">
                                                    <li class="list-group-item p-0 mr-1 border-0 bg-0" data-toggle="tooltip" :title="game_user.user.username" v-for="game_user in previous_game.game_users">
                                                        <img class="avatar-s rounded-circle" :src="game_user.user.avatar" />
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </template>

                    <template v-else>
                        <p class="lead">You have not finished any games yet.</p>
                    </template>
                </div>

                <div class="tab-pane fade" id="create" role="tabpanel" aria-labelledby="create-tab" v-if="!active_created_game">
                    <form method="post" action="/games" @submit.prevent="createGame">
                        <div class="row">
                             <div class="col-md-6 mb-3">
                                <label for="title">Title</label>
                                <input class="form-control" type="text" name="title" placeholder="Title" v-validate="'required|min:3|max:191'" :class="{'input': true, 'is-invalid': errors.has('title') }" v-model="create_game.title">
                                <div v-show="errors.has('title')" class="invalid-feedback">{{ errors.first('title') }}</div>
                            </div>

                             <div class="col-md-6 mb-3">
                                <label for="scope">Scope</label>
                                <select id="scope" name="scope" class="form-control" :class="{'input': true, 'is-invalid': errors.has('scope') }" v-validate="'required'" v-model="create_game.scope">
                                    <option value="public">Public</option>
                                </select>
                                <div v-show="errors.has('scope')" class="invalid-feedback">{{ errors.first('scope') }}</div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Create Game</button>
                    </form>
                </div>
            </div>
        </template>

        <template v-else>
            <template v-if="available_games.length">
                <ul class="list-group">
                    <li class="list-group-item list-group-item-action p-0" v-for="available_game in available_games">
                        <a :href="'/games/' + available_game.game_id" class="no-hover">
                            <div class="card border-0 bg-0">
                                <div class="card-body">
                                    <h3 class="card-title">
                                        {{ available_game.title }}
                                    </h3>
                                    <h6 class="card-subtitle mb-2 text-muted">Created {{ available_game.created_at }}</h6>
                                    <div class="card-footer border-0 bg-0 p-0 text-muted">
                                        <ul class="users list-group flex-row align-items-end bg-0">
                                            <li class="list-group-item p-0 border-0 bg-0" data-toggle="tooltip" :title="game_user.user.username" v-for="game_user in available_game.game_users">
                                                <img class="avatar-s rounded-circle" :src="game_user.user.avatar" />
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </li>
                </ul>
            </template>

            <template v-else>
                <p class="lead">There are no games currently available.</p>
            </template>
        </template>
    </div>
</template>

<script>
    export default {
        props: [
            'user',
            'available_games',
            'participating_games',
            'active_created_game',
            'previous_games'
        ],

        data() {
            return {
                create_game: {
                    title: '',
                    scope: ''
                }
            }
        },

        methods: {
            createGame() {
                this.$validator.validateAll().then((result) => {
                    if (result) {
                        axios.post('/games', this.create_game).then(response => {
                            this.$emit('gamecreated', response.data);
                            $('#participating-tab').trigger('click');
                        });
                    }
                });
            }
        }
    };
</script>