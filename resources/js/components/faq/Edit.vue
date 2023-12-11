<template>
    <div class="overlay" v-if="displayOverlay">
        <div class="overlay__inner">
            <div class="overlay__content">
                <span class="spinner"></span>
            </div>
        </div>
    </div>
    <div class="form-box">
        <vee-form
            :validation-schema="schema"
            @submit="updateFaq"
            enctype="multipart/form-data"
        >
            <div class="row">
                <div class="col-md-12">
                    <h4 class="subHeading">Basic Details</h4>
                </div>
                <div class="col-md-12">
                    <div class="form-field">
                        <label for="">Question<sup>*</sup></label>
                        <vee-field
                            name="question"
                            type="text"
                            v-on:input="onChangeOfTitle()"
                            v-model="faqData.question"
                            class="form-control"
                            placeholder="Enter Question"
                        />
                        <div class="error invalid-feedback">
                            <ErrorMessage
                                class="invalid-feedback"
                                name="question"
                            />
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-field">
                        <label for="">Categories<sup>*</sup></label>
                        <vee-field
                            v-slot="{ field, errors }"
                            class="form-control"
                            name="categories"
                            type="text"
                            v-model="faqData.categoryIds"
                        >
                            <Multiselect
                                v-model="faqData.categoryIds"
                                mode="tags"
                                v-bind="field"
                                :close-on-select="false"
                                :searchable="true"
                                :create-option="false"
                                valueProp="id"
                                label="name"
                                :required="true"
                                :options="categories"
                                placeholder="Select Categories"
                            />
                            <div
                                class="error invalid-feedback"
                                v-if="errors[0]"
                            >
                                {{ errors[0] }}
                            </div>
                        </vee-field>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-field">
                        <label for="">Answer<sup>*</sup></label>
                        <ckeditor
                            :editor="editor"
                            v-model="faqData.answer"
                            :config="editorConfig"
                        ></ckeditor>
                        <div class="error invalid-feedback">
                            {{ descError }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <h4 class="subHeading">SEO Fields</h4>
                </div>
                <div class="col-md-4">
                    <div class="form-field">
                        <label for="">Meta Title</label>
                        <vee-field
                            name="meta_title"
                            type="text"
                            v-model="faqData.meta_title"
                            class="form-control"
                            placeholder="Enter Meta Title"
                        />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-field">
                        <label for="">Meta Description</label>
                        <vee-field
                            name="meta_description"
                            type="text"
                            v-model="faqData.meta_description"
                            class="form-control"
                            placeholder="Enter Meta Description"
                        />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-field">
                        <label for="">Meta Keywords</label>
                        <vee-field
                            name="meta_keywords"
                            type="text"
                            v-model="faqData.meta_keywords"
                            class="form-control"
                            placeholder="Enter Meta Keywords"
                        />
                    </div>
                </div>
            </div>
            <div class="submit-wrap pt-3">
                <button type="submit" class="btn btn-secondary">SUBMIT</button>
            </div>
        </vee-form>
    </div>
</template>
<style src="@vueform/multiselect/themes/default.css"></style>
<script>
import { fetch, update } from "@/api/faq";
export default {
    data() {
        return {
            displayOverlay: true,
            faqData: {
                question: null,
                sub_title: null,
                answer: "",
                meta_title: null,
                meta_description: null,
                meta_keywords: null,
            },
            tempfaqData: {},
            schema: {
                question: "required|min:20",
                categories: "required",
            },
            descError: null,
        };
    },
    async mounted() {
        await this.getCategories("faq");
        await this.getFaq();
        let that = this;
        new Promise(function (resolve, reject) {
            setTimeout(() => {
                that.faqData = that.tempfaqData;
                that.displayOverlay = false;
                resolve();
            }, 2500);
        });
    },
    methods: {
        async getFaq() {
            const id = `${this.$route.params.faqsId}`;
            fetch(id).then((response) => {
                if (response.success === true) {
                    this.tempfaqData = response.data;
                }
            });
        },
        updateFaq() {
            this.descError = null;
            if (this.faqData.answer == "") {
                this.descError = "This field is required.";
            } else if (this.faqData.answer.length < 50) {
                this.descError = "Minimum 50 characters are allowed.";
            } else {
                let data = new FormData();
                data.append("question", this.faqData.question);
                data.append("answer", this.faqData.answer);
                data.append("categories", this.faqData.categoryIds);
                data.append("meta_title", this.faqData.meta_title);
                data.append("meta_description", this.faqData.meta_description);
                data.append("meta_keywords", this.faqData.meta_keywords);
                update(data, this.faqData.id)
                    .then((response) => {
                        this.handleResponse(response);
                        if (response.success == true) {
                            this.$router.push({
                                path: `/faqs/list`,
                            });
                        }
                    })
                    .catch((error) => {
                        this.handleCatch(error);
                    });
            }
        },
        onChangeOfTitle() {
            this.faqData.meta_title = this.faqData.question;
            this.faqData.meta_description = this.faqData.question;
            this.faqData.meta_keywords = this.faqData.question;
        },
    },
};
</script>

<style scoped>
.overlay {
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    position: fixed;
    background: rgba(255, 255, 255, 0.7);
    z-index: 9999;
}

.overlay__inner {
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    position: absolute;
}

.overlay__content {
    left: 50%;
    position: absolute;
    top: 50%;
    transform: translate(-50%, -50%);
}

.spinner {
    width: 50px;
    height: 50px;
    display: inline-block;
    border-width: 5px;
    border-color: #0c0c0c0d;
    border-top-color: #1a1818;
    animation: spin 1s infinite linear;
    border-radius: 100%;
    border-style: solid;
}

@keyframes spin {
    100% {
        transform: rotate(360deg);
    }
}
</style>
