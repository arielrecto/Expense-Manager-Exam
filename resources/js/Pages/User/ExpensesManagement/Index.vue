<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import TableColumn from '@/Components/Table/TableColumn.vue'
import TableRow from '@/Components/Table/TableRow.vue'
import { Head, router, usePage } from '@inertiajs/vue3'
import { ref } from 'vue'
import BreadCrumps from '@/Components/BreadCrump.vue'
import Alert from '@/Components/Alert.vue'

defineProps({
    expenses: Array,
    auth: Object,
    categories: Array
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

const newExpense = ref({
    category: null,
    amount: null,
    entryDate: null,
});



const addExpense = () => {
    router.post('/expenses', newExpense.value, {
        onSuccess: () => {
            flash.value.message_success = page().props.flash?.message_success
        },
        onError: () => {
            errors.value = {
                ...page().props.errors
            }
        }
    })
    newExpense.value = {
        category: null,
        amount: null,
        entryDate: null,
    }

    errors.value = {

    }
}

const editModalOpen = (expense) => {
    editModalId.value = expense.id;

    newExpense.value = {
        category: expense.category.id,
        amount: expense.amount,
        entryDate: expense.entry_date,
    }

    editModal.value.showModal()
}


const updateExpense = () => {
    router.put(`/expenses/${editModalId.value}`, newExpense.value, {
        onSuccess: () => {
            flash.value.message_success = page().props.flash.message_success
        }
    })
    newExpense.value = {
        category: null,
        amount: null,
        entryDate: null,
    }
    editModalId.value = null;

    editModal.form.method = 'dialog'

}

const deleteExpense = () => {
    router.delete(`/expenses/${editModalId.value}`, {
        onSuccess: () => {
            flash.value.message_success = page().props.flash.message_success

            newExpense.value = {
                category: null,
                amount: null,
                entryDate: null,
            }
        }
    });


}
</script>


<template>
    <AuthenticatedLayout :user="auth.user">
        <Alert :flash="flash" />

        <div class="flex items-center justify-between text-black">
            <h1>
                Expenses
            </h1>
            <BreadCrumps :labels="['Expenses Management']">
                Expenses
            </BreadCrumps>
        </div>

        <!-- Open the modal using ID.showModal() method -->

        <TableColumn :columnLabels="[
            'Category',
            'amount',
            'entry date'
        ]">

            <template v-if="expenses.length !== 0">
                <template v-for="expense in expenses" :key="expense.id">
                    <TableRow class="p-2">
                        <th class="p-2 capitalize">
                            {{ expense?.category.name }}
                        </th>
                        <td class="p-2">
                            ₱ {{ expense.amount ?? 'N/A' }}
                        </td>
                        <td class="p-2">
                            {{ expense.entry_date }}
                        </td>
                        <td class="p-2">
                            <button class="btn btn-warning btn-sm" @click="editModalOpen(expense)">View</button>
                        </td>
                    </TableRow>
                </template>
            </template>

            <template v-else>
                <TableRow class="p-2">
                    <th class="p-2 capitalize">
                        No Expenses Record
                    </th>

                </TableRow>
            </template>


        </TableColumn>

        <div class="flex justify-end">
            <button class="btn btn-accent btn-sm" onclick="my_modal_1.showModal()">Add Expenses</button>
        </div>

        <dialog id="my_modal_1" class="modal">
            <div class="modal-box bg-white text-black">
                <h3 class="text-lg font-bold">Add expense</h3>

                <span v-if="flash?.message_success" class="text-sm text-success"> {{ flash?.message_success }}</span>

                <div class="flex flex-col gap-2">
                    <label for="">Category</label>
                    <select v-model="newExpense.category" class="select select-accent w-full bg-white">
                        <option disabled selected>Select Category</option>
                        <template v-for="category in categories" :key="category.id">
                            <option :value="category.id">{{ category.name }}</option>
                        </template>

                    </select>
                    <span class="text-xs text-error">
                        {{ errors?.category }}
                    </span>
                </div>

                <div class="flex flex-col gap-2">
                    <label for="">Amount</label>
                    <input type="number" v-model="newExpense.amount" class="input input-md input-accent bg-white"
                        placeholder="Amount">
                    <span class="text-xs text-error">
                        {{ errors?.amount }}
                    </span>
                </div>

                <div class="flex flex-col gap-2 ">
                    <label for="">Entry Date</label>
                    <input type="date" v-model="newExpense.entryDate" class="input input-md input-accent bg-white"
                        placeholder="Description">

                    <span class="text-xs text-error">
                        {{ errors?.entryDate }}
                    </span>
                </div>

                <div class="modal-action">

                    <form method="dialog">

                        <button class="btn btn-error">Close</button>
                    </form>
                    <button @click="addExpense" class="btn btn-success">
                        Save
                    </button>
                </div>
            </div>
        </dialog>

        <dialog :id="`my_modal_${editModalId}`" ref="editModal" class="modal">
            <div class="modal-box bg-white text-black">
                <h3 class="text-lg font-bold">Edit expense</h3>

                <span v-if="flash?.message_success" class="text-sm text-success"> {{ flash?.message_success }}</span>

                <div class="flex flex-col gap-2">
                    <label for="">Category</label>
                    <select v-model="newExpense.category" class="select select-accent w-full bg-white">
                        <option disabled selected>Select Category</option>
                        <template v-for="category in categories" :key="category.id">
                            <option :value="category.id">{{ category.name }}</option>
                        </template>

                    </select>
                    <span class="text-xs text-error">
                        {{ errors?.category }}
                    </span>
                </div>

                <div class="flex flex-col gap-2">
                    <label for="">Amount</label>
                    <input type="number" v-model="newExpense.amount" class="input input-md input-accent bg-white"
                        placeholder="Amount">
                </div>

                <div class="flex flex-col gap-2 ">
                    <label for="">Entry Date</label>
                    <input type="date" v-model="newExpense.entryDate" class="input input-md input-accent bg-white"
                        placeholder="Description">
                </div>
                <div class="modal-action flex justify-between">
                    <button class="btn btn-error" @click="deleteExpense">Delete</button>
                    <div class="flex items-center gap-2">
                        <form method="dialog">

                            <button class="btn btn-error">Close</button>
                        </form>
                        <button @click="updateExpense" class="btn btn-success">
                            Save
                        </button>
                    </div>

                </div>
            </div>
        </dialog>

    </AuthenticatedLayout>

</template>
