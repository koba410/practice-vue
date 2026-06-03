<script setup>
import { useForm } from '@inertiajs/vue3';

defineProps({
    errors: {
        type: Object,
    },
});

// InertiaTestへの受け渡しのキーがtitleやcontentである。newTitleやnewContentはInertiaTestコンポーネント内だけの変数であるためここで設定するとしたら不適切である。
const form = useForm({
    title: '',
    content: '',
});

const submitFunction = () => {
    form.post(route('inertia.store'));
};
</script>

<template>
    <form @submit.prevent="submitFunction">
        <input type="text" name="title" v-model="form.title"><br>
        <div v-if="errors.title">
            <p class="text-sm text-red-600">{{ errors.title }}</p>
        </div>
        <input type="text" name="content" v-model="form.content"><br>
        <div v-if="errors.content">
            <p class="text-sm text-red-600">{{ errors.content }}</p>
        </div>
        <button>送信</button>
    </form>
</template>