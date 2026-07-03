<script setup>
import { Link, router } from '@inertiajs/vue3';
import { useAuthStore } from '@/stores/auth';
import { useAppStore } from '@/stores/app';

const auth = useAuthStore();
const app = useAppStore();

function logout() {
    router.post(route('logout'));
}
</script>

<template>
    <div class="min-h-screen bg-gray-50 flex">
        <aside
            :class="app.sidebarOpen ? 'w-64' : 'w-16'"
            class="bg-white border-r border-gray-200 flex flex-col transition-all duration-300 min-h-screen"
        >
            <div class="flex items-center justify-between px-4 py-4 border-b border-gray-200">
                <span v-if="app.sidebarOpen" class="text-lg font-bold text-blue-600">Clinic MS</span>
                <button
                    @click="app.toggleSidebar()"
                    class="p-1.5 rounded-lg text-gray-500 hover:bg-gray-100"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

            <nav class="flex-1 px-2 py-4 space-y-1">
                <Link
                    :href="route('dashboard')"
                    class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors"
                    :class="{ 'bg-blue-50 text-blue-600': $page.url.startsWith('/dashboard') }"
                >
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span v-if="app.sidebarOpen">Dashboard</span>
                    <!-- <span v-if="app.sidebarOpen">{{ auth  }}</span> -->
                </Link>

                <Link
                    v-if="auth.isSuperAdmin"
                    :href="route('users.index')"
                    class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors"
                    :class="{ 'bg-blue-50 text-blue-600': $page.url.startsWith('/users') }"
                >
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span v-if="app.sidebarOpen">Users</span>
                </Link>

                <Link
                    v-if="auth.isSuperAdmin || auth.isReceptionist"
                    :href="route('doctors.index')"
                    class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors"
                    :class="{ 'bg-blue-50 text-blue-600': $page.url.startsWith('/doctors') }"
                >
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <span v-if="app.sidebarOpen">Doctors</span>
                </Link>

                <Link
                    v-if="auth.isDoctor && auth.doctorId"
                    :href="route('appointments.index', auth.doctorId)"
                    class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors"
                    :class="{ 'bg-blue-50 text-blue-600': $page.url.includes('/appointments') }"
                >
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span v-if="app.sidebarOpen">My Appointments</span>
                </Link>

                <div
                    v-if="auth.isDoctor && !auth.doctorId"
                    class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-gray-400 cursor-not-allowed"
                >
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span v-if="app.sidebarOpen">No profile linked</span>
                </div>
            </nav>

            <div class="border-t border-gray-200 p-4">
                <div v-if="app.sidebarOpen" class="mb-3">
                    <p class="text-sm font-medium text-gray-800 truncate">{{ auth.user?.name }}</p>
                    <p class="text-xs text-gray-500 capitalize">{{ auth.role?.replace('_', ' ') }}</p>
                </div>
                <button
                    @click="logout"
                    class="flex items-center gap-2 w-full px-3 py-2 text-sm font-medium text-red-600 rounded-lg hover:bg-red-50 transition-colors"
                >
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span v-if="app.sidebarOpen">Logout</span>
                </button>
            </div>
        </aside>

        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-semibold text-gray-800">
                        <slot name="header">Dashboard</slot>
                    </h1>
                </div>
                <div class="flex items-center gap-2 text-sm text-gray-600">
                    <span class="px-2 py-1 bg-blue-50 text-blue-700 rounded-full text-xs font-medium capitalize">
                        {{ auth.role?.replace('_', ' ') }}
                    </span>
                    <span>{{ auth.user?.name }}</span>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-6">
                <div
                    v-if="$page.props.flash?.success"
                    class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm"
                >
                    {{ $page.props.flash.success }}
                </div>
                <div
                    v-if="$page.props.flash?.error"
                    class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm"
                >
                    {{ $page.props.flash.error }}
                </div>

                <slot />
            </main>
        </div>
    </div>
</template>
