<!-- components/chatItem.vue -->

<template>
    <div class="message system" v-if="showSystemPresentFlower">
        <div class="content">
            <div class="plain">
                <pre>{{item.name}}  刚刚向讲师献了一朵花。<span class="text-flower" @tap="onClickPresentFlower">我也献花</span></pre>
            </div>
        </div>
    </div>
    <div class="message system" v-if="showQuestion">
        <div class="content">
            <div class="plain">
                <pre>{{item.name}}  刚刚向讲师提出了一个问题。<span class="text-ask" @tap="onClickQuestion">我也提问</span></pre>
            </div>
        </div>
    </div>
    <template v-if="showChatUser">
        <div class="message" :class="{'me': class.me}" v-if="me" message-id="{{item.message_id}}">
            <img class="avatar" :src="item.avatar" />
            <div class="content">
                <div class="nickname"><span class="time">{{timeShow}}</span>我</div>
                <div class="bubble bubble-primary right">
                    <div class="bubble-cont">
                        <div class="plain">
                            <pre>{{{content|qqface|at}}}</pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="message" :class="{'me': class.me}" v-else message-id="{{item.message_id}}">
            <img class="avatar" :src="item.avatar" />
            <div class="content">
                <div class="nickname">{{item.name}}<span class="time">{{timeShow}}</span></div>
                <div>
                    <div class="bubble bubble-default left" style="float: left;">
                        <div class="bubble-cont">
                            <div class="plain">
                                <pre>{{{content|qqface}}}</pre>
                            </div>
                        </div>
                    </div>
                    <div style="float: left;line-height:40px;margin-left: 10pt;" @tap="onButtonAtClick">
                        <span class="badge_reply">回复</span>
                    </div>
                </div>
            </div>
        </div>
    </template>


</template>

<script>
module.exports = {
    props: ['item', 'index'],
    data () {
        let me = (this.item.user_id == uid);
        let timeShow = this.item.time.substr(-8);
        // 是否是送花
        let showSystemPresentFlower = false;
        // 是否是提问
        let showQuestion = false;
        // 是否是普通聊天
        let showChatUser = false;
        if (this.item.message_type == 'presentFlower') {
            showSystemPresentFlower = true;
        } else if (this.item.message_type == 'question') {
            showQuestion = true;
        } else {
            showChatUser = true;
        }
        return {
            me,
            timeShow,
            showSystemPresentFlower,
            showQuestion,
            showChatUser,
            content: _.escape(this.item.content),
            class: {me}
        };
    },
    ready: function () {
    },
    methods: {
        onClickQuestion (){
            this.$dispatch('goQuestion');
        },
        onClickPresentFlower () {
            this.$dispatch('presentFlower');
        },
        // 回复
        onButtonAtClick () {
            this.$dispatch('chatListButtonReply', {uid: this.item.user_id, name: this.item.name, avatar: this.item.avatar});
        }
    }
};
</script>
