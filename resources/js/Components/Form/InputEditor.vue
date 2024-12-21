<template>
    <div id="editor"></div>
</template>
<script>

import '@toast-ui/editor/dist/toastui-editor.css';
import { Editor } from '@toast-ui/vue-editor';

export default {
    components: {Editor},

    props: {
        default: "",
        title: {
            required: true,
            default: ""
        },
        required: {
            default: true
        },
        category: {
            default : ""
        }
    },

    data(){
        return {
            value: this.default,
            editor: "",
        }
    },

    methods: {
        changeContents(e){
            if(this.editor)
                this.$emit("change", this.editor.getHTML());
        },

        async uploadImage(blob){
            let formData = new FormData();

            formData.append("file", blob);

            return axios.post("/api/imageUpload", formData)
                .then(response => {
                    return response.data;
                });
        },

        async onUploadImage(blob, callback) {
            const url = await this.uploadImage(blob);

            callback(url, '');

            this.changeContents();

            return false;
        },

        setFrame(){
            if(this.category === "전자제품")
                this.editor.setHTML("<p>1.제품<br/><br/>2.브랜드<br/><br/>3.모델명<br/><br/>4.증상<br/><br/>5.연락처<br/><br/>6.방문가능 일자 및 시간<br/><br/>7.사진 및 동영상 첨부(필수/ 대용량 파일은 하단 첨부파일을 통해 업로드)</p>");

            if(this.category === "기타수리")
                this.editor.setHTML("<p>1.위치<br/><br/>2.제품<br/><br/>3.증상<br/><br/>4.연락처<br/><br/>5.방문가능 일자 및 시간<br/><br/>6.사진 및 동영상 첨부(필수/ 대용량 파일은 하단 첨부파일을 통해 업로드)</p>");
        }
    },

    mounted() {
        let self = this;

        this.editor = new toastui.Editor({
            el: document.querySelector('#editor'),
            previewStyle: 'vertical',
            height: '500px',
            initialValue: this.value,
            initialEditType: "wysiwyg",
            options: {
                minHeight: '200px',
                language: 'en-US',
                useCommandShortcut: true,
                usageStatistics: true,
                hideModeSwitch: true,
                toolbarItems: [
                    ['heading', 'bold', 'image'],
                ]
            },
            toolbarItems: [
                ['heading', 'bold', 'image'],
            ],
            hooks: {
                addImageBlobHook: self.onUploadImage
            },
            events: {
                change: self.changeContents
            }
        });

        this.setFrame();
    },

    watch: {
        value: function(value, oldValue) {
            this.$emit("change", value);
        },
        category: function(value, oldValue) {
            this.setFrame();
        },
    }
}
</script>
