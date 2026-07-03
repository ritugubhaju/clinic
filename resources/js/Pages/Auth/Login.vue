<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';

defineProps({ status: String });

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

function submit() {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
}
</script>

<template>
    <GuestLayout>
        <h2 class="text-xl font-semibold text-gray-800 mb-6">Sign in to your account</h2>

        <div v-if="status" class="mb-4 px-4 py-3 bg-green-50 text-green-700 text-sm rounded-lg">
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email address</label>
                <input
                    v-model="form.email"
                    type="email"
                    autocomplete="email"
                    required
                    class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                    placeholder="you@example.com"
                />
                <InputError :message="form.errors.email" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input
                    v-model="form.password"
                    type="password"
                    autocomplete="current-password"
                    required
                    class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                    placeholder="••••••••"
                />
                <InputError :message="form.errors.password" />
            </div>

            <div class="flex items-center">
                <input
                    v-model="form.remember"
                    id="remember"
                    type="checkbox"
                    class="w-4 h-4 text-blue-600 border-gray-300 rounded"
                />
                <label for="remember" class="ml-2 text-sm text-gray-600">Remember me</label>
            </div>

            <button
                type="submit"
                :disabled="form.processing"
                class="w-full py-2.5 px-4 bg-blue-600 hover:bg-blue-700 disabled:opacity-60 text-white text-sm font-semibold rounded-xl transition-colors"
            >
                {{ form.processing ? 'Signing in...' : 'Sign in' }}
            </button>
        </form>

        <p class="mt-5 text-center text-sm text-gray-500">
            Don't have an account?
            <Link :href="route('register')" class="text-blue-600 hover:underline font-medium">Register here</Link>
        </p>
    </GuestLayout>
</template>
