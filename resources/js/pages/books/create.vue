<template>
    <div class="flex justify-center pt-10">
        <div
            class="lg:w-lg md:w-md max-sm:w-full p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
            <div>
                <div>
                    <h1 class="text-2xl font-bold pb-6 text-center">Add Book</h1>
                </div>
                <div class="gap-5">
                    <div class="flex flex-col gap-2 w-50" style="flex: 1">
                        <label for="types" class="">Authors</label>
                        <select id="types" v-model="form.author.id"
                                class="lg:w-[400px] xl:w-[500px] md:w-[300px] px-3 bg-white rounded-md border-2 py-1.5 text-gray-900 ring-0 border-gray-300 placeholder:text-gray-400 focus:border-green-500 sm:text-sm">
                            <option v-for="(item, index) in authors" :key="index" :value="item.id">{{item.first_name
                                }}
                            </option>
                        </select>
                    </div>
                    <div class="flex flex-col gap-2 max-sm:mt-10 mt-5">
                        <label class="block text-sm font-medium leading-6 text-gray-900" for="title">Title</label>
                        <input type="text" min="0" max="24" id="title"
                               class="lg:w-[400px] xl:w-[500px] md:w-[300px] px-3 rounded-md border-2 py-1.5 text-gray-900 ring-0 border-gray-300 placeholder:text-gray-400 focus:border-green-500 sm:text-sm"
                               required v-model="form.title"/>
                    </div>
                </div>
                <div class="gap-5 mt-5">
                    <div class="flex flex-col gap-2 max-sm:mt-10">
                        <label class="block text-sm font-medium leading-6 text-gray-900"
                               for="release_date">Release date</label>
                        <input type="date" min="0" max="24" id="release_date"
                               class="lg:w-[400px] xl:w-[500px] md:w-[300px] px-3 rounded-md border-2 py-1.5 text-gray-900 ring-0 border-gray-300 placeholder:text-gray-400 focus:border-green-500 sm:text-sm"
                               required v-model="form.release_date"/>
                    </div>
                    <div class="md:flex flex-col gap-3 mt-5">
                    <label class="block text-sm font-medium leading-6 text-gray-900"
                           for="description">Description</label>
                    <textarea type="text" id="description"
                               class="lg:w-[400px] xl:w-[500px] md:w-[300px] px-3 rounded-md border-2 py-1.5 text-gray-900 ring-0 border-gray-300 placeholder:text-gray-400 focus:border-green-500 sm:text-sm"
                              required v-model="form.description"/>
                    </div>
                    <div class="flex flex-col gap-2 max-sm:mt-10 mt-5">
                        <label class="block text-sm font-medium leading-6 text-gray-900" for="isbn">ISBN</label>
                        <input type="text" min="0" max="24" id="isbn"
                               class="lg:w-[400px] xl:w-[500px] md:w-[300px] px-3 rounded-md border-2 py-1.5 text-gray-900 ring-0 border-gray-300 placeholder:text-gray-400 focus:border-green-500 sm:text-sm"
                               required v-model="form.isbn"/>
                    </div>
                </div>
                <div class="gap-5 mt-5">
                    <div class="flex flex-col gap-2 max-sm:mt-10 ">
                        <label class="block text-sm font-medium leading-6 text-gray-900" for="format">Format</label>
                        <input type="text" min="0" max="24" id="format"
                               class="lg:w-[400px] xl:w-[500px] md:w-[300px] px-3 rounded-md border-2 py-1.5 text-gray-900 ring-0 border-gray-300 placeholder:text-gray-400 focus:border-green-500 sm:text-sm"
                               required v-model="form.format"/>
                    </div>
                    <div class="flex flex-col gap-2 max-sm:mt-10 mt-5">
                        <label class="block text-sm font-medium leading-6 text-gray-900" for="number_of_pages">Number of pages</label>
                        <input type="number" min="0" max="24" id="number_of_pages"
                                class="lg:w-[400px] xl:w-[500px] md:w-[300px] px-3 rounded-md border-2 py-1.5 text-gray-900 ring-0 border-gray-300 placeholder:text-gray-400 focus:border-green-500 sm:text-sm"
                               required v-model="form.number_of_pages"/>
                    </div>
                </div>
                <div>
                    <button @click="createBook()"
                        class="flex float-end mt-10 justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        Submit
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>

    import {onMounted, ref} from 'vue'
    import axios from "axios";
    import Swal from "sweetalert2";
    import {callApi} from "../../Https/Axio";

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

    const defaultForm = {
        author: {
            id: 0
        },
        title: '',
        release_date: '',
        description: '',
        isbn: '',
        format: '',
        number_of_pages: ''
    };
    const form = ref({
        author: {
            id: 0
        },
        title: '',
        release_date: '',
        description: '',
        isbn: '',
        format: '',
        number_of_pages: ''
    })

    const createBook = async () => {

        callApi('post','books', form.value)
        .then(function (response) {
            Toast.fire({
                icon: "success",
                title: 'Book added successfully!'
            });
            form.value = defaultForm;
        }).catch(function (error) {
            console.log(error);
            Toast.fire({
                icon: "error",
                title: error.response?.data?.message || 'something went wrong!'
            });
        });
    }

    const authors = ref([]);
    const getAuthor = async () => {
        const response =  await callApi('get','authors');
        authors.value =  response.data?.items;
    }


    onMounted(() => {
        getAuthor();
    });
</script>
