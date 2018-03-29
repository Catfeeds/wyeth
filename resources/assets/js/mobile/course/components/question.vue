<!-- components/question.vue -->

<template>
<question-list></question-list>
<question-new></question-new>
</template>

<script>
module.exports = {
    data() {
        return {};
    },
    components: {
        'question-list': require('./questionList.vue'),
        'question-new': require('./questionNew.vue'),
    },
    events: {
        'child-msg': function (msg) {
            console.log(msg);
        }
    }
};
</script>
