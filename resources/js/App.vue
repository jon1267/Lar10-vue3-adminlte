<script setup>
import {computed, onMounted, ref} from 'vue';
import AppNavbar from './components/AppNavbar.vue';
import SidebarLeft from './components/SidebarLeft.vue';
import SidebarRight from './components/SidebarRight.vue';
import AppFooter from './components/AppFooter.vue';
import { useAuthUserStore } from './stores/AuthUserStore.js';
import { useSettingStore } from "./stores/SettingStore.js";

const authUserStore = useAuthUserStore();
const settingStore = useSettingStore();
//authUserStore.getAuthUser();

const currentThemeMode = computed(() => {
    return (settingStore.theme === 'dark') ? 'dark-mode' : '';
});

// this remove to SettingStore.js (pinia store)
//const settings = ref(null);
//const fetchSettings = () => {
//    axios.get('/api/settings')
//        .then((response) => {
//            settings.value = response.data;
//        });
//};

// this remove to AuthUserStore.js (pinia store)
//const user = ref(null);
//
//const fetchAuthUser = () => {
//    axios.get('/api/profile')
//        .then((response) => {
//            user.value = response.data;
//        });
//};

/* removed to SidebarLeft
const logout = () => {
    axios.post('/logout')
        .then((response) => {
            window.location.href = '/';
            //window.location.href = '/login';
        });
};*/

// !need, all remove to pinia store
//onMounted(() => {
//    fetchSettings();
//    fetchAuthUser();
//});
</script>

<template>

    <div v-if="authUserStore.user.name !== ''" id="app" class="wrapper" :class="currentThemeMode">

        <AppNavbar />

        <!--<SidebarLeft :user="user" :settings="settings" />-->
        <SidebarLeft />

        <div class="content-wrapper">
            <router-view/>
        </div>

        <SidebarRight />

        <!--<AppFooter :settings="settings" />-->
        <AppFooter />

    </div>
    <div v-else class="login-page" :class="currentThemeMode">
        <router-view/>
    </div>

</template>
