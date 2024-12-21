<template>
    <div class="files-wrap">
        <div class="box-title">
            <h3 class="title">
                <i class="xi-paperclip"></i>
                첨부파일
            </h3>

            <input type="file" id="f" multiple @change="changeFile">
            <label for="f" class="m-btn type02" v-if="!disabled">
                <i class="xi-download"></i>
                파일 업로드
            </label>
        </div>

        <div class="files">
            <div class="empty" v-if="defaultFiles.length === 0 && files.length === 0">첨부파일이 존재하지 않습니다.</div>

            <div class="file" v-for="(file, index) in defaultFiles" :key="index">
                <a :href="file.url" class="title">
                    <i class="xi-paperclip"></i>
                    {{ file.name }}
                </a>
            </div>

            <div class="file" v-for="(file, index) in files" v-if="defaultFiles.length === 0" :key="index">
                <div class="title">
                    <i class="xi-paperclip"></i>
                    {{ file.name }}
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        default: {
            default() {
                return []
            }
        },
        required: {
            default: true
        },
        title: {
            required: false,
        },
        multiple: {
            default: false
        },
        disabled: {
            default: false,
        }
    },

    data(){
        return {
            defaultFiles: this.default,
            files: []
        }
    },

    methods: {
        changeFile(event) {
            this.files = Array.from(event.target.files).map(file => {
                return {
                    name: file.name,
                    file: file,
                    thumbnail: URL.createObjectURL(file),
                };
            });

            this.$emit("change", this.files);
        },
    },

}
</script>
