<!-- components/questionNew.vue -->

<template>
    <div class="question-sprite bottom">
        <div class="tip-answer"><span>老师已回答了{{answeredNum}}个问题</span></div>
        <div class="head-records-ul courseing" v-if="items.length == 0">老师讲课中</div>
        <ul class="head-records-ul" v-bind:style="recordsUlStyle">
            <li v-for="item in items" class="animated" transition="fadeRightBig">
                <img :src="item.avatar" />
            </li>
        </ul>
        <div class="question-sprite button-ask"  @click="goAsk"></div>
    </div>

    <div class="pop" :class="{'f-hide': hide}">
        <div class="question-sprite pop-question">
            <span class="cancel" @click="cancel">取消</span>
            <span class="send" @click="send">发送</span>
            <textarea v-model="content" id="QuestionSayText" cols="50" rows="5" onfocus="if(value=='我觉得你说的很有道理····'){value=''}"
                      >我觉得你说的很有道理····</textarea>
            <input type="checkbox" v-model="receiveMore" checked="checked"/>
            <span style="left: 41px;top: 234px;width: 332px;background: white;display:none;"></span>
        </div>
    </div>

    <div class="question-sprite tip-success" :class="{'f-hide': tipSuccessHide}"></div>

</template>

<script>
export default {
    data (){
        return {
            items: [],
            hide: true,
            // 提示成功
            tipSuccessHide: true,
            // message
            content: '',
            // 接受其他专家课后回答
            receiveMore: false,
            img: {
                tmp: `${staticUrl}/mobile/boardcast/img/temp.jpg`
            }
        }
    },
    computed: {
        // 已回答数
        answeredNum: function () {
            return this.items.length;
        },
        recordsUlStyle: function () {
            let obj = {};
            if (this.items.length <= 6) {
                obj.paddingTop = '70px';
            }
            return obj;
        }
    },
    methods: {
        goAsk (){
            this.hide = false;
            this.$nextTick(function () {
                $('#QuestionSayText').focus();
            });
        },
        cancel (){
            this.hide = true;
            this.content = '';
        },
        send (){
            var self = this;

            if (!this.content) {
                // alert('请填写内容');
                return false;
            }
            /* if(!this.receiveMore){
                alert('没有点接受按扭');
                return false;
            }*/

            var message = {};
            message.type = 'say';
            message.cid = cid;
            message.author_id = uid;
            message.user_type = userType;
            message.message_type = 1;
            message.content = this.content;
            // 暂时去掉抢话筒的功能
            message.microphone_id = 0;
            message.source_id = 0;
            message.source_author_id = 0;
            message.receive_more = this.receiveMore ? 1 : 0;
            manager.sendSocketData(message);
            this.hide = true;
            this.tipSuccessHide = false;
            setTimeout(function(){
                self.tipSuccessHide = true;
                self.content = '';
            }, 1000);
        },
        fetchItems() {
            var self = this;
            $.ajax({
                type: "POST",
                url: "/api/message/answered",
                data: {cid: cid, perpage: 12},
                dataType: "json",
                success: function(result){
                    if (result.status == 1) {
                        self.items = _.concat(result.data.list, self.items);
                    }
                }
            });
        }
    },
    ready () {
        var self = this;
        this.fetchItems();
        // 绑定socket事件
        $(document).on('ws.message.say.answered', function (e, data) {
            // 只显示16个
            if (self.items.length < 12) {
                self.items.push(data);
            }
        });
    }
}
</script>
