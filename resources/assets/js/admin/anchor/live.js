$(document).on('main.getToken.done', function() {
    var Vue = require('vue');
    var _ = require('lodash/core');

    Vue.use(require('vue-animated-list'));

    // 定义qqface filter
    Vue.filter('qqface', (str) => {
        str = str.replace(/\</g,'&lt;');
        str = str.replace(/\>/g,'&gt;');
        str = str.replace(/\n/g,'<br/>');
        str = str.replace(/\[em_([0-9]*)\]/g,`<img src="${staticUrl}/mobile/boardcast/img/emoticon/$1.gif" border="0" />`);
        return str;
    });
    Vue.filter('escape', (str) => {
        return _.escape(str);
    });

    // 用户讨论区
    var liveUserChat = new Vue({
        el: '#chat_user',
        components: {
            'chat-user': require('./components/chatUser.vue')
        }
    });
});
