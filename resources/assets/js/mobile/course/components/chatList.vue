<!-- components/questionList.vue -->

<template>
    <div id="message-container" class="box chat">
        <div class="box-bd">
            <chat-item v-for="item in items" :item="item" :index="$index"></chat-item>
        </div>
    </div>
    <div class="message-sprite tip-refresh" :class="{'f-hide': hide.refresh}"></div>
    <div class="message-sprite tip-upload" :class="{'f-hide': hide.upload}"></div>
</template>

<script>
module.exports = {
    data () {
        return {
            currentUid: uid,
            items: [],
            // 最小的messageid
            minMessageid: 0,
            hasNextPage: true,
            hide: {
                refresh: true,
                upload: true
            },
            state: {
                // 是否获取 ajax数据中
                refresh: false
            },
            scroll: null,
            // 最后接收问题的时间
            lastQuestionDate: new Date(null),
            // 最后接收系统消息送花的时间
            lastsPresentFlowerDate: new Date(null)
        };
    },
    ready () {
        var self = this;
        this.fetchItems();

        // 留言页兼容代码
        $('.page-message').height($(window).height() - 78);
        $('#message-container').height($(window).height() - 78 - 85 - 62);

        // 绑定socket事件
        $(document).on('ws.message.say.chatUser', this.onMessageSayChatUser);
        // 绑定 有人提问
        $(document).on('ws.message.say.question', this.onMessageSayQuestion);

        // 绑定 有人送花
        $(document).on('ws.message.say.presentFlower', this.onMessageSayPresentFlower);

        // 绑定留言讨论打开事件
        $(document).on('main.chat.show', this.onMainChatShow);

    },
    methods: {
        onMainChatShow () {
            var scroll = this.scroll;
            scroll.refresh();
            this.scrollToEnd(scroll);
        },
        onMessageSayChatUser(e, data) {
            this.items.push(data);
            this.scrollToEndOnEnd();

        },
        onMessageSayQuestion(e, data) {
            let canPush = false;
            let nowDate = new Date();
            if (data.user_id == uid) {
                canPush = true;
            } else if (!this.lastQuestionDate.getTime() || (nowDate.getTime() - this.lastQuestionDate.getTime()) > 60000) {
                // 1分钟内只接收一次
                canPush = true;
            }
            canPush = true;
            if (canPush) {
                this.items.push(data);
                this.lastQuestionDate = nowDate;
                this.scrollToEndOnEnd();
            }
        },
        onMessageSayPresentFlower(e, data) {
            let canPush = false;
            let nowDate = new Date();
            if (data.user_id == uid) {
                canPush = true;
            } else if (!this.lastsPresentFlowerDate.getTime() || (nowDate.getTime() - this.lastsPresentFlowerDate.getTime()) > 60000) {
                // 1分钟内只接收一次
                canPush = true;
            }
            canPush = true;
            if (canPush) {
                this.items.push(data);
                this.lastsPresentFlowerDate = nowDate;
                this.scrollToEndOnEnd();
            }
        },
        // 在底部时有消息进来后,下次DOM更新时再滚动到底部
        scrollToEndOnEnd() {
            let self = this;
            var scroll = self.scroll;
            var onEend = - scroll.y + scroll.wrapperHeight == scroll.scrollerHeight ? true : false;
            self.$nextTick(function () {
                scroll.refresh();
                self.scrollToEnd(scroll);
            });
        },
        /**
         * 直接滚动到底部
         * @param scroll
         */
        scrollToEnd(scroll) {
            scroll.scrollTo(0, scroll.maxScrollY);
        },
        // 滚动结束后回调
        scrollEnd() {
            // 在顶部 从上往下拉
            if(this.scroll.y == 0) {
                if (!this.state.refresh) {
                    // 获取上一页数据
                    this.fetchItems();
                }
            }
            this.hide.refresh = true;
        },
        scrollIng() {
            // 在顶部 从上往下拉
            // console.log(this.scroll.y + ' ' + this.scroll.directionY);
            if(this.scroll.y >= 0 && this.scroll.directionY <= 0 &&  !this.state.refresh) {
                this.hide.refresh = false;
            }
        },
        fetchItems() {
            var self = this;
            if (!this.hasNextPage) {
                return true;
            }
            this.state.refresh = true;

            $.ajax({
                type: "POST",
                url: "/api/message/chats",
                data: {
                    cid: cid,
                    messageid: self.minMessageid,
                    page_size: 5
                },
                dataType: "json",
                success: function(result){
                    self.state.refresh = false;
                    if (result.status == 1) {
                        self.minMessageid = result.data.messageid;
                        self.hasNextPage = result.data.hasNextPage;
                        self.items = _.concat(result.data.list, self.items);
                        self.$nextTick(function () {
                            if (!car2._messageScroll) {
                                car2._messageScroll = new IScroll('#message-container', {
                                    hScrollbar: false,
                                    vScrollbar: true,
                                    bounce: true,
                                    scrollbars: true,
                                    probeType: 2,
                                    tap:true
                                });
                                self.scroll = car2._messageScroll;
                                self.scroll.on('scrollEnd', self.scrollEnd);
                                self.scroll.on('scroll', self.scrollIng);
                            }
                            self.scroll.refresh();
                        });
                    }
                }
            });
        }
    },
    components: {
        'chat-item': require('./chatItem.vue'),
    }
};
</script>
