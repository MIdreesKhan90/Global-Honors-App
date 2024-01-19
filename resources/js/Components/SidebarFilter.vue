<script setup>
import {Disclosure, DisclosureButton, DisclosurePanel} from "@headlessui/vue";
import {ChevronRightIcon} from "@heroicons/vue/20/solid";
import {useAwardsStore} from "@/stores/awards";
import {onMounted, ref} from "vue";

const awardsStore = useAwardsStore();

const {
    industries,
    categories,
    locations,
    businessFunctions,
    activeIndustries,
    activeLocations,
    activeCategories,
    activeBusinessFunctions
} = defineProps({
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
    activeIndustries: Array,
    activeLocations: Array,
    activeCategories: Array,
    activeBusinessFunctions: Array
});

const isOpen = ref(false);

const navigation = {
    industry: {
        current: false,
        children: industries,
    },
    category: {
        current: false,
        children: categories,
    },
    location: {
        current: false,
        children: locations.map((item) => ({
            id: item.id,
            name: item.location,
        })),
    },
    businessFunction: {
        current: false,
        children: businessFunctions,
    },
};



function handleDisclosureClick(event) {
    if (event.detail !== 1) {
        return;
    }
    const panels = [...document.querySelectorAll('[aria-expanded=true][aria-label=panel]')]
    panels.map((panel) => {
        if(panel !== event.currentTarget){
            panel.click()
        }
    })
}

</script>

