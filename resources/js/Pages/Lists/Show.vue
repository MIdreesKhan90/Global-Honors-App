<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, Link, router} from '@inertiajs/vue3';
import {ChevronDownIcon, ChevronRightIcon, PlusIcon} from "@heroicons/vue/24/solid/index.js";
import {computed, ref} from "vue";
import axios from "axios";

const listName = ref(list.name)

const {
  list,
  awards
} = defineProps({
  list: {
    type: Object
  },
  awards: {
    type: Array
  }
})

const isDeleteList = ref(false);
const isRemovedSuccess = ref(false);
const isLoading = ref(false);
// const isLoading = ref(false);
const successMessage = ref(null);

function openDeleteModal() {
  isDeleteList.value = true;
}

function closeDeleteModal() {
  isDeleteList.value = false;
}


const awardsDeleteFromList = () => {
  isDeleteList.value = false;
  isLoading.value = true;
  // isLoading.value = true;
  axios.post('/remove-from-list', {
    list_id: list.id,
    award_ids: checkedAwards.value
  })
      .then(response => {
        // Handle success, close modal, show success message
        console.log(response.data);

        if (response.data) {
          // isLoading.value = false;
          isLoading.value = false;
          successMessage.value = response.data.message;
          isRemovedSuccess.value = true;

          // isDeleteList.value = false;
          setTimeout(() => {
            // isRemovedSuccess.value = false;
              router.get('/list/' + list.id);
          }, 2000);

        }
        checkedAwards.value = [];
      })
      .catch(error => {
        // Handle errors, possibly validation errors
        console.error(error.response.data);
      });
}

const closeRemoveMessage = () => {
  isRemovedSuccess.value = false;
}

// Computed property to determine if all awards are selected
const allSelected = computed(() => {
  return checkedAwards.value.length === awards.length;
});

// Method to toggle select all / deselect all
const toggleSelectAll = () => {
  if (checkedAwards.value.length === awards.length) {
    // All awards are currently selected, so clear the array to deselect all
    checkedAwards.value = [];
  } else {
    // Not all awards are selected, so fill the array with all award IDs
    checkedAwards.value = awards.map(award => award.id);
    console.log('All :' + checkedAwards.value)
  }
};


const checkedAwards = ref([]);

const toggleCheckbox = (awardId) => {
  const index = checkedAwards.value.indexOf(awardId);
  if (index === -1) {
    // Award ID is not currently in the array, so add it
    checkedAwards.value.push(awardId);
    console.log('Single :' + checkedAwards.value)
  } else {
    // Award ID is currently in the array, so remove it
    checkedAwards.value.splice(index, 1);
    console.log('Single :' + checkedAwards.value)
  }
};

const isChecked = (awardId) => {
  return checkedAwards.value.includes(awardId);
}

// In your Vue component methods
function downloadFile() {
  axios({
    url: route('lists.export', {list: list.id}),
    method: 'GET',
    responseType: 'blob', // Important
  }).then((response) => {
    // Create a new Blob object using the response data of the onload object
    const blob = new Blob([response.data], {type: response.headers['content-type']});
    const link = document.createElement('a');
    link.href = window.URL.createObjectURL(blob);
    link.download = `${list.name.replace(/\s+/g, '_')}.xlsx`;
    link.click();
    window.URL.revokeObjectURL(link.href);
  }).catch(error => console.error('Download error:', error));
}


const selectedPeople = ref([])
const indeterminate = computed(() => selectedPeople.value.length > 0 && selectedPeople.value.length < awards.length)
</script>

