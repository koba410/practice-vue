<script setup>
import { Head, Link, router } from "@inertiajs/vue3";

defineProps({
    id: String,
    blog: Object,
});

const deleteConfirm = (id) => {
    // console.log(id);
    router.delete(route("inertia.delete", { id: id }), {
        onBefore: () => confirm('削除しますか？'),
    });
};
</script>

<template>
    <Head title="Inertia Show" />
    <div>
        <h1>Inertia Show</h1>
    </div>
    <p>ID: {{ id }}</p>
    <p>件名：{{ blog.title }}</p>
    <p>本文：{{ blog.content }}</p>

    <button @click="deleteConfirm(blog.id)">削除</button>
</template>
