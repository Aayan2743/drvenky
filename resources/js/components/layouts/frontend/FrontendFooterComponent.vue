<template>
    <LoadingComponent :props="loading" />

    <footer class="pt-12 bg-secondary mobile:hidden">
        <div class="container">
            <div class="row">
                <div class="col-12 md:col-4 lg:col-5 mb-6 md:mb-0">
                    <div class="tablet:text-center tablet:mx-auto w-full max-w-xs">
                        <router-link :to="{ name: 'frontend.home' }">
                            <img class="mb-8 w-36" :src="setting.theme_footer_logo" alt="logo" >
                        </router-link>

                        <form @submit.prevent="saveSubscription" class="mt-5 mb-6 block">
                            <label class="mb-3 font-medium text-white">
                                {{ $t('message.subscribe_to_our_newsletter') }}
                            </label>
                            <div class="flex w-full h-10 rounded-3xl p-1 bg-white">
                                <input type="email" v-model="subscriptionProps.post.email"
                                    :placeholder="$t('label.your_email_address')" class="w-full h-full pl-3 pr-2">
                                <button type="submit"
                                    class="text-xs font-semibold capitalize flex-shrink-0 px-3 h-full rounded-3xl bg-primary text-white">
                                    {{ $t('button.subscribe') }}
                                </button>
                            </div>
                        </form>
                        <nav v-if="setting.social_media_facebook || setting.social_media_twitter || setting.social_media_instagram || setting.social_media_youtube"
                            class="flex flex-wrap items-center gap-6 tablet:justify-center">
                            <a v-if="setting.social_media_facebook" target="_blank" :href="setting.social_media_facebook"
                                class="lab-fill-facebook w-7 h-7 !leading-7 text-center rounded-full text-sm text-secondary bg-white transition-all duration-300 hover:text-white hover:bg-primary"></a>
                            <a v-if="setting.social_media_twitter" target="_blank" :href="setting.social_media_twitter"
                                class="lab-fill-x w-7 h-7 !leading-7 text-center rounded-full text-sm text-secondary bg-white transition-all duration-300 hover:text-white hover:bg-primary"></a>
                            <a v-if="setting.social_media_instagram" target="_blank" :href="setting.social_media_instagram"
                                class="lab-fill-instagram w-7 h-7 !leading-7 text-center rounded-full text-sm text-secondary bg-white transition-all duration-300 hover:text-white hover:bg-primary"></a>
                            <a v-if="setting.social_media_youtube" target="_blank" :href="setting.social_media_youtube"
                                class="lab-fill-youtube w-7 h-7 !leading-7 text-center rounded-full text-sm text-secondary bg-white transition-all duration-300 hover:text-white hover:bg-primary"></a>
                        </nav>
                    </div>
                </div>
                <div class="col-12 md:col-8 lg:col-7">
                    <div class="row">
                        <div class="col-6 sm:col-4 mb-4 sm:mb-0">
                            <h4 class="text-[22px] font-semibold capitalize mb-6 text-white">{{ $t('label.support') }}</h4>
                            <nav v-if="supportPages.length > 0" class="flex flex-col gap-4">
                                <router-link v-for="supportPage in supportPages"
                                    class="w-fit text-sm font-medium capitalize text-white transition-all duration-300 hover:text-primary"
                                    :to="{ name: 'frontend.page', params: { slug: supportPage.slug } }">
                                    {{ supportPage.title }}
                                </router-link>
                            </nav>
                        </div>
                        <div class="col-6 sm:col-4 mb-4 sm:mb-0">
                            <h4 class="text-[22px] font-semibold capitalize mb-6 text-white">{{ $t('label.legal') }}</h4>
                            <nav v-if="legalPages.length > 0" class="flex flex-col gap-4">
                                <router-link v-for="legalPage in legalPages"
                                    class="w-fit text-sm font-medium capitalize text-white transition-all duration-300 hover:text-primary"
                                    :to="{ name: 'frontend.page', params: { slug: legalPage.slug } }">
                                    {{ legalPage.title }}
                                </router-link>
                            </nav>
                        </div>
                        <div class="col-12 sm:col-4">
                            <h4 class="text-[22px] font-semibold capitalize mb-6 text-white">
                                {{ $t('label.contact') }}</h4>
                            <ul class="flex flex-col gap-5">
                                <li class="flex gap-3">
                                    <i class="lab-fill-location text-sm flex-shrink-0 text-white"></i>
                                    <span class="text-sm font-medium text-white">{{ setting.company_address }}</span>
                                </li>
                                <li class="flex gap-3">
                                    <i class="lab-fill-mail text-sm flex-shrink-0 text-white"></i>
                                    <span class="text-sm font-medium text-white">{{ setting.company_email }}</span>
                                </li>
                                <li class="flex gap-3">
                                    <i class="lab-fill-calling text-sm flex-shrink-0 text-white"></i>
                                    <span class="text-sm font-medium text-white">{{ setting.company_phone }}</span>
                                </li>

                                <li class="flex gap-3">
                                        <i class="lab-fill-mail text-sm flex-shrink-0 text-white"></i>
                                        <button @click="toggleQueryForm" class="text-sm font-medium text-white underline hover:text-primary">
                                            Contact Us Form
                                        </button>
                                    </li>
                            </ul>

                            <dl class="mt-6">
                                <dd class="flex gap-3">
                                    <a target="_blank" class="router-link-active router-link-exact-active"
                                        v-if="setting.site_android_app_link" :href="setting.site_android_app_link">
                                        <img class="h-8 rounded-lg" :src="setting.image_play_store" alt="app"
                                            >
                                    </a>
                                    <a target="_blank" class="router-link-active router-link-exact-active"
                                        v-if="setting.site_ios_app_link" :href="setting.site_ios_app_link">
                                        <img class="h-8 rounded-lg" :src="setting.image_app_store" alt="app">
                                    </a>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="pt-4 pb-24 lg:py-4 mt-8 text-center border-t border-white/5">
            <p class="text-xs font-medium text-white">{{ appName }}</p>
            <!-- <p class="text-xs font-medium text-white">{{ setting.site_copyright }}</p> -->
            <!-- <p class="text-xs font-medium text-white">{{ process.env.MIX_HOST  }}</p> -->
            <!-- <p class="text-xs font-medium text-white">{{ config('app.name') }}</p> -->
            <!-- <span class="text-sm font-medium text-white">{{ config('app.company_phone') }}</span> -->
        </div>
    </footer>



    <div v-if="showQueryForm" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
    <div class="bg-white rounded-lg p-6 w-96 modal-content">
        <h2 class="text-lg font-semibold mb-4">Query Form</h2>
        <form @submit.prevent="submitQueryForm" class="flex flex-col items-center justify-center">
            <div class="mb-4 w-full">
                <label class="block mb-1 text-sm font-medium">Name</label>
                <input type="text" v-model="queryForm.name" class="w-full h-10 px-3 border rounded-md" required />
            </div>
            <div class="mb-4 w-full">
                <label class="block mb-1 text-sm font-medium">Email</label>
                <input type="email" v-model="queryForm.email" class="w-full h-10 px-3 border rounded-md" required />
            </div>
            <div class="mb-4 w-full">
                    <label class="block mb-1 text-sm font-medium">Phone</label>
                    <input 
                        type="tel" 
                        v-model="queryForm.phone" 
                        class="w-full h-10 px-3 border rounded-md" 
                        required 
                        pattern="[0-9]{10}" 
                        title="Phone number must be exactly 10 digits."
                    />
                </div>

            <div class="mb-4 w-full">
                <label class="block mb-1 text-sm font-medium">Message</label>
                <textarea v-model="queryForm.message" class="w-full h-24 px-3 border rounded-md" required></textarea>
            </div>
            <div class="flex justify-end w-full">
                <button type="button" @click="toggleQueryForm" class="px-4 py-2 mr-2 bg-gray-200 rounded-md hover:bg-gray-300">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-dark">
                    Submit
                </button>
            </div>
        </form>
    </div>
