<template>
    <LoadingComponent :props="loading" />

    <div class="row p-4">
        <div v-if="images.length" class="col-12 sm:col-6 lg:col-5">
            <Swiper dir="ltr" :spaceBetween="10" :navigation="true" :thumbs="{ swiper: thumbsSwiper }" :modules="modules"
                class="gallery-swiper">
                <SwiperSlide v-for="(image, index) in images" :key="index" class="w-full">
                    <img class="w-full rounded-2xl" :src="image" alt="gallery" />
                </SwiperSlide>
            </Swiper>

            <Swiper dir="ltr" @swiper="setThumbsSwiper" :spaceBetween="12" :slidesPerView="4" :freeMode="true"
                :watchSlidesProgress="true" :modules="modules" class="thumb-swiper">
                <SwiperSlide v-for="(image, index) in images" :key="index"
                    class="w-full cursor-pointer rounded-lg border border-gray-200 transition-all duration-500">
                    <img class="w-full rounded-lg border-2 border-gray-200 transition-all duration-500" :src="image"
                        alt="gallery" />
                </SwiperSlide>
            </Swiper>
        </div>

        <div v-else class="col-12 sm:col-6 lg:col-5">
            <img :src="product.image" alt="products" class="w-full rounded-2xl">
        </div>

        <div class="col-12 sm:col-6 lg:col-7 lg:pl-10">
            <h2 class="text-3xl sm:text-4xl font-bold capitalize mb-5">{{ product.name }}</h2>
            <h3 class="flex items-start gap-4 mb-5">
                <span class="text-2xl font-bold">
                    {{
                        currencyFormat(temp.price, setting.site_digit_after_decimal_point,
                            setting.site_default_currency_symbol, setting.site_currency_position)
                    }}
                </span>
                <del v-if="product.is_offer" class="text-lg font-bold text-shopperz-red">
                    {{
                        currencyFormat(temp.oldPrice, setting.site_digit_after_decimal_point,
                            setting.site_default_currency_symbol, setting.site_currency_position)
                    }}
                </del>
            </h3>

            <div class="flex flex-wrap items-center gap-2 border-b border-gray-100 mb-6 pb-6">
                <starRating border-color="#FFBC1F" :rounded-corners="true" :padding="2.5" :border-width="2.5"
                    :star-size="11" class="-mt-0.5" inactive-color="#FFFFFF" active-color="#FFBC1F"
                    :round-start-rating="false" :show-rating="false" :read-only="true" :max-rating="5"
                    :rating="(product.rating_star / product.rating_star_count)" />
                <div v-if="product.rating_star_count > 0" class="flex items-center gap-1">
                    <span class="text-base font-medium whitespace-nowrap text-text">
                        {{ (product.rating_star / product.rating_star_count).toFixed(1) }}
                    </span>
                    <span class="text-base font-medium whitespace-nowrap text-text hover:text-primary cursor-pointer">
                        ({{
                            product.rating_star_count
                        }} {{ product.rating_star_count > 1 ? $t('label.reviews') : $t('label.review') }})
                    </span>
                </div>
            </div>

            <VariationComponent v-if="initialVariations.length > 0 && variationComponent" :method="selectedVariationMethod"
                :variations="initialVariations" />

            <dl class="flex flex-wrap items-center gap-x-6 gap-y-3 mb-8">
                <dt class="capitalize text-lg font-semibold">{{ $t('label.quantity') }}:</dt>
                <dd class="flex items-center gap-6">
                    <div class="flex items-center gap-1 w-20 p-1 rounded-full bg-[#F7F7FC]">
                        <button @click.prevent="quantityDecrement" type="button" :class="temp.quantity === 1 ? 'cursor-not-allowed': ''"
                            class="lab-fill-circle-minus text-lg leading-none transition-all duration-300 hover:text-primary"></button>
                        <input type="number" v-model="temp.quantity" v-on:keypress="onlyNumber($event)"
                            v-on:keyup="quantityUp" class="text-center w-full h-5 text-sm font-medium">
                        <button @click.prevent="quantityIncrement" type="button" :class="temp.stock === temp.quantity ? 'cursor-not-allowed': ''"
                            class="lab-fill-circle-plus text-lg leading-none transition-all duration-300 hover:text-primary"></button>
                    </div>
                    <div v-if="!initialVariations.length || selectedVariation != null">
                        <p v-if="temp.stock > 0" class="capitalize">
                            {{ $t('label.available') }}:
                            <b>({{ temp.stock }}) </b>
                            {{ product.unit }}
                        </p>
                        <p v-else class="capitalize text-danger">
                            {{ $t('label.stock_out') }}
                        </p>
                    </div>
                </dd>
            </dl>

            <dl v-if="temp.quantity > 1" class="flex flex-wrap items-center gap-x-6 gap-y-3 mb-8">
                <dt class="capitalize text-lg font-semibold">{{ $t('label.total_price') }}:</dt>
                <dd class="flex items-center gap-6 text-green-500 font-semibold text-lg">
                    {{ currencyFormat(temp.totalPrice, setting.site_digit_after_decimal_point, setting.site_default_currency_symbol, setting.site_currency_position) }}
                </dd>
            </dl>

            <div class="flex flex-wrap items-center gap-8 mb-10">
                <button @click.prevent="addToCart" 
                @keydown="handleKeydown" 
                :disabled="enableAddToCardButton" type="button"
                    :class="enableAddToCardButton === false ? 'shadow-btn-primary !bg-primary' : ''"
                    class="flex items-center gap-3 px-8 h-12 leading-12 rounded-full transition-all duration-500 bg-slate-400 text-white">
                    <i class="lab-line-bag text-xl"></i>
                    <span class="whitespace-nowrap font-bold">F2 to add</span>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import { ref } from "vue";
