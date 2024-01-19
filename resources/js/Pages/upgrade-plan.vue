<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

import { CheckIcon } from "@heroicons/vue/24/solid";
import { InformationCircleIcon } from "@heroicons/vue/24/outline";

</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>

        <div class="mt-40">
            <h1 class="font-dmSerif-display text-center text-6xl text-gray-900 mb-4">Upgrade your plan</h1>
            <p class="text-gray-500 text-xl text-center">Lorem ipsum dolor sit amet consectetur adipiscing elit Ut et massa
                mi.</p>
            <div class="grid grid-cols-2 gap-10 mt-12 max-w-3xl mx-auto">
                <div class="p-8 rounded-lg border border-gray-200 bg-white flex flex-col justify-between">
                    <div>
                        <h4 class="text-center text-gray-900 leading-none text-xl font-bold mb-4">Standard</h4>
                        <h1 class="text-center leading-none text-5xl font-extrabold text-gray-900 mb-4">$XXX/mo</h1>
                        <p class="text-base font-normal text-center text-gray-500">billed yearly, one year commitment</p>
                        <ul class="mt-8">
                            <li class="flex items-center justify-between mb-4">
                                <div class="flex items-center">
                                    <CheckIcon class="h-4 w-4" />
                                    <span class="ms-2 text-base font-normal text-gray-900">Access to entire award
                                        database</span>
                                </div>
                                <div class="relative group">
                                    <InformationCircleIcon class="w-4 h-4 cursor-pointer" />
                                    <div
                                        class="hidden group-hover:block rounded bg-gray-900 py-2 px-3 absolute bottom-8 left-1/2 -translate-x-1/2 after:absolute after:w-4 after:h-4 after:-bottom-2 after:left-1/2 after:-translate-x-1/2 after:bg-gray-900 after:rotate-45 w-48">
                                        <p class="leading-tight text-xs font-normal text-gray-200">Lorem ipsum dolor sit
                                            amet consectetur adipiscing elit Ut et massa mi. Aliquam in hendrerit urna.
                                            Pellentesque sit amet sapien.</p>
                                    </div>
                                </div>
                            </li>
                            <li class="flex items-center justify-between mb-4">
                                <div class="flex items-center">
                                    <CheckIcon class="h-4 w-4" />
                                    <span class="ms-2 text-base font-normal text-gray-900">3 create your own lists</span>
                                </div>
                                <div class="relative group">
                                    <InformationCircleIcon class="w-4 h-4 cursor-pointer" />
                                    <div
                                        class="hidden group-hover:block rounded bg-gray-900 py-2 px-3 absolute bottom-8 left-1/2 -translate-x-1/2 after:absolute after:w-4 after:h-4 after:-bottom-2 after:left-1/2 after:-translate-x-1/2 after:bg-gray-900 after:rotate-45 w-48">
                                        <p class="leading-tight text-xs font-normal text-gray-200">Lorem ipsum dolor sit
                                            amet consectetur adipiscing elit Ut et massa mi. Aliquam in hendrerit urna.
                                            Pellentesque sit amet sapien.</p>
                                    </div>
                                </div>
                            </li>
                            <li class="flex items-center justify-between mb-4">
                                <div class="flex items-center">
                                    <CheckIcon class="h-4 w-4" />
                                    <span class="ms-2 text-base font-normal text-gray-900">Up-to-date notifications</span>
                                </div>
                                <div class="relative group">
                                    <InformationCircleIcon class="w-4 h-4 cursor-pointer" />
                                    <div
                                        class="hidden group-hover:block rounded bg-gray-900 py-2 px-3 absolute bottom-8 left-1/2 -translate-x-1/2 after:absolute after:w-4 after:h-4 after:-bottom-2 after:left-1/2 after:-translate-x-1/2 after:bg-gray-900 after:rotate-45 w-48">
                                        <p class="leading-tight text-xs font-normal text-gray-200">Lorem ipsum dolor sit
                                            amet consectetur adipiscing elit Ut et massa mi. Aliquam in hendrerit urna.
                                            Pellentesque sit amet sapien.</p>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <a href=""
                        class="w-full p-2 text-center bg-gray-200 rounded-sm text-gray-400 text-sm font-semibold block mt-12">Current
                        plan</a>
                </div>
                <div class="p-8 rounded-lg border border-gray-200 bg-white flex flex-col justify-between">
                    <div>
                        <h4 class="text-center text-gray-900 leading-none text-xl font-bold mb-4">Premier</h4>
                        <h1 class="text-center leading-none text-5xl font-extrabold text-gray-900 mb-4">$XXX/mo</h1>
                        <p class="text-base font-normal text-center text-gray-500">billed yearly, one year commitment</p>
                        <ul class="mt-8">
                            <li class="flex items-center justify-between mb-4">
                                <div class="flex items-center">
                                    <CheckIcon class="h-4 w-4" />
                                    <span class="ms-2 text-base font-normal text-gray-900">Everything in Standard
                                        plus:</span>
                                </div>
                                <div class="relative group">
                                    <InformationCircleIcon class="w-4 h-4 cursor-pointer" />
                                    <div
                                        class="hidden group-hover:block rounded bg-gray-900 py-2 px-3 absolute bottom-8 left-1/2 -translate-x-1/2 after:absolute after:w-4 after:h-4 after:-bottom-2 after:left-1/2 after:-translate-x-1/2 after:bg-gray-900 after:rotate-45 w-48">
                                        <p class="leading-tight text-xs font-normal text-gray-200">Lorem ipsum dolor sit
                                            amet consectetur adipiscing elit Ut et massa mi. Aliquam in hendrerit urna.
                                            Pellentesque sit amet sapien.</p>
                                    </div>
                                </div>
                            </li>
                            <li class="flex items-center justify-between mb-4">
                                <div class="flex items-center">
                                    <CheckIcon class="h-4 w-4" />
                                    <span class="ms-2 text-base font-normal text-gray-900">Unlimited create your own
                                        lists</span>
                                </div>
                                <div class="relative group">
                                    <InformationCircleIcon class="w-4 h-4 cursor-pointer" />
                                    <div
                                        class="hidden group-hover:block rounded bg-gray-900 py-2 px-3 absolute bottom-8 left-1/2 -translate-x-1/2 after:absolute after:w-4 after:h-4 after:-bottom-2 after:left-1/2 after:-translate-x-1/2 after:bg-gray-900 after:rotate-45 w-48">
                                        <p class="leading-tight text-xs font-normal text-gray-200">Lorem ipsum dolor sit
                                            amet consectetur adipiscing elit Ut et massa mi. Aliquam in hendrerit urna.
                                            Pellentesque sit amet sapien.</p>
                                    </div>
                                </div>
                            </li>
                            <li class="flex items-center justify-between mb-4">
                                <div class="flex items-center">
                                    <CheckIcon class="h-4 w-4" />
                                    <span class="ms-2 text-base font-normal text-gray-900">Unlimited download lists</span>
                                </div>
                                <div class="relative group">
                                    <InformationCircleIcon class="w-4 h-4 cursor-pointer" />
                                    <div
                                        class="hidden group-hover:block rounded bg-gray-900 py-2 px-3 absolute bottom-8 left-1/2 -translate-x-1/2 after:absolute after:w-4 after:h-4 after:-bottom-2 after:left-1/2 after:-translate-x-1/2 after:bg-gray-900 after:rotate-45 w-48">
                                        <p class="leading-tight text-xs font-normal text-gray-200">Lorem ipsum dolor sit
                                            amet consectetur adipiscing elit Ut et massa mi. Aliquam in hendrerit urna.
                                            Pellentesque sit amet sapien.</p>
                                    </div>
                                </div>
                            </li>
                            <li class="flex items-center justify-between mb-4">
                                <div class="flex items-center">
                                    <CheckIcon class="h-4 w-4" />
                                    <span class="ms-2 text-base font-normal text-gray-900">Client profiles</span>
                                </div>
                                <div class="relative group">
                                    <InformationCircleIcon class="w-4 h-4 cursor-pointer" />
                                    <div
                                        class="hidden group-hover:block rounded bg-gray-900 py-2 px-3 absolute bottom-8 left-1/2 -translate-x-1/2 after:absolute after:w-4 after:h-4 after:-bottom-2 after:left-1/2 after:-translate-x-1/2 after:bg-gray-900 after:rotate-45 w-48">
                                        <p class="leading-tight text-xs font-normal text-gray-200">Lorem ipsum dolor sit
                                        amet consectetur adipiscing elit Ut et massa mi. Aliquam in hendrerit urna.
                                        Pellentesque sit amet sapien.</p>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <a href=""
                    class="w-full p-2 text-center bg-primary-700 rounded-sm text-white text-sm font-semibold block mt-12">Upgrade
                    to Premier</a>
            </div>
        </div>
    </div>


</AuthenticatedLayout></template>
