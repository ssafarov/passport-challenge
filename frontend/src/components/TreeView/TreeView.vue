<template>
    <b-row>
        <b-col>
            <h3>Live Factories Tree
                <b-button size="sm" variant="primary"
                          :class="{disabled: treeIsInEditState}"
                          v-if="!isAdding"
                          @click="addNewChild">Add new Factory
                </b-button>
            </h3>
            <ul class="tree">
                <li>
                    <div v-if="isAdding">
                        <h5>Adding a new factory</h5>
                        <input type="text" class="edit title" placeholder="Title" id="fTitle"
                               :class="{error: !isTitleOk}"
                               v-model="newItem.title"/>&nbsp;@&nbsp;
                        <input type="text" class="edit number" placeholder="Amount"
                               :class="{error: !isAmountOk}"
                               v-model="newItem.amount"/>&nbsp;with&nbsp;
                        <input type="text" class="edit number" placeholder="Min"
                               :class="{error: !isLowOk}"
                               v-model="newItem.low"/>&nbsp;-&nbsp;
                        <input type="text" class="edit number" placeholder="Max"
                               :class="{error: !isHighOk}"
                               v-model="newItem.high"/>&nbsp;range
                        <div v-if="showAlert" class="hint error">There are some errors in input. Please fix all red
                            fields before proceed.
                        </div>
                        <br/>
                        <b-button size="sm" variant="success"
                                  :class="{disabled: !isInputOk}"
                                  @click="addNewChildSubmit">Save
                        </b-button>&nbsp;
                        <b-button size="sm" variant="secondary"
                                  @click="addNewChildCancel">Cancel
                        </b-button>
                    </div>
                </li>
                <item   v-if="!isAdding"
                        @bus="bus"
                        @editing="editing"
                        @deleting="deleting"
                        class="item"
                        :model="treeData"
                        :state="treeIsInEditState">
                </item>
            </ul>
        </b-col>
        <b-col md="4"> <!-- Intructions -->
            <b-jumbotron header="" lead="Instructions & legend" class="tree-view-help">
                <b-row>
                    <b-col>
                        <ul class="dotted">
                            <li>Click on "Add new Factory" button to add new tree node.</li>
                            <li>Single click on node to open/close.</li>
                            <li>Double click on node to edit/recreate.</li>
                            <li>Right click on node to rename, adjust an amount, range or delete.</li>
                            <li><b>Title</b> should be non-empty string.</li>
                            <li><b>Amount</b> should be positive non zero integer between 1 and 16.</li>
                            <li><b>Min</b> and <b>Max</b> should be positive non zero integers.</li>
                            <li><b>Max</b> value should be greater than <b>Min</b>.</li>
                        </ul>
                    </b-col>
                </b-row>
            </b-jumbotron>
        </b-col>
    </b-row>

</template>

