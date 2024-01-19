<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, Link, router, usePage} from '@inertiajs/vue3';

import {CheckIcon} from "@heroicons/vue/24/solid";
import {InformationCircleIcon} from "@heroicons/vue/24/outline";
import {computed} from "vue";

const customer = usePage().props.auth.user;

const {pricing} = defineProps({
    pricing: Object,
})


const firstPlanData = {
    plan_name: pricing.first_plan_name,
};

const secondPlanData = {
    plan_name: pricing.second_plan_name,
};

const updgradePlan = (value) => {
    router.visit('/checkout', {
        data: value,
        onSuccess: page => {
        },
    })
}

const formattedRibbonText = computed(() => {
    return pricing.premier_promo_label.replace(/(\$\d+)/, '<span class="font-black text-2xl">$1</span>');
});

</script>

<template>
    <Head title="Upgrade Plan"/>

    <AuthenticatedLayout>

        <div class="mt-40">
            <h1 class="font-dmSerif-display text-center text-6xl text-gray-900 mb-4">{{ pricing.page_title }}</h1>
            <p class="text-gray-500 text-xl text-center">{{ pricing.page_desc }}</p>
            <div class="grid grid-cols-2 gap-10 mt-12 max-w-[840px] mx-auto">
                <div class="p-8 rounded-lg border border-gray-200 bg-white flex flex-col justify-between">
                    <div>
                        <h4 class="text-center text-gray-900 leading-none text-xl font-bold mb-4">{{ pricing.first_plan_name }}</h4>
                        <h1 class="text-center leading-none text-5xl font-extrabold text-gray-900 mb-4">{{
                                pricing.first_plan_title
                            }}</h1>
                        <p class="text-base font-normal text-center text-gray-500">{{ pricing.first_plan_desc }}</p>
                        <ul class="mt-8">
                            <li class="flex items-center justify-between mb-4"
                                v-for="(item, i) in JSON.parse(pricing.first_plan_list_items)" :key="i">
                                <div class="flex items-center">
                                    <CheckIcon class="h-4 w-4"/>
                                    <span class="ms-2 text-base font-normal text-gray-900">{{ item.label }}</span>
                                </div>
                                <div class="relative group" v-if="item.tooltip">
                                    <InformationCircleIcon class="w-4 h-4 cursor-pointer"/>
                                    <div
                                        class="hidden group-hover:block rounded bg-gray-900 py-2 px-3 absolute bottom-8 left-1/2 -translate-x-1/2 after:absolute after:w-4 after:h-4 after:-bottom-2 after:left-1/2 after:-translate-x-1/2 after:bg-gray-900 after:rotate-45 w-48">
                                        <p class="leading-tight text-xs font-normal text-gray-200">{{ item.tooltip }}</p>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <Link
                        @click.prevent="updgradePlan(secondPlanData)"
                        v-if="customer.user_type !== 1"
                        class="w-full p-2 text-center rounded-sm text-sm font-semibold block mt-12 text-white bg-primary-700">
                        {{ pricing.first_plan_button_title }}
                    </Link>
                    <a v-else
                       disabled
                       class="w-full p-2 text-center cursor-not-allowed rounded-sm text-sm font-semibold block mt-12 text-gray-400 bg-gray-200"
                    >
                        Current plan
                    </a>
                </div>
                <div class="relative p-8 rounded-lg border border-gray-200 bg-white flex flex-col justify-between">
                    <div>
                        <div class="w-[116px] h-[116px] absolute -top-7 -right-7 flex items-center justify-center px-1" v-if="pricing.is_premier_promo === 1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="absolute z-0 right-0 top-0 w-full" width="116" height="115"
                                 viewBox="0 0 116 115" fill="none">
                                <path
                                    d="M56.7314 1.04078C57.4689 0.435719 58.5311 0.435719 59.2686 1.04078L64.8585 5.62695C65.4262 6.09278 66.2036 6.20995 66.8835 5.93218L73.5768 3.19741C74.4599 2.8366 75.4749 3.14968 76.0012 3.94524L79.991 9.9753C80.3962 10.5878 81.1045 10.9289 81.8361 10.8639L89.0381 10.2235C89.9883 10.139 90.8659 10.7373 91.1344 11.6527L93.1695 18.5909C93.3762 19.2956 93.9525 19.8303 94.6707 19.9838L101.742 21.4947C102.674 21.694 103.337 22.5245 103.323 23.4783L103.223 30.7081C103.213 31.4424 103.606 32.1233 104.247 32.4816L110.558 36.0096C111.391 36.475 111.779 37.4638 111.485 38.3713L109.258 45.2503C109.032 45.949 109.207 46.7155 109.714 47.2469L114.705 52.4784C115.364 53.1686 115.443 54.2278 114.895 55.0084L110.739 60.9254C110.317 61.5264 110.258 62.3104 110.586 62.9676L113.813 69.4379C114.239 70.2915 114.003 71.327 113.249 71.9114L107.534 76.3406C106.953 76.7905 106.666 77.5223 106.786 78.2469L107.962 85.381C108.118 86.3222 107.587 87.2421 106.694 87.5782L99.9272 90.1261C99.2399 90.3849 98.7497 90.9996 98.6503 91.7272L97.672 98.8912C97.543 99.8364 96.7643 100.559 95.8122 100.617L88.5951 101.057C87.8621 101.102 87.2125 101.545 86.9031 102.211L83.8566 108.768C83.4547 109.633 82.4977 110.094 81.5707 109.869L74.5446 108.162C73.8309 107.989 73.0797 108.221 72.5876 108.766L67.7437 114.134C67.1047 114.842 66.0544 115 65.2349 114.512L59.0239 110.81C58.3931 110.434 57.6069 110.434 56.9761 110.81L50.7651 114.512C49.9456 115 48.8953 114.842 48.2563 114.134L43.4124 108.766C42.9204 108.221 42.1691 107.989 41.4554 108.162L34.4293 109.869C33.5023 110.094 32.5453 109.633 32.1434 108.768L29.0969 102.211C28.7875 101.545 28.1379 101.102 27.4049 101.057L20.1878 100.617C19.2357 100.559 18.457 99.8364 18.328 98.8912L17.3497 91.7272C17.2503 90.9996 16.7601 90.3849 16.0728 90.1261L9.30615 87.5782C8.41341 87.2421 7.88233 86.3222 8.03759 85.381L9.21438 78.2469C9.33391 77.5223 9.04669 76.7905 8.4662 76.3406L2.75115 71.9114C1.99715 71.327 1.7608 70.2915 2.18659 69.4379L5.41391 62.9676C5.74171 62.3104 5.68296 61.5264 5.26087 60.9254L1.10525 55.0084C0.556991 54.2278 0.636366 53.1686 1.29485 52.4784L6.28593 47.2469C6.79288 46.7155 6.96782 45.949 6.74163 45.2503L4.5147 38.3713C4.2209 37.4638 4.60895 36.475 5.44162 36.0096L11.753 32.4816C12.394 32.1233 12.7871 31.4424 12.7769 30.7081L12.6766 23.4783C12.6633 22.5245 13.3256 21.694 14.2584 21.4947L21.3293 19.9838C22.0475 19.8303 22.6238 19.2956 22.8305 18.5909L24.8656 11.6527C25.1341 10.7373 26.0117 10.139 26.9619 10.2235L34.1639 10.8639C34.8955 10.9289 35.6038 10.5878 36.009 9.9753L39.9988 3.94524C40.5251 3.14968 41.5401 2.8366 42.4232 3.19741L49.1165 5.93218C49.7964 6.20995 50.5738 6.09278 51.1415 5.62695L56.7314 1.04078Z"
                                    fill="#CBEF43"/>
                            </svg>
                            <p class="uppercase leading-none font-black text-center relative z-1">{{ pricing.premier_promo_badge }}</p>
                        </div>

                        <h4 class="text-center text-gray-900 leading-none text-xl font-bold mb-4">{{
                                pricing.second_plan_name
                            }}</h4>
                        <h1 class="text-center leading-none text-5xl font-extrabold text-gray-900 mb-4" :class="{'line-through': pricing.is_premier_promo === 1}">{{
                                pricing.second_plan_title
                            }}</h1>
                        <div class="text-center" v-if="pricing.is_premier_promo === 1">
                            <div class="relative mb-2 inline-block">
                                <svg class="absolute z-0 top-2 -left-5" xmlns="http://www.w3.org/2000/svg" width="28" height="37" viewBox="0 0 28 37" fill="none">
                                    <path d="M0.5 0H22.8043H27.5V30.4222V37H0.5L9.3587 18.5L0.5 0Z" fill="black"/>
                                </svg>
                                <p class="relative z-10 bg-primary-900 text-lime-700 px-2 py-1.5 text-xl font-black max-w-72 text-center" v-html="formattedRibbonText"></p>
                                <svg class="absolute z-0 top-2 -right-5" xmlns="http://www.w3.org/2000/svg" width="28" height="37" viewBox="0 0 28 37" fill="none">
                                    <path d="M27.5 0H5.19565H0.5V30.4222V37H27.5L18.6413 18.5L27.5 0Z" fill="black"/>
                                </svg>
                            </div>
                        </div>

                        <p class="text-base font-normal text-center text-gray-500">{{ pricing.second_plan_desc }}</p>
                        <ul class="mt-8">
                            <li class="flex items-center justify-between mb-4"
                                v-for="(item, i) in JSON.parse(pricing.second_plan_list_items)" :key="i">
                                <div class="flex items-center">
                                    <CheckIcon class="h-4 w-4"/>
                                    <span class="ms-2 text-base font-normal text-gray-900">{{ item.label }}</span>
                                </div>
                                <div class="relative group" v-if="item.tooltip">
                                    <InformationCircleIcon class="w-4 h-4 cursor-pointer"/>
                                    <div
                                        class="hidden group-hover:block rounded bg-gray-900 py-2 px-3 absolute bottom-8 left-1/2 -translate-x-1/2 after:absolute after:w-4 after:h-4 after:-bottom-2 after:left-1/2 after:-translate-x-1/2 after:bg-gray-900 after:rotate-45 w-48">
                                        <p class="leading-tight text-xs font-normal text-gray-200">{{ item.tooltip }}</p>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <Link
                        v-if="customer.user_type !== 2"
                        @click.prevent="updgradePlan(secondPlanData)"
                        class="w-full p-2 text-center rounded-sm text-sm font-semibold block mt-12 text-white bg-primary-700">
                        {{ pricing.second_plan_button_title }}
                    </Link>
                    <a v-else
                       disabled
                       class="w-full p-2 text-center cursor-not-allowed rounded-sm text-sm font-semibold block mt-12 text-gray-400 bg-gray-200"
                    >
                        Current plan
                    </a>
                </div>
            </div>
        </div>


    </AuthenticatedLayout>
</template>
