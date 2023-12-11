import { useToast } from "vue-toast-notification";
import "vue-toast-notification/dist/theme-sugar.css";
import { fetchList } from "@/api/category";
import Multiselect from "@vueform/multiselect";
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";
export default {
    components: {
        Multiselect,
    },
    data() {
        return {
            file: "",
            categories: [],
            editor: ClassicEditor,
            editorConfig: {
                toolbar: {
                    items: [
                        "heading",
                        "|",
                        "bold",
                        "italic",
                        "blockQuote",
                        "|",
                        "list",
                        "|",
                        "link",
                        "|",
                        "bulletedList",
                        "numberedList",
                        "|",
                        "undo",
                        "redo",
                    ],
                },
            },
        };
    },
    computed: {
        extension() {
            return this.file ? this.file.name.split(".").pop() : "";
        },
    },
    methods: {
        formatDate(date) {
            const year = new Date(date).getFullYear();
            const month = new Date(date).getMonth();
            const day = new Date(date).getDate();
            return `${month+1}/${day}/${year}`;
        },
        goToEdit(url) {
            this.$router.push({ path: url })
        },
        handleResponse(response) {
            const $toast = useToast();
            $toast.open({
                message: response.message,
                type: response.success === true ? "success" : "error",
                position: "top-right",
            });
        },
        handleCatch(error) {
            const $toast = useToast();
            $toast.open({
                message: error.response.data.message,
                type: "error",
                position: "top-right",
            });
        },
        getCategories(type) {
            fetchList(`?type=${type}&active=true`).then((response) => {
                this.categories = response.data.data;
            });
        },
    }
};