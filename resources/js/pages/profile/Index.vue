<template>

    <div class="mt-20">
        <div class="max-w-sm mx-auto bg-white dark:bg-gray-900 rounded-lg overflow-hidden shadow-lg">
            <div class="border-b px-4 pb-6">
                <div class="text-center my-4">
                    <img class="h-32 w-32 rounded-full border-4 border-white dark:border-gray-800 mx-auto my-4"
                         :src="`/images/avatar.png`" alt="">
                    <div class="py-2">
                        <h3 class="font-bold text-2xl text-gray-800 dark:text-white mb-1"> {{`${profile.first_name} ${profile.last_name}`}}</h3>
                        <div class="flex text-gray-700 justify-center mt-2 dark:text-gray-300 items-center">
                            {{profile.gender}}
                        </div>
                        <div class="flex font-bold text-gray-700 justify-center mt-2 dark:text-gray-300 items-center">
                            {{profile.email}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import {ref} from 'vue'

    let user = localStorage.getItem('user');
    const profile = ref(user ? JSON.parse(user) : null)
</script>
