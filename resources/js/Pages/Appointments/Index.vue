<script setup>
    import { ref } from 'vue';
    import { useForm, router, Link } from '@inertiajs/vue3';
    import AppLayout from '@/Layouts/AppLayout.vue';
    import Modal from '@/Components/Modal.vue';
    import Pagination from '@/Components/Pagination.vue';
    import InputError from '@/Components/InputError.vue';

    const props = defineProps({
        doctor: Object,
        appointments: Object,
        canManage: Boolean,
        statuses: Array,
    });

    const showCreate = ref(false);
    const showEdit = ref(false);
    const showDelete = ref(false);
    const selectedAppointment = ref(null);

    const createForm = useForm({
        doctor_id: props.doctor.id,
        patient_name: '',
        scheduled_at: '',
        status: 'pending',
    });

    const editForm = useForm({
        patient_name: '',
        scheduled_at: '',
        status: '',
    });

    function openEdit(appointment) {
        selectedAppointment.value = appointment;
        editForm.patient_name = appointment.patient_name;
        editForm.scheduled_at = appointment.scheduled_at?.slice(0, 16);
        editForm.status = appointment.status;
        showEdit.value = true;
    }

    function openDelete(appointment) {
        selectedAppointment.value = appointment;
        showDelete.value = true;
    }

    function submitCreate() {
        createForm.post(route('appointments.store'), {
            onSuccess: () => {
                showCreate.value = false;
                createForm.reset();
                createForm.clearErrors();
            },
        });
    }

    function closeCreate() {
        showCreate.value = false;
        createForm.reset();
        createForm.clearErrors();
    }

    function submitEdit() {
        editForm.put(route('appointments.update', selectedAppointment.value.id), {
            onSuccess: () => {
                showEdit.value = false;
            },
        });
    }

    function confirmDelete() {
        router.delete(route('appointments.destroy', selectedAppointment.value.id), {
            onSuccess: () => {
                showDelete.value = false;
            },
        });
    }

    function changePage(page) {
        router.get(route('appointments.index', props.doctor.id), { page }, { preserveState: true });
    }

    const statusColors = {
        pending: 'bg-amber-50 text-amber-700',
        confirmed: 'bg-blue-50 text-blue-700',
        cancelled: 'bg-red-50 text-red-700',
        completed: 'bg-green-50 text-green-700',
    };

    function formatDate(dateStr) {
        if (!dateStr) return '—';
        return new Date(dateStr).toLocaleString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
        });
    }
</script>

