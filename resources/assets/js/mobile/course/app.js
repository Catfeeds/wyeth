$(document).on('main.loading.done', function() {
    var Vue = require('vue');
    var _ = require('lodash/core');
    Vue.use(require('vue-animated-list'));

    // 定义转场动画样式
    Vue.transition('fadeRightBig', {
        enterClass: 'fadeInRightBig',
        leaveClass: 'fadeOutRightBig'
    });

    // 定义qqface filter
    Vue.filter('qqface', (str) => {
        str = str.replace(/\</g,'&lt;');
        str = str.replace(/\>/g,'&gt;');
        str = str.replace(/\n/g,'<br/>');
        str = str.replace(/\[em_([0-9]*)\]/g,`<img src="${staticUrl}/mobile/boardcast/img/emoticon/$1.gif" border="0" />`);
        return str;
    });
    // 定义 @高亮 filter
    Vue.filter('at', (str) => {
        "use strict";
        return str.replace(/(@\S*)(\s|$)/g, "<span style='color: #0052ab'>$1</span>$2")
    });

    // 定义配置
    window.vueEnv =  {
        chatAnchor: {
            uid: 101,
            name: '主持人',
            avatar: 'http://7xk3aj.com1.z0.glb.clouddn.com/FlN9bfkk4Nnsmu3azPLYJeEkFtI4'
        }
    };

    // 妈妈提问区
    var appPageQuestion = new Vue({
        el: '#page-question',
        components: {
            'question': require('./components/question.vue')
        }
    });
    // 关闭直播间讨论区 打开 妈妈提问区
    var closeChatAndOpenQuestion = function () {
        $('.page-message').addClass('page-message-disappear');
        setTimeout(() => {
            $('.page-message').addClass('f-hide');
            $('.page-message').removeClass('page-message-appear');
            $('.page-message').removeClass('page-message-disappear');

            //  打开 妈妈提问区
            $('.page-question').removeClass('f-hide');
            $('.page-question').addClass('page-question-appear');
            $(document).trigger('main.question.show');
        }, 800);
    };

    var closeChat = function () {
        $('.page-message').addClass('page-message-disappear');
        setTimeout(() => {
            $('.page-message').addClass('f-hide');
            $('.page-message').removeClass('page-message-appear');
            $('.page-message').removeClass('page-message-disappear');
        }, 800);
    };

    // 直播间讨论区
    var appPageChat = new Vue({
        el: '#page-chat',
        components: {
            'chat': require('./components/chat.vue')
        },
        events: {
            goQuestion: closeChatAndOpenQuestion,
            presentFlower: closeChat
        }
    });
});
