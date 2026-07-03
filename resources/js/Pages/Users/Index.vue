<script setup>
import { ref } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Modal from '@/Components/Modal.vue';
import Pagination from '@/Components/Pagination.vue';
import InputError from '@/Components/InputError.vue';

const props = defineProps({
    users: Object,
});

const showCreate = ref(false);
const showEdit = ref(false);
const showDelete = ref(false);
const selectedUser = ref(null);

const roles = ['super_admin', 'receptionist', 'doctor'];

const createForm = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: 'receptionist',
});

const editForm = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: '',
});

function openEdit(user) {
    selectedUser.value = user;
    editForm.name = user.name;
    editForm.email = user.email;
    editForm.password = '';
    editForm.password_confirmation = '';
    editForm.role = user.role;
    showEdit.value = true;
}

function openDelete(user) {
    selectedUser.value = user;
    showDelete.value = true;
}

function submitCreate() {
    createForm.post(route('users.store'), {
        onSuccess: () => {
            showCreate.value = false;
            createForm.reset();
        },
    });
}

function submitEdit() {
    editForm.put(route('users.update', selectedUser.value.id), {
        onSuccess: () => {
            showEdit.value = false;
        },
    });
}

function confirmDelete() {
    router.delete(route('users.destroy', selectedUser.value.id), {
        onSuccess: () => {
            showDelete.value = false;
        },
    });
}

function changePage(page) {
    router.get(route('users.index'), { page }, { preserveState: true });
}
</script>

<template>
    <AppLayout>
        <template #header>Users</template>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <h2 class="text-base font-semibold text-gray-800">All Users</h2>
                <button
                    @click="showCreate = true"
                    class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl transition-colors"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add User
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Role</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Joined</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <tr v-if="!users.data?.length">
                            <td colspan="5" class="px-6 py-10 text-center text-gray-400 text-sm">No users found.</td>
                        </tr>
                        <tr v-for="user in users.data" :key="user.id" class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-3.5 font-medium text-gray-800">{{ user.name }}</td>
                            <td class="px-6 py-3.5 text-gray-600">{{ user.email }}</td>
                            <td class="px-6 py-3.5">
                                <span
                                    :class="{
                                        'bg-purple-50 text-purple-700': user.role === 'super_admin',
                                        'bg-blue-50 text-blue-700': user.role === 'receptionist',
                                        'bg-green-50 text-green-700': user.role === 'doctor',
                                    }"
                                    class="px-2.5 py-1 rounded-full text-xs font-medium capitalize"
                                >
                                    {{ user.role.replace('_', ' ') }}
                                </span>
                            </td>
                            <td class="px-6 py-3.5 text-gray-500">{{ new Date(user.created_at).toLocaleDateString() }}</td>
                            <td class="px-6 py-3.5 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button
                                        @click="openEdit(user)"
                                        class="px-3 py-1.5 text-xs font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors"
                                    >
                                        Edit
                                    </button>
                                    <button
                                        @click="openDelete(user)"
                                        class="px-3 py-1.5 text-xs font-medium text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors"
                                    >
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="px-6 border-t border-gray-100">
                <Pagination :pagination="users" @change="changePage" />
            </div>
        </div>

        <Modal :show="showCreate" title="Add New User" @close="showCreate = false">
            <form @submit.prevent="submitCreate" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Full name</label>
                    <input v-model="createForm.name" type="text" required class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                    <InputError :message="createForm.errors.name" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email address</label>
                    <input v-model="createForm.email" type="email" required class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                    <InputError :message="createForm.errors.email" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                    <select v-model="createForm.role" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white">
                        <option v-for="r in roles" :key="r" :value="r">{{ r.replace('_', ' ') }}</option>
                    </select>
                    <InputError :message="createForm.errors.role" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input v-model="createForm.password" type="password" required class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                    <InputError :message="createForm.errors.password" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirm password</label>
                    <input v-model="createForm.password_confirmation" type="password" required class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" @click="showCreate = false" class="px-4 py-2 text-sm text-gray-600 border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors">Cancel</button>
                    <button type="submit" :disabled="createForm.processing" class="px-4 py-2 text-sm font-medium bg-blue-600 hover:bg-blue-700 text-white rounded-xl disabled:opacity-60 transition-colors">
                        {{ createForm.processing ? 'Creating...' : 'Create User' }}
                    </button>
                </div>
            </form>
        </Modal>

        <Modal :show="showEdit" title="Edit User" @close="showEdit = false">
            <form @submit.prevent="submitEdit" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Full name</label>
                    <input v-model="editForm.name" type="text" required class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                    <InputError :message="editForm.errors.name" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email address</label>
                    <input v-model="editForm.email" type="email" required class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                    <InputError :message="editForm.errors.email" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                    <select v-model="editForm.role" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white">
                        <option v-for="r in roles" :key="r" :value="r">{{ r.replace('_', ' ') }}</option>
                    </select>
                    <InputError :message="editForm.errors.role" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">New password <span class="text-gray-400 font-normal">(leave blank to keep current)</span></label>
                    <input v-model="editForm.password" type="password" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                    <InputError :message="editForm.errors.password" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirm new password</label>
                    <input v-model="editForm.password_confirmation" type="password" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" @click="showEdit = false" class="px-4 py-2 text-sm text-gray-600 border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors">Cancel</button>
                    <button type="submit" :disabled="editForm.processing" class="px-4 py-2 text-sm font-medium bg-blue-600 hover:bg-blue-700 text-white rounded-xl disabled:opacity-60 transition-colors">
                        {{ editForm.processing ? 'Saving...' : 'Save Changes' }}
                    </button>
                </div>
            </form>
        </Modal>

        <Modal :show="showDelete" title="Delete User" max-width="sm" @close="showDelete = false">
            <p class="text-sm text-gray-600 mb-5">
                Are you sure you want to delete <strong>{{ selectedUser?.name }}</strong>? This action cannot be undone.
            </p>
            <div class="flex justify-end gap-3">
                <button @click="showDelete = false" class="px-4 py-2 text-sm text-gray-600 border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors">Cancel</button>
                <button @click="confirmDelete" class="px-4 py-2 text-sm font-medium bg-red-600 hover:bg-red-700 text-white rounded-xl transition-colors">Delete</button>
            </div>
        </Modal>
    </AppLayout>
</template>
