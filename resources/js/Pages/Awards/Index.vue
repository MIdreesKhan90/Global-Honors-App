<script setup>
import {computed, onMounted, ref, watch} from "vue";
import {Link, router} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Head} from "@inertiajs/vue3";
import SidebarFilter from "@/Components/SidebarFilter.vue";
import {Menu, MenuButton, MenuItem, MenuItems} from "@headlessui/vue";
import {
    MagnifyingGlassIcon,
    ArrowUpIcon,
    XMarkIcon,
    Squares2X2Icon,
    ListBulletIcon,
    MapPinIcon,
    CalendarDaysIcon,
} from "@heroicons/vue/24/solid";
import {useAwardsStore} from "@/stores/awards";
import {debounce} from "lodash";
import TextInput from "@/Components/TextInput.vue";
import axios from "axios";

const isAddAwardOpen = ref(false)
const isAddListOpen = ref(false)
const isAddedSuccess = ref(false)
const isAddToSearchOpen = ref(false)
const isAddNewSearchOpen = ref(false)
const newlistName = ref('');
const selectedList = ref(null);
const selectedSearchId = ref(null);
const selectedSearchName = ref(null);
const userLists = ref(null);
const userSearchLists = ref(null);
const successMessage = ref(null);
const searchListId = ref(null);
const searchQuery = ref();
const newSearchListName = ref('');


const addNewSearchList = () => {
    isLoading.value = true;
    isAddNewSearchOpen.value = false
    let formData = {
        name: newSearchListName.value,
    }
    axios.post('/add-search-list', formData)
        .then((res) => {
            console.log(res.data.successMessage);
            isLoading.value = false;
            selectSearchListId(res.data.searchList);
            addToSearchList();
            closeAddNewSearchModal();
            // openAddToSearchModal();
            newSearchListName.value = '';

        })
        .catch((error) => {
            console.log(error);
        });
};

const selectSearchListId = (item) => {
    selectedSearchId.value = item.id;
    selectedSearchName.value = item.name;
}
const addToSearchList = () => {
    const currentUrl = window.location.href;
    const url = new URL(currentUrl);

    isLoading.value = true;
    isAddToSearchOpen.value = false

    if (selectedSearchId.value === null || url.search === null) {
        console.error('No list selected or no search found.');
        isLoading.value = false;
        return;
    }
    let formData = {
        list_id: selectedSearchId.value,
        search_query: url.search,
    }

    axios.post('/add-search-to-list', formData)
        .then((res) => {
            isLoading.value = false;
            isAddedSuccess.value = true;
            successMessage.value = 'Your ' + selectedSearchName.value + ' search has been saved.';

            setTimeout(() => {
                isAddedSuccess.value = false;
                successMessage.value = '';
            }, 5000);
            selectedSearchId.value = null;
            selectedSearchName.value = null;
        })
        .catch((error) => {
            console.log(error);
        });
}

const toggleCheckbox = (awardId) => {
    awardsStore.toggleCheckbox(awardId);

    console.log(awardsStore.checkedAwards);
};

const isChecked = (awardId) => {
    return awardsStore.checkedAwards.includes(awardId);
};

const toggleAllAwards = () => {
    const awardsList = awards.data;
    for (const key in awardsList) {
        if (awardsList.hasOwnProperty(key)) {
            const award = awardsList[key];
            awardsStore.toggleCheckboxByValue(award.id, allAwardsToggle.value);
        }
    }
};


// onMounted(() => {
//     getUserLists();
//     getUserSearchLists();
// })

const openAddToSearchModal = () => {
    getUserSearchLists();
    isAddToSearchOpen.value = true
}
const closeAddToSearchModal = () => {
    isAddToSearchOpen.value = false
}
const openAddNewSearchModal = () => {
    isAddToSearchOpen.value = false
    isAddNewSearchOpen.value = true
}
const closeAddNewSearchModal = () => {
    isAddNewSearchOpen.value = false
}
const closeAddAwardModal = () => {
    isAddAwardOpen.value = false
}
const openAddAwardModal = () => {
    getUserLists();
    isAddAwardOpen.value = true
}

