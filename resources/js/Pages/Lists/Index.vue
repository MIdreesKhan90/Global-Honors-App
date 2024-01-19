<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, Link, router} from '@inertiajs/vue3';
import {ChevronRightIcon, EllipsisHorizontalIcon} from "@heroicons/vue/24/solid";
import {Menu, MenuButton, MenuItem, MenuItems} from "@headlessui/vue";
import {ref} from "vue";
import axios from "axios";
import TextInput from "@/Components/TextInput.vue";

const deletingListId = ref(null);
const renamingListId = ref(null);
const newListName = ref('');
const isDeleteList = ref(false);
const isRenameList = ref(false);
const isCopyList = ref(false);
const isLoading = ref(false);

const {lists} = defineProps({
    lists: {
        type: Array,
    }
});

function openDeleteModal(listId, currentName) {
    deletingListId.value = listId;
    newListName.value = currentName;
    isDeleteList.value = true;
}

function closeDeleteModal() {
    isDeleteList.value = false;
}

function openRenameModal(listId, currentName) {
    renamingListId.value = listId;
    newListName.value = currentName; // Sets the current name in the input for editing
    console.log(newListName.value);
    isRenameList.value = true;
}

// Call this method when confirming the rename action
function renameList() {
    isLoading.value = true;
    // Call your API endpoint to rename the list
    axios.post('/list/rename', {
        id: renamingListId.value,
        name: newListName.value
    }).then((res) => {

        console.log(res.data.message);


    }).catch(error => {
        console.error('There was an error renaming the list:', error);
    }).finally(() => {
        isLoading.value = false; // Stop loading regardless of the outcome
        closeRenameModal();
        router.get('/lists'); // This seems like an attempt to refresh the lists, you might need to adjust based on your routing and state management
    });
}

// Call this method when confirming the delete action
function deleteList() {
    isLoading.value = true;
    axios.post('/list/delete', {
        id: deletingListId.value
    }).then((res) => {

        console.log(res.data.message);

    }).catch(error => {
        console.error('There was an error deleting the list:', error);
    }).finally(() => {
        isLoading.value = false; // Stop loading regardless of the outcome
        closeDeleteModal();
        router.get('/lists'); // This seems like an attempt to refresh the lists, you might need to adjust based on your routing and state management
    });
}

function closeRenameModal() {
    isRenameList.value = false;
}

function openCopyModal() {
    isCopyList.value = true;
}

function closeCopyModal() {
    isCopyList.value = false;
}
</script>

<template>
    <Head title="Dashboard"/>

    <AuthenticatedLayout>

        <div v-if="isCopyList">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div
                        class="relative transform overflow-hidden rounded bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                        <div class="bg-white px-4 py-4">
                            <div class="flex justify-end">
                                <a
                                    href="#"
                                    @click="closeCopyModal">
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
                                <div class="px-4 py-4">
                                    <h3 class="text-lg font-bold font-mulish text-gray-700">Copy item</h3>
                                    <div class="mt-2">
                                        <p class="text-sm font-normal font-mulish text-gray-500">Would you like to copy <span
                                            class="font-bold text-gray-700">List 1</span> and all it’s contents?</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-between gap-2 mx-8 pb-7">
                            <a
                                href="#"
                                @click="closeCopyModal"
                                class="border border-gray-200 rounded-sm py-2 px-2 text-center text-sm font-medium w-full">
                                Cancel
                            </a>
                            <a
                                href="#"
                                class="border border-gray-200 text-white bg-primary-700 rounded-sm py-2 px-2 text-center text-sm font-medium w-full">
                                Copy
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="isDeleteList">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div
                        class="relative transform overflow-hidden rounded bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                        <div class="bg-white px-4 py-4">
                            <div class="flex justify-end">
                                <a
                                    href="#"
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
                            <div class="sm:flex sm:items-start mx-3">
                                <div class="mt-2 text-left">
                                    <h3 class="text-lg font-bold font-mulish text-gray-700">Delete item?</h3>
                                    <div class="mt-2">
                                        <p class="text-sm font-normal font-mulish text-gray-500">Are you sure you want to delete <span
                                            class="font-bold text-gray-700">{{ newListName }}</span>? This action cannot be undone.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-between gap-2 mx-7 py-7">
                            <a
                                href="#"
                                @click="closeDeleteModal"
                                class="border border-gray-200 rounded-sm py-2 px-2 text-center text-sm font-medium w-full">
                                Cancel
                            </a>
                            <a
                                href="#"
                                @click.prevent="deleteList"
                                class="border border-gray-200 text-white bg-error-700 rounded-sm py-2 px-2 text-center text-sm font-medium w-full">
                                Delete
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="isRenameList">
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
                                <h3 class="text-lg font-bold font-mulish text-gray-700">Rename</h3>
                                <div class="mt-2 flex justify-between border border-primary-600 rounded-sm p-2">
                                    <TextInput v-model="newListName"
                                               class="w-full py-2 border border-gray-300 rounded-sm bg-gray-50">
                                    </TextInput>
