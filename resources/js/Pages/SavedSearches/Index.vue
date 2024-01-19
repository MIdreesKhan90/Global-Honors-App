<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, Link, router} from '@inertiajs/vue3';
import {ChevronRightIcon, EllipsisHorizontalIcon} from "@heroicons/vue/24/solid";
import {Menu, MenuButton, MenuItem, MenuItems} from "@headlessui/vue";
import {computed, onMounted, ref} from "vue";
import axios from "axios";
import TextInput from "@/Components/TextInput.vue";


const { lists } = defineProps({
    lists: {
        type: Array
    }
})

const isDeleteSearch = ref(false);
const isRenameSearch = ref(false);
const isLoading = ref(false);
const searchListId = ref(null);
const searchListName = ref('');

function openDeleteModal(id) {
    searchListId.value = id;
    isDeleteSearch.value = true;
}
function closeDeleteModal() {
    isDeleteSearch.value = false;
}

const deleteList = () => {
    isDeleteSearch.value = false;
    const id = searchListId.value;
    router.delete(`/delete-list/${id}`, {
        onSuccess: (page) => {
            console.log('yes');
        },
    })
}

function openRenameModal(item) {
    searchListId.value = item.id;
    searchListName.value = item.name;
    isRenameSearch.value = true;
}
function closeRenameModal() {
    isRenameSearch.value = false;
}

const renameList = () => {
    isRenameSearch.value = false;
    const id = searchListId.value;
    router.put(`/rename-list/${id}`, {
        name: searchListName.value,
        onSuccess: (page) => {
            console.log('yes');
        },
    })
}
const readableQuery = (query) => {
    const params = new URLSearchParams(query);
    const readableParts = [];
    // Mapping of parameter keys to labels
    const paramLabels = {
        'keywords': 'Keywords',
        'industry': 'Industry',
        'businessFunction': 'Business Function',
        'location': 'Location'
    };

    for (const [key, label] of Object.entries(paramLabels)) {
        const paramValue = params.get(key);
        if (paramValue) {
            const names = JSON.parse(paramValue).map(item => item.name ?? item).join(', ');
            readableParts.push(`<strong>${label}:</strong> ${names}`);
        }
    }
    return readableParts.join(', ');
};


</script>

