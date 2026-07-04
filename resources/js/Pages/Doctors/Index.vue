<script setup>
import { ref } from 'vue';
import { useForm, router, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Modal from '@/Components/Modal.vue';
import Pagination from '@/Components/Pagination.vue';
import InputError from '@/Components/InputError.vue';

const props = defineProps({
    doctors: Object,
    canManage: Boolean,
});

const showCreate = ref(false);
const showEdit = ref(false);
const showDelete = ref(false);
const showImport = ref(false);
const selectedDoctor = ref(null);

const createForm = useForm({
    name: '',
    specialization: '',
    license_no: '',
});

const editForm = useForm({
    name: '',
    specialization: '',
    license_no: '',
});

const importForm = useForm({
    file: null,
});

function openEdit(doctor) {
    selectedDoctor.value = doctor;
    editForm.name = doctor.name;
    editForm.specialization = doctor.specialization;
    editForm.license_no = doctor.license_no;
    showEdit.value = true;
}

function openDelete(doctor) {
    selectedDoctor.value = doctor;
    showDelete.value = true;
}

function submitCreate() {
    createForm.post(route('doctors.store'), {
        onSuccess: () => {
            showCreate.value = false;
            createForm.reset();
        },
    });
}

function submitEdit() {
    editForm.put(route('doctors.update', selectedDoctor.value.id), {
        onSuccess: () => {
            showEdit.value = false;
        },
    });
}

function confirmDelete() {
    router.delete(route('doctors.destroy', selectedDoctor.value.id), {
        onSuccess: () => {
            showDelete.value = false;
        },
    });
}

function submitImport() {
    importForm.post(route('doctors.import'), {
        onSuccess: () => {
            showImport.value = false;
            importForm.reset();
        },
    });
}

function handleFileChange(e) {
    importForm.file = e.target.files[0];
}

function changePage(page) {
    router.get(route('doctors.index'), { page }, { preserveState: true });
}
</script>

<template>
    <AppLayout>
        <template #header>Doctors</template>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 flex-wrap gap-3">
                <h2 class="text-base font-semibold text-gray-800">All Doctors</h2>
                <div v-if="canManage" class="flex items-center gap-2 flex-wrap">
                    <a
                        :href="route('doctors.export')"
                        class="flex items-center gap-2 px-3 py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-sm font-medium rounded-xl transition-colors"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Export CSV
                    </a>
                    <button
                        @click="showImport = true"
                        class="flex items-center gap-2 px-3 py-2 bg-amber-50 hover:bg-amber-100 text-amber-700 text-sm font-medium rounded-xl transition-colors"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                        Import CSV
                    </button>
                    <button
                        @click="showCreate = true"
                        class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl transition-colors"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Add Doctor
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm table-fixed">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Specialization</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-44">License No.</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider w-56">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <tr v-if="!doctors.data?.length">
                            <td colspan="5" class="px-6 py-10 text-center text-gray-400 text-sm">No doctors found.</td>
                        </tr>
                        <tr v-for="doctor in doctors.data" :key="doctor.id" class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-3.5 font-medium text-gray-800 truncate">{{ doctor.name }}</td>
                            <td class="px-6 py-3.5 text-gray-600 truncate">{{ doctor.specialization }}</td>
                            <td class="px-6 py-3.5 text-gray-600 font-mono text-xs truncate">{{ doctor.license_no }}</td>
                            <td class="px-6 py-3.5 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <Link
                                        :href="route('appointments.index', doctor.id)"
                                        class="px-3 py-1.5 text-xs font-medium text-violet-600 bg-violet-50 hover:bg-violet-100 rounded-lg transition-colors"
                                    >
                                        Appointments
                                    </Link>
                                    <template v-if="canManage">
                                        <button
                                            @click="openEdit(doctor)"
                                            class="px-3 py-1.5 text-xs font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors"
                                        >
                                            Edit
                                        </button>
                                        <button
                                            @click="openDelete(doctor)"
                                            class="px-3 py-1.5 text-xs font-medium text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors"
                                        >
                                            Delete
                                        </button>
                                    </template>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="px-6 border-t border-gray-100">
                <Pagination :pagination="doctors" @change="changePage" />
            </div>
        </div>

        <Modal :show="showCreate" title="Add New Doctor" @close="showCreate = false">
            <form @submit.prevent="submitCreate" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Full name</label>
                    <input v-model="createForm.name" type="text" required class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                    <InputError :message="createForm.errors.name" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Specialization</label>
                    <input v-model="createForm.specialization" type="text" required class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                    <InputError :message="createForm.errors.specialization" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">License number</label>
                    <input v-model="createForm.license_no" type="text" required class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                    <InputError :message="createForm.errors.license_no" />
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" @click="showCreate = false" class="px-4 py-2 text-sm text-gray-600 border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors">Cancel</button>
                    <button type="submit" :disabled="createForm.processing" class="px-4 py-2 text-sm font-medium bg-blue-600 hover:bg-blue-700 text-white rounded-xl disabled:opacity-60 transition-colors">
                        {{ createForm.processing ? 'Creating...' : 'Create Doctor' }}
                    </button>
                </div>
            </form>
        </Modal>

        <Modal :show="showEdit" title="Edit Doctor" @close="showEdit = false">
            <form @submit.prevent="submitEdit" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Full name</label>
                    <input v-model="editForm.name" type="text" required class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                    <InputError :message="editForm.errors.name" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Specialization</label>
                    <input v-model="editForm.specialization" type="text" required class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                    <InputError :message="editForm.errors.specialization" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">License number</label>
                    <input v-model="editForm.license_no" type="text" required class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                    <InputError :message="editForm.errors.license_no" />
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" @click="showEdit = false" class="px-4 py-2 text-sm text-gray-600 border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors">Cancel</button>
                    <button type="submit" :disabled="editForm.processing" class="px-4 py-2 text-sm font-medium bg-blue-600 hover:bg-blue-700 text-white rounded-xl disabled:opacity-60 transition-colors">
                        {{ editForm.processing ? 'Saving...' : 'Save Changes' }}
                    </button>
                </div>
            </form>
        </Modal>

        <Modal :show="showDelete" title="Delete Doctor" max-width="sm" @close="showDelete = false">
            <p class="text-sm text-gray-600 mb-5">
                Are you sure you want to delete <strong>{{ selectedDoctor?.name }}</strong>? All their appointments will also be deleted.
            </p>
            <div class="flex justify-end gap-3">
                <button @click="showDelete = false" class="px-4 py-2 text-sm text-gray-600 border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors">Cancel</button>
                <button @click="confirmDelete" class="px-4 py-2 text-sm font-medium bg-red-600 hover:bg-red-700 text-white rounded-xl transition-colors">Delete</button>
            </div>
        </Modal>

        <Modal :show="showImport" title="Import Doctors from CSV" max-width="sm" @close="showImport = false">
            <p class="text-sm text-gray-500 mb-4">CSV must have columns: <code class="text-xs bg-gray-100 px-1.5 py-0.5 rounded">name, specialization, license_no</code></p>
            <form @submit.prevent="submitImport" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Choose file</label>
                    <input
                        type="file"
                        accept=".csv,.txt"
                        @change="handleFileChange"
                        required
                        class="w-full text-sm text-gray-600 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                    />
                    <InputError :message="importForm.errors.file" />
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" @click="showImport = false" class="px-4 py-2 text-sm text-gray-600 border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors">Cancel</button>
                    <button type="submit" :disabled="importForm.processing" class="px-4 py-2 text-sm font-medium bg-blue-600 hover:bg-blue-700 text-white rounded-xl disabled:opacity-60 transition-colors">
                        {{ importForm.processing ? 'Importing...' : 'Import' }}
                    </button>
                </div>
            </form>
        </Modal>
    </AppLayout>
</template>
