<script setup>
    import {onMounted, ref} from 'vue';
    import { callApi } from '../../Https/Axio';
    import Swal from "sweetalert2";
    import moment from "moment";
    import Loader from '../../components/Loader.vue';

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

    const loading = ref(false);
    const authors = ref([]);
    const pagination = ref(null);
    const currentPage = ref(1);
    const getAuthor = async () => {
        loading.value = true;
        await callApi('get',`authors?page=${currentPage.value}`).then((response)=>{
           authors.value =  response.data?.items;
            delete response.data.items
            pagination.value = response.data;
       }).finally(() => loading.value = false);
    }

    const deleteAuthor = async (id) => {
        loading.value = true;
        await callApi('delete',`authors/${id}`,{_method:'delete'})
        .then(response => {
            Toast.fire({
                icon: "success",
                title: 'Author has been deleted..!'
            });
            getAuthor();
        })
        .catch(error => {
            console.log(error.response)
            Toast.fire({
                icon: "error",
                title: error.response?.data?.message || 'something went wrong!'
            });
        });
        loading.value = false;
    }

    const previous = () => {
        if (currentPage.value != 1) {
            currentPage.value -= 1;
            getAuthor();
        }
    }

    const next = () => {
        if (currentPage.value < pagination.value.total_pages) {
            currentPage.value += 1;
            getAuthor();
        }
    }

    onMounted(() => {
        getAuthor();
    });
</script>
<template>
    <section class="py-10">
        <Loader :loading="loading" />
        <div class="max-w-screen-xl mx-auto">
            <div class="mt-12 bg-white shadow-sm border rounded-lg overflow-x-auto">
                <table class="w-full table-auto text-sm text-left">
                    <thead class="bg-gray-100 text-gray-600 font-medium border-b">
                    <tr class="py-6 px-6 ">
                        <th scope="col" class="px-6 py-5">
                            First Name
                        </th>
                        <th scope="col" class="px-6 py-5">
                            Last Name
                        </th>
                        <th scope="col" class="px-6 py-5">
                            Gender
                        </th>
                        <th scope="col" class="px-6 py-5">
                            Birthday
                        </th>
                        <th scope="col" class="px-6 py-5">
                            Place Of Birth
                        </th>
                        <th scope="col" class="px-6 py-5 text-center">
                            Action
                        </th>
                    </tr>
                    </thead>
                    <tbody class="text-gray-600 divide-y">
                    <tr v-for="(author, index) in authors" :key="index"
                        class="hover:duration-300 authorTable">
                        <td class="px-6 py-4">
                            {{ author.first_name }}
                        </td>
                        <td class="px-6 py-4">
                            {{ author.last_name }}
                        </td>
                        <td class="px-6 py-4">
                            {{ author.gender }}
                        </td>
                        <td class="px-6 py-4">
                            {{ moment(author.birthday).format('Y-M-D') }}
                        </td>
                        <td class="px-6 py-4">
                            {{ author.place_of_birth }}
                        </td>
                        <td class="px-6 py-4 flex gap-2 justify-center">
                            <router-link :to="{ name: 'authorView', params: { id: author.id } }">
                                <span class=" hover:bg-[#808080] hover:duration-300 bg-white p-[12px] px-5 rounded-md border-2 border-gray-300 font-bold">View</span>
                            </router-link>
                            <button @click="deleteAuthor(author.id)" class="mx-2"><span class=" hover:bg-red-400  hover:duration-300 bg-red-500 p-[12px] px-5 rounded-md text-white font-bold">Delete</span></button>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div v-if="pagination && pagination.total_pages > 1" class="container mx-auto mt-12 px-4 text-gray-600 md:px-8">
                    <div class="flex items-center justify-between text-sm text-gray-600 font-medium">
                        <a href="#" @click.prevent="previous" :class="{'disabled' : currentPage == 1}" class="px-4 py-2 border rounded-lg duration-150 hover:bg-gray-50">Previous</a>
                        <div>
                            Page {{pagination.current_page}} of {{pagination.total_pages}}
                        </div>
                        <a href="#" @click.prevent="next" :class="{'disabled' : currentPage >= pagination.total_pages}" class="px-4 py-2 border rounded-lg duration-150 hover:bg-gray-50">Next</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

</template>

<style>
    .disabled {
        pointer-events: none;
        opacity: 0.5;
    }
    .authorTable:hover {
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        background-color: rgba(243, 244, 246);
    }
</style>