<!--                                    <div class="mt-1">-->
<!--                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">-->
<!--                                            <g clip-path="url(#clip0_1554_568)">-->
<!--                                                <path-->
<!--                                                    d="M6.85632 5.64645L6.50277 6L6.85632 6.35355L11.3815 10.8787C11.4448 10.9455 11.4798 11.0343 11.479 11.1264C11.4781 11.2197 11.4407 11.3089 11.3748 11.3748C11.3089 11.4407 11.2197 11.4781 11.1264 11.479C11.0343 11.4798 10.9455 11.4448 10.8787 11.3815L6.35355 6.85632L6 6.50277L5.64645 6.85632L1.11754 11.3852L1.11749 11.3852L1.11145 11.3914C1.07864 11.4254 1.0394 11.4525 0.996011 11.4711C0.952617 11.4898 0.905949 11.4996 0.858732 11.5C0.81151 11.5004 0.764679 11.4914 0.720971 11.4735C0.677266 11.4556 0.637558 11.4292 0.604165 11.3958C0.570773 11.3624 0.544365 11.3227 0.526484 11.279C0.508601 11.2353 0.499603 11.1885 0.500013 11.1413C0.500424 11.094 0.510235 11.0474 0.528873 11.004C0.547512 10.9606 0.574606 10.9214 0.608573 10.8886L0.608625 10.8886L0.614771 10.8825L5.14368 6.35355L5.49723 6L5.14368 5.64645L0.618506 1.12127C0.55521 1.05447 0.520241 0.96569 0.521042 0.873579C0.521852 0.780331 0.559254 0.691132 0.625193 0.625193C0.691132 0.559254 0.780331 0.521852 0.873579 0.521042C0.96569 0.520241 1.05447 0.55521 1.12127 0.618506L5.64645 5.14368L6 5.49723L6.35355 5.14368L10.8825 0.614771L10.8825 0.614824L10.8886 0.608573C10.9214 0.574606 10.9606 0.547512 11.004 0.528874C11.0474 0.510235 11.094 0.500424 11.1413 0.500013C11.1885 0.499603 11.2353 0.508601 11.279 0.526483C11.3227 0.544365 11.3624 0.570773 11.3958 0.604165C11.4292 0.637558 11.4556 0.677266 11.4735 0.720971C11.4914 0.764679 11.5004 0.81151 11.5 0.858732C11.4996 0.905949 11.4898 0.952617 11.4711 0.996008C11.4525 1.0394 11.4254 1.07864 11.3914 1.11145L11.3914 1.11139L11.3852 1.11754L6.85632 5.64645Z"-->
<!--                                                    fill="#111928" stroke="#111928"/>-->
<!--                                            </g>-->
<!--                                            <defs>-->
<!--                                                <clipPath id="clip0_1554_568">-->
<!--                                                    <rect width="12" height="12" fill="white"/>-->
<!--                                                </clipPath>-->
<!--                                            </defs>-->
<!--                                        </svg>-->
<!--                                    </div>-->
                                </div>

                            </div>
                        </div>
                        <div class="flex justify-center gap-2 mx-7 pb-7">
                            <a
                                href="#"
                                @click="closeRenameModal"
                                class="border border-gray-200 rounded-sm py-2 px-2 text-center text-sm font-medium w-full">
                                Cancel
                            </a>
                            <a href="#"
                                @click.prevent="renameList"
                                class="border border-gray-200 text-white bg-primary-700 rounded-sm py-2 px-2 text-center text-sm font-medium w-full">
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
                        <Link :href="'/awards'"><p class="text-sm font-medium text-gray-700">Awards</p></Link>
                        <ChevronRightIcon class="h-3 w-3 text-gray-500 mx-2" aria-hidden="true"/>
                        <p class="text-sm font-medium text-gray-500">My Lists</p>
                    </div>
                </div>
                <h4 class="text-4xl font-bold text-gray-800 font-mulish my-6">
                    My Lists
                </h4>

                <div class="grid grid-cols-3 gap-5 bg-white rounded-sm mt-3">
                    <div v-for="list in lists" class="border border-gray-200 px-4 py-6">
                        <div class="flex justify-between px-2">
                            <div>
                                <h1 class="text-gray-900 font-mulish font-bold">{{ list.name }}</h1>
                                <p class="text-gray-900 text-sm font-normal">{{ list.awards_count }} awards • Last edited {{ list.latest_update }}</p>
                                <Link
                                    :href="route('list.show', { list: list.id })"
                                    class="p-2 w-2/5 text-center rounded-sm text-xs font-semibold block mt-12 text-white bg-primary-700">
                                    View list
                                </Link>
                            </div>
                            <div class="-mt-2 -mr-2">
                                <Menu as="div" class="relative inline-block text-left mt-4 sm:mt-0">                                    <div>
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
                                                       @click="openDeleteModal(list.id, list.name)"
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
                                                       @click="openRenameModal(list.id,list.name)"
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
                                                <MenuItem v-slot="{ active }">
                                                    <a href="#"
                                                       @click="openCopyModal"
                                                       class="flex gap-2 px-2 py-3 items-center"
                                                       :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block px-4 py-2 text-sm']">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="17" viewBox="0 0 16 17"
                                                             fill="none"
                                                             class="w-4 h-4">
                                                            <path
                                                                d="M13.1496 0.500008H9.04146C8.73238 0.499198 8.4262 0.560933 8.14067 0.681638C7.85513 0.802342 7.5959 0.97962 7.37798 1.20321L6.82349 1.76721C6.75057 1.84159 6.69272 1.92989 6.65326 2.02707C6.6138 2.12426 6.59348 2.22842 6.59348 2.33361C6.59348 2.54605 6.67622 2.74979 6.82349 2.90001C6.97076 3.05023 7.1705 3.13462 7.37877 3.13462C7.58704 3.13462 7.78678 3.05023 7.93404 2.90001L8.48697 2.33521C8.63433 2.18554 8.83352 2.10105 9.04146 2.10001H13.0978L13.1496 11.7C12.9416 11.7 12.7421 11.7843 12.595 11.9343C12.4479 12.0843 12.3653 12.2878 12.3653 12.5C12.3653 12.7122 12.4479 12.9157 12.595 13.0657C12.7421 13.2157 12.9416 13.3 13.1496 13.3C13.5585 13.2924 13.9479 13.1197 14.2322 12.8198C14.5166 12.5199 14.6727 12.1172 14.6664 11.7V2.10001C14.6727 1.68283 14.5166 1.28013 14.2322 0.980198C13.9479 0.680267 13.5585 0.507583 13.1496 0.500008Z"
                                                                fill="#6B7280"/>
                                                            <path
                                                                d="M10.0124 3.70001H5.90508C5.59585 3.699 5.2895 3.76064 5.00381 3.88136C4.71812 4.00207 4.45877 4.17945 4.24082 4.40321L2.02284 6.6656C1.80348 6.88793 1.62958 7.15247 1.51124 7.44388C1.3929 7.7353 1.33246 8.04778 1.33345 8.3632V14.9C1.32694 15.3171 1.4829 15.7198 1.76712 16.0198C2.05133 16.3197 2.44059 16.4924 2.84948 16.5H10.0124C10.4214 16.4924 10.8107 16.3197 11.0951 16.0198C11.3794 15.7199 11.5355 15.3172 11.5292 14.9V5.3C11.5355 4.88283 11.3794 4.48013 11.0951 4.1802C10.8107 3.88026 10.4214 3.70758 10.0124 3.70001ZM5.2549 5.6312V7.7H3.22673L5.2549 5.6312ZM2.90203 14.9V9.3H5.2549C5.67092 9.3 6.06989 9.13143 6.36406 8.83137C6.65823 8.53132 6.82349 8.12435 6.82349 7.7V5.29521L9.95595 5.27921C9.9598 5.28538 9.96147 5.29273 9.96065 5.3L10.0124 14.9H2.90203Z"
                                                                fill="#6B7280"/>
                                                        </svg>
                                                        <p class="text-sm font-normal text-gray-500 font-Mulish">Copy</p>
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
