<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import TableColumn from '@/Components/Table/TableColumn.vue'
import TableRow from '@/Components/Table/TableRow.vue'
import { Head, router, usePage } from '@inertiajs/vue3'
import { ref } from 'vue'
import BreadCrumps from '@/Components/BreadCrump.vue'
import Alert from '@/Components/Alert.vue'

defineProps({
    categories: Array,
    auth: Object
})

const page = usePage;

const flash = ref({
    message_success: page().props.flash?.message_success,
    message_error: page().props.flash?.message_error,
})

const errors = ref({

});


const editModalId = ref(null);
const editModal = ref()

const newCategory = ref({
    name: null,
    description: null,
});



const addCategory = () => {
    router.post('/admin/categories', newCategory.value, {
        onSuccess: () => {
            flash.value.message_success = page().props.flash?.message_success
        },
        onError: () => {
            errors.value = {
                ...page().props.errors
            }
        }
    })
    newCategory.value = {
        name: null,
        description: null
    }
}

const editModalOpen = (category) => {
    editModalId.value = category.id;

    newCategory.value = {
        name: category.name,
        description: category.description
    }

    editModal.value.showModal()
}


const updateCategory = () => {
    router.put(`/admin/categories/${editModalId.value}`, newCategory.value, {
        onSuccess: () => {
            flash.value.message_success = page().props.flash?.message_success
        }
    })
    newCategory.value = {
        name: null,
        description: null
    }

    editModalId.value = null;

    editModal.form.method = 'dialog'

}

const deleteCategory = () => {
    router.delete(`/admin/categories/${editModalId.value}`, {
        onSuccess: () => {
            flash.value.message_success = page().props.flash?.message_success,
                newCategory.value = {
                    name: null,
                    description: null
                }
        }
    });
}
</script>


<template>
    <AuthenticatedLayout :user="auth.user">
        <Alert :flash="flash" />

        <div class="flex items-center justify-between text-sky-950">
            <h1>
                Category Management
            </h1>
            <BreadCrumps :labels="['Expenses Management']">
                Category
            </BreadCrumps>
        </div>

        <!-- Open the modal using ID.showModal() method -->

        <TableColumn :columnLabels="[
            'category',
            'description',
            'created_at'
        ]">

            <template v-for="category in categories" :key="category.id">
                <TableRow class="p-2">
                    <th class="p-2 capitalize">
                        {{ category.name }}
                    </th>
                    <td class="p-2">
                        {{ category.description ?? 'N/A' }}
                    </td>
                    <td class="p-2">
                        {{ category.created_at }}
                    </td>
                    <td class="p-2">
                        <button class="btn btn-warning btn-sm" @click="editModalOpen(category)">View</button>
                    </td>
                </TableRow>
            </template>


        </TableColumn>

        <div class="flex justify-end">
            <button class="btn btn-accent btn-sm" onclick="my_modal_1.showModal()">Add Category</button>
        </div>

        <dialog id="my_modal_1" class="modal">
            <div class="modal-box bg-white text-black">
                <h3 class="text-lg font-bold">Add category</h3>

                <span v-if="flash?.message_success" class="text-sm text-success"> {{ flash?.message_success }}</span>

                <div class="flex flex-col gap-2">
                    <label for="">Display Name</label>
                    <input type="text" v-model="newCategory.name" class="input input-md input-accent bg-white"
                        placeholder="Display Name">
                    <span class="text-xs text-error">
                        {{ errors?.name }}
                    </span>
                </div>
                <div class="flex flex-col gap-2">
                    <label for="">Description</label>
                    <input type="text" v-model="newCategory.description" class="input input-md input-accent bg-white"
                        placeholder="Description">

                    <span class="text-xs text-error">
                        {{ errors?.description }}
                    </span>
                </div>

                <div class="modal-action">

                    <form method="dialog">

                        <button class="btn btn-error">Close</button>
                    </form>
                    <button @click="addCategory" class="btn btn-success">
                        Save
                    </button>
                </div>
            </div>
        </dialog>

        <dialog :id="`my_modal_${editModalId}`" ref="editModal" class="modal">
            <div class="modal-box bg-white text-black">
                <h3 class="text-lg font-bold">Edit category</h3>

                <span v-if="flash?.message_success" class="text-sm text-success"> {{ flash?.message_success }}</span>

                <div class="flex flex-col gap-2">
                    <label for="">Display Name</label>
                    <input type="text" v-model="newCategory.name" class="input input-md input-accent bg-white"
                        placeholder="Display Name">
                </div>
                <div class="flex flex-col gap-2">
                    <label for="">Description</label>
                    <input type="text" v-model="newCategory.description" class="input input-md input-accent bg-white"
                        placeholder="Description">
                </div>

                <div class="modal-action flex justify-between">
                    <button class="btn btn-error" @click="deleteCategory">Delete</button>
                    <div class="flex items-center gap-2">
                        <form method="dialog">

                            <button class="btn btn-error">Close</button>
                        </form>
                        <button @click="updateCategory" class="btn btn-success">
                            Save
                        </button>
                    </div>

                </div>
            </div>
        </dialog>

    </AuthenticatedLayout>

</template>
