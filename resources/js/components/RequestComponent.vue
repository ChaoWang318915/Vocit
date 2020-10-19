<template>
    <div>
        <div class="sixteen wide tablet twelve wide computer column">
            <div class="ui red message" v-if="!isPostable">
                <div class="header">You have 0 posts left.</div>
                <p>
                    Please increase your post create limit by visting
                    <a :href="'/' + businessDomain + '/profile'" class="link"
                        >account page</a
                    >.
                </p>
            </div>

            <div
                class="ui green message"
                v-if="postLimit > 0 && role !== 'redeeming'"
            >
                <div class="header">You have {{ postLimit }} posts left.</div>
            </div>

            <div v-if="role !== 'redeeming' && isPostable">
                <div class="ui header" v-if="!isEditing">
                    Create a Request Post
                </div>
                <div class="ui header" v-if="isEditing">Edit Post</div>
                <div class="ui fluid card">
                    <div class="content">
                        <div class="ui form create-form">
                            <div class="field" v-show="0">
                                <label
                                    >Request Type:
                                    <label
                                        ><input
                                            class="pl-2"
                                            value="1"
                                            v-model="request_type"
                                            name="request_type"
                                            type="radio"
                                        />
                                        Request</label
                                    >
                                    <label
                                        ><input
                                            class="pl-2"
                                            value="2"
                                            v-model="request_type"
                                            name="request_type"
                                            type="radio"
                                        />
                                        AD Request</label
                                    >
                                </label>
                            </div>
                            <div class="field">
                                <vue-dropzone
                                    ref="myVueDropzone"
                                    id="dropzone"
                                    :options="dropzoneOptions"
                                    @vdropzone-complete="filesUploaded($event)"
                                    @vdropzone-sending="disbalePostButton"
                                >
                                    <div class="dropzone-custom-content">
                                        <h3 class="dropzone-custom-title">
                                            Drag and drop to upload content!
                                        </h3>
                                        <div class="subtitle">
                                            ...or click to select a file from
                                            your computer
                                        </div>
                                    </div>
                                </vue-dropzone>
                            </div>
                            <div class="field" v-if="previousImage">
                                <label>Previous Image</label>
                                <img
                                    class="ui image"
                                    v-bind:src="previousImage"
                                />
                            </div>
                            <div class="field">
                                <label>What they will get?</label>
                                <input
                                    type="text"
                                    placeholder="Start with get... (required)"
                                    maxlength="120"
                                    v-model="shortDescription"
                                    @keyup="checkShortDescriptionLimit($event)"
                                    @keydown="
                                        checkShortDescriptionLimit($event)
                                    "
                                />
                                <span class="text-fade"
                                    >{{ 120 - shortDescLimit }} Characters
                                    left</span
                                >
                            </div>
                            <div class="field">
                                <label>Add Rules of Request</label>
                                <textarea
                                    placeholder="Add Rules of Request"
                                    maxlength="400"
                                    v-model="content"
                                    @keyup="checkLongDescriptionLimit($event)"
                                    @keydown="checkLongDescriptionLimit($event)"
                                ></textarea>
                                <span class="text-fade"
                                    >{{ 400 - longDescLimit }} Characters
                                    left</span
                                >
                            </div>
                            <div class="two fields">
                                <div class="field middle aligned">
                                    <label>Coupon</label>
                                    <input
                                        class="coupon-input popup-element"
                                        minlength="4"
                                        v-model="coupon"
                                        type="text"
                                        placeholder="Enter 4 digit coupon code"
                                        maxlength="4"
                                        data-content="If you dont set any coupon value a 4 digit value will be set"
                                    />
                                </div>
                                <!--                                <div class="field">-->
                                <!--                                    <label>Expires In</label>-->
                                <!--                                    <input class="coupon-input popup-element" v-model="expiresIn" type="text" placeholder="Days" maxlength="2">-->
                                <!--                                </div>-->
                            </div>
                        </div>
                    </div>
                </div>

                <button
                    class="ui btn-primary button action-button"
                    @click="updatePost()"
                    v-if="!isEditing"
                >
                    Create
                </button>
                <button
                    class="ui btn-primary button action-button"
                    @click="updatePost()"
                    v-if="isEditing"
                >
                    Update
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import $ from "jquery";
import Vue from "vue";
import vue2Dropzone from "vue2-dropzone";
import "vue2-dropzone/dist/vue2Dropzone.min.css";
import VueToast from "vue-toast-notification";
import "vue-toast-notification/dist/theme-default.css";
import NProgress from "nprogress";
import "nprogress/nprogress.css";
Vue.use(VueToast);

