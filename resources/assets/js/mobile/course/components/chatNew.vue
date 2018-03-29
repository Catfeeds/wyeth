<!-- components/questionNew.vuex -->

<template>
<div>
    <div class="message-sprite button-bottom" @click="goChat"></div>
    <!-- pop -->
    <div class="pop" :class="{'f-hide': hidePop}">
        <div class="message-sprite pop-message">
            <span class="cancel" @click="cancel">取消</span>
            <span class="send" @click="send">发送</span>
            <textarea v-model="content" id="chatSayText" cols="50" rows="5"
                onfocus="if(value=='我觉得你说的很有道理····'){value=''}"
                >我觉得你说的很有道理····</textarea>
            <div class="message-sprite button-at" @click="onButtonAtClick"></div>
            <div class="message-sprite button-at-name">{{atUserName}}</div>
            <div class="message-sprite button-face" :class="[hideFaceBox ? '' : 'keyboard']" @click="onButtonFaceClick"></div>
        </div>
        <div class="pop-at-box" :class="{'f-hide': hideAt}">
            <ul>
                <chat-at-item v-for="user in useAtUsers" :user="user" :index="$index" track-by="$index"></chat-at-item>
            </ul>
        </div>
        <div class="arrow-up-at" :class="{'f-hide': hideAt}"></div>
        <div class="pop-face-box" :class="{'f-hide': hideFaceBox}"></div>
        <div class="arrow-up-face" :class="{'f-hide': hideFaceBox}"></div>
    </div>
    <!-- end -->
    <div class="message-sprite tip-success" :class="{'f-hide': tipSuccessHide}"></div>
</div>
</template>

<script>
_.remove = require('lodash/remove');
_.reverse = require('lodash/reverse');
export default {
    atUserDefault () {
        return {uid: 0, name: '', avatar: ''};
    },
    props: {
        atUser: {
            type: Object,
            default (){
              return this.defaultAtUser;
            }
        },
        // 是否隐藏
        hidePop: {
            type: Boolean,
            default: true,
            twoWay: true
        }
    },
    data (){
        return {
            atUsers: [],
            useAtUsers: [],
            hideFaceBox: true,
            hideAt: true,
            class: {
                keyboard: false
            },
            // 提示成功
            tipSuccessHide: true,
            // message
            content: '',
            img: {
                tmp: `${staticUrl}/mobile/boardcast/img/temp.jpg`
            },
            defaultAtUser: {uid: 0, name: '', avatar: ''}
        }
    },
    computed: {
        // at人的名子重新处理
        atUserName () {
            let name = '';
            if (this.atUser) {
                name = this.atUser.name;
                if(this.atUser.name == 'Miss惠') {
                    name = vueEnv.chatAnchor.name;
                }
            }
            return name;
        }
    },
    methods: {
        goChat (){
            this.hidePop = false;
            this.atUser = this.defaultAtUser;
            this.$nextTick(function () {
                $('#chatSayText').focus();
            });
        },
        cancel (){
            this.hidePop = true;
            this.hideFaceBox = true;
            this.content = '';
        },
        send (){
            var self = this;

            if (!this.content) {
                // alert('请填写内容');
                return false;
            }
            var message = {};
            message.type = 'say';
            message.cid = cid;
            message.author_id = uid;
            message.user_type = userType;
            // 聊天普通用户的
            message.message_type = 'chatUser';
            message.content = this.content;
            message.source_id = 0;
            message.source_author_id = 0;
            if (this.atUser.uid ) {
                message.content = `@${this.atUser.name} ${this.content}`;
                message.source_author_id = this.atUser.uid;
            }
            manager.sendSocketData(message);
            this.hidePop = true;
            this.tipSuccessHide = false;
            setTimeout(function(){
                self.tipSuccessHide = true;
                self.content = '';
            }, 1000);

            // 记录atUsers
            if (this.atUser && this.atUser.uid && this.atUser.uid != vueEnv.chatAnchor.uid && this.atUser.name != '主持人') {
                _.remove(this.atUsers, function (user) {
                    return user.uid == self.atUser.uid;
                });
                this.atUsers.push(this.atUser);
            }
        },
        // 表情
        onButtonFaceClick() {
            $('#chatSayText').focus();
            if (this.hideFaceBox) {
                this.hideFaceBox = false;
                this.hideAt = true;
            } else {
                this.hideFaceBox = true;
            }
        },
        // AT
        onButtonAtClick () {
            this.hideFaceBox = true;
            if(this.hideAt) {
                this.hideAt = false;
            } else {
                this.hideAt = true;
            }
        }
    },
    ready () {
        var self = this;
        // 表情
        $('.button-face').qqFace({
            id : 'facebox',
            assign: 'chatSayText',
            path: `${staticUrl}/mobile/boardcast/img/emoticon/` //表情存放的路径
        });
    },
    components: {
        'chat-at-item': require('./chatAtItem.vue')
    },
    events: {
        chatAtItemClick (user) {
            this.atUser = user;
        }
    },
    watch: {
        'hidePop': function (newValue, oldValue) {
            if (newValue == false) {
                if (this.atUsers.length) {
                    this.useAtUsers = _.concat([vueEnv.chatAnchor], this.atUsers.slice(-3));
                } else {
                    this.useAtUsers = [vueEnv.chatAnchor];
                }
            }
        }
    }
}
</script>
