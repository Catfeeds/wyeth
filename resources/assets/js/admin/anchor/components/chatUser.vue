<!-- components/chatUser.vue -->

<template>
    <a style="cursor: pointer; display: block; text-align: center;" @click="fetchItems">点击获取更多历史消息</a>
    <div id="chat-user-message-container" class="box chat">
        <div class="box-bd">
            <template  v-for="item in items">
            <div class="activity" :class="{'alt': item.user_type == 2}" hw_message_id="{{item.message_id}}">
                <span><img :src="item.avatar" style="-webkit-border-radius:50%; border-radius: 50%; width:45px;"></span>
                <div class="activity-desk">
                    <div class="panel" style="margin-bottom: 0px;">
                        <div class="panel-body" style="padding: 10px;">
                            <div :class="[item.user_type == 2 ? 'arrow-alt' : 'arrow']"></div>
                            <div>
                                <a class="btn btn-white btn-xs" style="margin: 0 10px 0 0">
                                    <i class=" fa fa-clock-o" style="margin-right: 2px;"></i>{{item.time}}  {{item.name}}
                                </a>
                                <a v-if="item.user_type != 2" class="btn btn-success btn-xs forwarding" style="margin: 0 10px 0 0" href="javascript:reply('{{item.user_id}}', '{{item.name}}', '{{item.message_id}}')">回复Ta</a>
                            </div>
                            <p>{{{item.content|escape|qqface}}}</p>
                        </div>
                    </div>
                </div>
            </div>
            </template>
        </div>
    </div>
</template>

<script>
    module.exports = {
        data () {
            return {
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
                }
            };
        },
        ready () {
            var self = this;
            this.fetchItems();

            // 绑定socket事件
            $(document).on('ws.message.say.chatUser', this.onMessageSayChatUser);
        },
        methods: {
            fetchItems() {
                var self = this;
                if (!this.hasNextPage) {
                    return true;
                }
                if (this.state.refresh) {
                    return true;
                }
                this.state.refresh = true;

                $.ajax({
                    type: "POST",
                    url: "/api/message/chats",
                    data: {
                        cid: cid,
                        messageid: self.minMessageid,
                        page_size: 15
                    },
                    dataType: "json",
                    success: function(result){
                        self.state.refresh = false;
                        if (result.status == 1) {
                            self.minMessageid = result.data.messageid;
                            self.hasNextPage = result.data.hasNextPage;
                            self.items = _.concat(result.data.list, self.items);
                        }
                    }
                });
            },
            onMessageSayChatUser(e, data) {
                this.items.push(data);
                if (is_scroll) {
                    this.$nextTick(function () {
                        $('#chat_user').scrollTop($('#chat_user')[0].scrollHeight);
                    });
                }
            }
        }
    };
</script>
