<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { ref } from "vue";

const newTitle = ref("");
const newContent = ref("");
</script>

<template>
    <Head title="Inertia Test" />
    <div>
        <h1>Inertia Test</h1>
    </div>
    <a href="/">aタグです。</a><br />
    <Link href="/">linkタグです。</Link><br /><br />

    <Link :href="route('inertia.index')">名前付きルートの確認です。</Link><br />

    <Link :href="route('inertia.show', { id: 89 })">
        ルートパラメータのテストです。
    </Link>

    <div class="mb-8"></div>
    <!-- v-model内のnewTitleやnewContentはvueコンポーネント内だけの変数であり、この中には値が格納されるためコントローラへ受け渡す際はキーではない。 -->
    <input type="text" name="newTitle" v-model="newTitle" /><br />
    <input type="text" name="newContent" v-model="newContent" /><br />
    <!-- :dataはtitleやcontentがコントローラへの受け渡しのキーになる。 -->
    <Link
        as="button"
        method="post"
        :href="route('inertia.store')"
        :data="{ title: newTitle, content: newContent }"
        >DB保存テスト
    </Link>
</template>
