<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, Link} from '@inertiajs/vue3';

import {Menu, MenuButton, MenuItem, MenuItems} from "@headlessui/vue";
import {ChevronDownIcon, ChevronRightIcon, ChevronLeftIcon, PlusIcon} from "@heroicons/vue/24/solid";
import {ref} from "vue";
import axios from "axios";
import TextInput from "@/Components/TextInput.vue";

const {award, previousURL} = defineProps({
  award: {
    type: Object
  },
  relatedAwards: {
    type: Array
  },
  previousURL: String
})

const userLists = ref(null);
const selectedList = ref(null);
const successMessage = ref(null);
const newlistName = ref('');
const isLoading = ref(false);
const isAddedSuccess = ref(false);
const isAddAwardOpen = ref(false);
const isAddListOpen = ref(false);

const openAddAwardModal = () => {
  getUserLists();
  isAddAwardOpen.value = true
}
const closeAddAwardModal = () => {
  isAddAwardOpen.value = false
}

const openAddListModal = () => {
  isAddAwardOpen.value = false
  isAddListOpen.value = true
}
const closeAddListModal = () => {
  isAddListOpen.value = false
}
const selectListId = (id) => {
  selectedList.value = id;

  console.log(selectedList.value);
}
const getUserLists = () => {
  axios.get('/get-lists')
      .then((res) => {
        // console.log(res.data.lists);
        userLists.value = res.data.lists;
      })
      .catch((error) => {
        console.log(error)
      })
}

const awardsAddedToList = () => {
  if (selectedList.value === null) {
    console.error('No list selected.');
    return;
  }
  // console.log('List ID:', selectedList.value);
  // console.log('Award ID:', [award.id]);
  isLoading.value = true;
  isAddAwardOpen.value = false;
  axios.post('/add-to-list', {
    list_id: selectedList.value,
    award_ids: [award.id]
  })
      .then(response => {
        console.log(response.data);
        isLoading.value = false;
        if (response.data) {
          successMessage.value = response.data.message;
          isAddedSuccess.value = true;

          setTimeout(() => {
            isAddedSuccess.value = false;
          }, 5000);
        }
        selectedList.value = null;
      })
      .catch(error => {
        console.error(error.response.data);
      });
}

function createNewList() {
  isLoading.value = true;
  isAddListOpen.value = false;
  let formData = {
    name: newlistName.value,
  }
  axios.post('/list-save', formData)
      .then((res) => {
        console.log(res.data.message);
        isLoading.value = false;
        selectListId(res.data.listId);
        awardsAddedToList();
        closeAddListModal();
        // openAddAwardModal();
        newlistName.value = '';
      })
      .catch((error) => {
        console.log(error);
      });
}

</script>

<template>
  <Head title="Dashboard"/>

  <AuthenticatedLayout>

    <section class="bg-white pt-14 pb-2 h-92vh overflow-x-auto">
      <div class="container mx-auto px-4 sm:px-0">
        <div class="sm:flex items-center justify-between">
          <div class="flex items-center">
            <Link :href="previousURL"><p class="text-sm font-medium text-gray-700 flex items-center">
              <ChevronLeftIcon class="h-4 w-4 font-extrabold text-gray-500" aria-hidden="true"/>
              Back</p></Link>
