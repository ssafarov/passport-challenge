<template>
    <b-container class="application">
        <WelcomePage title="Passport Code Challenge App: Vue.js and Lumen"/>
        <b-row v-bind:key="index" v-for="(error, index) in errors" class="errors">
            <b-alert show v-if="error.status === 'error'" variant="danger" class="full-width">
                <span>{{error.message}}</span>
            </b-alert>
            <b-alert show v-else-if="error.status === 'success'" variant="success" class="full-width">
                <span>{{error.message}}</span>
            </b-alert>
            <b-alert show v-else-if="error.status === 'info'" variant="info" class="full-width">
                <span>{{error.message}}</span>
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
    import axios from 'axios';
    import SHA1 from 'sha1';
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
                tree_key: 'cf23df2207d99a74fbe169e3eba035e633b65d94',
                api_url: 'http://api.local/api/',
                factories: {title:'Root', hash: SHA1(this.tree_key + (Math.random() * crypto.getRandomValues(new Uint8Array(1)))), isRoot: true, children: []},
                errors: []
            };
        },
        mounted: function () {
            this.makeRequest('factories');
            let channel = this.$pusher.subscribe('factories.'+this.tree_key);
            channel.bind('factories\\updated', (event) => {
                this.factories = JSON.parse(event.update);
            });
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
                this.factories = data;
                this.makeRequest('factories/update', 'post');
            },
            makeRequest: function (endpoint, method = 'get') {
                const sender = axios.create({
                    baseURL: this.api_url,
                    timeout: 50000,
                    dataType: 'json',
                    accept: 'application/json, text/plain, */*',
                });

                let config = {
                    method: method,
                    url: endpoint,
                    headers: {
                        Authorization: this.tree_key
                    },
                    data: {
                        tree_key: this.tree_key,
                        payload: this.factories
                    },
                };
                this.errors = [];
                sender.request(config)
                .then(response => {
                    if (response.data.payload){
                        this.errors.push({status: 'success', message: response.data.message});
                        this.factories = JSON.parse(response.data.payload.data);
                    } else {
                        this.errors.push({status: 'info', message: 'Empty payload received. Tree is not syncronized. Try to refresh page.'});
                    }
                })
                .catch(error => {
                    this.errors.push({status: 'error', message: 'Api call was not successfull. Please try again later. Response: '+error});
                })
            }
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

    .full-width{
        width: 100%;
    }

</style>
