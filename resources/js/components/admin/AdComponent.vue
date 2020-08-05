<template>
<div>
    <div class="ui grid">
        <div class="column">
            <div>
                <h3 class="ui small header">Publish Ad</h3>
                <div class="ui fluid form">
                    <div class="ui field">
                        <label>Description</label>
                        <textarea v-model="description"></textarea>
                    </div>
                    <div class="ui two fluid fields">
                        <div class="field">
                            <input type="text" name="first-name" placeholder="Url" v-model="link">
                        </div>
                        <div class="field">
                            <select class="ui dropdown" v-model="position">
                                <option value="1">Front Page</option>
                                <option value="2">Post Create Page</option>
                            </select>
                        </div>
                    </div>
                    <div class="ui two fluid fields">
                        <div class="field">
                            <label>Select Image</label>
                            <button class="ui green labeled icon button choose-images-btn">
                                <i class="upload icon"></i>
                                Upload
                            </button>
                            <span v-show="filesCount > 0">{{filesCount}} image selected</span>
                            <input ref="file" v-on:change="handleFilesChange()" name="images" type="file" class="d-none image-input" accept="image/x-png,image/gif,image/jpeg">
                        </div>
                        <div class="field">
                        </div>
                    </div>
                    <div class="field">
                        <div class="ui red small header" v-show="formError">{{formError}}</div>
                    </div>
                    <div class="field">
                        <button class="ui button bg-custom" @click="saveAd()">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
    import NProgress from "nprogress";
    import Vue from "vue";

    export default {
        data() {
            return {
                link: '',
                description: '',
                images: '',
                position: 1,
                filesCount : 0,
                formError: '',
            }
        },
        mounted() {

        },
        methods: {
            saveAd() {
                if(this.position && this.description.length > 0 && this.filesCount > 0 && this.link.length > 0){
                    let formData = new FormData();
                    let images = this.images;
                    for( var i = 0; i < images.length; i++ ){
                        let file = images[i];
                        formData.append('images[' + i + ']', file);
                    }

                    formData.append('position',  this.position);
                    formData.append('description',  this.description);
                    formData.append('link',  this.link);

                    let url = '/api/ads';
                    NProgress.start();
                    axios.post(url, formData).then(response => {
                        NProgress.done();
                        Vue.$toast.success(response.data.message);
                        this.link = '';
                        this.description = '';
                        this.images = '';
                        this.position = 1;
                        this.filesCount = 0;
                        this.formError= ''
                    }).catch(error => {
                        NProgress.done();
                        let response = error.response;
                        Vue.$toast.error(response.data.message);
                    });
                }
                else{
                    this.formError = 'Fill all fields';
                }

            },
            handleFilesChange() {
                this.images = this.$refs.file.files;
                this.filesCount = this.$refs.file.files.length
            },
        }
    }
</script>

<style scoped>

</style>