</div>




</template>


<script>
import statusEnum from "../../../enums/modules/statusEnum";
import axios from "axios";
import alertService from "../../../services/alertService";
import LoadingComponent from "../../frontend/components/LoadingComponent";
import menuSectionEnum from "../../../enums/modules/menuSectionEnum";
import _ from "lodash";

export default {
    name: "FrontendFooterComponent",
    components: { LoadingComponent },
    data() {
        return {
            
            appName: process.env.MIX_HOST1,
            loading: {
                isActive: false,
            },
            legalPages: [],
            supportPages: [],
            enums: {
                statusEnum: statusEnum,
                menuSectionEnum: menuSectionEnum
            },
            subscriptionProps: {
                post: {
                    email: ""
                }
            },
            showQueryForm: false, // For toggling the query form modal
            queryForm: { // Data for the query form
                name: "",
                email: "",
                phone: "",
                message: ""
            }
        }
    },
    computed: {
        setting: function () {
            return this.$store.getters['frontendSetting/lists'];
        }
    },
    mounted() {
        this.loading.isActive = true;
        this.$store.dispatch("frontendPage/lists", {
            paginate: 0,
            order_column: "id",
            order_type: "asc",
            status: this.enums.statusEnum.ACTIVE
        }).then(res => {
            if (res.data.data.length > 0) {
                _.forEach(res.data.data, (page) => {
                    if (page.menu_section_id === this.enums.menuSectionEnum.LEGAL) {
                        this.legalPages.push(page);
                    } else {
                        this.supportPages.push(page);
                    }
                });
            }
            this.loading.isActive = false;
        }).catch((err) => {
            this.loading.isActive = false;
        });
    },
    methods: {
        saveSubscription: function () {
            const url = '/frontend/subscriber';
            this.loading.isActive = true;
            axios.post(url, this.subscriptionProps.post).then(res => {
                this.loading.isActive = false;
                this.subscriptionProps.post.email = "";
                alertService.success(this.$t("message.subscribe"));
            }).catch((err) => {
                this.loading.isActive = false;
            });
        },
        toggleQueryForm() {
            this.showQueryForm = !this.showQueryForm;
        },
       
        async submitQueryForm() {
    const apiUrl = process.env.MIX_HOST; // Base API URL from environment variables
    const endpoint = `${apiUrl}/api/query_form`; // Define the API endpoint
    this.loading.isActive = true; // Activate loading indicator

    try {
        // Send the query form data to the backend
        const response = await axios.post(endpoint, this.queryForm);

        // Handle success response
        this.loading.isActive = false;
        alertService.success(this.$t("message.query_submitted"));
        
        // Reset the form and close the modal
        this.queryForm = { name: "", email: "", message: "" ,phone:""};
        this.showQueryForm = false;
    } catch (error) {
        // Handle error response
        this.loading.isActive = false;

        // Check if server returned validation errors
        if (error.response && error.response.data.errors) {
            const validationErrors = error.response.data.errors;
            alertService.error(
                Object.values(validationErrors).flat().join(" ")
            );
        } else {
            alertService.error(this.$t("message.query_failed"));
        }
    }
}




    }
}
</script>

<style scoped>
    .modal {
        position: fixed;
        inset: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        background: rgba(0, 0, 0, 0.5);
        z-index: 50;
    }

    .modal-content {
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        max-width: 500px;
        width: 100%;
    }
</style>