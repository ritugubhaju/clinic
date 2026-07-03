<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';

const props = defineProps({
    roles: Array,
});

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: 'receptionist',
});

function submit() {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
}
</script>

<template>
    <GuestLayout>
        <h2 class="text-xl font-semibold text-gray-800 mb-6">Create an account</h2>

        <form @submit.prevent="submit" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Full name</label>
                <input
                    v-model="form.name"
                    type="text"
                    required
                    class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                    placeholder="John Doe"
                />
                <InputError :message="form.errors.name" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email address</label>
                <input
                    v-model="form.email"
                    type="email"
                    required
                    class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                    placeholder="you@example.com"
                />
                <InputError :message="form.errors.email" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                <select
                    v-model="form.role"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition bg-white"
                >
                    <option v-for="r in roles" :key="r.value" :value="r.value">{{ r.label }}</option>
                </select>
                <InputError :message="form.errors.role" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input
                    v-model="form.password"
                    type="password"
                    required
                    class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                    placeholder="Min. 8 characters"
                />
                <InputError :message="form.errors.password" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Confirm password</label>
                <input
                    v-model="form.password_confirmation"
                    type="password"
                    required
                    class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                    placeholder="••••••••"
                />
            </div>

            <button
                type="submit"
                :disabled="form.processing"
                class="w-full py-2.5 px-4 bg-blue-600 hover:bg-blue-700 disabled:opacity-60 text-white text-sm font-semibold rounded-xl transition-colors"
            >
                {{ form.processing ? 'Creating account...' : 'Create account' }}
            </button>
        </form>

        <p class="mt-5 text-center text-sm text-gray-500">
            Already have an account?
            <Link :href="route('login')" class="text-blue-600 hover:underline font-medium">Sign in</Link>
        </p>
    </GuestLayout>
</template>
