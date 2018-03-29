<!-- components/questionList.vue -->

<template>
    <div id="question-container" style="overflow: hidden;">
        <ul class="question-records-ul">
            <li v-for="item in items">
                <dl>
                    <dt>
                        <img :src="item.avatar" />
                    </dt>
                    <dd>
                        <div class="title">
                            <span>{{item.name}} {{item.remark}}</span>
                            <div class="question-sprite remind f-hide"></div>
                        </div>
                        <div class="question">
                            <span>{{item.content}}</span>
                        </div>
                    </dd>
                </dl>
            </li>
        </ul>
    </div>
    <div class="question-sprite tip-refresh" :class="{'f-hide': hide.refresh}"></div>
    <div class="question-sprite tip-upload" :class="{'f-hide': hide.upload}"></div>
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
            },
            scroll: null
        };
    },
    ready: function () {
        var self = this;
        this.fetchItems();
        // 提问页兼容代码

        $('.page-question').height($(window).height() - 78);
        $('#question-container').height($(window).height() - 78 - 153 - 62);

        // 绑定socket事件
        $(document).on('ws.message.say.question', function (e, data) {
            self.items.push(data);
            var scroll = car2._questionScroll;
            var onEend = - scroll.y + scroll.wrapperHeight == scroll.scrollerHeight ? true : false;
            self.$nextTick(function () {
                scroll.refresh();
                self.scrollToEnd(scroll);
            });
        });
        // 绑定问题打开事件
        $(document).on('main.question.show', function(e) {
            var scroll = car2._questionScroll;
            scroll.refresh();
            self.scrollToEnd(scroll);
        });

    },
    methods: {
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
            if(this.scroll.y >= 0 && this.scroll.directionY == -1 &&  !this.state.refresh) {
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
                url: "/api/message/questions",
                data: {
                    cid: cid,
                    messageid: self.minMessageid
                },
                dataType: "json",
                success: function(result){
                    self.state.refresh = false;
                    if (result.status == 1) {
                        self.minMessageid = result.data.messageid;
                        self.hasNextPage = result.data.hasNextPage;
                        self.items = _.concat(result.data.list, self.items);
                        self.$nextTick(function () {
                            if (!car2._questionScroll) {
                                car2._questionScroll = new IScroll('#question-container', {
                                    hScrollbar: false,
                                    vScrollbar: true,
                                    bounce: true,
                                    scrollbars: true,
                                    probeType: 2
                                });
                                self.scroll = car2._questionScroll;
                                self.scroll.on('scrollEnd', self.scrollEnd);
                                self.scroll.on('scroll', self.scrollIng);
                            }
                            self.scroll.refresh();
                        });
                    }
                }
            });
        }
    }
};
</script>
