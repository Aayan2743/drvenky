<template>
    <div v-if="products.length > 0" v-for="product in products"
        class="sm:p-2 miron rounded-2xl sm:shadow-card transition-all duration-300 sm:hover:shadow-hover group">
        <div class="relative overflow-hidden rounded-xl isolate">
            <label
                class="capitalize text-xs font-semibold rounded-xl py-1 px-2 shadow-badge absolute top-3 left-3 z-10 bg-secondary text-white"
                v-if="product.is_offer && product.flash_sale">{{ $t('label.flash_sale') }}</label>

            <button type="button" @click.prevent="wishlist(product, product.wishlist = !product.wishlist)"
                :class="product.wishlist ? 'lab-fill-heart text-primary' : 'lab-line-heart'"
                class="w-7 h-7 leading-7 rounded-full text-center text-base shadow-badge absolute top-3 right-3 z-10 bg-white">
            </button>

            <router-link class="overflow-hidden rounded-xl w-full"
                :to="{ name: 'frontend.product.details', params: { slug: product.slug } }">
                <img :src="product.cover" alt="product"
                    class="w-full rounded-xl transition-all duration-300 group-hover:scale-105 group-hover:rotate-3">
            </router-link>
        </div>

        <div class="px-1 sm:px-0 pt-4 pb-2">
            <h3
                class="capitalize text-base font-semibold whitespace-nowrap transition-all duration-300 hover:text-primary">
                <router-link class="block overflow-hidden text-ellipsis"
                    :to="{ name: 'frontend.product.details', params: { slug: product.slug } }">
                    {{ product.name }}
                </router-link>
            </h3>

            <div class="flex flex-wrap items-center gap-2 mb-5">
                <div class="flex items-center gap-1">
                    <starRating border-color="#FFBC1F" :rounded-corners="true" :padding="2.5" :border-width="2.5"
                        :star-size="9" class="mt-[2px]" inactive-color="#FFFFFF" active-color="#FFBC1F"
                        :round-start-rating="false" :show-rating="false" :read-only="true" :max-rating="5"
                        :rating="(product.rating_star / product.rating_star_count)" />
                </div>
                <div v-if="product.rating_star_count > 0" class="flex items-center gap-1 mt-[5px]">
                    <span class="text-xs font-medium whitespace-nowrap text-text">{{ (product.rating_star /
                        product.rating_star_count).toFixed(1) }}</span>
                    <span class="text-xs font-medium whitespace-nowrap text-text hover:text-primary">({{
                        product.rating_star_count }} {{ product.rating_star_count > 1 ? $t('label.reviews') :
                            $t('label.review') }})</span>
                </div>
            </div>

            <div class="flex flex-wrap-reverse items-center gap-x-3 gap-y-1" v-if="product.is_offer">
                <h3 class="text-xl sm:text-[22px] font-bold">
                    <span>{{ product.discounted_price }}</span>
                </h3>
                <h4 class="text-sm sm:text-base font-semibold text-shopperz-red">
                    <del>{{ product.currency_price }}</del>
                </h4>
            </div>
            <h4 class="text-xl sm:text-[22px] font-bold" v-else>
                <span>{{ product.currency_price }}</span>
            </h4>
        </div>
    </div>
</template>

<script>

import starRating from "vue-star-rating";
import router from "../../../router";
export default {
    name: "ProductListComponent",
    components: {
        starRating
    },
    props: {
        "products": "object",
    },
    data() {
        return {
            rating: []
        }
    },
    methods: {
        wishlist: function (product, toggle) {
            this.$store.dispatch("frontendWishlist/toggle", {
                product_id: product.id,
                toggle: toggle
            }).then((res) => {
            }).catch((err) => {
                if (err.response.status === 401) {
                    product.wishlist = false;
                    router.push({ name: "auth.login" });
                }
            });
        },
    }
}
</script>