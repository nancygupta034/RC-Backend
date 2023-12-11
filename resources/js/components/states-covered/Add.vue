<template>
    <div class="form-box mb-3">
        <vee-form :validation-schema="schema" @submit="submit">
            <div class="row">
                <div class="col col-xl-6">
                    <div class="form-field">
                        <vee-field
                            name="state_id"
                            as="select"
                            v-model="state_id"
                            class="form-control"
                        >
                            <option value="">Select value</option>
                            <option
                                v-for="(state, index) in states"
                                :key="index"
                                :value="state.id"
                            >
                                {{ state.name }}
                            </option>
                        </vee-field>
                        <ErrorMessage class="text-red-600" name="state_id" />
                        <p v-if="showMsg">{{ msg }}</p>
                    </div>
                </div>
                <div class="col-sm-auto">
                    <div class="submit-wrap">
                        <button
                            type="submit"
                            class="btn btn-primary-light btn-medium min-small"
                        >
                            {{ id == 0 ? "Add" : "Update" }}
                        </button>
                    </div>
                </div>
            </div>
        </vee-form>
    </div>
</template>

<script>
import { create, update } from "@/api/statesCovered";
import { fetchList } from "@/api/states";
export default {
    name: "AddStatesCovered",
    props: {
        stateId: {
            type: Number,
            default: 0,
        },
        id: {
            type: Number,
            default: 0,
        },
    },
    data() {
        return {
            showMsg: false,
            msg: null,
            msgType: "success",
            states: [],
            state_id: this.stateId > 0 ? this.stateId : null,
            schema: {
                state_id: "required|regex:^([^0-9]*)$",
            },
        };
    },
    mounted() {
        this.getStates();
    },
    methods: {
        getStates(pageNo = 0) {
            fetchList("?skip=" + pageNo).then((response) => {
                this.states = response.data;
            });
        },
        submit() {
            if (this.id > 0) {
                this.update();
            } else {
                this.add();
            }
        },
        add() {
            create({ state_id: this.state_id })
                .then((response) => {
                    this.state_id = null;
                    this.displayMsg(
                        response.message,
                        true,
                        response.success == true ? "success" : "error"
                    );
                    setTimeout(() => {
                        this.displayMsg("", "success", false);
                    }, 1000);
                    this.$emit("changePage", 0);
                })
                .catch((error) => {
                    if (error.response?.data) {
                        this.displayMsg(response.message, true, "error");
                    }
                });
        },
        update() {
            update({ state_id: this.state_id }, this.id)
                .then((response) => {
                    this.state_id = null;
                    this.displayMsg(
                        response.message,
                        true,
                        response.success == true ? "success" : "error"
                    );
                    setTimeout(() => {
                        this.displayMsg("", "success", false);
                    }, 1000);
                    this.$emit("changePage", 0);
                })
                .catch((error) => {
                    if (error.response?.data) {
                        this.displayMsg(response.message, true, "error");
                    }
                });
        },
        displayMsg(msg, type, showMsg) {
            this.msg = msg;
            this.showMsg = showMsg;
            this.msgType = type;
        },
    },
};
</script>
