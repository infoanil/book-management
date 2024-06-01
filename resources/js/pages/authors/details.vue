<script setup>
    import { computed, onMounted, ref } from 'vue'
    import axios from 'axios'
    import { useRoute } from 'vue-router';
    import { callApi } from '../../Https/Axio';
    import Swal from "sweetalert2";
    import moment from 'moment';
    import Loader from '../../components/Loader.vue';

    const author = ref({})
    const route = useRoute();
    const loading = ref(false);

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

    onMounted(() => {
        getAuthor()
    });
    const getAuthor = async () => {
        let id = route.params.id;
        loading.value = true;
        await callApi('get',`authors/${id}`).then(response => {
            author.value = response.data;
        }).finally(() => {
            loading.value = false;
        })
    }

    const fullName = computed(() => author.value.first_name + ' ' + author.value.last_name);

    const handleDeleteBook = async (id) => {
        loading.value = true;
        await callApi('delete', `books/${id}`).then(function () {
            Toast.fire({
                icon: "success",
                title: 'Book deleted successfully!'
            });
            getAuthor();
        }).catch(function (error) {
            console.log(error.response)
            Toast.fire({
                icon: "error",
                title: error.response?.data?.message || 'something went wrong!'
            });
        });
    }
</script>
<template>
    <div
        class="w-full mx-auto my-10 p-4 sm:p-8">
        <Loader v-if="loading" :loading="loading && author.first_name" />
       <div class=" lg:flex gap-5 md:w-full">

           <div class="lg:w-1/2">
               <div class="flex items-center justify-between mb-4">
                   <h5 class="text-xl font-bold leading-none text-gray-900 dark:text-white">Author Details</h5>
               </div>

               <div v-if="!loading" class="flow-root w-full p-5 rounded overflow-hidden shadow-lg bg-white">
                   <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                       <li class="py-3 sm:py-4">
                           <div class="flex items-center">
                               <div class="flex-1 min-w-0 ms-4">
                                   <p class="text-sm font-medium text-gray-900 truncate dark:text-white">Name</p>
                               </div>
                               <div class="text-sm font-medium text-slate-600 truncate dark:text-white">
                                   {{ fullName }}
                               </div>
                           </div>
                       </li>
                       <li class="py-3 sm:py-4">
                           <div class="flex items-center">
                               <div class="flex-1 min-w-0 ms-4">
                                   <p class="text-sm font-medium text-gray-900 truncate dark:text-white">Birthday</p>
                               </div>
                               <div class="text-sm font-medium text-slate-600 truncate dark:text-white">
                                   {{ moment(author.birthday).format('Y-MM-DD') }}
                               </div>
                           </div>
                       </li>
                       <li class="py-3 sm:py-4">
                           <div class="flex items-center">
                               <div class="flex-1 min-w-0 ms-4">
                                   <p class="text-sm font-medium text-gray-900 truncate dark:text-white">Gender</p>
                               </div>
                               <div class="text-sm font-medium text-slate-600 truncate dark:text-white">
                                   {{ author.gender }}
                               </div>
                           </div>
                       </li>
                       <li class="py-3 sm:py-4">
                           <div class="flex items-center">
                               <div class="flex-1 min-w-0 ms-4">
                                   <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                       Place Of Birth
                                   </p>
                               </div>
                               <div class="text-sm font-medium text-slate-600 truncate dark:text-white">
                                   {{ author.place_of_birth }}
                               </div>
                           </div>
                       </li>
                   </ul>
               </div>
           </div>

           <div class="lg:w-1/2 lg:mt-0 mt-10">

               <div class="flex items-center justify-between mb-4">
                   <h5 class="text-xl font-bold leading-none text-gray-900 dark:text-white">Author Books</h5>
               </div>
               <div v-if="!loading && author.books && author.books.length" class="relative overflow-x-auto  max-w-2xl p-5 rounded overflow-hidden shadow-lg bg-white">

                   <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                       <thead
                           class="text-xs text-gray-700 bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                       <tr>
                           <th scope="col" class="px-6 py-3">Title</th>
                           <th scope="col" class="px-6 py-3">Release Date</th>
                           <th scope="col" class="px-6 py-3">Description</th>
                           <th scope="col" class="px-6 py-3">Pages</th>
                           <th scope="col" class="px-6 py-3 text-center">Action</th>
                       </tr>
                       </thead>
                       <tbody>
                       <tr v-for="(book , index) in author.books" :key="index" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                           <td class="px-6 py-4">{{ book.title }}</td>
                           <td class="px-6 py-4">{{ moment(book.release_date).format('Y-MM-DD') }}</td>
                           <td class="px-6 py-4">{{ book.description }}</td>
                           <td class="px-6 py-4">{{ book.number_of_pages }}</td>
                           <td class="px-6 py-4">
                               <button @click="handleDeleteBook(book.id)" class="mx-2"><span class=" hover:bg-red-400  hover:duration-300 bg-red-500 p-[12px] px-5 rounded-md text-white font-bold">Delete</span></button>
                           </td>
                       </tr>
                       </tbody>
                   </table>
               </div>
               <div v-else class="text-2xl font-bold text-center mt-20">
                   No Author Books
               </div>
           </div>
       </div>
    </div>
</template>
