<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";

import { Disclosure, DisclosureButton, DisclosurePanel, Menu, MenuButton, MenuItem, MenuItems } from "@headlessui/vue";
import { ArrowUpIcon, BellIcon, XMarkIcon, ChevronDownIcon, Squares2X2Icon, ListBulletIcon, MapPinIcon, CalendarDaysIcon, PlusIcon } from "@heroicons/vue/24/solid";



import { ChevronRightIcon } from '@heroicons/vue/20/solid'


const user = {
  name: "Tom Cook",
  email: "tom@example.com",
  imageUrl: "https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80",
};

const userNavigation = [
  { name: "Your Profile", href: "#" },
  { name: "Settings", href: "#" },
  { name: "Sign out", href: "#" },
];

const gridView = true;
const listView = false;

const showGrid = () => {
  gridView.value = true;
  listView.value = false;
};

const showList = () => {
  gridView.value = false;
  listView.value = true;
};



const navigation = [
  {
    name: 'Industry',
    current: false,
    children: [
      { name: 'Business', href: '#' },
      { name: 'Business Leader', href: '#' },
      { name: 'Corporate Social Responsibility', href: '#' },
      { name: 'Construction Architecture', href: '#' },
      { name: 'Customer Contact', href: '#' },
      { name: 'Design Innovation', href: '#' },
      { name: 'Education', href: '#' },
      { name: 'Energy Utilities', href: '#' },
    ],
  },
  {
    name: 'Category',
    current: false,
    children: [
      { name: 'Venture Backed Companies', href: '#' },
      { name: 'Water Systems', href: '#' },
      { name: 'Wealth Advisor', href: '#' },
      { name: 'Wealth Management', href: '#' },
      { name: 'Web Design & Development', href: '#' },
      { name: 'Web Marketing', href: '#' },
      { name: 'Women', href: '#' },
      { name: 'Workplace', href: '#' },
      { name: 'Young Leaders', href: '#' },
    ],
  },
  {
    name: 'Location',
    current: false,
    children: [
      { name: 'Business', href: '#' },
      { name: 'Business Leader', href: '#' },
      { name: 'Corporate Social Responsibility', href: '#' },
      { name: 'Construction Architecture', href: '#' },
      { name: 'Customer Contact', href: '#' },
      { name: 'Design Innovation', href: '#' },
      { name: 'Education', href: '#' },
      { name: 'Energy Utilities', href: '#' },
    ],
  },
]



</script>

