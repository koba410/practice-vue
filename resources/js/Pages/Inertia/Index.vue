<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    blogs: {
        type: Array,
    },
});
</script>

<template>
    <Head title="Inertia Index" />
    <div>
        <h1>Inertia Index</h1>
        <p>
            名前付きルート <code>inertia.index</code>（URL:
            <code>/inertia/index</code>）のページです。
        </p>
        <Link href="/inertia-test">Inertia Test へ戻る</Link>
    </div>
    <div v-if="$page.props.flash.message">
        <p class="text-sm text-green-600">{{ $page.props.flash.message }}</p>
    </div>
    <ul v-for="blog in blogs" :key="blog.id">
        <li><Link class="text-blue-500" :href="route('inertia.show', { id: blog.id })">件名：{{ blog.title }}本文：{{ blog.content }}</Link></li>
    </ul>
</template>