<template>
  <Head title="Dashboard"/>

  <AuthenticatedLayout>
    <section class="bg-white pt-14 pb-2 h-92vh overflow-x-auto">
      <div class="container mx-auto px-4 sm:px-0">
        <div class="sm:flex items-center justify-between">
          <div class="flex items-center">
            <Link :href="'/awards'"><p class="text-sm font-medium text-gray-700">Awards</p></Link>
            <ChevronRightIcon class="h-3 w-3 text-gray-500 mx-2" aria-hidden="true"/>
              <Link :href="'/lists'"><p class="text-sm font-medium text-gray-700">My Lists</p></Link>
            <ChevronRightIcon class="h-3 w-3 text-gray-500 mx-2" aria-hidden="true"/>
            <p class="text-sm font-medium text-gray-500">{{ listName }}</p>
          </div>
        </div>
        <div class="flex justify-between my-6">
          <h4 class="text-4xl font-bold text-gray-800 font-mulish">
            {{ listName }}
          </h4>
          <Link @click.prevent="downloadFile"
                class="flex gap-2 bg-primary-700 text-white text-center justify-center p-2 rounded-sm" download>
            <svg class="w-5 h-5 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                 viewBox="0 0 20 20">
              <path
                  d="M14.707 7.793a1 1 0 0 0-1.414 0L11 10.086V1.5a1 1 0 0 0-2 0v8.586L6.707 7.793a1 1 0 1 0-1.414 1.414l4 4a1 1 0 0 0 1.416 0l4-4a1 1 0 0 0-.002-1.414Z"/>
              <path
                  d="M18 12h-2.55l-2.975 2.975a3.5 3.5 0 0 1-4.95 0L4.55 12H2a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-4a2 2 0 0 0-2-2Zm-3 5a1 1 0 1 1 0-2 1 1 0 0 1 0 2Z"/>
            </svg>
            Download list
          </Link>
        </div>
        <hr>
        <div class="flex justify-between items-center my-6">
          <h4 class="text-sm flex items-center gap-1 font-bold text-gray-800 font-mulish">
            <input @click="toggleSelectAll"
                   id="default-checkbox"
                   type="checkbox"
                   :checked="allSelected"
                   class="w-5 h-5 mr-2 rounded text-primary-700 focus:ring-primary-700 bg-gray-100 border-gray-300 accent-primary-700 hover:accent-primary-700"
            />
            <span class="text-sm font-normal">{{ list.awards_count }}</span> Awards
          </h4>
          <a href="#"
             @click.prevent="openDeleteModal"
             class="flex gap-2 bg-primary-700 text-white text-center justify-center p-2 rounded-sm">
            Remove ({{ checkedAwards.length }})
          </a>
        </div>

        <div class="relative border rounded-sm overflow-x-auto">
          <div class="relative">
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

            <table class="w-full text-sm text-left text-gray-500 table-auto">
              <thead class="text-sm font-bold text-gray-700 bg-gray-50">
              <tr>
                <th scope="col" class="relative px-7 sm:w-12 sm:px-6">
                  <!--                  <input type="checkbox"-->
                  <!--                         class="absolute left-4 top-1/2 -mt-2 h-4 w-4 rounded border-gray-300 text-primary-700 focus:ring-primary-700"-->
                  <!--                         :checked="indeterminate || selectedPeople.length === awards.length"-->
                  <!--                         :indeterminate="indeterminate"-->
                  <!--                         @change="selectedPeople = $event.target.checked ? awards.map((item) => item.id) : []"/>-->
                </th>
                <th scope="col" class="min-w-[12rem] py-3.5 pr-3 text-left text-sm font-semibold text-gray-900">Name
                </th>
                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Sponsor</th>
                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Deadline</th>
                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Award URL</th>
                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Category URL</th>
                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Cost</th>
                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Industry</th>
                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Business Function</th>
                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Location</th>
              </tr>
              </thead>
              <tbody>
              <tr v-for="(item, i) in awards" :key="item.id"
                  :class="[selectedPeople.includes(item.id) && 'bg-gray-50']"
                  class="bg-white py-2 border text-gray-500 text-sm font-normal">
                <td class="relative px-7 sm:w-12 sm:px-6">
                  <div v-if="selectedPeople.includes(item.id)"
                       class="absolute inset-y-0 left-0 w-0.5 bg-primary-700"></div>
                  <input type="checkbox"
                         :id="'award-checkbox-' + item.id"
                         :value="item.id"
                         :checked="isChecked(item.id)"
                         @change="toggleCheckbox(item.id)"
                         class="h-4 w-4 text-primary-700 border-gray-300 rounded">
                </td>
                <td :class="['whitespace-nowrap py-4 pr-3 text-sm font-medium', selectedPeople.includes(item.id) ? 'text-primary-700' : 'text-gray-900']">
                  {{ item.name ?? '---' }}
                </td>
                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                  {{ item.sponsors?.name ?? '---' }}
                </td>
                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                  {{ item.deadline_date ?? '---' }}
                </td>
                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                  {{ item.details_url ?? '---' }}
                </td>
                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                  {{ item.categories_url ?? '---' }}
                </td>
                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                  {{ item.entry_cost ? '$' + item.entry_cost : '---' }}
                </td>
                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                  {{ item.industries?.name ?? '---' }}
                </td>
                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                  {{ item.businessFunctions?.name ?? '---' }}
                </td>
                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                  {{ item.location ?? '---' }}
                </td>
              </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div v-if="isDeleteList">
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
                    <h3 class="text-lg font-bold font-mulish text-gray-700">Remove awards</h3>
                    <div class="mt-2">
                      <p class="text-sm font-normal font-mulish text-gray-500">Are you sure you want to remove <span
                          class="font-bold text-gray-700">{{ checkedAwards.length }} </span>awards? This action cannot
                        be undone.</p>
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
                      @click.prevent="awardsDeleteFromList"
                      class="border border-gray-200 text-white bg-error-700 rounded-sm py-2 px-2 text-center text-sm font-medium w-full">
                    Delete
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div v-if="isRemovedSuccess"
           class="fixed bottom-5 flex left-4 z-50 justify-start items-center gap-3 w-72 bg-gray-800 border shadow text-white px-4 py-3 rounded-lg"
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
          <button @click.prevent="closeRemoveMessage">
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
    </section>
  </AuthenticatedLayout>
</template>
