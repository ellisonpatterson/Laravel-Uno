<template>
    <ul class="chat">
        <li class="clearfix" v-for="message in messages">
            <div class="chat-avatar clearfix">
                <img class="avatar-m rounded-circle" :src="message.user.avatar" />
            </div>

            <div class="chat-body clearfix">
                <div class="header">
                    <strong>
                        {{ message.user.username }}
                    </strong>
                    <span class="float-right">#{{ message.chat_message_id }}</span>
                </div>
                <p>
                    {{ message.message }}
                </p>
            </div>
        </li>
    </ul>
</template>

<script>
    export default {
        props: ['messages']
    };
</script>