<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <!-- <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Awards</h2>
    </template> -->

    <div class="h-92vh">
      <div class="bg-white border-b border-gray-200">
        <Disclosure as="nav" class="" v-slot="{ open }">
          <DisclosurePanel class="md:hidden">
            <div class="space-y-1 px-2 pb-3 pt-2 sm:px-3">
              <DisclosureButton v-for="item in navigation" :key="item.name" as="a" :href="item.href" :class="[
                item.current ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white',
                'block rounded-md px-3 py-2 text-base font-medium',
              ]" :aria-current="item.current ? 'page' : undefined">{{ item.name }}</DisclosureButton>
            </div>
            <div class="border-t border-gray-700 pb-3 pt-4">
              <div class="flex items-center px-5">
                <div class="flex-shrink-0">
                  <img class="h-10 w-10 rounded-full" :src="user.imageUrl" alt="" />
                </div>
                <div class="ml-3">
                  <div class="text-base font-medium leading-none text-white">{{ user.name }}</div>
                  <div class="text-sm font-medium leading-none text-gray-400">{{ user.email }}</div>
                </div>
                <button type="button"
                  class="relative ml-auto flex-shrink-0 rounded-full bg-gray-800 p-1 text-gray-400 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800">
                  <span class="absolute -inset-1.5" />
                  <span class="sr-only">View notifications</span>
                  <BellIcon class="h-6 w-6" aria-hidden="true" />
                </button>
              </div>
              <div class="mt-3 space-y-1 px-2">
                <DisclosureButton v-for="item in userNavigation" :key="item.name" as="a" :href="item.href"
                  class="block rounded-md px-3 py-2 text-base font-medium text-gray-400 hover:bg-gray-700 hover:text-white">
                  {{ item.name }}</DisclosureButton>
              </div>
            </div>
          </DisclosurePanel>
        </Disclosure>
      </div>
      <main class="bg-gray-50 flex h-full">
        <div class="w-80 px-6 py-6 bg-white border-r border-gray-200 h-full flex flex-col justify-between">
          <div>
            <h3 class="mb-6 text-gray-900 text-lg font-bold">Browse awards by</h3>
            <nav class="flex flex-1 flex-col">
              <ul role="list" class="flex flex-1 flex-col gap-y-7">
                <li>
                  <ul role="list" class="-mx-2 space-y-1">
                    <li v-for="item in navigation" :key="item.name">
                      <a v-if="!item.children" :href="item.href"
                        :class="[item.current ? 'bg-gray-50' : 'hover:bg-gray-50', 'group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold text-gray-700']">
                        <component :is="item.icon" class="h-6 w-6 shrink-0 text-gray-400" aria-hidden="true" />
                        {{ item.name }}
                      </a>
                      <Disclosure as="div" v-else v-slot="{ open }">
                        <DisclosureButton
                          :class="[item.current ? 'bg-gray-50' : 'hover:bg-gray-50', 'flex items-center w-full text-left rounded-md p-2 gap-x-3 text-sm leading-6 text-gray-700 border-b']">
                          <component :is="item.icon" class="h-6 w-6 shrink-0 text-gray-400" aria-hidden="true" />
                          {{ item.name }}
                          <ChevronRightIcon
                            :class="[open ? 'rotate-90 text-gray-500' : 'text-gray-400', 'ml-auto h-5 w-5 shrink-0']"
                            aria-hidden="true" />
                        </DisclosureButton>
                        <DisclosurePanel as="ul" class="mt-1 px-2 h-64 overflow-auto">
                    <li v-for="subItem in item.children" :key="subItem.name" class="flex items-center mt-3">
                      <!-- 44px -->
                      <input id="default-checkbox" type="checkbox" value=""
                        class="text-primary-700 bg-gray-100 border-gray-300 accent-primary-700 hover:accent-primary-700 mr-2 cursor-pointer" />
                      <DisclosureButton as="a" :href="subItem.href"
                        :class="[subItem.current ? 'bg-gray-50' : 'hover:bg-gray-50', 'block rounded-md text-sm leading-6 text-gray-700']">
                        {{ subItem.name }}</DisclosureButton>
                    </li>
                    </DisclosurePanel>
                    </Disclosure>
                </li>
              </ul>
              </li>
              </ul>
            </nav>
            <a href="" class="text-primary-700 text-sm font-medium underline block text-right mt-6">Clear All</a>
          </div>
          <div class="w-full bg-gray-100 p-3 gap-3">
            <p class="text-gray-600 text-xs font-light mb-3">
              Need a custom list? We’ll have your list ready in less than 48 hours!
            </p>
            <button type="button"
              class="btn rounded-sm border border-primary-700 text-primary-700 py-2 px-3 text-xs font-semibold">
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
              <form>
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                      xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                      <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                    </svg>
                  </div>
                  <input type="search" id="default-search"
                    class="block leading-tight text-sm font-normal text-gray-500 py-4 pr-4 pl-9 border border-gray-200 rounded-sm w-80"
                    placeholder="Search Mockups, Logos..." required />
                </div>
              </form>
              <div class="flex items-center">
                <button type="button" class="btn text-sm font-semibold text-primary-700 p-2 border border-primary-700"
                  @click="showGrid">
                  <Squares2X2Icon class="h-5 w-5" />
                </button>
                <button type="button" class="btn text-sm font-semibold text-primary-700 p-2 ms-2" @click="showList">
                  <ListBulletIcon class="h-5 w-5" />
                </button>
                <button type="button"
                  class="btn text-sm font-semibold text-primary-700 py-2 px-3 border border-primary-700 ms-6">
                  Sort by
                </button>
              </div>
            </div>
            <div class="flex items-center flex-wrap mt-9 gap-3">
              <h5 class="text-gray-900 text-sm font-bold">Active filters</h5>
              <div class="rounded border border-primary-200 bg-primary-50 px-2 py-1 flex items-center">
                <p class="mr-2 text-xs font-normal text-gray-900">Business Leaders</p>
                <XMarkIcon class="w-4 h-4" />
              </div>
              <div class="rounded border border-primary-200 bg-primary-50 px-2 py-1 flex items-center">
                <p class="mr-2 text-xs font-normal text-gray-900">Construction Architecture</p>
                <XMarkIcon class="w-4 h-4" />
              </div>
              <div class="rounded border border-primary-200 bg-primary-50 px-2 py-1 flex items-center">
                <p class="mr-2 text-xs font-normal text-gray-900">Education</p>
                <XMarkIcon class="w-4 h-4" />
              </div>
              <div class="rounded border border-primary-200 bg-primary-50 px-2 py-1 flex items-center">
                <p class="mr-2 text-xs font-normal text-gray-900">Marketing Leaders</p>
                <XMarkIcon class="w-4 h-4" />
              </div>
              <div class="rounded border border-primary-200 bg-primary-50 px-2 py-1 flex items-center">
                <p class="mr-2 text-xs font-normal text-gray-900">PR & Marketing</p>
                <XMarkIcon class="w-4 h-4" />
              </div>
              <div class="rounded border border-primary-200 bg-primary-50 px-2 py-1 flex items-center">
                <p class="mr-2 text-xs font-normal text-gray-900">Women</p>
                <XMarkIcon class="w-4 h-4" />
              </div>
              <div class="rounded border border-primary-200 bg-primary-50 px-2 py-1 flex items-center">
                <p class="mr-2 text-xs font-normal text-gray-900">International</p>
                <XMarkIcon class="w-4 h-4" />
              </div>
              <div class="rounded border border-primary-200 bg-primary-50 px-2 py-1 flex items-center">
                <p class="mr-2 text-xs font-normal text-gray-900">United States</p>
                <XMarkIcon class="w-4 h-4" />
              </div>
              <div class="rounded border border-primary-200 bg-primary-50 px-2 py-1 flex items-center">
                <p class="mr-2 text-xs font-normal text-gray-900">Canada</p>
                <XMarkIcon class="w-4 h-4" />
              </div>
              <a href="/" class="text-xs font-normal text-gray-900 underline capitalize">clear all</a>
            </div>
            <div class="mt-9">
              <div class="flex items-center justify-between mb-3">
                <h4 class="text-sm font-bold"><span class="font-normal">23</span> Awards</h4>
                <Menu as="div" class="relative inline-block text-left">
                  <div>
                    <MenuButton
                      class="inline-flex w-full justify-center items-center gap-x-1.5 rounded-sm bg-primary-700 px-5 py-3 text-white">
                      + Add to
                      <ChevronDownIcon class="h-6 w-6" aria-hidden="true" />
                    </MenuButton>
                  </div>

                  <transition enter-active-class="transition ease-out duration-100"
                    enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100"
                    leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100"
                    leave-to-class="transform opacity-0 scale-95">
                    <MenuItems
                      class="absolute right-0 z-10 mt-2 w-56 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                      <div class="py-1">
                        <MenuItem v-slot="{ active }">
                        <a href="#"
                          :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block px-4 py-2 text-sm']">
                          List 1
                        </a>
                        </MenuItem>
                        <MenuItem v-slot="{ active }">
                        <a href="#"
                          :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block px-4 py-2 text-sm']">
                          List 2
                        </a>
                        </MenuItem>
                        <MenuItem v-slot="{ active }">
                        <a href="#"
                          :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block px-4 py-2 text-sm']">
                          List 3
                        </a>
                        </MenuItem>
                        <MenuItem v-slot="{ active }">
                        <a href="#"
                          :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'px-4 py-2 text-sm flex items-center']">
                          <PlusIcon class="h-6 w-6 mr-2" aria-hidden="true" />
                          Create new list
                        </a>
                        </MenuItem>
                      </div>
                    </MenuItems>
                  </transition>
                </Menu>
              </div>
              <div class="grid grid-cols-3 gap-5" v-if="gridView">
                <div
                  class="p-6 bg-white border border-gray-200 rounded-sm h-60 flex flex-col justify-between hover:shadow-xl hover:border-2 hover:border-primary-100">
                  <div>
                    <div class="flex justify-between">
                      <div class="w-3/4">
                        <h6 class="text-primary-600 text-xs font-bold">Business Leaders</h6>
                        <h2 class="text-gray-900 text-sm font-bold">Women in Fire Safety Awards</h2>
                        <h5 class="text-xs font-normal text-gray-900">Women in Fire Safety</h5>
                      </div>
                      <input id="default-checkbox" type="checkbox" value=""
                        class="w-6 h-6 text-primary-700 bg-gray-100 border-gray-300 accent-primary-700 hover:accent-primary-700" />
                    </div>
                    <p class="text-xs font-light text-gray-800 mt-3">
                      In honor of the outstanding achievements from the exceptional women within the fire safety
                      industry.
                    </p>
                  </div>
                  <div class="flex items-center">
                    <p class="flex text-xs font-light text-gray-900">
                      <MapPinIcon class="w-4 h-4 mr-2" />
                      International
                    </p>
                    <p class="flex text-xs font-light text-gray-900 ml-6">
                      <CalendarDaysIcon class="w-4 h-4 mr-2" />
                      Deadline 6/23/23
                    </p>
                  </div>
                </div>
                <div
                  class="p-6 bg-white border border-gray-200 rounded-sm h-60 flex flex-col justify-between hover:shadow-xl hover:border-2 hover:border-primary-100">
                  <div>
                    <div class="flex justify-between">
                      <div class="w-3/4">
                        <h6 class="text-primary-600 text-xs font-bold">Construction Architecture</h6>
                        <h2 class="text-gray-900 text-sm font-bold">
                          National Association of Women in Construction NAWIC Awards
                        </h2>
                        <h5 class="text-xs font-normal text-gray-900">
                          NAWIC-National Association of Women in Construction
                        </h5>
                      </div>
                      <input id="default-checkbox" type="checkbox" value=""
                        class="w-6 h-6 text-primary-700 bg-gray-100 border-gray-300 accent-primary-700 hover:accent-primary-700" />
                    </div>
                    <p class="text-xs font-light text-gray-800 mt-3">
                      In honor of the outstanding achievements from the exceptional women within the fire safety
                      industry.
                    </p>
                  </div>
                  <div class="flex items-center">
                    <p class="flex text-xs font-light text-gray-900">
                      <MapPinIcon class="w-4 h-4 mr-2" />
                      International
                    </p>
                    <p class="flex text-xs font-light text-gray-900 ml-6">
                      <CalendarDaysIcon class="w-4 h-4 mr-2" />
                      Deadline 6/23/23
                    </p>
                  </div>
                </div>
                <div
                  class="p-6 bg-white border border-gray-200 rounded-sm h-60 flex flex-col justify-between hover:shadow-xl hover:border-2 hover:border-primary-100">
                  <div>
                    <div class="flex justify-between">
                      <div class="w-3/4">
                        <h6 class="text-primary-600 text-xs font-bold">Construction Architecture</h6>
                        <h2 class="text-gray-900 text-sm font-bold">
                          National Association of Women in Construction NAWIC Awards
                        </h2>
                        <h5 class="text-xs font-normal text-gray-900">
                          NAWIC-National Association of Women in Construction
                        </h5>
                      </div>
                      <input id="default-checkbox" type="checkbox" value=""
                        class="w-6 h-6 text-primary-700 bg-gray-100 border-gray-300 accent-primary-700 hover:accent-primary-700" />
                    </div>
                    <p class="text-xs font-light text-gray-800 mt-3">
                      The National Association of Women in Construction (NAWIC) Awards showcase the achievements and
                      industry advancements of...
                    </p>
                  </div>
                  <div class="flex items-center">
                    <p class="flex text-xs font-light text-gray-900">
                      <MapPinIcon class="w-4 h-4 mr-2" />
                      International
                    </p>
                    <p class="flex text-xs font-light text-gray-900 ml-6">
                      <CalendarDaysIcon class="w-4 h-4 mr-2" />
                      Deadline 6/23/23
                    </p>
                  </div>
                </div>
                <div
                  class="p-6 bg-white border border-gray-200 rounded-sm h-60 flex flex-col justify-between hover:shadow-xl hover:border-2 hover:border-primary-100">
                  <div>
                    <div class="flex justify-between">
                      <div class="w-3/4">
                        <h6 class="text-primary-600 text-xs font-bold">Business Leaders</h6>
                        <h2 class="text-gray-900 text-sm font-bold">Women in Fire Safety Awards</h2>
                        <h5 class="text-xs font-normal text-gray-900">Women in Fire Safety</h5>
                      </div>
                      <input id="default-checkbox" type="checkbox" value=""
                        class="w-6 h-6 text-primary-700 bg-gray-100 border-gray-300 accent-primary-700 hover:accent-primary-700" />
                    </div>
                    <p class="text-xs font-light text-gray-800 mt-3">
                      In honor of the outstanding achievements from the exceptional women within the fire safety
                      industry.
                    </p>
                  </div>
                  <div class="flex items-center">
                    <p class="flex text-xs font-light text-gray-900">
                      <MapPinIcon class="w-4 h-4 mr-2" />
                      International
                    </p>
                    <p class="flex text-xs font-light text-gray-900 ml-6">
                      <CalendarDaysIcon class="w-4 h-4 mr-2" />
                      Deadline 6/23/23
                    </p>
                  </div>
                </div>
                <div
                  class="p-6 bg-white border border-gray-200 rounded-sm h-60 flex flex-col justify-between hover:shadow-xl hover:border-2 hover:border-primary-100">
                  <div>
                    <div class="flex justify-between">
                      <div class="w-3/4">
                        <h6 class="text-primary-600 text-xs font-bold">Construction Architecture</h6>
                        <h2 class="text-gray-900 text-sm font-bold">
                          National Association of Women in Construction NAWIC Awards
                        </h2>
                        <h5 class="text-xs font-normal text-gray-900">
                          NAWIC-National Association of Women in Construction
                        </h5>
                      </div>
                      <input id="default-checkbox" type="checkbox" value=""
                        class="w-6 h-6 text-primary-700 bg-gray-100 border-gray-300 accent-primary-700 hover:accent-primary-700" />
                    </div>
                    <p class="text-xs font-light text-gray-800 mt-3">
                      In honor of the outstanding achievements from the exceptional women within the fire safety
                      industry.
                    </p>
                  </div>
                  <div class="flex items-center">
                    <p class="flex text-xs font-light text-gray-900">
                      <MapPinIcon class="w-4 h-4 mr-2" />
                      International
                    </p>
                    <p class="flex text-xs font-light text-gray-900 ml-6">
                      <CalendarDaysIcon class="w-4 h-4 mr-2" />
                      Deadline 6/23/23
                    </p>
                  </div>
                </div>
                <div
                  class="p-6 bg-white border border-gray-200 rounded-sm h-60 flex flex-col justify-between hover:shadow-xl hover:border-2 hover:border-primary-100">
                  <div>
                    <div class="flex justify-between">
                      <div class="w-3/4">
                        <h6 class="text-primary-600 text-xs font-bold">Construction Architecture</h6>
                        <h2 class="text-gray-900 text-sm font-bold">
                          National Association of Women in Construction NAWIC Awards
                        </h2>
                        <h5 class="text-xs font-normal text-gray-900">
                          NAWIC-National Association of Women in Construction
                        </h5>
                      </div>
                      <input id="default-checkbox" type="checkbox" value=""
                        class="w-6 h-6 text-primary-700 bg-gray-100 border-gray-300 accent-primary-700 hover:accent-primary-700" />
                    </div>
                    <p class="text-xs font-light text-gray-800 mt-3">
                      The National Association of Women in Construction (NAWIC) Awards showcase the achievements and
                      industry advancements of...
                    </p>
                  </div>
                  <div class="flex items-center">
                    <p class="flex text-xs font-light text-gray-900">
                      <MapPinIcon class="w-4 h-4 mr-2" />
                      International
                    </p>
                    <p class="flex text-xs font-light text-gray-900 ml-6">
                      <CalendarDaysIcon class="w-4 h-4 mr-2" />
                      Deadline 6/23/23
                    </p>
                  </div>
                </div>
                <div
                  class="p-6 bg-white border border-gray-200 rounded-sm h-60 flex flex-col justify-between hover:shadow-xl hover:border-2 hover:border-primary-100">
                  <div>
                    <div class="flex justify-between">
                      <div class="w-3/4">
                        <h6 class="text-primary-600 text-xs font-bold">Business Leaders</h6>
                        <h2 class="text-gray-900 text-sm font-bold">Women in Fire Safety Awards</h2>
                        <h5 class="text-xs font-normal text-gray-900">Women in Fire Safety</h5>
                      </div>
                      <input id="default-checkbox" type="checkbox" value=""
                        class="w-6 h-6 text-primary-700 bg-gray-100 border-gray-300 accent-primary-700 hover:accent-primary-700" />
                    </div>
                    <p class="text-xs font-light text-gray-800 mt-3">
                      In honor of the outstanding achievements from the exceptional women within the fire safety
                      industry.
                    </p>
                  </div>
                  <div class="flex items-center">
                    <p class="flex text-xs font-light text-gray-900">
                      <MapPinIcon class="w-4 h-4 mr-2" />
                      International
                    </p>
                    <p class="flex text-xs font-light text-gray-900 ml-6">
                      <CalendarDaysIcon class="w-4 h-4 mr-2" />
                      Deadline 6/23/23
                    </p>
                  </div>
                </div>
                <div
                  class="p-6 bg-white border border-gray-200 rounded-sm h-60 flex flex-col justify-between hover:shadow-xl hover:border-2 hover:border-primary-100">
                  <div>
                    <div class="flex justify-between">
                      <div class="w-3/4">
                        <h6 class="text-primary-600 text-xs font-bold">Construction Architecture</h6>
                        <h2 class="text-gray-900 text-sm font-bold">
                          National Association of Women in Construction NAWIC Awards
                        </h2>
                        <h5 class="text-xs font-normal text-gray-900">
                          NAWIC-National Association of Women in Construction
                        </h5>
                      </div>
                      <input id="default-checkbox" type="checkbox" value=""
                        class="w-6 h-6 text-primary-700 bg-gray-100 border-gray-300 accent-primary-700 hover:accent-primary-700" />
                    </div>
                    <p class="text-xs font-light text-gray-800 mt-3">
                      In honor of the outstanding achievements from the exceptional women within the fire safety
                      industry.
                    </p>
                  </div>
                  <div class="flex items-center">
                    <p class="flex text-xs font-light text-gray-900">
                      <MapPinIcon class="w-4 h-4 mr-2" />
                      International
                    </p>
                    <p class="flex text-xs font-light text-gray-900 ml-6">
                      <CalendarDaysIcon class="w-4 h-4 mr-2" />
                      Deadline 6/23/23
                    </p>
                  </div>
                </div>
                <div
                  class="p-6 bg-white border border-gray-200 rounded-sm h-60 flex flex-col justify-between hover:shadow-xl hover:border-2 hover:border-primary-100">
                  <div>
                    <div class="flex justify-between">
                      <div class="w-3/4">
                        <h6 class="text-primary-600 text-xs font-bold">Construction Architecture</h6>
                        <h2 class="text-gray-900 text-sm font-bold">
                          National Association of Women in Construction NAWIC Awards
                        </h2>
                        <h5 class="text-xs font-normal text-gray-900">
                          NAWIC-National Association of Women in Construction
                        </h5>
                      </div>
                      <input id="default-checkbox" type="checkbox" value=""
                        class="w-6 h-6 text-primary-700 bg-gray-100 border-gray-300 accent-primary-700 hover:accent-primary-700" />
                    </div>
                    <p class="text-xs font-light text-gray-800 mt-3">
                      The National Association of Women in Construction (NAWIC) Awards showcase the achievements and
                      industry advancements of...
                    </p>
                  </div>
                  <div class="flex items-center">
                    <p class="flex text-xs font-light text-gray-900">
                      <MapPinIcon class="w-4 h-4 mr-2" />
                      International
                    </p>
                    <p class="flex text-xs font-light text-gray-900 ml-6">
                      <CalendarDaysIcon class="w-4 h-4 mr-2" />
                      Deadline 6/23/23
                    </p>
                  </div>
                </div>
              </div>
              <div class="list-view" v-if="listView">
                <div
                  class="border border-gray-200 bg-white rounded-sm py-3 px-6 min-h-180 flex items-center mb-3 hover:shadow-xl hover:border-2 hover:border-primary-100">
                  <div class="flex gap-9">
                    <div class="flex gap-6 items-center">
                      <input id="default-checkbox" type="checkbox" value=""
                        class="w-6 h-6 text-primary-700 bg-gray-100 border-gray-300 accent-primary-700 hover:accent-primary-700" />
                      <div class="w-72">
                        <h6 class="text-primary-600 text-xs font-bold">Business Leaders</h6>
                        <h2 class="text-gray-900 text-sm font-bold">Women in Fire Safety Awards</h2>
                        <h5 class="text-xs font-normal text-gray-900">Women in Fire Safety</h5>
                      </div>
                    </div>
                    <div class="w-96">
                      <p class="text-xs font-light text-gray-800 mt-3">
                        In honor of the outstanding achievements from the exceptional women within the fire safety
                        industry.
                      </p>
                    </div>
                    <div>
                      <p class="flex text-xs font-light text-gray-900 mb-5">
                        <MapPinIcon class="w-4 h-4 mr-1" />
                        International
                      </p>
                      <p class="flex text-xs font-light text-gray-900">
                        <CalendarDaysIcon class="w-4 h-4 mr-1" />
                        Deadline 6/23/23
                      </p>
                    </div>
                  </div>
                </div>
                <div
                  class="border border-gray-200 bg-white rounded-sm py-3 px-6 min-h-180 flex items-center mb-3 hover:shadow-xl hover:border-2 hover:border-primary-100">
                  <div class="flex gap-9">
                    <div class="flex gap-6 items-center">
                      <input id="default-checkbox" type="checkbox" value=""
                        class="w-6 h-6 text-primary-700 bg-gray-100 border-gray-300 accent-primary-700 hover:accent-primary-700" />
                      <div class="w-72">
                        <h6 class="text-primary-600 text-xs font-bold">Construction Architecture</h6>
                        <h2 class="text-gray-900 text-sm font-bold">National Association of Women in Construction NAWIC
                          Awards</h2>
                        <h5 class="text-xs font-normal text-gray-900">NAWIC-National Association of Women in Construction
                        </h5>
                      </div>
                    </div>
                    <div class="w-96">
                      <p class="text-xs font-light text-gray-800 mt-3">
                        The National Association of Women in Construction (NAWIC) Awards showcase the achievements and
                        industry advancements of women, men and organizations in the construction industry. NAWIC is a
                        not-for-profit organization that seeks to promote and improve the construction...
                      </p>
                    </div>
                    <div>
                      <p class="flex text-xs font-light text-gray-900 mb-5">
                        <MapPinIcon class="w-4 h-4 mr-1" />
                        International
                      </p>
                      <p class="flex text-xs font-light text-gray-900">
                        <CalendarDaysIcon class="w-4 h-4 mr-1" />
                        Deadline 6/23/23
                      </p>
                    </div>
                  </div>
                </div>
                <div
                  class="border border-gray-200 bg-white rounded-sm py-3 px-6 min-h-180 flex items-center mb-3 hover:shadow-xl hover:border-2 hover:border-primary-100">
                  <div class="flex gap-9">
                    <div class="flex gap-6 items-center">
                      <input id="default-checkbox" type="checkbox" value=""
                        class="w-6 h-6 text-primary-700 bg-gray-100 border-gray-300 accent-primary-700 hover:accent-primary-700" />
                      <div class="w-72">
                        <h6 class="text-primary-600 text-xs font-bold">Construction Architecture</h6>
                        <h2 class="text-gray-900 text-sm font-bold">National Association of Women in Construction NAWIC
                          Awards</h2>
                        <h5 class="text-xs font-normal text-gray-900">NAWIC-National Association of Women in Construction
                        </h5>
                      </div>
                    </div>
                    <div class="w-96">
                      <p class="text-xs font-light text-gray-800 mt-3">
                        The National Association of Women in Construction (NAWIC) Awards showcase the achievements and
                        industry advancements of women, men and organizations in the construction industry. NAWIC is a
                        not-for-profit organization that seeks to promote and improve the construction...
                      </p>
                    </div>
                    <div>
                      <p class="flex text-xs font-light text-gray-900 mb-5">
                        <MapPinIcon class="w-4 h-4 mr-1" />
                        International
                      </p>
                      <p class="flex text-xs font-light text-gray-900">
                        <CalendarDaysIcon class="w-4 h-4 mr-1" />
                        Deadline 6/23/23
                      </p>
                    </div>
                  </div>
                </div>
                <div
                  class="border border-gray-200 bg-white rounded-sm py-3 px-6 min-h-180 flex items-center mb-3 hover:shadow-xl hover:border-2 hover:border-primary-100">
                  <div class="flex gap-9">
                    <div class="flex gap-6 items-center">
                      <input id="default-checkbox" type="checkbox" value=""
                        class="w-6 h-6 text-primary-700 bg-gray-100 border-gray-300 accent-primary-700 hover:accent-primary-700" />
                      <div class="w-72">
                        <h6 class="text-primary-600 text-xs font-bold">Business Leaders</h6>
                        <h2 class="text-gray-900 text-sm font-bold">Women in Fire Safety Awards</h2>
                        <h5 class="text-xs font-normal text-gray-900">Women in Fire Safety</h5>
                      </div>
                    </div>
                    <div class="w-96">
                      <p class="text-xs font-light text-gray-800 mt-3">
                        In honor of the outstanding achievements from the exceptional women within the fire safety
                        industry.
                      </p>
                    </div>
                    <div>
                      <p class="flex text-xs font-light text-gray-900 mb-5">
                        <MapPinIcon class="w-4 h-4 mr-1" />
                        International
                      </p>
                      <p class="flex text-xs font-light text-gray-900">
                        <CalendarDaysIcon class="w-4 h-4 mr-1" />
                        Deadline 6/23/23
                      </p>
                    </div>
                  </div>
                </div>
                <div
                  class="border border-gray-200 bg-white rounded-sm py-3 px-6 min-h-180 flex items-center mb-3 hover:shadow-xl hover:border-2 hover:border-primary-100">
                  <div class="flex gap-9">
                    <div class="flex gap-6 items-center">
                      <input id="default-checkbox" type="checkbox" value=""
                        class="w-6 h-6 text-primary-700 bg-gray-100 border-gray-300 accent-primary-700 hover:accent-primary-700" />
                      <div class="w-72">
                        <h6 class="text-primary-600 text-xs font-bold">Construction Architecture</h6>
                        <h2 class="text-gray-900 text-sm font-bold">National Association of Women in Construction NAWIC
                          Awards</h2>
                        <h5 class="text-xs font-normal text-gray-900">NAWIC-National Association of Women in Construction
                        </h5>
                      </div>
                    </div>
                    <div class="w-96">
                      <p class="text-xs font-light text-gray-800 mt-3">
                        The National Association of Women in Construction (NAWIC) Awards showcase the achievements and
                        industry advancements of women, men and organizations in the construction industry. NAWIC is a
                        not-for-profit organization that seeks to promote and improve the construction...
                      </p>
                    </div>
                    <div>
                      <p class="flex text-xs font-light text-gray-900 mb-5">
                        <MapPinIcon class="w-4 h-4 mr-1" />
                        International
                      </p>
                      <p class="flex text-xs font-light text-gray-900">
                        <CalendarDaysIcon class="w-4 h-4 mr-1" />
                        Deadline 6/23/23
                      </p>
                    </div>
                  </div>
                </div>
                <div
                  class="border border-gray-200 bg-white rounded-sm py-3 px-6 min-h-180 flex items-center mb-3 hover:shadow-xl hover:border-2 hover:border-primary-100">
                  <div class="flex gap-9">
                    <div class="flex gap-6 items-center">
                      <input id="default-checkbox" type="checkbox" value=""
                        class="w-6 h-6 text-primary-700 bg-gray-100 border-gray-300 accent-primary-700 hover:accent-primary-700" />
                      <div class="w-72">
                        <h6 class="text-primary-600 text-xs font-bold">Construction Architecture</h6>
                        <h2 class="text-gray-900 text-sm font-bold">National Association of Women in Construction NAWIC
                          Awards</h2>
                        <h5 class="text-xs font-normal text-gray-900">NAWIC-National Association of Women in Construction
                        </h5>
                      </div>
                    </div>
                    <div class="w-96">
                      <p class="text-xs font-light text-gray-800 mt-3">
                        The National Association of Women in Construction (NAWIC) Awards showcase the achievements and
                        industry advancements of women, men and organizations in the construction industry. NAWIC is a
                        not-for-profit organization that seeks to promote and improve the construction...
                      </p>
                    </div>
                    <div>
                      <p class="flex text-xs font-light text-gray-900 mb-5">
                        <MapPinIcon class="w-4 h-4 mr-1" />
                        International
                      </p>
                      <p class="flex text-xs font-light text-gray-900">
                        <CalendarDaysIcon class="w-4 h-4 mr-1" />
                        Deadline 6/23/23
                      </p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <a href="" class="btn p-4 fixed right-8 bottom-8 border border-primary-700 bg-white shadow-lg rounded">
            <ArrowUpIcon class="h-5 w-5" aria-hidden="true" />
          </a>
        </div>
      </main>
    </div>
  </AuthenticatedLayout>
</template>

<style>
*::-webkit-scrollbar {
  width: 12px;
}

*::-webkit-scrollbar-track {
  background: #fff;
}

*::-webkit-scrollbar-thumb {
  background-color: #888;
  border-radius: 20px;
  border: 3px solid #fff;
}
</style>
