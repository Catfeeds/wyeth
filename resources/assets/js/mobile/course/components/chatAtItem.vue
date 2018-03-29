<!-- components/chatAtItem.vue -->

<template>
    <li @click="onSpanUserClick">
        <span>{{user.name}}</span>
    </li>
</template>

<script>
module.exports = {
    props: ['user', 'index'],
    data () {
        return {
        };
    },
    ready: function () {
    },
    methods: {
        // 点击At的人
        onSpanUserClick () {
            this.$dispatch('chatAtItemClick', this.user);
        }
    }
};
</script>