export default {
    props: [
        "apiToken",
        "editablePost",
        "accessRole",
        "postLimit",
        "activeBusinessName"
    ],
    components: {
        vueDropzone: vue2Dropzone
    },
    data() {
        return {
            postId: "",
            coupon: "",
            content: "",
            shortDescription: "Get ",
            cardNumber: "",
            cardCvc: "",
            cardYear: "",
            cardMonth: "",
            filesCount: 0,
            formError: "",
            loginToken: "",
            isUploaded: "",
            previousImage: "",
            apiUrl: this.$session.get("api_url"),
            isEditing: false,
            role: "",
            expiresIn: 30,
            isDraft: 0,
            request_type: 1,
            hasActivePayment: true,
            hasActiveSubscription: false,
            paymentPackages: "",
            choosenPlan: "",
            isPostable: false,
            businessDomain: "",
            shortDescLimit: 4,
            longDescLimit: 0,
            dropzoneOptions: {
                url: "/api/posts/draft",
                maxFiles: 1,
                paramName: "file",
                uploadMultiple: false,
                maxFilesize: 1,
                acceptedFiles: "image/*",
                timeout: 100 * 1000,
                params: {
                    post_id:
                        this.editablePost &&
                        Object.keys(this.editablePost).length > 0
                            ? this.editablePost.id
                            : ""
                    // request_type: this.request_type
                },
                headers: {
                    Authorization: "Bearer " + this.apiToken
                },
                init: function() {
                    this.on("addedfile", function(file) {
                        if (this.files.length > 1) {
                            this.removeFile(this.files[0]);
                        }
                    });
                }
            }
        };
    },
    mounted() {
        this.loginToken = this.apiToken;
        this.role = this.accessRole;
        let editablePost = this.editablePost;
        this.hasActivePayment = this.hasPaid;
        this.hasActiveSubscription = this.hasSubscription;
        this.paymentPackages = this.paymentPlans;

        this.isPostable = this.postLimit > 0;
        this.businessDomain = this.activeBusinessName;
        let $this = this;
        if (this.editablePost && Object.keys(this.editablePost).length > 0) {
            this.isEditing = true;
            this.postId = this.editablePost.id;
            this.content = this.editablePost.content;
            this.shortDescription = this.editablePost.short_description;
            this.coupon = this.editablePost.coupon;
            this.expiresIn = this.editablePost.expires_in
                ? this.editablePost.expires_in
                : "";
            this.previousImage = this.editablePost.attachments[0].thumb_url;
        }

        $(document).on("click", ".payment-plan-go", function() {
            $(".payment-plan").removeClass("green");
            $(this).addClass("green");
            $this.choosenPlan = $(this).attr("data-plan");
        });
    },
    methods: {
        isValidData() {
            if (this.content.length > 0 && this.shortDescription.length > 4) {
                return true;
            }

            return false;
        },
        updatePost(choosePayment = false) {
            if (this.request_type === 1) {
                if (!this.isEditing && this.filesCount === 0) {
                    this.formError = "Please add an image.";
                    return false;
                }
            }

            if (!this.isValidData()) {
                this.formError = "Please fill all required fields";
                // console.log('Please fill all required fields')
                return false;
            }

            if (this.coupon.length < 4) {
                this.formError = "Please enter 4 digit coupon code";
                return false;
            }

            if (this.request_type === 1) {
                if (!this.postId && this.isEditing) {
                    this.formError = "Upload an image first";
                    return false;
                }
            }

            if (choosePayment && !this.choosenPlan) {
                this.formError = "Select a plan";
                return false;
            }

            let cardDetails = {
                number: this.cardNumber,
                year: this.cardYear,
                month: this.cardMonth,
                cvc: this.cardCvc
            };

            let postDetails = {
                coupon: this.coupon,
                content: this.content,
                expires_in: this.expiresIn,
                short_description: this.shortDescription,
                is_draft: choosePayment ? 1 : 0,
                is_editing: this.isEditing,
                request_type: this.request_type
            };

            // console.log(postDetails);

            let formData = {
                card: cardDetails,
                post: postDetails
            };

            NProgress.start();
            // if(!this.postId && !this.isEditing){
            //     axios.post('/api/posts', formData).then(response => {
            //         NProgress.done();
            //         Vue.$toast.success(response.data.message);
            //         let $this = this;

            //         if(choosePayment){
            //             window.location.href = '/payment?post=' + response.data.data.id + '&package=' + this.choosenPlan
            //         }
            //         else{
            //             setTimeout(() => {
            //                 window.location.href = '/'
            //             }, 1000)
            //         }
            //     }).catch(error => {
            //         NProgress.done();
            //         let response = error.response;
            //         this.formError = response.data.message
            //     });
            // }
            // else{
            const request = this.postId
                ? { method: "put", url: `/api/posts/${this.postId}` }
                : { method: "post", url: `/api/posts` };

            axios[request.method](request.url, formData)
                .then(response => {
                    NProgress.done();
                    Vue.$toast.success(response.data.message);
                    let $this = this;

                    if (choosePayment) {
                        window.location.href =
                            "/payment?post=" +
                            response.data.data.id +
                            "&package=" +
                            this.choosenPlan;
                    } else {
                        setTimeout(() => {
                            window.location.href = "/";
                        }, 1000);
                    }
                })
                .catch(error => {
                    NProgress.done();
                    let response = error.response;
                    this.formError = response.data.message;
                });
            // }
        },
        filesUploaded($event) {
            if ($event.xhr.status === 201) {
                let response = JSON.parse($event.xhr.response);
                console.log(response);
                this.filesCount = 1;
                this.postId = response.data.id;
                if (this.isEditing) {
                    this.previousImage = response.data.attachments[0].thumb_url;
                }
            }
            $(".action-button").removeClass("disabled");
        },
        checkShortDescriptionLimit($this) {
            this.shortDescLimit = this.shortDescription.length;

            if (120 - this.shortDescription.length === 0) {
                $this.preventDefault();
            }
        },
        checkLongDescriptionLimit($this) {
            this.longDescLimit = this.content.length;
            if (400 - this.content.length === 0) {
                $this.preventDefault();
            }
        },
        disbalePostButton(file, xhr, formData) {
            formData.append("request_type", this.request_type);
            $(".action-button").addClass("disabled");
        }
    },
    computed: {
        validateRequestData: function() {
            return this.firstName + " " + this.lastName;
        }
    }
};
</script>

<style scoped></style>
