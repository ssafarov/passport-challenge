<template>
    <b-row>
        <b-col>
            <h3>Live Factories Tree
                <b-button size="sm" variant="primary"
                          :class="{disabled: treeIsInEditState}"
                          v-if="!isEditing"
                          @click="addNewChild">Add new Factory
                </b-button>
            </h3>
            <ul class="tree">
                <li>
                    <div v-if="isEditing">
                        <h5>Adding a new factory</h5>
                        <input type="text" class="edit title" placeholder="Factory title" id="fTitle"
                               :class="{error: !isTitleOk}"
                               v-model="model.title"/>&nbsp;@&nbsp;
                        <input type="text" class="edit number" placeholder="Amount"
                               :class="{error: !isAmountOk}"
                               v-model="amount"/>&nbsp;with&nbsp;
                        <input type="text" class="edit number" placeholder="Min"
                               :class="{error: !isLowOk}"
                               v-model="model.low"/>&nbsp;-&nbsp;
                        <input type="text" class="edit number" placeholder="Max"
                               :class="{error: !isHighOk}"
                               v-model="model.high"/>&nbsp;bounds
                        <div v-if="showAlert" class="hint error">There are some errors in input. Please fix all red
                            fields before proceed.
                        </div>
                        <b-button size="sm" variant="success"
                                  :class="{disabled: !isInputOk}"
                                  @click="submitEdit">Save
                        </b-button>&nbsp;
                        <b-button size="sm" variant="secondary"
                                  @click="cancelEdit">Cancel
                        </b-button>
                    </div>
                </li>
                <item
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
                amount: null,
                treeIsInEditState: false,
                treeIsInAddingState: false,
                model: {hash: '', title: '', isRoot: false, children: [], low: null, high: null},
            }
        },
        mounted: function () {
            this.amount = this.currentAmount;
        },
        computed: {
            currentAmount: function () {
                return this.model.children && this.model.children.length ? this.model.children.length : null;
            },
            isEditing: function () {
                return this.treeIsInAddingState
            },
            isTitleOk: function () {
                let regex = new RegExp(/(<([^>]+)>)/ig);
                let sTest = String(this.model.title);
                return (sTest !== '' && sTest !== null && !regex.test(sTest));
            },
            isAmountOk: function () {
                let iTest = Number(this.amount);
                return Number.isInteger(iTest) && iTest > 0 && iTest <= 16;
            },
            isLowOk: function () {
                let iTest = Number(this.model.low);
                return Number.isInteger(iTest) && iTest > 0;
            },
            isHighOk: function () {
                let iTest = Number(this.model.high);
                return Number.isInteger(iTest) && iTest > 0 && iTest > this.model.low;
            },
            isInputOk: function () {
                return this.isAmountOk && this.isLowOk && this.isHighOk && this.isTitleOk;
            }
        },
        methods: {
            bus: function (data) {
                this.$emit('bus', data);
            },
            editing: function (state) {
                this.treeIsInEditState = state;
                this.$emit('editing', state);
            },
            deleting: function (model) {
                this.treeIsInEditState = true;
                this.$emit('editing', this.treeIsInEditState);
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
                this.treeIsInEditState = false;
                this.$emit('editing', this.treeIsInEditState);
                this.$emit('bus', this.treeData);
            },
            addNewChild: function () {
                if (this.treeIsInEditState) {
                    return false;
                }
                this.treeIsInAddingState = true;
                this.editing(this.treeIsInAddingState);
            },
            submitEdit: function () {
                // Input checks
                this.showAlert = !this.isInputOk;
                if (!this.isInputOk) {
                    return false;
                }

                // Rebuild Childs
                this.rebuildChilds();

                this.treeData.children.push(this.model);

                this.beforeEditCache = null;
                this.treeIsInAddingState = false;
                this.treeIsInEditState = false;

                this.$emit('bus', this.model);
                this.$emit('editing', this.treeIsInEditState);
            },
            cancelEdit: function () {
                this.model = Object.assign(this.model, this.beforeEditCache);
                this.treeIsInAddingState = false;
                this.showAlert = false;

                this.editing(this.treeIsInAddingState);
            },
            rebuildChilds: function () {
                this.model.children = [];
                this.model.hash = SHA1(this.model.title + (Math.random() * crypto.getRandomValues(new Uint8Array(1))));
                for (let i = 0; i < this.amount; i++) {
                    this.model.children.push({
                        'hash' : SHA1(this.model.hash + (Math.random() * crypto.getRandomValues(new Uint8Array(1)))),
                        'title': Math.floor(Math.random() * (this.model.high - this.model.low + 1) + this.model.low)
                    });
                }
            }
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