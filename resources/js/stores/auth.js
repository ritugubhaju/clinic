import { defineStore } from 'pinia';
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

export const useAuthStore = defineStore('auth', () => {
    const page = usePage();

    const user = computed(() => page.props.auth?.user ?? null);
    const role = computed(() => user.value?.role ?? null);
    const permissions = computed(() => user.value?.permissions ?? []);
    const doctorId = computed(() => user.value?.doctor_id ?? null);

    const isSuperAdmin = computed(() => role.value === 'super_admin');
    const isReceptionist = computed(() => role.value === 'receptionist');
    const isDoctor = computed(() => role.value === 'doctor');

    function hasPermission(permission) {
        return permissions.value.includes(permission);
    }

    return {
        user,
        role,
        permissions,
        doctorId,
        isSuperAdmin,
        isReceptionist,
        isDoctor,
        hasPermission,
    };
});
