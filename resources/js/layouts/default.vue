<script setup>
    import Swal from "sweetalert2";
    import {ref} from 'vue';
    import Auth from '@/auth/Auth.js';
    import {useRouter, useRoute} from 'vue-router';
    import {
        Dialog,
        DialogPanel,
        Menu,
        MenuButton,
        MenuItem,
        MenuItems,
        TransitionChild,
        TransitionRoot,
    } from '@headlessui/vue'

    const router = useRouter()
    const route = useRoute()
    let user = localStorage.getItem('user');
    const profileData = ref(user ? JSON.parse(user) : null)
    const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        }
    });

    const logout = () => {
        Auth.logout();
        router.push({ name: 'login' })
    }
    const sidebarOpen = ref(false);
    const navigation = ref([
        { name: 'Dashboard', href: '/dashboard', current: false },
        { type: 'heading', name: 'Books' },
        { name: 'Authors', href: '/authors', current: true },
        { name: 'Books', href: '/books', current: false },
        { name: 'Profile', href: '/profile', current: false },
    ]);

    const userNavigation = [
        { name: 'Log out', onAction: logout},
    ]

    const changeMenu = (i) => {
        navigation.value.forEach(item => item.current = false);
        navigation.value[i].current = true;
    }

</script>

<template>
    <div class="w-full h-[100%]">
        <TransitionRoot as="template" :show="sidebarOpen">
            <Dialog as="div" class="relative z-50 lg:hidden" @close="sidebarOpen = false">
                <TransitionChild as="template" enter="transition-opacity ease-linear duration-300"
                                 enter-from="opacity-0" enter-to="opacity-100"
                                 leave="transition-opacity ease-linear duration-300" leave-from="opacity-100"
                                 leave-to="opacity-0">
                    <div class="fixed inset-0 bg-gray-900/80"/>
                </TransitionChild>

                <div class="fixed inset-0 flex">
                    <TransitionChild as="template" enter="transition ease-in-out duration-300 transform"
                                     enter-from="-translate-x-full" enter-to="translate-x-0"
                                     leave="transition ease-in-out duration-300 transform" leave-from="translate-x-0"
                                     leave-to="-translate-x-full">
                        <DialogPanel class="relative mr-16 flex w-full max-w-xs flex-1">
                            <TransitionChild as="template" enter="ease-in-out duration-300" enter-from="opacity-0"
                                             enter-to="opacity-100" leave="ease-in-out duration-300"
                                             leave-from="opacity-100" leave-to="opacity-0">
                                <div class="absolute left-full top-0 flex w-16 justify-center pt-5">
                                    <button type="button" class="-m-2.5 p-2.5" @click="sidebarOpen = false">
                                        <span class="sr-only">Close sidebar</span>
                                        <XMarkIcon class="h-6 w-6 text-white" aria-hidden="true"/>
                                    </button>
                                </div>
                            </TransitionChild>
                            <!-- Sidebar component, swap this element with another sidebar if you like -->
                            <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-indigo-600 px-6 pb-4">
                                <div class="flex h-16 shrink-0 items-center w-fit -mx-3 mt-3">
                                    <ApplicationLogo color="#fff"/>
                                </div>
                                <nav class="flex flex-1 flex-col">
                                    <ul role="list" class="flex flex-1 flex-col gap-y-7">
                                        <li>
                                            <ul role="list" class="-mx-2 space-y-1">
                                                <li v-for="item in navigation" :key="item.name">
                                                    <div v-if="item.type"
                                                         class="text-xs font-semibold leading-6 text-indigo-200">{{
                                                        item.name }}
                                                    </div>
                                                    <RouterLink v-else :to="item.href"
                                                                :class="[item.current ? 'bg-indigo-700 text-white' : 'text-indigo-200 hover:text-white hover:bg-indigo-700', 'group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold']">
                                                        <component :is="item.icon"
                                                                   :class="[item.current ? 'text-white' : 'text-indigo-200 group-hover:text-white', 'h-6 w-6 shrink-0']"
                                                                   aria-hidden="true"/>
                                                        {{ item.name }}
                                                    </RouterLink>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </Dialog>
        </TransitionRoot>

        <!-- Static sidebar for desktop -->
        <div class="hidden left-0 lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-72 lg:flex-col">
            <!-- Sidebar component, swap this element with another sidebar if you like -->
            <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-indigo-600 px-6 pb-4">
                <div class="flex h-16 shrink-0 items-center w-fit -mx-3 mt-3">
                    <ApplicationLogo color="#fff"/>
                </div>
                <nav class="flex flex-1 flex-col">
                    <ul role="list" class="flex flex-1 flex-col gap-y-7">
                        <li>
                            <ul role="list" class="-mx-2 space-y-1">
                                <li v-for="(item, i) in navigation" :key="item.name">
                                    <div v-if="item.type"
                                         class="text-xs font-semibold leading-6 text-indigo-200 mt-3 mb-2">{{ item.name
                                        }}
                                    </div>
                                    <RouterLink v-else :to="item.href" @click="changeMenu(i)"
                                                :class="[item.current ? 'bg-indigo-700 text-white' : 'text-indigo-200 hover:text-white hover:bg-indigo-700', 'group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold']">
                                        <component :is="item.icon"
                                                   :class="[item.current ? 'text-white' : 'text-indigo-200 group-hover:text-white', 'h-6 w-6 shrink-0']"
                                                   aria-hidden="true"/>
                                        {{ item.name }}
                                    </RouterLink>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>

        <div class="lg:pl-72 fixed top-0 w-full left-0 h-[100%]">
            <div
                class="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 border-b border-gray-200 bg-white px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8">
                <button type="button" class="-m-2.5 p-2.5 text-gray-700 lg:hidden" @click="sidebarOpen = true">
                    <span class="sr-only">Open sidebar</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 20 20"><path fill="currentColor" fill-rule="evenodd" d="M2 4.75A.75.75 0 0 1 2.75 4h14.5a.75.75 0 0 1 0 1.5H2.75A.75.75 0 0 1 2 4.75ZM2 10a.75.75 0 0 1 .75-.75h14.5a.75.75 0 0 1 0 1.5H2.75A.75.75 0 0 1 2 10Zm0 5.25a.75.75 0 0 1 .75-.75h14.5a.75.75 0 0 1 0 1.5H2.75a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd"/></svg>
                </button>

                <!-- Separator -->
                <div class="h-6 w-px bg-gray-900/10 lg:hidden" aria-hidden="true"/>

                <div class="flex justify-between items-center flex-1 gap-x-4 self-stretch lg:gap-x-6">
                    <p class="font-bold">{{route.meta.title}}</p>
                    <div class="flex items-center gap-x-4 lg:gap-x-6">

                        <!-- Separator -->
                        <div class="hidden lg:block lg:h-6 lg:w-px lg:bg-gray-900/10" aria-hidden="true"/>

                        <!-- Profile dropdown -->
                        <Menu as="div" class="relative">
                            <MenuButton class="-m-1.5 flex items-center p-1.5">
                                <span class="sr-only">Open user menu</span>
                                <img class="h-10 w-10 rounded-full bg-gray-50"
                                     :src="'/images/avatar.png'"
                                     alt=""/>
                                <span class="ml-4   text-sm leading-6 text-gray-900"
                                      aria-hidden="true">{{profileData?.first_name || 'Admin'}} </span>
                                <span class="hidden lg:flex lg:items-center">
                            <ChevronDownIcon class="ml-2 h-5 w-5 text-gray-400" aria-hidden="true"/>
                </span>
                            </MenuButton>
                            <transition enter-active-class="transition ease-out duration-100"
                                        enter-from-class="transform opacity-0 scale-95"
                                        enter-to-class="transform opacity-100 scale-100"
                                        leave-active-class="transition ease-in duration-75"
                                        leave-from-class="transform opacity-100 scale-100"
                                        leave-to-class="transform opacity-0 scale-95">
                                <MenuItems
                                    class="absolute right-0 z-10 mt-2.5 w-[200px] origin-top-right rounded-md bg-white py-2 shadow-lg ring-1 ring-gray-900/5 focus:outline-none">
                                    <div class="border-b pb-4 mt-1">
                                        <div class="flex px-5 items-center">
                                            <img class="h-10 w-10 rounded-full bg-gray-50"
                                                 :src="'/images/avatar.png'"
                                                 alt="user"/>
                                            <span class="ml-4   text-sm leading-6 text-gray-900"
                                                  aria-hidden="true">{{profileData?.first_name || 'Admin'}} </span>
                                        </div>
                                    </div>
                                    <MenuItem v-for="item in userNavigation" :key="item.name" v-slot="{ active }">
                                        <div @click="item.onAction"
                                             :class="[active ? 'bg-gray-50' : '', 'block px-3 py-2 text-sm leading-6 text-gray-900 cursor-pointer my-2', item.border ? 'border-t mt-3 flex pb-0 pt-3' : '', 'flex gap-2']">
                                            <component :is="item.icon"
                                                       class="h-6 w-6 shrink-0"
                                                       aria-hidden="true"/>
                                            {{ item.name }}
                                        </div>
                                    </MenuItem>
                                </MenuItems>
                            </transition>
                        </Menu>
                    </div>
                </div>
            </div>

            <main class="bg-gray-200 h-[100%]">
                <div class="px-4 sm:px-6 lg:px-8">
                    <slot></slot>
                </div>
            </main>
        </div>

    </div>
</template>

<style >
.header-section .router-link-exact-active {
    background-color: rgb(249 250 251 / var(--tw-bg-opacity));

}
</style>
