<template>
    <b-container class="application">
        <WelcomePage msg="Passport Code Challenge App: Vue.js and Lumen"/>
        <b-row v-bind:key="index" v-for="(error, index) in errors" class="errors">
            <b-alert show variant="danger">
                <span>{{error.message}} ({{error.status}})</span>
            </b-alert>
        </b-row>
        <br/>
        <tree-view :tree-data="factories" @bus="bus"></tree-view>
        <br/>
        <b-row class="footer">
            <b-col>
                <ul class="footer-list">
                    <li><a href="http://sergeysafarov.info">Sergei Safarov</a></li>
                    <li><a href="mailto:inbox@sergeysafarov.com">inbox@sergeysafarov.com</a></li>
                    <li><a href="https://github.com/ssafarov/passport-challenge">Github repo</a></li>
                </ul>
            </b-col>
        </b-row>
    </b-container>

</template>

<script>
    import {BAlert, BCol, BContainer, BRow} from 'bootstrap-vue';

    import Welcome from './components/Welcome.vue'
    import TreeView from './components/TreeView/TreeView';

    export default {
        name: 'app',
        components: {
            'b-container': BContainer,
            'b-row': BRow,
            'b-col': BCol,
            'b-alert': BAlert,

            WelcomePage: Welcome,
            TreeView: TreeView
        },
        data: function () {
            return {
                factories: {children: []},
                errors: []
            };
        },
        mounted: function () {
            console.log('App mounted');
        },
        filters: {
            formatter: function (item) {
                let output = '';
                for (let i = 0; 1 < item.length; i++) {
                    output = output.concat(item[i] + '\n');
                }
                return output;
            }
        },
        methods: {
            bus: function (data) {
                console.log(data);
            },
        }
    }
</script>

<style lang="scss">
    .application {
        font-family: 'Avenir', Helvetica, Arial, sans-serif;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        text-align: left;
        color: #2c3e50;
        margin-top: 40px;
    }

    .container {
        max-width: 1500px;
    }

    .footer {
        text-align: center;
        font-size: 0.8rem;
    }

    .footer li {
        display: inline-block;
        list-style: none;
        margin: 0 10px;
    }

</style>
