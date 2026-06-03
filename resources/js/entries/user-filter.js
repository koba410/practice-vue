import { createApp } from 'vue';
import UserFilter from '../components/UserFilter.vue';

const el = document.getElementById('user-filter-app');

if (el) {
    createApp(UserFilter, {
        users: JSON.parse(el.dataset.users),
    }).mount(el);
}