<script>
    import {BButton, BCol, BJumbotron, BRow} from 'bootstrap-vue';

    import SHA1 from 'sha1';

    import TreeViewItem from "./Item.vue";

    export default {
        name: "tree-view",
        props: ["treeData"],
        components: {
            Item: TreeViewItem,
            'b-jumbotron': BJumbotron,
            'b-row': BRow,
            'b-col': BCol,
            'b-button': BButton
        },
        data: function () {
            return {
                showAlert: false,
                treeIsInEditState: false,
                treeIsInAddingState: false,
                model: {hash: '', title: '', isRoot: false, children: [], amount: null, low: null, high: null},
                newItem: {hash: '', title: '', isRoot: false, children: [], amount: null, low: null, high: null},
            }
        },
        mounted: function () {
            this.model = this.treeData;
        },
        computed: {
            isRoot: function () {
                return this.model.isRoot
            },
            isAdding: function () {
                return this.treeIsInAddingState
            },
            isEditing: function () {
                return this.treeIsInEditState
            },
            isTitleOk: function () {
                let regex = new RegExp(/(<([^>]+)>)/ig);
                let sTest = String(this.newItem.title);
                return (sTest !== undefined && sTest !== '' && sTest !== null && !regex.test(sTest));
            },
            isAmountOk: function () {
                let iTest = Number(this.newItem.amount);
                return Number.isInteger(iTest) && iTest > 0 && iTest <= 16;
            },
            isLowOk: function () {
                let iTest = Number(this.newItem.low);
                return Number.isInteger(iTest) && iTest > 0;
            },
            isHighOk: function () {
                let iTest = Number(this.newItem.high);
                return Number.isInteger(iTest) && iTest > 0 && iTest > this.newItem.low;
            },
            isInputOk: function () {
                return this.isAmountOk && this.isLowOk && this.isHighOk && this.isTitleOk;
            }
        },
        methods: {
            bus: function (model) {
                let childLength = this.treeData.children.length;
                for (let i = 0; i < childLength; i++) {
                    if (this.treeData.children[i].hash === model.hash) {
                        this.treeData.children[i] = model;
                        break;
                    }
                }
                this.$emit('bus', this.treeData)
            },
            adding: function (state) {
                this.treeIsInAddingState = state;
            },
            editing: function (state) {
                this.treeIsInEditState = state;
                this.$emit('editing', state);
            },
            deleting: function (model) {
                this.editing(true);

                let index = null, childLength = this.treeData.children.length;
                for (let i = 0; i < childLength; i++) {
                    if (this.treeData.children[i].hash === model.hash) {
                        index = i;
                        break;
                    }
                }
                if (index !== null) {
                    this.treeData.children.splice(index, 1);
                }

                this.editing(false);

                this.$emit('bus', this.treeData);
            },
            addNewChild: function () {
                if (this.treeIsInAddingState || this.treeIsInEditState) {
                    return false;
                }

                this.adding(true);
                this.editing(true);
            },
            addNewChildSubmit: function () {
                // Input checks
                this.showAlert = !this.isInputOk;
                if (!this.isInputOk) {
                    return false;
                }

                // Rebuild Childs from the root
                this.newItem.children = [];
                this.newItem.hash = SHA1(this.newItem.title + (Math.random() * crypto.getRandomValues(new Uint8Array(1))));
                for (let i = 0; i < this.newItem.amount; i++) {
                    let min = Number (this.newItem.low), max = Number(this.newItem.high);
                    this.newItem.children.push({
                        'hash' : SHA1(this.newItem.hash + (Math.random() * crypto.getRandomValues(new Uint8Array(1)))),
                        'title': Math.floor((Math.random() * (max - min + 1)) + min)
                    });
                }
                this.treeData.children.push(this.newItem);
                this.newItem = {hash: '', title: '', isRoot: false, children: [], amount: null, low: null, high: null};

                this.beforeEditCache = null;
                this.adding(false);
                this.editing(false);

                //Whole tree here
                this.$emit('bus', this.treeData);
            },
            addNewChildCancel: function () {
                this.model = Object.assign(this.model, this.beforeEditCache);
                this.showAlert = false;
                this.adding(false);
                this.editing(false);
            },
        }
    }
</script>

<style scoped lang="scss">

    ul {
        padding-left: 1em;
        line-height: 1.5em;
        list-style-type: none;
        text-align: left;
    }

    .dotted {
        list-style-type: square;
    }

    input.error {
        color: #f24;
        background-color: #ffc7c8 !important;
    }

    input {
        margin: 2px;
    }

    input.btn {
        padding: 0.25rem 0.75rem;
        vertical-align: baseline;
    }

    input.btn-tertiary {
        background-color: #f24;
        color: #eee
    }

    input.btn-tertiary:hover {
        background-color: #f68
    }

    .btn-icon-save::before,
    .btn-icon-cancel::before,
    .btn-icon-delete::before {
        color: #444;
        font-size: 10px;
        position: relative;
        left: 1px;
        top: 5px;
        transition: -webkit-transform 0.1s ease;
        transition: transform 0.1s ease;
        transition: transform 0.1s ease, -webkit-transform 0.1s ease;
        -webkit-transition: -webkit-transform 0.1s ease;
    }

    .btn-icon-save::before {
        content: "\25b6";
    }

    .btn-icon-cancel::before {
        content: "\25b6";
    }

    .btn-icon-delete::before {
        content: "\25b6";
    }

    input.edit {
        border: #aaa solid 1px;
        background-color: #fff;
        padding: 4px;
        border-radius: 4px;
    }

    input.title {
        max-width: 200px;
    }

    input.number {
        max-width: 60px;
    }

    input::-webkit-input-placeholder {
        font-style: italic;
        font-weight: 300;
        color: #666;
    }

    input::-moz-placeholder {
        font-style: italic;
        font-weight: 300;
        color: #666;
    }

    input::input-placeholder {
        font-style: italic;
        font-weight: 300;
        color: #666;
    }


    .tree-view-help {
        padding: 1rem;
    }

    .hint {
        color: #eee;
        background-color: #999;
        padding: 3px;
        border: #999 1px solid;
        border-radius: 5px;
        font-size: 70%;
        margin: 5px 0;
        text-align: center;
    }

    .hint.error {
        background-color: #ffc8cd;
        border: #f24 1px solid;
        color: #ee010f;
        max-width: 550px;
    }

</style>