<template>
    <nav class="flex flex-1 flex-col">
        <ul role="list" class="flex flex-1 flex-col gap-y-7">
            <li>
                <ul role="list" class="-mx-2 space-y-1">
                    <li>
                        <a
                            v-if="!navigation['industry'].children"
                            href="/"
                            :class="[
                navigation['industry'].current ? 'bg-gray-50' : 'hover:bg-gray-50',
                'group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold text-gray-700',
              ]"
                        >
                            Industry
                        </a>
                        <Disclosure as="div" :defaultOpen="activeIndustries.length > 0" v-else v-slot="{ open }">
                            <DisclosureButton
                                aria-label="panel"
                                @click="handleDisclosureClick"
                                :class="[
                            open ? 'bg-gray-50' : 'hover:bg-gray-50',
                            'flex items-center w-full text-left rounded-md p-2 gap-x-3 text-sm leading-6 text-gray-700 border-b',
                            ]"
                            >
                                Industry
                                <ChevronRightIcon
                                    :class="[open ? 'rotate-90 text-gray-500' : 'text-gray-400', 'ml-auto h-5 w-5 shrink-0']"
                                    aria-hidden="true"
                                />
                            </DisclosureButton>
                            <DisclosurePanel as="ul" class="mt-1 px-2 h-64 overflow-auto custom-sm-scrollbar">
                                <li v-for="(subItem, i) in navigation['industry'].children" :key="subItem.name" class="flex items-center mt-3">
                                    <input
                                        :id="subItem.name + '-' + i"
                                        v-model="awardsStore.activeFilters['industry']"
                                        type="checkbox"
                                        :value="subItem"
                                        class="text-primary-700 bg-gray-100 border-gray-300 accent-primary-700 hover:accent-primary-700 mr-2 cursor-pointer"
                                    />
                                    <label
                                        :for="subItem.name + '-' + i"
                                        class="cursor-pointer"
                                        :class="[
                      subItem.current ? 'bg-gray-50' : 'hover:bg-gray-50',
                      'block rounded-md text-sm leading-6 text-gray-700',
                    ]"
                                    >
                                        {{ subItem.name }}
                                    </label>
                                </li>
                            </DisclosurePanel>
                        </Disclosure>
                    </li>

                    <!--          <li>-->
                    <!--            <a-->
                    <!--                v-if="!navigation['category'].children"-->
                    <!--                href="/"-->
                    <!--                :class="[-->
                    <!--                navigation['category'].current ? 'bg-gray-50' : 'hover:bg-gray-50',-->
                    <!--                'group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold text-gray-700',-->
                    <!--              ]"-->
                    <!--            >-->
                    <!--              Category-->
                    <!--            </a>-->
                    <!--            <Disclosure as="div" :defaultOpen="activeCategories.length > 0" v-else v-slot="{ open }">-->
                    <!--              <DisclosureButton-->
                    <!--                  :class="[-->
                    <!--                  navigation['category'].current ? 'bg-gray-50' : 'hover:bg-gray-50',-->
                    <!--                  'flex items-center w-full text-left rounded-md p-2 gap-x-3 text-sm leading-6 text-gray-700 border-b',-->
                    <!--                ]"-->
                    <!--              >-->
                    <!--                Category-->
                    <!--                <ChevronRightIcon-->
                    <!--                    :class="[open ? 'rotate-90 text-gray-500' : 'text-gray-400', 'ml-auto h-5 w-5 shrink-0']"-->
                    <!--                    aria-hidden="true"-->
                    <!--                />-->
                    <!--              </DisclosureButton>-->
                    <!--              <DisclosurePanel as="ul" class="mt-1 px-2 h-64 overflow-auto custom-sm-scrollbar">-->
                    <!--                <li v-for="(subItem, i) in navigation['category'].children" :key="subItem.name" class="flex items-center mt-3">-->
                    <!--                  <input-->
                    <!--                      :id="subItem.name + '-' + i"-->
                    <!--                      v-model="awardsStore.activeFilters['category']"-->
                    <!--                      type="checkbox"-->
                    <!--                      :value="subItem"-->
                    <!--                      class="text-primary-700 bg-gray-100 border-gray-300 accent-primary-700 hover:accent-primary-700 mr-2 cursor-pointer"-->
                    <!--                  />-->
                    <!--                  <label-->
                    <!--                      :for="subItem.name + '-' + i"-->
                    <!--                      class="cursor-pointer"-->
                    <!--                      :class="[-->
                    <!--                      subItem.current ? 'bg-gray-50' : 'hover:bg-gray-50',-->
                    <!--                      'block rounded-md text-sm leading-6 text-gray-700',-->
                    <!--                    ]"-->
                    <!--                  >-->
                    <!--                    {{ subItem.name }}-->
                    <!--                  </label>-->
                    <!--                </li>-->
                    <!--              </DisclosurePanel>-->
                    <!--            </Disclosure>-->
                    <!--          </li>-->

                    <li>
                        <a
                            v-if="!navigation['businessFunction'].children"
                            href="/"
                            :class="[
                navigation['businessFunction'].current ? 'bg-gray-50' : 'hover:bg-gray-50',
                'group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold text-gray-700',
              ]"
                        >
                            Business Function
                        </a>
                        <Disclosure as="div" :defaultOpen="activeBusinessFunctions.length > 0" v-else v-slot="{ open }">
                            <DisclosureButton
                                aria-label="panel"
                                @click="handleDisclosureClick"
                                :class="[
                  open ? 'bg-gray-50' : 'hover:bg-gray-50',
                  'flex items-center w-full text-left rounded-md p-2 gap-x-3 text-sm leading-6 text-gray-700 border-b',
                ]"
                            >
                                Business Function
                                <ChevronRightIcon
                                    :class="[open ? 'rotate-90 text-gray-500' : 'text-gray-400', 'ml-auto h-5 w-5 shrink-0']"
                                    aria-hidden="true"
                                />
                            </DisclosureButton>
                            <DisclosurePanel as="ul" class="mt-1 px-2 h-64 overflow-auto custom-sm-scrollbar">
                                <li v-for="(subItem, i) in navigation['businessFunction'].children" :key="subItem.name"
                                    class="flex items-center mt-3">
                                    <input
                                        :id="subItem.name + '-' + i"
                                        v-model="awardsStore.activeFilters['businessFunction']"
                                        type="checkbox"
                                        :value="subItem"
                                        class="text-primary-700 bg-gray-100 border-gray-300 accent-primary-700 hover:accent-primary-700 mr-2 cursor-pointer"
                                    />
                                    <label
                                        :for="subItem.name + '-' + i"
                                        class="cursor-pointer"
                                        :class="[
                      subItem.current ? 'bg-gray-50' : 'hover:bg-gray-50',
                      'block rounded-md text-sm leading-6 text-gray-700',
                    ]"
                                    >
                                        {{ subItem.name }}
                                    </label>
                                </li>
                            </DisclosurePanel>
                        </Disclosure>
                    </li>

                    <li>
                        <a
                            v-if="!navigation['location'].children"
                            href="/"
                            :class="[
                navigation['location'].current ? 'bg-gray-50' : 'hover:bg-gray-50',
                'group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold text-gray-700',
              ]"
                        >
                            Location
                        </a>
                        <Disclosure as="div" :defaultOpen="activeLocations.length > 0" v-else v-slot="{ open }">
                            <DisclosureButton
                                aria-label="panel"
                                @click="handleDisclosureClick"
                                :class="[
                  open ? 'bg-gray-50' : 'hover:bg-gray-50',
                  'flex items-center w-full text-left rounded-md p-2 gap-x-3 text-sm leading-6 text-gray-700 border-b',
                ]"
                            >
                                Location
                                <ChevronRightIcon
                                    :class="[open ? 'rotate-90 text-gray-500' : 'text-gray-400', 'ml-auto h-5 w-5 shrink-0']"
                                    aria-hidden="true"
                                />
                            </DisclosureButton>
                            <DisclosurePanel as="ul" class="mt-1 px-2 h-64 overflow-auto custom-sm-scrollbar">
                                <li v-for="(subItem, i) in navigation['location'].children" :key="subItem.name"
                                    class="flex items-center mt-3">
                                    <input
                                        :id="subItem.name + '-' + i"
                                        v-model="awardsStore.activeFilters['location']"
                                        type="checkbox"
                                        :value="subItem"
                                        class="text-primary-700 bg-gray-100 border-gray-300 accent-primary-700 hover:accent-primary-700 mr-2 cursor-pointer"
                                    />
                                    <label
                                        :for="subItem.name + '-' + i"
                                        class="cursor-pointer"
                                        :class="[
                      subItem.current ? 'bg-gray-50' : 'hover:bg-gray-50',
                      'block rounded-md text-sm leading-6 text-gray-700',
                    ]"
                                    >
                                        {{ subItem.name }}
                                    </label>
                                </li>
                            </DisclosurePanel>
                        </Disclosure>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
</template>
