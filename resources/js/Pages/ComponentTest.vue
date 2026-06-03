<script setup>
import { ref } from "vue";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";

// v-model でつなぐ「箱」。最初の文字列が input の初期表示になる
const title = ref("初期値");

// 子（TextInput）が値を変えたときに飛ぶイベントを受け取る（教材の emit 確認用）
const emitTest = (e) => console.log(e);
</script>

<template>
    <GuestLayout>
        <InputLabel value="タイトル" />

        <!-- v-model の左と右は同じ ref 名にする（Login.vue の form.email と同じ仕組み） -->
        <TextInput
            v-model="title"
            class="mt-1 block w-full"
            @update:modelValue="emitTest"
        />

        <!-- 入力するとこの表示も変わる → つながっている証拠 -->
        <p class="mt-4 text-sm text-gray-600">いまの値: {{ title }}</p>
    </GuestLayout>
</template>