<!--            <p class="text-sm font-medium text-gray-500">Award details</p>-->
          </div>
          <Menu as="div" class="relative inline-block text-left mt-4 sm:mt-0">
            <div>
              <MenuButton
                  @click.prevent="openAddAwardModal"
                  class="inline-flex w-full text-xs font-semibold justify-center items-center gap-x-1.5 rounded-sm bg-primary-700 px-3 py-2 text-white">
                + Add to list
              </MenuButton>
            </div>
          </Menu>
        </div>

        <h1 class="text-4xl font-bold mt-6 mb-8">{{ award.name }}</h1>

        <div class="grid sm:grid-cols-2 grid-cols-1 gap-24">
          <div>
            <h3 class="pb-5 mb-5 border-b border-gray-200 text-xl font-bold text-gray-900">About</h3>
            <h6 class="text-sm font-bold text-gray-900">Industry</h6>
            <p class="text-sm font-normal mb-5 text-gray-600">
              {{ award.industries && award.industries.name ? award.industries.name : '---' }}
            </p>
            <h6 class="text-sm font-bold text-gray-900">Business Function</h6>
            <p class="text-sm font-normal mb-5 text-gray-600"
            >
              {{ award.business_functions && award.business_functions.name ? award.business_functions.name : '---' }}
            </p>
            <h6 class="text-sm font-bold text-gray-900">Description</h6>
            <div v-if="award.description" class="text-sm font-normal mb-5 text-gray-600" v-html="award.description">
            </div>
            <div v-else class="text-sm font-normal mb-5 text-gray-600">
                ---
            </div>
            <h6 class="text-sm font-bold text-gray-900">Website</h6>

            <a v-if="award.details_url"
                :href="award.details_url"
               target="_blank"
               class="block text-sm text-primary-700 underline">{{ award.details_url }}</a>
            <p v-else>---</p>
            <h6 class="text-sm font-bold text-gray-900 mt-4 ">Award Categories</h6>
            <a v-if="award.categories_url"
               :href="award.categories_url"
               target="_blank"
               class="block text-sm text-primary-700 underline">{{ award.categories_url }}</a>
            <p v-else>---</p>
          </div>
          <div>
            <div v-if="award.sponsors">
              <h3 class="pb-5 mb-5 border-b border-gray-200 text-xl font-bold text-gray-900">Sponsor</h3>
              <div v-if="award.sponsors.logo">
                <img :src="award.sponsors.logo" alt="image" class="mb-9">
              </div>
              <div v-else class="mt-6 mb-8">
                <Link class="text-4xl font-bold leading-normal text-primary-700 underline">{{ award.sponsors.name }}</Link>
              </div>
            </div>
            <div class="grid grid-cols-3 border-b border-gray-200">
              <div class="pb-5">
                <h6 class="text-sm font-bold text-gray-900">Early deadline</h6>
                <p class="text-sm font-normal text-gray-600">{{ award.early_deadline_date ?? '---' }}</p>
              </div>
              <div class="pb-5">
                <h6 class="text-sm font-bold text-gray-900">Deadline</h6>
                <p class="text-sm font-normal text-gray-600">{{ award.deadline_date ?? '---' }}</p>
              </div>
              <div class="pb-5">
                <h6 class="text-sm font-bold text-gray-900">Awarding date</h6>
                <p class="text-sm font-normal text-gray-600">{{ award.awarding_date ?? '---' }}</p>
              </div>
            </div>
            <div class="grid grid-cols-3 border-b border-gray-200">
              <div class="py-5">
                <h6 class="text-sm font-bold text-gray-900">Early entry cost</h6>
                <p class="text-sm font-normal text-gray-600">
                  {{ award.early_entry_cost != null ? '$' + award.early_entry_cost : '---' }}
                </p>
              </div>
              <div class="py-5">
                <h6 class="text-sm font-bold text-gray-900">Entry cost</h6>
                <p class="text-sm font-normal text-gray-600">
                  {{ award.entry_cost != null ? '$' + award.entry_cost : '---' }}
                </p>
              </div>
            </div>
            <div class="grid grid-cols-3 border-b border-gray-200">
              <div class="py-5">
                <h6 class="text-sm font-bold text-gray-900">Location</h6>
                <p class="text-sm font-normal text-gray-600">
                  {{ award.region != null ? award.region.toUpperCase() : '---' }}
                </p>
              </div>
            </div>
            <div class="pt-5" v-if="relatedAwards.length > 0">
              <h6 class="text-sm font-bold text-gray-900">More Awards From The Sponsor</h6>
              <div v-for="(item, i) in relatedAwards" :key="i">
                <a
                    :href="route('awards.show', item.id)"
                    class="block mt-3 text-sm text-primary-700 underline">{{
                    item.name
                  }}</a>
              </div>
            </div>
          </div>
        </div>
        <div
            v-if="isAddedSuccess"
            class="fixed bottom-5 flex left-4 z-50  justify-start items-center gap-3 w-72 bg-gray-800 border shadow text-white px-4 py-3 rounded-lg"
            role="alert">
          <div class="bg-primary-900 w-7 h-6 rounded-sm">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 20 20" fill="none">
              <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M16.707 5.29279C16.8945 5.48031 16.9998 5.73462 16.9998 5.99979C16.9998 6.26495 16.8945 6.51926 16.707 6.70679L8.70698 14.7068C8.51945 14.8943 8.26514 14.9996 7.99998 14.9996C7.73482 14.9996 7.48051 14.8943 7.29298 14.7068L3.29298 10.7068C3.11082 10.5182 3.01003 10.2656 3.01231 10.0034C3.01458 9.74119 3.11975 9.49038 3.30516 9.30497C3.49057 9.11956 3.74138 9.01439 4.00358 9.01211C4.26578 9.00983 4.51838 9.11063 4.70698 9.29279L7.99998 12.5858L15.293 5.29279C15.4805 5.10532 15.7348 5 16 5C16.2651 5 16.5195 5.10532 16.707 5.29279Z"
                    fill="#529397"/>
            </svg>
          </div>
          <div>
            <p class="text-sm font-bold font-mulish">{{ successMessage }}</p>
          </div>
          <div class="">
            <button @click.prevent="closeSuccessMessage">
              <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
                <g clip-path="url(#clip0_1554_1282)">
                  <path
                      d="M7.20988 6L11.7388 1.47109C11.8205 1.39216 11.8857 1.29775 11.9305 1.19336C11.9754 1.08896 11.999 0.976687 12 0.863076C12.001 0.749465 11.9793 0.636794 11.9363 0.531639C11.8933 0.426484 11.8297 0.33095 11.7494 0.250611C11.669 0.170273 11.5735 0.106739 11.4684 0.0637164C11.3632 0.020694 11.2505 -0.000954946 11.1369 3.23065e-05C11.0233 0.00101956 10.911 0.0246235 10.8066 0.0694668C10.7023 0.11431 10.6078 0.179495 10.5289 0.261217L6 4.79012L1.47109 0.261217C1.30972 0.105355 1.09358 0.0191112 0.869234 0.0210607C0.644888 0.0230102 0.430283 0.112997 0.27164 0.27164C0.112997 0.430283 0.0230102 0.644888 0.0210607 0.869234C0.0191112 1.09358 0.105355 1.30972 0.261217 1.47109L4.79012 6L0.261217 10.5289C0.179495 10.6078 0.11431 10.7023 0.0694668 10.8066C0.0246235 10.911 0.00101956 11.0233 3.23065e-05 11.1369C-0.000954946 11.2505 0.020694 11.3632 0.0637164 11.4684C0.106739 11.5735 0.170273 11.669 0.250611 11.7494C0.33095 11.8297 0.426484 11.8933 0.531639 11.9363C0.636794 11.9793 0.749465 12.001 0.863076 12C0.976687 11.999 1.08896 11.9754 1.19336 11.9305C1.29775 11.8857 1.39216 11.8205 1.47109 11.7388L6 7.20988L10.5289 11.7388C10.6903 11.8946 10.9064 11.9809 11.1308 11.9789C11.3551 11.977 11.5697 11.887 11.7284 11.7284C11.887 11.5697 11.977 11.3551 11.9789 11.1308C11.9809 10.9064 11.8946 10.6903 11.7388 10.5289L7.20988 6Z"
                      fill="#9CA3AF"/>
                </g>
                <defs>
                  <clipPath id="clip0_1554_1282">
                    <rect width="12" height="12" fill="white"/>
                  </clipPath>
                </defs>
              </svg>
            </button>
          </div>
        </div>
      </div>
      <div class="relative z-10" v-if="isAddAwardOpen">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
          <div class="flex min-h-full items-end justify-center text-center sm:items-center sm:p-0">
            <div
                class="relative transform overflow-hidden rounded bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
              <div class="bg-white px-4 py-4">
                <div class="flex justify-end">
                  <a
                      href="#"
                      @click.prevent="closeAddAwardModal">
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
                <div class="mx-1 py-6">
                  <h3 class="text-lg mx-2 font-bold font-mulish text-gray-700"> Add awards to...
                  </h3>
                  <div class="mt-2">
                    <ul class="text-sm font-normal font-mulish text-gray-500 max-h-72 overflow-y-auto">
                      <li v-for="list in userLists" :key="list.id" class="py-2 px-2 cursor-pointer"
                          :class="{'bg-gray-200' : selectedList === list.id}"
                          @click.prevent="selectListId(list.id)">
                        {{ list.name }}
                      </li>
                    </ul>
                    <a
                        href="#"
                        @click.prevent="openAddListModal"
                        class="py-3 mx-1 gap-1 flex text-gray-500">
                      <svg xmlns="http://www.w3.org/2000/svg" class="mr-1 mt-1" width="12" height="13"
                           viewBox="0 0 12 13" fill="none">
                        <g clip-path="url(#clip0_1531_4933)">
                          <path
                              d="M11.3333 5.83333H6.66667V1.16667C6.66667 0.989856 6.59643 0.820286 6.4714 0.695262C6.34638 0.570238 6.17681 0.5 6 0.5C5.82319 0.5 5.65362 0.570238 5.5286 0.695262C5.40357 0.820286 5.33333 0.989856 5.33333 1.16667V5.83333H0.666667C0.489856 5.83333 0.320286 5.90357 0.195262 6.0286C0.0702379 6.15362 0 6.32319 0 6.5C0 6.67681 0.0702379 6.84638 0.195262 6.9714C0.320286 7.09643 0.489856 7.16667 0.666667 7.16667H5.33333V11.8333C5.33333 12.0101 5.40357 12.1797 5.5286 12.3047C5.65362 12.4298 5.82319 12.5 6 12.5C6.17681 12.5 6.34638 12.4298 6.4714 12.3047C6.59643 12.1797 6.66667 12.0101 6.66667 11.8333V7.16667H11.3333C11.5101 7.16667 11.6797 7.09643 11.8047 6.9714C11.9298 6.84638 12 6.67681 12 6.5C12 6.32319 11.9298 6.15362 11.8047 6.0286C11.6797 5.90357 11.5101 5.83333 11.3333 5.83333Z"
                              fill="#2C5558"/>
                        </g>
                        <defs>
                          <clipPath id="clip0_1531_4933">
                            <rect width="12" height="12" fill="white" transform="translate(0 0.5)"/>
                          </clipPath>
                        </defs>
                      </svg>
                      <span
                          class="text-sm font-normal font-mulish"
                      >Create new list</span>
                    </a>
                  </div>
                </div>
              </div>
              <div class="flex justify-between gap-2 mx-8 mb-7">
                <a
                    href="#"
                    @click.prevent="closeAddAwardModal"
                    class="border border-gray-200 rounded-sm py-2 px-2 text-center text-sm font-medium w-full">
                  Cancel
                </a>
                <button
                    @click.prevent="awardsAddedToList()"
                    :class="{'bg-primary-700 text-white' : selectedList}"
                    class="border border-gray-200 text-gray-400 bg-gray-200 rounded-sm py-2 px-2 text-center text-sm font-medium w-full">
                  Add
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="relative z-10" v-if="isAddListOpen">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
          <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div
                class="relative transform overflow-hidden rounded bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
              <div class="bg-white px-4 py-3">
                <div class="flex justify-end">
                  <a
                      href="#"
                      @click.prevent="closeAddListModal">
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
                <div class="mx-3 pt-6">
                  <h3 class="text-lg font-bold font-mulish text-gray-700">Create new list</h3>
                  <div class="mt-2">
                    <TextInput placeholder="New list name"
                               v-model="newlistName"
                               class="w-full py-2 border border-gray-300 rounded-sm bg-gray-50">
                    </TextInput>
                  </div>
                </div>
              </div>
              <div class="flex justify-center gap-2 mx-7 py-7">
                <a href="#"
                   @click.prevent="closeAddListModal"
                   class="border border-gray-200 rounded-sm py-2 px-2 text-center text-sm font-medium w-full">
                  Cancel
                </a>
                <a
                    @click.prevent="createNewList"
                    href="#"
                    :class="{'bg-primary-700 text-white' : newlistName}"
                    class="border border-gray-200 text-gray-400 bg-gray-200 rounded-sm py-2 px-2 text-center text-sm font-medium w-full">
                  Save
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>

    </section>

  </AuthenticatedLayout>
</template>
