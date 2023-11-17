<script setup>
import { onMounted, ref } from 'vue';
import AppNavbar from './components/AppNavbar.vue';
import SidebarLeft from './components/SidebarLeft.vue';
import SidebarRight from './components/SidebarRight.vue';
import AppFooter from './components/AppFooter.vue';
import { useAuthUserStore } from './stores/AuthUserStore.js';

const authUserStore = useAuthUserStore();
authUserStore.getAuthUser();

const settings = ref(null);
const fetchSettings = () => {
    axios.get('/api/settings')
        .then((response) => {
            settings.value = response.data;
        });
};

const user = ref(null);

const fetchAuthUser = () => {
    axios.get('/api/profile')
        .then((response) => {
            user.value = response.data;
        });
};

const logout = () => {
    axios.post('/logout')
        .then((response) => {
            window.location.href = '/';
            //window.location.href = '/login';
        });
};

onMounted(() => {
    fetchSettings();
    fetchAuthUser();
});
</script>

<template>

    <div id="app" class="wrapper">

        <AppNavbar />

        <SidebarLeft :user="user" :settings="settings" />

        <div class="content-wrapper">
            <router-view/>
        </div>

        <SidebarRight />

        <AppFooter :settings="settings" />

    </div>

</template>
