<script setup>
import { computed, ref } from 'vue';

const props = defineProps({
    users: {
        type: Array,
        required: true,
    },
});

const query = ref('');

const filteredUsers = computed(() => {
    const q = query.value.trim().toLowerCase();
    if (!q) {
        return props.users;
    }

    return props.users.filter((user) =>
        user.name.toLowerCase().includes(q) ||
        user.email.toLowerCase().includes(q)
    );
});
</script>

<template>
    <section class="border border-gray-200 rounded-lg p-4 bg-white">
        <h2 class="text-lg font-medium mb-3">Vue 検索フィルタ</h2>
        <input
            v-model="query"
            type="search"
            placeholder="名前またはメールで検索..."
            class="w-full border border-gray-300 rounded px-3 py-2 mb-4 text-sm"
        />
        <p class="text-sm text-gray-600 mb-2">{{ filteredUsers.length }} 件表示</p>
        <ul class="divide-y divide-gray-100">
            <li
                v-for="user in filteredUsers"
                :key="user.id"
                class="py-2 text-sm flex justify-between gap-4"
            >
                <span class="font-medium">{{ user.name }}</span>
                <span class="text-gray-500">{{ user.email }}</span>
            </li>
        </ul>
    </section>
</template>
