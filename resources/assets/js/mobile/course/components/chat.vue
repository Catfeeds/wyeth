<!-- components/chat.vue -->

<template>
    <chat-list></chat-list>
    <chat-new :at-user="atUser" :hide-pop.sync="hideNewPop"></chat-new>
</template>

<script>
module.exports = {
    data() {
        return {
            atUser: {},
            hideNewPop: true
        };
    },
    components: {
        'chat-list': require('./chatList.vue'),
        'chat-new': require('./chatNew.vue')
    },
    events: {
        chatListButtonReply: function (user) {
            this.atUser = user;
            this.hideNewPop = false;
        }
    }
};
</script>
