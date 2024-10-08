<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import TableColumn from '@/Components/Table/TableColumn.vue'
import TableRow from '@/Components/Table/TableRow.vue'
import { Head, router, usePage } from '@inertiajs/vue3'
import { ref } from 'vue'
import BreadCrumps from '@/Components/BreadCrump.vue'
import Alert from '@/Components/Alert.vue'

defineProps({
    users: Object,
    auth: Object,
    roles: Array
});


const page = usePage;

const flash = ref({
    message_success: page().props.flash?.message_success,
    message_error: page().props.flash?.message_error,
})

const errors = ref({

});


const userInit = ref({
    name: null,
    email: null,
    role: null
});


const userModalId = ref(null)

const addUser = () => {
    router.post('/admin/user', userInit.value, {
        onSuccess : () => {
            flash.value.message_success = page().props.flash?.message_success
        },
        onError : () => {
            errors.value = {
                ...page().props.errors
            }
        }
    });
    userInit.value = {
        name: null,
        email: null,
        role: null
    };
}

const selectedRole = (e) => {
    userInit.value.role = e.target.value
}

const userModal = ref()


const userModalOpen = (user) => {
    userModalId.value = user.id;
    userInit.value = {
        name: user.name,
        email: user.email,
        role: user.roles[0].id
    }


    userModal.value.showModal();
}


const updateUser = () => {


    router.put(`/admin/user/${userModalId.value}`, userInit.value, {
        onSuccess : () => {
            console.log(page().props.flash?.message_success)
            flash.value.message_success = page().props.flash?.message_success
        }
    });

    userInit.value = {
        name: null,
        email: null,
        role: null
    };

}


const deleteUser = () => {


    router.delete(`/admin/user/${userModalId.value}`, {
        onSuccess : () => {
            flash.value.message_success = page().props.flash?.message_success
        }
    });


}

</script>


<template>


    <AuthenticatedLayout :user="auth.user">

        <Alert :flash="flash"/>
        <div class="flex items-center justify-between text-black">
            <h1>
                User Management
            </h1>


            <BreadCrumps :labels="['User Management']">
                Users
            </BreadCrumps>
        </div>


        <TableColumn :columnLabels="[
            'username',
            'email',
            'role'
        ]">

            <template v-for="user in users" :key="user.id">
                <TableRow class="p-2">
                    <th class="p-2 capitalize">
                        {{ user.name }}
                    </th>
                    <td class="p-2">
                        {{ user.email ?? 'N/A' }}
                    </td>
                    <td class="p-2 divide-x">
                        <template v-for="role in user.roles" :key="role.id">
                            <span>
                                {{ role.name }}
                            </span>
                        </template>
                    </td>
                    <td class="p-2">
                        <button @click="userModalOpen(user)" class="btn btn-warning btn-sm">View</button>
                    </td>
                </TableRow>
            </template>


        </TableColumn>


        <div class="flex justify-end">
            <button class="btn btn-accent btn-sm" onclick="my_modal_1.showModal()">Add User</button>
        </div>



        <dialog id="my_modal_1" class="modal">
            <div class="modal-box bg-white text-black">
                <h3 class="text-lg font-bold">Add Role</h3>

                <span v-if="flash?.message_success" class="text-sm text-success"> {{ flash?.message_success }}</span>

                <div class="flex flex-col gap-2">
                    <label for="">Username</label>
                    <input type="text" name="name" v-model="userInit.name" class="input input-md input-accent bg-white"
                        placeholder="Username">
                    <span class="text-xs text-error">
                        {{ errors?.name }}
                    </span>

                </div>
                <div class="flex flex-col gap-2">
                    <label for="">Emails</label>
                    <input type="email" name="email" v-model="userInit.email"
                        class="input input-md input-accent bg-white" placeholder="Email">
                    <span class="text-xs text-error">
                        {{ errors?.email }}
                    </span>
                </div>


                <div class="flex flex-col gap-2">
                    <label for="">Roles</label>
                    <select @change="selectedRole($event)" class="select select-primary w-full bg-white">
                        <option disabled selected>Select Role</option>
                        <template v-for="role in roles" :key="role.id">
                            <option :value="role.id">{{ role.name }}</option>
                        </template>

                    </select>
                    <span class="text-xs text-error">
                        {{ errors?.role }}
                    </span>
                </div>

                <div class="modal-action">

                    <form method="dialog">
                        <!-- if there is a button in form, it will close the modal -->
                        <button class="btn btn-error">Close</button>
                    </form>
                    <button @click="addUser" class="btn btn-success">
                        Save
                    </button>
                </div>
            </div>
        </dialog>


        <dialog :id="`my_modal_${userModalId}`" class="modal" ref="userModal">
            <div class="modal-box bg-white text-black">
                <h3 class="text-lg font-bold">Edit User</h3>

                <span v-if="flash?.message_success" class="text-sm text-success"> {{ flash?.message_success }}</span>

                <div class="flex flex-col gap-2">
                    <label for="">Username</label>
                    <input type="text" name="name" v-model="userInit.name" class="input input-md input-accent bg-white"
                        placeholder="Username">
                    <span class="text-xs text-error">
                        {{ errors?.name }}
                    </span>

                </div>
                <div class="flex flex-col gap-2">
                    <label for="">Emails</label>
                    <input type="email" name="email" v-model="userInit.email"
                        class="input input-md input-accent bg-white" placeholder="Email">
                    <span class="text-xs text-error">
                        {{ errors?.email }}
                    </span>
                </div>


                <div class="flex flex-col gap-2">
                    <label for="">Roles</label>
                    <select @change="selectedRole($event)" class="select select-primary w-full bg-white">
                        <option disabled selected>Select Role</option>
                        <template v-for="role in roles" :key="role.id">
                            <option :value="role.id">{{ role.name }}</option>
                        </template>

                    </select>
                    <span class="text-xs text-error">
                        {{ errors?.role }}
                    </span>
                </div>


                <div class="modal-action flex justify-between">

                    <button @click="deleteUser" class="btn btn-error">
                        delete
                    </button>
                    <div class="flex items-center gap-2">
                        <form method="dialog">
                            <!-- if there is a button in form, it will close the modal -->
                            <button class="btn btn-error">Close</button>
                        </form>
                        <button @click="updateUser" class="btn btn-success">
                            Save
                        </button>
                    </div>



                </div>
            </div>
        </dialog>

    </AuthenticatedLayout>

</template>
