<script setup>
defineProps({
    pagination: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(['change']);
</script>

<template>
    <div class="flex items-center justify-between px-1 py-3">
        <p class="text-sm text-gray-500">
            Showing
            <span class="font-medium text-gray-700">
                {{ (pagination.current_page - 1) * pagination.per_page + 1 }}
            </span>
            to
            <span class="font-medium text-gray-700">
                {{ Math.min(pagination.current_page * pagination.per_page, pagination.total) }}
            </span>
            of
            <span class="font-medium text-gray-700">{{ pagination.total }}</span>
            results
        </p>

        <div class="flex items-center gap-1">
            <button
                :disabled="pagination.current_page <= 1"
                @click="emit('change', pagination.current_page - 1)"
                class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed transition-colors"
            >
                Previous
            </button>

            <template v-for="page in pagination.last_page" :key="page">
                <button
                    v-if="Math.abs(page - pagination.current_page) <= 2 || page === 1 || page === pagination.last_page"
                    @click="emit('change', page)"
                    :class="page === pagination.current_page
                        ? 'bg-blue-600 text-white border-blue-600'
                        : 'border-gray-200 text-gray-600 hover:bg-gray-50'"
                    class="w-8 h-8 text-sm rounded-lg border transition-colors"
                >
                    {{ page }}
                </button>
                <span
                    v-else-if="Math.abs(page - pagination.current_page) === 3"
                    class="px-1 text-gray-400"
                >
                    …
                </span>
            </template>

            <button
                :disabled="pagination.current_page >= pagination.last_page"
                @click="emit('change', pagination.current_page + 1)"
                class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed transition-colors"
            >
                Next
            </button>
        </div>
    </div>
</template>
