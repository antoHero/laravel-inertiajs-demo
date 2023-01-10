<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/inertia-vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import Modal from '@/Components/Modal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import { Inertia } from '@inertiajs/inertia'

import { ref } from 'vue';

defineProps({
    categories: Array
})

const form = useForm({
    name: ''
})



const categoryName = ref('')
const categorySlug = ref('')
const loading = ref(false)

const openModal = ref(false)
const confirmingUserDeletion = ref(false)
const confirmUpdateCategory = ref(false)

const confirmUserDeletion = (slug, name) => {
    confirmingUserDeletion.value = true;
    categorySlug.value = slug
    categoryName.value = name
};

const updateForm = useForm({
    name: categoryName.value
})

const viewCategory = (slug, name) => {
    confirmUpdateCategory.value = true
    categorySlug.value = slug
    categoryName.value = name
}

const closeUpdateModal = () => {
    confirmUpdateCategory.value = false
}

const closeModal = () => {
    openModal.value = false;

    form.reset();
};

const closeModalDeleteModal = () => {
    confirmingUserDeletion.value = false;
}

const addNew = () => {
    
    form.post(route('categories.store'), {
        onFinish: () => {
            form.reset('name')
            openModal.value = false;
        },
    })
}

const deleteCategory = (slug) => {
    loading.value = true
    Inertia.delete(route('categories.destroy', slug), {
        onSuccess: () => closeModalDeleteModal(),
        
    })
    loading.value = false
}
</script>

<template>
    <Head title="Expense categories" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Categories</h2>

            <div class="">
                <div v-if="categories.length">
                    <table class="min-w-full divide-y divide-gray-200 text-sm mt-4">
                        <thead class="bg-gray-100">
                            <tr>
                                <th
                                    class="whitespace-nowrap px-4 py-2 text-left font-medium text-gray-900"
                                >
                                    <div class="flex items-center gap-2">
                                        Name of Category

                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-4 w-4 text-gray-700"
                                            viewBox="0 0 20 20"
                                            fill="currentColor"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                    </div>
                                </th>
                                
                                <th
                                    class="whitespace-nowrap px-4 py-2 text-left font-medium text-gray-900"
                                >
                                    Action
                                
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200">
                            <tr v-for="(category, index) in categories" :key="index">
                                
                                <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ category.name }}</td>
                                <td class="whitespace-nowrap px-4 py-2">
                                    <div class="flex gap-4">
                                        <Link :href="route('categories.show', category.slug)">
                                            <svg 
                                                xmlns="http://www.w3.org/2000/svg" 
                                                fill="none" 
                                                viewBox="0 0 24 24" 
                                                stroke-width="1.5" 
                                                stroke="currentColor" 
                                                class="w-6 h-6 cursor-pointer"
                                            >
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </Link>
                                        
                                        <svg 
                                            xmlns="http://www.w3.org/2000/svg" 
                                            fill="none" 
                                            viewBox="0 0 24 24" 
                                            stroke-width="1.5" 
                                            stroke="currentColor" 
                                            class="w-6 h-6 text-red-500 cursor-pointer"
                                            @click="confirmUserDeletion(category.slug, category.name)"
                                        >
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>

                                    </div>
                                    
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="flex justify-start items-center mt-4" v-else>
                    <PrimaryButton class="text-white bg-indigo-500 hover:bg-indigo-700 rounded" @click="openModal = !openModal">
                        Create New
                    </PrimaryButton>
                </div>
            </div>
            <Modal :show="openModal" @close="closeModal">
                <div class="p-6">
                    <div class="mt-6">
                        <InputLabel for="category" value="Category" class="sr-only" />

                        <TextInput
                            id="name"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.name"
                            placeholder="Category name"
                            autofocus
                            autocomplete="name"
                        />

                        <InputError :message="form.errors.name" class="mt-2" />
                    </div>

                    <div class="mt-6 flex justify-end">
                        <SecondaryButton @click="closeModal"> Cancel </SecondaryButton>

                        <DangerButton
                            class="ml-3"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                            @click="addNew"
                        >
                            Add New
                        </DangerButton>
                    </div>
                </div>
            </Modal>

            <Modal :show="confirmingUserDeletion" @close="closeModalDeleteModal">
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900">
                        Are you sure you want to delete <span class="font-bold">{{ categoryName }}</span>?
                    </h2>

                    <p class="mt-1 text-sm text-gray-600">
                        Once this category has been deleted, all subcategories and expenses depending on this category will also be deleted
                    </p>

                    <div class="mt-6 flex justify-end">
                        <SecondaryButton @click="closeModalDeleteModal"> Cancel </SecondaryButton>

                        <DangerButton
                            class="ml-3"
                            :class="{ 'opacity-25': loading }"
                            :disabled="loading"
                            @click="deleteCategory(categorySlug)"
                        >
                            Yes, delete
                        </DangerButton>
                    </div>
                </div>
            </Modal>

        </template>
    </AuthenticatedLayout>

</template>