<template>
    <Head title="Dashboard"/>

    <AuthenticatedLayout>

        <div v-if="isDeleteSearch">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div
                        class="relative transform overflow-hidden rounded bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                        <div class="bg-white px-4 pb-4 pt-5 sm:p-5 sm:pb-4">
                            <div class="flex justify-end mb-3">
                                <a href="#"
                                   @click="closeDeleteModal">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12"
                                         fill="none">
                                        <g clip-path="url(#clip0_1531_4920)">
                                            <path
                                                d="M7.20988 6L11.7388 1.47109C11.8205 1.39216 11.8857 1.29775 11.9305 1.19336C11.9754 1.08896 11.999 0.976687 12 0.863076C12.001 0.749465 11.9793 0.636794 11.9363 0.531639C11.8933 0.426484 11.8297 0.33095 11.7494 0.250611C11.669 0.170273 11.5735 0.106739 11.4684 0.0637164C11.3632 0.020694 11.2505 -0.000954946 11.1369 3.23065e-05C11.0233 0.00101956 10.911 0.0246235 10.8066 0.0694668C10.7023 0.11431 10.6078 0.179495 10.5289 0.261217L6 4.79012L1.47109 0.261217C1.30972 0.105355 1.09358 0.0191112 0.869234 0.0210607C0.644888 0.0230102 0.430283 0.112997 0.27164 0.27164C0.112997 0.430283 0.0230102 0.644888 0.0210607 0.869234C0.0191112 1.09358 0.105355 1.30972 0.261217 1.47109L4.79012 6L0.261217 10.5289C0.179495 10.6078 0.11431 10.7023 0.0694668 10.8066C0.0246235 10.911 0.00101956 11.0233 3.23065e-05 11.1369C-0.000954946 11.2505 0.020694 11.3632 0.0637164 11.4684C0.106739 11.5735 0.170273 11.669 0.250611 11.7494C0.33095 11.8297 0.426484 11.8933 0.531639 11.9363C0.636794 11.9793 0.749465 12.001 0.863076 12C0.976687 11.999 1.08896 11.9754 1.19336 11.9305C1.29775 11.8857 1.39216 11.8205 1.47109 11.7388L6 7.20988L10.5289 11.7388C10.6903 11.8946 10.9064 11.9809 11.1308 11.9789C11.3551 11.977 11.5697 11.887 11.7284 11.7284C11.887 11.5697 11.977 11.3551 11.9789 11.1308C11.9809 10.9064 11.8946 10.6903 11.7388 10.5289L7.20988 6Z"
                                                fill="#9CA3AF"/>
                                        </g>
                                        <defs>
                                            <clipPath id="clip0_1531_4920">
                                                <rect width="12" height="12" fill="white"/>
                                            </clipPath>
                                        </defs>
                                    </svg>
                                </a>
                            </div>
                            <div class="sm:flex sm:items-start">
                                <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                    <h3 class="text-lg font-bold font-mulish text-gray-700">Remove search?</h3>
                                    <div class="mt-2">
                                        <p class="text-sm font-normal font-mulish text-gray-500">Are you sure you want to remove <span
                                            class="font-bold text-gray-700">Default</span>? This action cannot be undone.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-between py-6">
                            <div class="flex items-center mx-10">
                                <input
                                    id="default-checkbox"
                                    type="checkbox"
                                    value=""
                                    class="w-4 h-4 mr-2 text-primary-700 bg-gray-100 border-gray-300 accent-primary-700 hover:accent-primary-700"
                                />
                                <p class="leading-none text-xs font-normal">Don’t show this again</p>
                            </div>
                            <div class="flex gap-2 mx-10">
                                <a
                                    href="#"
                                    @click="closeDeleteModal"
                                    class="border border-gray-200 rounded-sm py-2 px-2 text-center text-sm font-medium w-full">
                                    Cancel
                                </a>
                                <button
                                    @click.prevent="deleteList"
                                    class="border border-gray-200 text-white bg-error-700 rounded-sm py-2 px-2 text-center text-sm font-medium w-full">
                                    Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="isRenameSearch">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div
                        class="relative transform overflow-hidden rounded bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                        <div class="bg-white px-4 py-4">
                            <div class="flex justify-end">
                                <a
                                    href="#"
                                    @click="closeRenameModal">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12"
                                         fill="none">
                                        <g clip-path="url(#clip0_1531_4920)">
                                            <path
                                                d="M7.20988 6L11.7388 1.47109C11.8205 1.39216 11.8857 1.29775 11.9305 1.19336C11.9754 1.08896 11.999 0.976687 12 0.863076C12.001 0.749465 11.9793 0.636794 11.9363 0.531639C11.8933 0.426484 11.8297 0.33095 11.7494 0.250611C11.669 0.170273 11.5735 0.106739 11.4684 0.0637164C11.3632 0.020694 11.2505 -0.000954946 11.1369 3.23065e-05C11.0233 0.00101956 10.911 0.0246235 10.8066 0.0694668C10.7023 0.11431 10.6078 0.179495 10.5289 0.261217L6 4.79012L1.47109 0.261217C1.30972 0.105355 1.09358 0.0191112 0.869234 0.0210607C0.644888 0.0230102 0.430283 0.112997 0.27164 0.27164C0.112997 0.430283 0.0230102 0.644888 0.0210607 0.869234C0.0191112 1.09358 0.105355 1.30972 0.261217 1.47109L4.79012 6L0.261217 10.5289C0.179495 10.6078 0.11431 10.7023 0.0694668 10.8066C0.0246235 10.911 0.00101956 11.0233 3.23065e-05 11.1369C-0.000954946 11.2505 0.020694 11.3632 0.0637164 11.4684C0.106739 11.5735 0.170273 11.669 0.250611 11.7494C0.33095 11.8297 0.426484 11.8933 0.531639 11.9363C0.636794 11.9793 0.749465 12.001 0.863076 12C0.976687 11.999 1.08896 11.9754 1.19336 11.9305C1.29775 11.8857 1.39216 11.8205 1.47109 11.7388L6 7.20988L10.5289 11.7388C10.6903 11.8946 10.9064 11.9809 11.1308 11.9789C11.3551 11.977 11.5697 11.887 11.7284 11.7284C11.887 11.5697 11.977 11.3551 11.9789 11.1308C11.9809 10.9064 11.8946 10.6903 11.7388 10.5289L7.20988 6Z"
                                                fill="#9CA3AF"/>
                                        </g>
                                        <defs>
                                            <clipPath id="clip0_1531_4920">
                                                <rect width="12" height="12" fill="white"/>
                                            </clipPath>
                                        </defs>
                                    </svg>
                                </a>
                            </div>
                            <div class="px-3 py-4">
                                <h3 class="text-lg font-bold font-mulish text-gray-700">Rename Search</h3>
                                <TextInput
                                    v-model="searchListName"
                                    class="w-full py-2 mt-2 border border-gray-300 rounded-sm bg-gray-50">
                                </TextInput>

                            </div>
                        </div>
                        <div class="flex justify-center gap-2 mx-7 pb-7">
                            <a
                                href="#"
                                @click="closeRenameModal"
                                class="border border-gray-200 rounded-sm py-2 px-2 text-center text-sm font-medium w-full">
                                Cancel
                            </a>
                            <a
                                @click.prevent="renameList"
                                :class="{'bg-primary-700 text-white' : searchListName}"
                                class="border border-gray-200 text-gray-400 bg-gray-200 rounded-sm py-2 px-2 text-center text-sm font-medium w-full">
                                Save
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <section class="bg-white pt-14 pb-2 h-92vh overflow-x-auto">
            <div class="container mx-auto px-4 sm:px-0">
                <div class="sm:flex items-center justify-between">
                    <div class="flex items-center">
                        <Link href="/awards"><p class="text-sm font-medium text-gray-700">Awards</p></Link>
                        <ChevronRightIcon class="h-3 w-3 text-gray-500 mx-2" aria-hidden="true"/>
                        <p class="text-sm font-medium text-gray-500">Saved Searches</p>
                    </div>
                </div>
                <h4 class="text-4xl font-bold text-gray-800 font-mulish my-6">
                    Saved Searches
                </h4>

                <div class="grid grid-cols-3 gap-5 bg-white rounded-sm mt-3">
                    <div class="border border-gray-200 px-4 py-6" v-for="(item, i) in lists" :key="i">
                        <div class="flex justify-between px-2">
                            <div>
                                <div class="min-h-[100px]">
                                    <h1 class="text-gray-900 font-mulish font-bold">{{ item.name }}</h1>
                                    <p class="text-gray-900 text-sm font-normal" v-html="readableQuery(item.search_query)"></p>
                                </div>
                                <Link v-if="item.search_query"
                                      :href="'/awards' + item.search_query"
                                      class="p-2 text-center rounded-sm text-xs font-semibold inline-block mt-4 text-white bg-primary-700">
                                    Select
                                </Link>
                            </div>
                            <div class="-mt-2 -mr-2">
                                <Menu as="div" class="relative inline-block text-left mt-4 sm:mt-0">
                                    <div>
                                        <MenuButton
                                            class="px-1">
                                            <EllipsisHorizontalIcon class="w-5 h-5"/>
                                        </MenuButton>
                                    </div>

                                    <transition enter-active-class="transition ease-out duration-100"
                                                enter-from-class="transform opacity-0 scale-95"
                                                enter-to-class="transform opacity-100 scale-100"
                                                leave-active-class="transition ease-in duration-75"
                                                leave-from-class="transform opacity-100 scale-100"
                                                leave-to-class="transform opacity-0 scale-95">
                                        <MenuItems
                                            class="absolute right-0 z-10 w-36 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                                            <div class="py-1">
                                                <MenuItem v-slot="{ active }">
                                                    <a href="#"
                                                       @click="openDeleteModal(item.id)"
                                                       class="flex gap-2 px-2 py-3 items-center"
                                                       :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block px-4 py-2 text-sm']">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="17" viewBox="0 0 16 17"
                                                             fill="none"
                                                             class="w-4 h-4">
                                                            <path
                                                                d="M14.5186 3.86842H11.2593V2.18421C11.2593 1.73753 11.0876 1.30914 10.782 0.993294C10.4764 0.677443 10.0619 0.5 9.62971 0.5H6.37045C5.93825 0.5 5.52374 0.677443 5.21813 0.993294C4.91252 1.30914 4.74082 1.73753 4.74082 2.18421V3.86842H1.48156C1.26546 3.86842 1.05821 3.95714 0.905402 4.11507C0.752594 4.27299 0.666748 4.48719 0.666748 4.71053C0.666748 4.93387 0.752594 5.14806 0.905402 5.30598C1.05821 5.46391 1.26546 5.55263 1.48156 5.55263H2.29638V14.8158C2.29638 15.2625 2.46807 15.6909 2.77368 16.0067C3.0793 16.3226 3.4938 16.5 3.92601 16.5H12.0742C12.5064 16.5 12.9209 16.3226 13.2265 16.0067C13.5321 15.6909 13.7038 15.2625 13.7038 14.8158V5.55263H14.5186C14.7347 5.55263 14.942 5.46391 15.0948 5.30598C15.2476 5.14806 15.3334 4.93387 15.3334 4.71053C15.3334 4.48719 15.2476 4.27299 15.0948 4.11507C14.942 3.95714 14.7347 3.86842 14.5186 3.86842ZM6.37045 2.18421H9.62971V3.86842H6.37045V2.18421ZM12.0742 14.8158H3.92601V5.55263H12.0742V14.8158Z"
                                                                fill="#6B7280"/>
                                                            <path
                                                                d="M6.37045 6.39474C6.15435 6.39474 5.9471 6.48346 5.79429 6.64138C5.64148 6.79931 5.55564 7.0135 5.55564 7.23684V13.1316C5.55564 13.3549 5.64148 13.5691 5.79429 13.727C5.9471 13.885 6.15435 13.9737 6.37045 13.9737C6.58655 13.9737 6.79381 13.885 6.94661 13.727C7.09942 13.5691 7.18527 13.3549 7.18527 13.1316V7.23684C7.18527 7.0135 7.09942 6.79931 6.94661 6.64138C6.79381 6.48346 6.58655 6.39474 6.37045 6.39474Z"
                                                                fill="#6B7280"/>
                                                            <path
                                                                d="M9.62971 6.39474C9.41361 6.39474 9.20636 6.48346 9.05355 6.64138C8.90074 6.79931 8.8149 7.0135 8.8149 7.23684V13.1316C8.8149 13.3549 8.90074 13.5691 9.05355 13.727C9.20636 13.885 9.41361 13.9737 9.62971 13.9737C9.84581 13.9737 10.0531 13.885 10.2059 13.727C10.3587 13.5691 10.4445 13.3549 10.4445 13.1316V7.23684C10.4445 7.0135 10.3587 6.79931 10.2059 6.64138C10.0531 6.48346 9.84581 6.39474 9.62971 6.39474Z"
                                                                fill="#6B7280"/>
                                                        </svg>
                                                        <p class="text-sm font-normal text-gray-500 font-mulish">Delete</p>
                                                    </a>
                                                </MenuItem>
                                                <MenuItem v-slot="{ active }">
                                                    <a href="#"
                                                       @click="openRenameModal(item)"
                                                       class="flex gap-2 px-2 py-3 items-center"
                                                       :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block px-4 py-2 text-sm']">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="17" viewBox="0 0 16 17"
                                                             fill="none"
                                                             class="w-4 h-4">
                                                            <path
                                                                d="M11.5628 13.6624C11.4636 13.7616 11.354 13.848 11.2404 13.9272L11.2532 14.9H1.59994V6.1H3.99985C4.88222 6.1 5.59979 5.3824 5.59979 4.5V2.1H11.1996V2.9C11.1996 2.9376 11.2156 2.9696 11.2212 3.0048C11.686 2.6208 12.2195 2.348 12.7995 2.2128V2.1C12.7995 1.2176 12.1059 0.5 11.2532 0.5H4.66303C4.02145 0.5 3.41827 0.7496 2.96549 1.2032L0.703174 3.4656C0.249591 3.9184 0 4.5216 0 5.1632V14.9C0 15.7824 0.693574 16.5 1.54634 16.5H11.2532C12.1059 16.5 12.7995 15.7824 12.7995 14.9V12.4256L11.5628 13.6624ZM3.99985 2.4312V4.5H1.93113L3.99985 2.4312Z"
                                                                fill="#6B7280"/>
                                                            <path
                                                                d="M7.16853 13.3C7.21813 13.3 7.26933 13.2952 7.31973 13.2848L10.0388 12.7408C10.1876 12.7112 10.3244 12.6376 10.4316 12.5304L15.325 7.6368C16.225 6.7376 16.225 5.2736 15.325 4.3744C14.8746 3.9248 14.2843 3.7 13.6931 3.7C13.1027 3.7 12.5115 3.9248 12.0619 4.3752L7.16853 9.2688C7.06134 9.376 6.98774 9.5128 6.95814 9.6616L6.41416 12.3808C6.36376 12.6328 6.44296 12.8936 6.62455 13.0752C6.77095 13.2208 6.96694 13.3 7.16853 13.3ZM13.1499 5.4624C13.2995 5.3128 13.4971 5.2376 13.6939 5.2376C13.8907 5.2376 14.0875 5.3128 14.2379 5.4624C14.5379 5.7624 14.5379 6.2504 14.2379 6.5504L13.9851 6.8032L12.8971 5.7152L13.1499 5.4624ZM8.42128 10.1912L11.81 6.8016L12.8979 7.8896L9.50844 11.2784L8.14929 11.5504L8.42128 10.1912Z"
                                                                fill="#6B7280"/>
                                                        </svg>
                                                        <p class="text-sm font-normal text-gray-500 font-Mulish">Rename</p>
                                                    </a>
                                                </MenuItem>
                                            </div>
                                        </MenuItems>
                                    </transition>
                                </Menu>
                            </div>
                        </div>
                    </div>
                    <div
                        v-if="isLoading"
                        class="absolute left-0 top-0 w-full h-full bg-white bg-opacity-75 z-10 flex items-center justify-center"
                    >
                        <svg
                            class="animate-spin h-8 w-8 text-black"
                            width="24"
                            height="24"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <g>
                                <rect x="11" y="1" width="2" height="5" opacity=".14"/>
                                <rect x="11" y="1" width="2" height="5" transform="rotate(30 12 12)" opacity=".29"/>
                                <rect x="11" y="1" width="2" height="5" transform="rotate(60 12 12)" opacity=".43"/>
                                <rect x="11" y="1" width="2" height="5" transform="rotate(90 12 12)" opacity=".57"/>
                                <rect x="11" y="1" width="2" height="5" transform="rotate(120 12 12)" opacity=".71"/>
                                <rect x="11" y="1" width="2" height="5" transform="rotate(150 12 12)" opacity=".86"/>
                                <rect x="11" y="1" width="2" height="5" transform="rotate(180 12 12)"/>
                            </g>
                        </svg>
                    </div>
                </div>
            </div>
        </section>
    </AuthenticatedLayout>
</template>
