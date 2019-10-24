<template>
        <li class="item">
            <div v-if="!isEditing"
                 :class="{bold: isNode}"
                 @bus="bus"
                 @editing="editing"
                 @deleting="deleting"
                 @click="toggleOpen"
                 @dblclick="startEdit"
                 @contextmenu.prevent="startEdit">
                <span class="chevron " :class="{opened: isOpened}" v-if="isNode"></span>
                {{ model.title }}
                <span v-if="isNode" class="hint">{{currentAmount}} {{ currentAmount | pluralize }}</span>&nbsp;<span v-if="model.low && model.high" class="hint">with range from {{model.low}} to {{model.high}}</span>
            </div>
            <div v-if="isEditing">
                <input type="text" class="edit title" placeholder="Title"
                       :class="{error: !isTitleOk}"
                       v-model="model.title"/>&nbsp;@&nbsp;
                <input type="text" class="edit number" placeholder="Amount"
                       :class="{error: !isAmountOk}"
                       v-model="model.amount"/>&nbsp;with&nbsp;
                <input type="text" class="edit number" placeholder="Min"
                       :class="{error: !isLowOk}"
                       v-model="model.low"/>&nbsp;-&nbsp;
                <input type="text" class="edit number" placeholder="Max"
                       :class="{error: !isHighOk}"
                       v-model="model.high"/>&nbsp;range
                <div v-if="showAlert" class="hint error">There are some errors in input. Please fix all red fields
                    before proceed.
                </div>
                <br/>
                <b-button size="sm" variant="success"
                          :class="{disabled: !isInputOk}"
                          @click="submitEdit">Save
                </b-button>&nbsp;
                <b-button size="sm" variant="secondary"
                          @click="cancelEdit">Cancel
                </b-button>&nbsp;
                <b-button size="sm" variant="tertiary"
                          @click="deleteNode">Delete
                </b-button>
            </div>
            <ul v-show="open" v-if="isNode">
                <tree-item
                        @bus="bus"
                        @editing="editing"
                        @deleting="deleting"
                        class="item"
                        v-for="(item, index) in model.children"
                        :key="index"
                        :model="item"
                        :state="state">
                </tree-item>
            </ul>
        </li>

</template>

<script>
    import {BButton} from 'bootstrap-vue';
    import SHA1 from 'sha1';

    export default {
        name: 'tree-item',
        props: {
            model: {hash: '', title: '', isRoot: false, children: [], amount:null, low: null, high: null},
            state: null
        },
        components: {
            'b-button': BButton
        },
        data: function () {
            return {
                showAlert: false,
                open: false,
                edit: false
            }
        },
        beforeEditingCache: function () {
            return {}
        },
        computed: {
            isRoot: function () {
                return this.model.isRoot
            },
            currentAmount: function () {
                return this.model.children && this.model.children.length ? this.model.children.length : this.model.amount;
            },
            isNode: function () {
                return this.model.children && this.model.children.length;
            },
            isOpened: function () {
                return this.open
            },
            isEditing: function () {
                return this.edit
            },
            isTitleOk: function () {
                let regex = new RegExp(/(<([^>]+)>)/ig);
                let sTest = String(this.model.title);
                return (sTest !== undefined && sTest !== '' && sTest !== null && !regex.test(sTest));
            },
            isAmountOk: function () {
                let iTest = Number(this.model.amount);
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
        filters: {
            pluralize: function (num) {
                return num === 1 ? 'item' : 'items'
            }
        },
        methods: {
            // events
            bus: function (model) {
                this.$emit('bus', model)
            },
            editing: function (state) {
                this.$emit('editing', state)
            },
            deleting: function (model) {
                this.$emit('deleting', model);
            },
            // methods
            toggleOpen: function () {
                if (this.isNode) {
                    this.open = !this.open;
                }
            },
            submitEdit: function () {
                // Input checks
                this.showAlert = !this.isInputOk;
                if (!this.isInputOk) {
                    return false;
                }

                // Rebuild Childs
                this.model.hash = SHA1(this.model.title + (Math.random() * crypto.getRandomValues(new Uint8Array(1))));
                this.model.children = [];
                for (let i = 0; i < this.model.amount; i++) {
                    this.model.children.push({
                        'hash' : SHA1(this.model.hash + (Math.random() * crypto.getRandomValues(new Uint8Array(1)))),
                        'title': Math.ceil(Math.random() * (this.model.high - this.model.low + 1) + this.model.low)
                    });
                }

                this.beforeEditCache = null;
                this.edit = false;

                this.$emit('editing', this.edit);
                this.$emit('bus', this.model);
            },
            cancelEdit: function () {
                this.model = Object.assign(this.model, this.beforeEditCache);
                this.edit = false;
                this.showAlert = false;

                this.$emit('bus', this.model);
                this.$emit('editing', this.edit);
            },
            deleteNode: function () {
                this.$emit('deleting', this.model);
            },
            startEdit: function () {
                if (this.state || this.model.isRoot) {
                    return;
                }

                this.edit = true;
                this.beforeEditCache = Object.assign({}, this.model);
                this.$emit('editing', this.edit);
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

    .item {
        cursor: pointer;
        max-width: 650px;
    }

    .chevron {
        display: inline-flex;
    }

    .chevron::before {
        color: #444;
        content: "\25b6";
        font-size: 10px;
        left: 1px;
        top: 5px;
        transition: -webkit-transform 0.1s ease;
        transition: transform 0.1s ease;
        transition: transform 0.1s ease, -webkit-transform 0.1s ease;
        -webkit-transition: -webkit-transform 0.1s ease;
    }

    .chevron.opened::before {
        transform: rotate(90deg);
        -webkit-transform: rotate(90deg);
    }

    .bold {
        font-weight: bold;
    }

    input.error {
        color: #f24;
        background-color: #ffc7c8 !important;
    }

    .hint {
        color: #eee;
        background-color: #999;
        padding: 3px 5px;
        border: #999 1px solid;
        border-radius: 9px;
        font-size: 70%;
    }

    .hint.error {
        background-color: #ff647d;
        border: #ff647d 1px solid;
        color: #eee;
        max-width: 630px;
        text-align: center;
    }

    input {
        margin: 2px;
    }

    .btn-tertiary {
        background-color: #f24;
        color: #eee
    }

    .btn-tertiary:hover {
        background-color: #f68
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
</style>
