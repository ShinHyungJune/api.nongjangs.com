<template>
    <li>
        <label>{{ title }}</label>
        <div class="img-box">
            <p v-if="files.length === 0">오른쪽 <span>파일찾기</span> 버튼을 눌러 썸네일 이미지를 등록해 주세요</p>
            <ul class="file-list" v-else>
                <li v-for="(file, index) in files" :key="index">{{ file.name }}</li>
            </ul>

            <input type="file" name="" id="a1" @change="changeFile" multiple v-if="multiple">
            <input type="file" name="" id="a1" @change="changeFile" v-else>
            <label for="a1" class="add-btn">
                파일찾기
            </label>
        </div>
    </li>

    <div>
        <div class="input-file-wrap" style="padding-top:10px; padding-bottom:10px;">
            <p>오른쪽 파일찾기 버튼을 눌러 썸네일 이미지를 등록해 주세요</p>
            <input type="file" name="f" id="f" @change="changeFile" accept="image/*">
            <label for="f">파일찾기</label>
        </div>

        <div class="file-imgs" v-if="defaultFiles.length > 0 && files.length === 0">
            <div class="file-img-wrap" v-for="(file, index) in defaultFiles" :key="index">
                <div class="m-ratioBox-wrap">
                    <div class="m-ratioBox">
                        <img :src="file.url" alt="">
                    </div>
                </div>
            </div>
        </div>

        <div class="file-imgs" v-else>
            <div class="file-img-wrap" v-for="(file, index) in files" :key="index">
                <div class="m-ratioBox-wrap">
                    <div class="m-ratioBox">
                        <img :src="file.thumbnail" alt="">
                    </div>
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
            required: true,
        },
        multiple: {
            default: false
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