import { Swiper, SwiperSlide } from 'swiper/vue';
import { FreeMode, Navigation, Thumbs } from 'swiper/modules';
import LoadingComponent from "../components/LoadingComponent";
import starRating from "vue-star-rating";
import targetService from "../../../services/targetService";
import ProductListComponent from "../components/ProductListComponent";
import VariationComponent from "../components/VariationComponent";
import appService from "../../../services/appService";
import alertService from "../../../services/alertService";

export default {
    name: "ProductDetailsComponent",
    components: {
        VariationComponent,
        ProductListComponent,
        starRating,
        Swiper,
        SwiperSlide,
        LoadingComponent
    },
    setup() {
        const thumbsSwiper = ref(null);
        const setThumbsSwiper = (swiper) => {
            thumbsSwiper.value = swiper;
        };
        return {
            thumbsSwiper,
            setThumbsSwiper,
            modules: [FreeMode, Navigation, Thumbs],
            slug: ""
        }
    },
    props: {
        "method": { type: Function },
        "productId": { type: Number }
    },
    data() {
        return {
            loading: {
                isActive: false,
            },
            props: {
                search: {
                    product_id: null,
                    review_limit: 3
                }
            },
            enableAddToCardButton: true,
            selectedVariation: null,
            productArray: {},
            variationComponent: false,
            initProduct: {
                isVariation: false,
                variationId: null,
                sku: null,
                stock: 0,
                quantity: 1,
                discount: 0,
                price: 0,
                oldPrice: 0,
                totalPrice: 0
            },
            temp: {
                name: "",
                image: "",
                isVariation: false,
                variationId: null,
                productId: 0,
                sku: null,
                stock: 0,
                taxes: {},
                quantity: 1,
                discount: 0,
                price: 0,
                oldPrice: 0,
                totalPrice: 0
            },
        }
    },
    computed: {
        setting: function () {
            return this.$store.getters['frontendSetting/lists'];
        },
        categories: function () {
            return this.$store.getters["posProductCategory/ancestorsAndSelf"];
        },
        initialVariations: function () {
            return this.$store.getters["posProductVariation/initialVariation"];
        },
        product: function () {
            return this.$store.getters["posProduct/show"];
        },
        images: function () {
            return this.$store.getters["posProduct/showImages"];
        },
        reviews: function () {
            return this.$store.getters["posProduct/showReviews"];
        }
    },
    methods: {
        // Handle adding to cart
        addToCart() {
            this.loading.isActive = true;
            this.$store.dispatch('posCart/addToCart', this.temp)
                .then(response => {
                    this.loading.isActive = false;
                    alertService.success(this.$t('label.successfully_added'));
                }).catch(error => {
                    this.loading.isActive = false;
                    alertService.error(this.$t('label.failed_to_add'));
                });
        },
    }
};
</script>

<style scoped>
.gallery-swiper {
    max-width: 100%;
    max-height: 420px;
    margin-bottom: 10px;
}

.thumb-swiper {
    margin-top: 10px;
}

.thumb-swiper .swiper-slide {
    width: 60px;
    cursor: pointer;
}

.text-primary {
    color: #FFBC1F;
}

.text-shopperz-red {
    color: #F44336;
}

.text-text {
    color: #4A4A4A;
}
</style>
