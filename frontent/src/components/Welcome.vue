<template>
    <b-row>
        <b-col>
            <h1>{{ title }}</h1>
        </b-col>
    </b-row>
</template>

<script>
    import {BCol, BRow} from 'bootstrap-vue';

    export default {
        name: 'WelcomePage',
        components: {
            'b-row': BRow,
            'b-col': BCol
        },
        props: {
            title: String
        }
    }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
    h1 {
        margin: 10px 0 30px;
    }
</style>