<template>
    <AppLayout>
        <template #header>Appointments</template>

        <div class="mb-4">
            <Link
                :href="route('doctors.index')"
                class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-blue-600 transition-colors"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M15 19l-7-7 7-7"
                    />
                </svg>
                Back to Doctors
            </Link>
        </div>

        <div class="bg-blue-50 border border-blue-100 rounded-2xl p-5 mb-5 flex items-center gap-4">
            <div
                class="w-11 h-11 bg-blue-600 rounded-xl flex items-center justify-center flex-shrink-0"
            >
                <svg
                    class="w-6 h-6 text-white"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                    />
                </svg>
            </div>
            <div>
                <p class="text-base font-semibold text-gray-800">Dr. {{ doctor.name }}</p>
                <p class="text-sm text-gray-500">
                    {{ doctor.specialization }} · {{ doctor.license_no }}
                </p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <h2 class="text-base font-semibold text-gray-800">Appointment List</h2>
                <button
                    v-if="canManage"
                    class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl transition-colors"
                    @click="showCreate = true"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 4v16m8-8H4"
                        />
                    </svg>
                    Add Appointment
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider"
                            >
                                Patient
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider"
                            >
                                Scheduled At
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider"
                            >
                                Status
                            </th>
                            <th
                                v-if="canManage"
                                class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider"
                            >
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <tr v-if="!appointments.data?.length">
                            <td
                                :colspan="canManage ? 4 : 3"
                                class="px-6 py-10 text-center text-gray-400 text-sm"
                            >
                                No appointments found.
                            </td>
                        </tr>
                        <tr
                            v-for="appointment in appointments.data"
                            :key="appointment.id"
                            class="hover:bg-gray-50 transition-colors"
                        >
                            <td class="px-6 py-3.5 font-medium text-gray-800">
                                {{ appointment.patient_name }}
                            </td>
                            <td class="px-6 py-3.5 text-gray-600">
                                {{ formatDate(appointment.scheduled_at) }}
                            </td>
                            <td class="px-6 py-3.5">
                                <span
                                    :class="statusColors[appointment.status]"
                                    class="px-2.5 py-1 rounded-full text-xs font-medium capitalize"
                                >
                                    {{ appointment.status }}
                                </span>
                            </td>
                            <td v-if="canManage" class="px-6 py-3.5 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button
                                        class="px-3 py-1.5 text-xs font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors"
                                        @click="openEdit(appointment)"
                                    >
                                        Edit
                                    </button>
                                    <button
                                        class="px-3 py-1.5 text-xs font-medium text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors"
                                        @click="openDelete(appointment)"
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
                <Pagination :pagination="appointments" @change="changePage" />
            </div>
        </div>

        <Modal :show="showCreate" title="Add New Appointment" @close="closeCreate">
            <form class="space-y-4" @submit.prevent="submitCreate">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Patient name</label>
                    <input
                        v-model="createForm.patient_name"
                        type="text"
                        required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    />
                    <InputError :message="createForm.errors.patient_name" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1"
                        >Scheduled date & time</label
                    >
                    <input
                        v-model="createForm.scheduled_at"
                        type="datetime-local"
                        required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    />
                    <InputError :message="createForm.errors.scheduled_at" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select
                        v-model="createForm.status"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white"
                    >
                        <option v-for="s in statuses" :key="s" :value="s" class="capitalize">
                            {{ s }}
                        </option>
                    </select>
                    <InputError :message="createForm.errors.status" />
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button
                        type="button"
                        class="px-4 py-2 text-sm text-gray-600 border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors"
                        @click="closeCreate"
                    >
                        Cancel
                    </button>
                    <button
                        type="submit"
                        :disabled="createForm.processing"
                        class="px-4 py-2 text-sm font-medium bg-blue-600 hover:bg-blue-700 text-white rounded-xl disabled:opacity-60 transition-colors"
                    >
                        {{ createForm.processing ? 'Creating...' : 'Create Appointment' }}
                    </button>
                </div>
            </form>
        </Modal>

        <Modal :show="showEdit" title="Edit Appointment" @close="showEdit = false">
            <form class="space-y-4" @submit.prevent="submitEdit">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Patient name</label>
                    <input
                        v-model="editForm.patient_name"
                        type="text"
                        required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    />
                    <InputError :message="editForm.errors.patient_name" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1"
                        >Scheduled date & time</label
                    >
                    <input
                        v-model="editForm.scheduled_at"
                        type="datetime-local"
                        required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    />
                    <InputError :message="editForm.errors.scheduled_at" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select
                        v-model="editForm.status"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white"
                    >
                        <option v-for="s in statuses" :key="s" :value="s" class="capitalize">
                            {{ s }}
                        </option>
                    </select>
                    <InputError :message="editForm.errors.status" />
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button
                        type="button"
                        class="px-4 py-2 text-sm text-gray-600 border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors"
                        @click="showEdit = false"
                    >
                        Cancel
                    </button>
                    <button
                        type="submit"
                        :disabled="editForm.processing"
                        class="px-4 py-2 text-sm font-medium bg-blue-600 hover:bg-blue-700 text-white rounded-xl disabled:opacity-60 transition-colors"
                    >
                        {{ editForm.processing ? 'Saving...' : 'Save Changes' }}
                    </button>
                </div>
            </form>
        </Modal>

        <Modal
            :show="showDelete"
            title="Delete Appointment"
            max-width="sm"
            @close="showDelete = false"
        >
            <p class="text-sm text-gray-600 mb-5">
                Are you sure you want to delete the appointment for
                <strong>{{ selectedAppointment?.patient_name }}</strong
                >?
            </p>
            <div class="flex justify-end gap-3">
                <button
                    class="px-4 py-2 text-sm text-gray-600 border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors"
                    @click="showDelete = false"
                >
                    Cancel
                </button>
                <button
                    class="px-4 py-2 text-sm font-medium bg-red-600 hover:bg-red-700 text-white rounded-xl transition-colors"
                    @click="confirmDelete"
                >
                    Delete
                </button>
            </div>
        </Modal>
    </AppLayout>
</template>