const closeAddListModal = () => {
    isAddListOpen.value = false
}
const openAddListModal = () => {
    isAddAwardOpen.value = false
    isAddListOpen.value = true
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
const getUserSearchLists = () => {
    axios.get('/get-search-lists')
        .then((res) => {
            userSearchLists.value = res.data.searchLists;
        })
        .catch((error) => {
            console.log(error)
        })
}
const awardsAddedToList = () => {
    // Check if selectedList or checkedAwards are not set
    if (selectedList.value === null || awardsStore.checkedAwards.length === 0) {
        console.error('No list selected or no awards checked.');
        return;
    }

    // console.log('List ID:', selectedList.value); // Should not be null or empty
    // console.log('Award IDs:', awardsStore.checkedAwards); // Should be an array with values
    isLoading.value = true;
    isAddAwardOpen.value = false;
    axios.post('/add-to-list', {
        list_id: selectedList.value,
        award_ids: awardsStore.checkedAwards
    })
        .then(response => {
            // Handle success, close modal, show success message
            console.log(response.data);
            isLoading.value = false;
            if (response.data) {
                successMessage.value = response.data.message;
                isAddedSuccess.value = true;

                setTimeout(() => {
                    isAddedSuccess.value = false;
                }, 5000);
            }
            // Reset selectedList and checkedAwards after successful addition
            selectedList.value = null;
            awardsStore.checkedAwards = [];
        })
        .catch(error => {
            // Handle errors, possibly validation errors
            console.error(error.response.data);
        });
}

const closeSuccessMessage = () => {
    isAddedSuccess.value = false;
}

const {
    awards,
    industries,
    categories,
    locations,
    keywords,
    activeLocation,
    activeIndustry,
    activeCategory,
    activeBusinessFunction,
} =
    defineProps({
        awards: {
            type: Object,
        },
        industries: {
            type: Array,
        },
        categories: {
            type: Array,
        },
        locations: {
            type: Array,
        },
        businessFunctions: {
            type: Array,
        },
        keywords: String,
        activeLocation: String,
        activeIndustry: String,
        activeCategory: String,
        activeBusinessFunction: String,
        sortBy: String,
    });

const awardsStore = useAwardsStore();
const isLoading = ref(false);
const isGridView = ref(true);
const isSearchInputDisabled = ref(false);
const sortOrder = ref('');
const allAwardsToggle = ref(false);

function createNewList() {
    isLoading.value = true;
    isAddListOpen.value = false;
    let formData = {
        name: newlistName.value,
    }
    axios.post('/list-save', formData)
        .then((res) => {
            console.log(res.data);
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

function removeFilterItem(type, id) {
    awardsStore.activeFilters[type] = awardsStore.activeFilters[type].filter((item) => item.id !== id);
}

function removeKeyword(type, id) {
    awardsStore.activeFilters[type].splice(id, 1);
}

function clearFilters() {
    awardsStore.activeFilters.industry = [];
    // awardsStore.activeFilters.category = [];
    awardsStore.activeFilters.location = [];
    awardsStore.activeFilters.businessFunction = [];
    awardsStore.activeFilters.keywords = [];
}

const truncateDescription = (text, limit) => {
    if (text && text.length) {
        return text.length <= limit ? text : text.slice(0, limit) + "...";
    } else {
        return "";
    }
};

function formatKey(key) {
    return key.replace(/([a-z])([A-Z])/g, '$1 $2');
}


const activeKeywordsArray = JSON.parse(keywords);
const activeLocationArray = JSON.parse(activeLocation);
const activeIndustryArray = JSON.parse(activeIndustry);
const activeCategoryArray = JSON.parse(activeCategory);
const activeBusinessFunctionArray = JSON.parse(activeBusinessFunction);

if (activeLocationArray || activeKeywordsArray || activeIndustryArray || activeCategoryArray || activeBusinessFunctionArray) {
    awardsStore.activeFilters["location"] = activeLocationArray;
    awardsStore.activeFilters["keywords"] = activeKeywordsArray;
    awardsStore.activeFilters["industry"] = activeIndustryArray;
    // awardsStore.activeFilters["category"] = activeCategoryArray;
    awardsStore.activeFilters["businessFunction"] = activeBusinessFunctionArray;
}

const search = ref('');
const searchArray = ref([]);

const activeIndustries = computed(() => awardsStore.activeFilters["industry"]);
// const activeCategories = computed(() => awardsStore.activeFilters["category"]);
const activeLocations = computed(() => awardsStore.activeFilters["location"]);
const activeBusinessFunctions = computed(() => awardsStore.activeFilters["businessFunction"]);

let data = {};

let doSearch = () => {
    isSearchInputDisabled.value = true;
    isLoading.value = true

    router.visit('/awards', {
        data: data,
        preserveState: true,
        preserveScroll: true,
        onSuccess: page => {
            isLoading.value = false;
            isSearchInputDisabled.value = false;
        },
    })

}

let doSearchNormal = (filters, sortOrder) => {
    // if (search.value.length > 0) {
    //   data["search"] = search.value;
    // }
    if (filters.keywords.length > 0) {
        data["keywords"] = JSON.stringify(filters["keywords"]);
    }
    if (filters.location.length > 0) {
        data["location"] = JSON.stringify(filters["location"]);
    }
    if (filters.industry.length > 0) {
        data["industry"] = JSON.stringify(filters["industry"]);
    }
    // if (filters.category.length > 0) {
    //   data["category"] = JSON.stringify(filters["category"]);
    // }
    if (filters.businessFunction.length > 0) {
        data["businessFunction"] = JSON.stringify(filters["businessFunction"]);
    }
    if (sortOrder.length > 0) {
        data["sortOrder"] = sortOrder;
    }

    doSearch();
}

let inputSearch = (search) => {

    // Add new search term if it's not already in the array and is not empty
    if (search !== '') {
        awardsStore.updateKeywords(search);
    }

    // Update the store only if there are valid search keywords
    if (awardsStore.activeFilters.keywords.length > 0) {
        data["keywords"] = JSON.stringify(awardsStore.activeFilters["keywords"]);
    }

    console.log(awardsStore.activeFilters["keywords"]);
    if (awardsStore.activeFilters.location.length > 0) {
        data["location"] = JSON.stringify(awardsStore.activeFilters["location"]);
    }
    if (awardsStore.activeFilters.industry.length > 0) {
        data["industry"] = JSON.stringify(awardsStore.activeFilters["industry"]);
    }
    // if (awardsStore.activeFilters.category.length > 0) {
    //   data["category"] = JSON.stringify(awardsStore.activeFilters["category"]);
    // }
    if (awardsStore.activeFilters.businessFunction.length > 0) {
        data["businessFunction"] = JSON.stringify(awardsStore.activeFilters["businessFunction"]);
    }
    if (sortOrder.value.length > 0) {
        data["sortOrder"] = sortOrder.value;
    }

    doSearch();
};

// watch(
//     [search],
//     ([newSearch]) => {
//         doSearchDelay(newSearch);
//     }
// );

watch(
    [awardsStore.activeFilters, sortOrder],
    ([newActiveFilters, newSortOrder]) => {
        doSearchNormal(newActiveFilters, newSortOrder);
    }
);

</script>

<template>
    <Head title="Dashboard"/>

    <AuthenticatedLayout>
        <!-- <template #header>
          <h2 class="font-semibold text-xl text-gray-800 leading-tight">Awards</h2>
        </template> -->

        <div class="relative z-10" v-if="isAddToSearchOpen">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                <div class="flex min-h-full items-end justify-center text-center sm:items-center sm:p-0">
                    <div
                        class="relative transform overflow-hidden rounded bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                        <div class="bg-white px-4 py-4">
                            <div class="flex justify-end">
                                <a
                                    href="#"
                                    @click.prevent="closeAddToSearchModal">
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
                            <div>
                                <h3 class="text-lg mx-2 font-bold font-mulish text-gray-700"> Save search to...</h3>
                                <div class="mt-2">
                                    <ul class="text-sm font-normal font-mulish text-gray-500 max-h-72 overflow-y-auto">
                                        <li class="py-2 px-2 cursor-pointer" v-for="(item, i) in userSearchLists" :key="i"
                                            :class="{'bg-gray-200' : selectedSearchId === item.id}"
                                            @click.prevent="selectSearchListId(item)">
                                            {{ item.name }}
                                        </li>
                                    </ul>
                                    <a
                                        href="#"
                                        @click.prevent="openAddNewSearchModal"
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
                                        >Create new search</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-between gap-2 mx-8 mb-7">
                            <a
                                href="#"
                                @click.prevent="closeAddToSearchModal"
                                class="border border-gray-200 rounded-sm py-2 px-2 text-center text-sm font-medium w-full">
                                Cancel
                            </a>
                            <button
                                @click.prevent="addToSearchList"
                                :class="{'bg-primary-700 text-white' : selectedSearchId}"
                                class="border border-gray-200 text-gray-400 bg-gray-200 rounded-sm py-2 px-2 text-center text-sm font-medium w-full">
                                Save
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="relative z-10" v-if="isAddNewSearchOpen">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div
                        class="relative transform overflow-hidden rounded bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                        <div class="bg-white px-4 py-3">
                            <div class="flex justify-end">
                                <a
                                    href="#"
                                    @click.prevent="closeAddNewSearchModal">
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
                                <h3 class="text-lg font-bold font-mulish text-gray-700">Create new search</h3>
                                <div class="mt-2">
                                    <TextInput placeholder="New search name"
                                               v-model="newSearchListName"
                                               class="w-full py-2 border border-gray-300 rounded-sm bg-gray-50">
                                    </TextInput>
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-center gap-2 mx-7 py-7">
                            <a href="#"
                               @click.prevent="closeAddNewSearchModal"
                               class="border border-gray-200 rounded-sm py-2 px-2 text-center text-sm font-medium w-full">
                                Cancel
                            </a>
                            <a
                                @click.prevent="addNewSearchList"
                                href="#"
                                :class="{'bg-primary-700 text-white' : newSearchListName}"
                                class="border border-gray-200 text-gray-400 bg-gray-200 rounded-sm py-2 px-2 text-center text-sm font-medium w-full">
                                Save
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="h-92vh">
            <main class="bg-gray-50 flex h-full">
                <div class="w-80 px-6 py-6 bg-white border-r border-gray-200 h-full flex flex-col justify-between">
                    <div>
                        <h3 class="mb-6 text-gray-900 text-lg font-bold">Browse awards by</h3>
                        <SidebarFilter
                            :industries="industries"
                            :categories="categories"
                            :awards="awards"
                            :locations="locations"
                            :businessFunctions="businessFunctions"
                            :activeLocations="activeLocations"
                            :activeIndustries="activeIndustries"
                            :activeBusinessFunctions="activeBusinessFunctions"
                        />
                        <div class="flex items-center justify-end">
                            <a
                                @click.prevent="clearFilters"
                                href="/"
                                class="text-primary-700 mx-2 text-sm font-medium underline block text-right mt-6"
                            >Clear All</a
                            >
                            <a
                                v-if="
                awardsStore.activeFilters['keywords'].length > 0 ||
                awardsStore.activeFilters['industry'].length > 0 ||
                awardsStore.activeFilters['location'].length > 0 ||
                awardsStore.activeFilters['businessFunction'].length > 0
              "
                                href="#"
                                @click.prevent="openAddToSearchModal"
                                class="p-2 w-2/5 mx-2 text-center rounded-sm text-xs font-semibold block mt-6 text-white bg-primary-700">
                                Save search
                            </a>
                        </div>

                    </div>
                    <div class="w-full bg-gray-100 p-3 gap-3 hidden">
                        <p class="text-gray-600 text-xs font-light mb-3">
                            Need a custom list? We’ll have your list ready in less than 48 hours!
                        </p>
                        <button
                            type="button"
                            class="btn rounded-sm border border-primary-700 text-primary-700 py-2 px-3 text-xs font-semibold"
                        >
                            Get Custom List
                        </button>
                    </div>
                </div>
                <div class="px-6 pt-6 w-full overflow-auto relative">
                    <div class="bg-white px-9 py-9 w-full">
                        <div class="pb-6 border-b border-gray-300">
                            <h1 class="text-gray-800 text-4xl font-bold">Awards</h1>
                        </div>
                        <div class="py-6 border-b border-gray-300 flex items-center justify-between">
                            <form @submit.prevent="inputSearch(search)" action="" class="flex items-center">
                                <div class="relative flex items-center">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <MagnifyingGlassIcon class="w-4 h-4 text-gray-500 dark:text-gray-400"/>
                                    </div>
                                    <!--                  <input-->
                                    <!--                      type="search"-->
                                    <!--                      v-model="search"-->
                                    <!--                      id="default-search"-->
                                    <!--                      class="block leading-tight text-sm font-normal text-gray-500 py-4 pr-4 pl-9 border border-gray-200 rounded-sm w-80"-->
                                    <!--                      placeholder="Search by key words"-->
                                    <!--                      :disabled="isSearchInputDisabled"-->
                                    <!--                  />-->
                                    <TextInput placeholder="Search by key words"
                                               v-model="search"
                                               :aria-disabled="isSearchInputDisabled"
                                               class="block leading-tight text-sm font-normal text-gray-500 py-4 pr-4 pl-9 border border-gray-200 rounded-sm w-80">
                                    </TextInput>
                                </div>
                                <button type="submit" class="bg-primary-700 p-3 ml-2 rounded">
                                    <MagnifyingGlassIcon class="w-7 h-7 text-white dark:text-gray-400"/>
                                </button>
                            </form>
                            <div class="flex items-center">
                                <button
                                    @click="isGridView = true"
                                    type="button"
                                    class="btn text-sm font-semibold text-primary-700 p-2"
                                    :class="{ 'border border-primary-700': isGridView }"
                                >
                                    <Squares2X2Icon class="h-5 w-5"/>
                                </button>
                                <button
                                    @click="isGridView = false"
                                    type="button"
                                    class="btn text-sm font-semibold text-primary-700 p-2 ms-2"
                                    :class="{ 'border border-primary-700': !isGridView }"
                                >
                                    <ListBulletIcon class="h-5 w-5"/>
                                </button>
                                <Menu as="div" class="relative inline-block ms-6">
                                    <div>
                                        <MenuButton class="text-sm font-semibold text-primary-700 py-2 px-3 border border-primary-700">
                                            Sort by
                                        </MenuButton>
                                    </div>

                                    <transition
                                        enter-active-class="transition ease-out duration-100"
                                        enter-from-class="transform opacity-0 scale-95"
                                        enter-to-class="transform opacity-100 scale-100"
                                        leave-active-class="transition ease-in duration-75"
                                        leave-from-class="transform opacity-100 scale-100"
                                        leave-to-class="transform opacity-0 scale-95"
                                    >
                                        <MenuItems
                                            class="absolute right-0 z-10 mt-2 w-56 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                                        >
                                            <div class="py-1">
                                                <MenuItem v-slot="{ active }">
                                                    <a
                                                        href="#"
                                                        @click.prevent="sortOrder = 'name-asc'"
                                                        :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block px-4 py-2 text-sm']"
                                                    >
                                                        Award name: A-Z
                                                    </a>
                                                </MenuItem>
                                                <MenuItem v-slot="{ active }">
                                                    <a
                                                        href="#"
                                                        @click.prevent="sortOrder = 'name-desc'"
                                                        :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block px-4 py-2 text-sm']"
                                                    >
                                                        Award name: Z-A
                                                    </a>
                                                </MenuItem>
                                                <MenuItem v-slot="{ active }">
                                                    <a
                                                        href="#"
                                                        @click.prevent="sortOrder = 'deadline-asc'"
                                                        :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block px-4 py-2 text-sm']"
                                                    >
                                                        Deadline: nearest to furthest
                                                    </a>
                                                </MenuItem>
                                                <MenuItem v-slot="{ active }">
                                                    <a
                                                        href="#"
                                                        @click.prevent="sortOrder = 'deadline-desc'"
                                                        :class="[
                              active ? 'bg-gray-100 text-gray-900' : 'text-gray-700',
                              'px-4 py-2 text-sm flex items-center',
                            ]"
                                                    >
                                                        Deadline: furthest to nearest
                                                    </a>
                                                </MenuItem>
                                            </div>
                                        </MenuItems>
                                    </transition>
                                </Menu>
                            </div>
                        </div>
                        <div
                            v-if="
                awardsStore.activeFilters['keywords'].length > 0 ||
                awardsStore.activeFilters['industry'].length > 0 ||
                awardsStore.activeFilters['location'].length > 0 ||
                awardsStore.activeFilters['businessFunction'].length > 0
              "
                            class="flex items-center flex-wrap mt-9 gap-3"
                        >
                            <h5 class="text-gray-900 text-sm font-bold">Active filters:</h5>

                            <template v-for="(value, key) in awardsStore.activeFilters">
                                <p class="text-primary-600 text-xs font-bold capitalize" v-if="value.length > 0">{{ formatKey(key) }} :</p>
                                <div
                                    v-for="(item, i) in value"
                                    :key="i"
                                    class="rounded border border-primary-200 bg-primary-50 px-2 py-1 flex items-center"
                                >
                                    <p class="mr-2 text-xs font-normal text-gray-900 capitalize">{{ item.name ?? item }}</p>
                                    <XMarkIcon @click="item.id ? removeFilterItem(key, item.id) : removeKeyword(key, i)"
                                               class="w-4 h-4 cursor-pointer"/>
                                </div>
                            </template>

                            <a @click.prevent="clearFilters" href="/" class="text-xs font-normal text-gray-900 underline capitalize"
                            >clear all</a
                            >
                        </div>
                        <div class="mt-9">
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <h4
                                        v-if="awards.total !== 0 &&
                      (awardsStore.activeFilters['keywords'].length > 0 ||
                      awardsStore.activeFilters['industry'].length > 0 ||
                      awardsStore.activeFilters['location'].length > 0 ||
                      awardsStore.activeFilters['businessFunction'].length > 0)"
                                        class="text-sm font-bold flex items-center">
                                        <input
                                            id="default-checkbox"
                                            type="checkbox"
                                            v-model="allAwardsToggle"
                                            @change="toggleAllAwards()"
                                            class="w-5 h-5 mr-2 rounded text-primary-700 focus:ring-primary-700 bg-gray-100 border-gray-300 accent-primary-700 hover:accent-primary-700"
                                        />
                                        <span class="font-normal mx-1">{{ awards.total }} </span> Awards
                                    </h4>
                                </div>
                                <Menu as="div">
                                    <div class="relative inline-block text-left">
                                        <MenuButton
                                            @click.prevent="openAddAwardModal"
                                            class="inline-flex w-full justify-center items-center gap-x-1.5 rounded-sm bg-primary-700 px-5 py-3 text-white"
                                        >
                                            + Add to list ({{ awardsStore.checkedAwards.length }})
                                        </MenuButton>
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
                                                            Add ({{ awardsStore.checkedAwards.length }})
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
                                </Menu>
                            </div>
                            <div v-if="isGridView">
                                <div class="grid grid-cols-3 gap-5">
                                    <Link :href="route('awards.show', item.id)"
                                          v-for="(item, i) in awards.data"
                                          :key="i"
                                          class="relative p-6 bg-white border border-gray-200 rounded-sm h-60 flex flex-col justify-between hover:shadow-xl hover:border-primary-100"
                                    >
                                        <div>
                                            <div class="flex justify-between">
                                                <div class="w-3/4">
                                                    <h6 class="text-primary-600 text-xs font-bold">
                                                        {{ item.industries && item.industries.name ? item.industries.name : "" }}
                                                    </h6>
                                                    <h2 class="text-gray-900 text-sm font-bold">{{ item.name }}</h2>

                                                    <h5 class="text-xs font-normal text-gray-900">
                                                        {{ item.sponsors && item.sponsors.name ? item.sponsors.name : "" }}
                                                    </h5>
                                                </div>
                                                <input
                                                    @click.stop="toggleCheckbox(item.id)"
                                                    id="award-checkbox-{{ item.id }}"
                                                    type="checkbox"
                                                    :checked="isChecked(item.id)"
                                                    class="w-6 h-6 text-primary-700 bg-gray-100 focus:ring-primary-700  border-gray-300 accent-primary-700 hover:accent-primary-700"
                                                />
                                            </div>
                                            <div class="text-xs font-light text-gray-800 mt-3"
                                                 v-html="truncateDescription(item.description, 150)">
                                            </div>
                                        </div>
                                        <div class="flex items-center">
                                            <p class="flex text-xs font-light text-gray-900">
                                                <MapPinIcon class="w-4 h-4 mr-2"/>
                                                {{ item.region }}
                                            </p>
                                            <p class="flex text-xs font-light text-gray-900 ml-6">
                                                <CalendarDaysIcon class="w-4 h-4 mr-2"/>
                                                Deadline {{ item.deadline_date }}
                                            </p>
                                        </div>
                                    </Link>
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
                                <div v-if="awards.total < 1">
                                    <div class="flex justify-center">No records were found that match the criteria.</div>
                                </div>
                            </div>
                            <div class="list-view" v-if="!isGridView">
                                <Link :href="route('awards.show', item.id)"
                                      v-for="(item, i) in awards.data"
                                      :key="i"
                                      class="border border-gray-200 bg-white rounded-sm py-3 px-6 min-h-180 flex items-center mb-3 hover:shadow-xl hover:border-2 hover:border-primary-100"
                                >
                                    <div class="flex gap-9">
                                        <div class="flex gap-6 items-center">
                                            <input
                                                @click.stop="toggleCheckbox(item.id)"
                                                id="award-checkbox-{{ item.id }}"
                                                type="checkbox"
                                                :checked="isChecked(item.id)"
                                                class="w-6 h-6 text-primary-700 focus:ring-primary-700  bg-gray-100 border-gray-300 accent-primary-700 hover:accent-primary-700"
                                            />
                                            <div class="w-72">
                                                <h6 class="text-primary-600 text-xs font-bold">
                                                    {{ item.industries && item.industries.name ? item.industries.name : "" }}
                                                </h6>
                                                <Link
                                                    :href="route('awards.show', item.id)"
                                                >
                                                    <h2 class="text-gray-900 text-sm font-bold">{{ item.name }}</h2>
                                                </Link>
                                                <h5 class="text-xs font-normal text-gray-900">
                                                    {{ item.sponsors && item.sponsors.name ? item.sponsors.name : "" }}
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="w-96 text-xs font-light text-gray-800 mt-3"
                                             v-html="truncateDescription(item.description, 150)">
                                        </div>
                                        <div>
                                            <p class="flex text-xs font-light text-gray-900 mb-5">
                                                <MapPinIcon class="w-4 h-4 mr-1"/>
                                                {{ item.region }}
                                            </p>
                                            <p class="flex text-xs font-light text-gray-900">
                                                <CalendarDaysIcon class="w-4 h-4 mr-1"/>
                                                Deadline {{ item.deadline_date }}
                                            </p>
                                        </div>
                                    </div>
                                </Link>
                                <div v-if="awards.total < 1">
                                    <div class="flex justify-center">No records were found that match the criteria.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a href="" class="btn-scroll-up">
                        <ArrowUpIcon class="h-5 w-5 text-primary-700" aria-hidden="true"/>
                    </a>
                    <div class="flex justify-center mt-5 mb-2" v-if="awards.total > 99">
                        <template v-for="(link,i) in awards.links" :key="link.label">
                            <div
                                v-if="link.url === null"
                                class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-900"
                                :class="{'rounded-l': i === 0, 'rounded-r': i === awards.links.length-1}"
                                v-html="link.label"></div>
                            <Link
                                v-else
                                class="px-3 py-2  border border-gray-300 text-sm font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-900"
                                :class="{ 'bg-primary-100 text-primary-700': link.active, 'bg-white': !link.active,
                   'rounded-l': i === 0,
                   'rounded-r': i === awards.links.length-1
              }"
                                :href="link.url" v-html="link.label">
                            </Link>
                        </template>
                    </div>
                </div>
            </main>
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
    </AuthenticatedLayout>
</template>
