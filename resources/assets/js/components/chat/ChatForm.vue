<template>
    <div class="chat-form pt-3">
        <div class="input-group">
            <input data-vv-delay="2000" id="btn-input" type="text" name="message" class="form-control" :class="{'input': true, 'is-invalid': errors.has('message') }" placeholder="Type your message here..." aria-label="Type your message here..." autocomplete="off" v-model="newMessage" @keyup.enter="sendMessage">

            <span class="input-group-btn">
                <button class="btn btn-primary" id="btn-chat" @click="sendMessage">Send</button>
            </span>
        </div>
    </div>
</template>

<script>
    export default {
        props: [
            'user',
            'game'
        ],

        data() {
            return {
                newMessage: ''
            }
        },

        methods: {
            sendMessage() {
                this.$validator.validateAll().then((result) => {
                    if (result) {
                        this.$emit('messagesent', {
                            user: this.user,
                            message: this.newMessage
                        });

                        this.newMessage = ''
                    }
                });
            }
        }
    }
</script>