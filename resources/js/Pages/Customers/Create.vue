<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { useForm } from "@inertiajs/vue3";
import { Core as YubinbangoCore } from "yubinbango-core2";

defineProps({
    errors: {
        type: Object,
    },
});

// InertiaTestへの受け渡しのキーがtitleやcontentである。newTitleやnewContentはInertiaTestコンポーネント内だけの変数であるためここで設定するとしたら不適切である。
const form = useForm({
    name: null,
    kana: null,
    tel: null,
    email: null,
    postcode: null,
    address: null,
    birthday: null,
    gender: null,
    memo: null,
});

const fetchAddress = () => {
    new YubinbangoCore(String(form.postcode), (value) => {
        form.address = value.region + value.locality + value.street;
    });
};

const storeCustomer = () => {
    form.post(route("customers.store"));
};
</script>

<template>
    <Head title="顧客登録" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">顧客登録</h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <section class="text-gray-600 body-font relative">
                            <form @submit.prevent="storeCustomer">
                                <div class="container px-5 py-8 mx-auto">
                                    <div class="lg:w-1/2 md:w-2/3 mx-auto">
                                        <div class="flex flex-wrap -m-2">
                                            <div class="p-2 w-full">
                                                <div class="relative">
                                                    <label for="name" class="leading-7 text-sm text-gray-600">顧客名</label>
                                                    <input
                                                        type="text"
                                                        id="name"
                                                        name="name"
                                                        v-model="form.name"
                                                        class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out" />
                                                </div>
                                                <div v-if="errors.name">
                                                    <p class="text-sm text-red-600">
                                                        {{ errors.name }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="p-2 w-full">
                                                <div class="relative">
                                                    <label for="kana" class="leading-7 text-sm text-gray-600">顧客名（カナ）</label>
                                                    <input
                                                        type="text"
                                                        id="kana"
                                                        name="kana"
                                                        v-model="form.kana"
                                                        class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out" />
                                                </div>
                                                <div v-if="errors.kana">
                                                    <p class="text-sm text-red-600">
                                                        {{ errors.kana }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="p-2 w-full">
                                                <div class="relative">
                                                    <label for="tel" class="leading-7 text-sm text-gray-600">電話番号</label>
                                                    <input
                                                        type="text"
                                                        id="tel"
                                                        name="tel"
                                                        v-model="form.tel"
                                                        class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out" />
                                                </div>
                                                <div v-if="errors.tel">
                                                    <p class="text-sm text-red-600">
                                                        {{ errors.tel }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="p-2 w-full">
                                                <div class="relative">
                                                    <label for="email" class="leading-7 text-sm text-gray-600">メールアドレス</label>
                                                    <input
                                                        type="text"
                                                        id="email"
                                                        name="email"
                                                        v-model="form.email"
                                                        class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out" />
                                                </div>
                                                <div v-if="errors.email">
                                                    <p class="text-sm text-red-600">
                                                        {{ errors.email }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="p-2 w-full">
                                                <div class="relative">
                                                    <label for="postcode" class="leading-7 text-sm text-gray-600">郵便番号</label>
                                                    <input
                                                        @input="fetchAddress"
                                                        type="text"
                                                        id="postcode"
                                                        name="postcode"
                                                        v-model="form.postcode"
                                                        class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out" />
                                                </div>
                                                <div v-if="errors.postcode">
                                                    <p class="text-sm text-red-600">
                                                        {{ errors.postcode }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="p-2 w-full">
                                                <div class="relative">
                                                    <label for="address" class="leading-7 text-sm text-gray-600">住所</label>
                                                    <input
                                                        type="text"
                                                        id="address"
                                                        name="address"
                                                        v-model="form.address"
                                                        class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out" />
                                                </div>
                                                <div v-if="errors.address">
                                                    <p class="text-sm text-red-600">
                                                        {{ errors.address }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="p-2 w-full">
                                                <div class="relative">
                                                    <label for="birthday" class="leading-7 text-sm text-gray-600">誕生日</label>
                                                    <input
                                                        type="date"
                                                        id="birthday"
                                                        name="birthday"
                                                        v-model="form.birthday"
                                                        class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out" />
                                                </div>
                                                <div v-if="errors.birthday">
                                                    <p class="text-sm text-red-600">
                                                        {{ errors.birthday }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="p-2 w-full">
                                                <div class="relative">
                                                    <label for="gender" class="leading-7 text-sm text-gray-600">性別</label>
                                                    <input type="radio" id="gender_male" name="gender" v-model="form.gender" value="0" />
                                                    <label for="gender_male" class="ml-2 mr-4">男性</label>
                                                    <input type="radio" id="gender_female" name="gender" v-model="form.gender" value="1" />
                                                    <label for="gender_female" class="ml-2 mr-4">女性</label>
                                                    <input type="radio" id="gender_other" name="gender" v-model="form.gender" value="2" />
                                                    <label for="gender_other" class="ml-2 mr-4">その他</label>
                                                </div>
                                                <div v-if="errors.gender">
                                                    <p class="text-sm text-red-600">
                                                        {{ errors.gender }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="p-2 w-full">
                                                <div class="relative">
                                                    <label for="memo" class="leading-7 text-sm text-gray-600">メモ</label>
                                                    <textarea
                                                        id="memo"
                                                        name="memo"
                                                        v-model="form.memo"
                                                        class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 h-32 text-base outline-none text-gray-700 py-1 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out"></textarea>
                                                </div>
                                                <div v-if="errors.memo">
                                                    <p class="text-sm text-red-600">
                                                        {{ errors.memo }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="p-2 w-full">
                                                <button class="flex mx-auto text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">顧客登録</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
