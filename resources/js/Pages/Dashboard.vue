<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    stats: Object,
});

const cards = [
    {
        label: 'Total Users',
        key: 'users',
        color: 'bg-blue-500',
        icon: 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
    },
    {
        label: 'Total Doctors',
        key: 'doctors',
        color: 'bg-emerald-500',
        icon: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
    },
    {
        label: 'Total Appointments',
        key: 'appointments',
        color: 'bg-violet-500',
        icon: 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
    },
    {
        label: 'Pending Appointments',
        key: 'pending_appointments',
        color: 'bg-amber-500',
        icon: 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
    },
];
</script>

<template>
    <AppLayout>
        <template #header>Dashboard</template>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <div
                v-for="card in cards"
                :key="card.key"
                class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center gap-4"
            >
                <div :class="card.color" class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="card.icon" />
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800">{{ stats[card.key] }}</p>
                    <p class="text-sm text-gray-500">{{ card.label }}</p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
