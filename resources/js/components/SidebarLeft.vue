<script setup>
import { useAuthUserStore } from '../stores/AuthUserStore.js';
import { useRouter } from 'vue-router';
import { useSettingStore } from '../stores/SettingStore';

const router = useRouter();

const authUserStore = useAuthUserStore();
const settingStore = useSettingStore();

// !need... all remove to pinia storage
//defineProps({
//    user: Object,
//    settings: Object,
//});

const logout = () => {
    axios.post('/logout')
        .then((response) => {
            authUserStore.user.name = '';
            router.push('/login'); // router.push('/') - not work;
            //window.location.href = '/'; //window.location.href = '/login';
        });
};
</script>

<template>
    <aside class="main-sidebar sidebar-dark-primary elevation-4">

        <a href="index3.html" class="brand-link">
            <img src="https://adminlte.io/themes/v3/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
            <!--<span class="brand-text font-weight-light">{{ settings?.app_name }}</span>-->
            <span class="brand-text font-weight-light">{{ settingStore.setting.app_name }}</span>
        </a>

        <div class="sidebar">

            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <!--<img :src="user?.avatar" class="img-circle elevation-2" alt="User Image">-->
                    <img :src="authUserStore.user.avatar" class="img-circle elevation-2" alt="User Image">
                </div>
                <div class="info">
                    <a href="#" class="d-block">{{ authUserStore.user.name }}</a>
                </div>
            </div>

            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                    <li class="nav-item">
                        <router-link to="/admin/dashboard" active-class="active" class="nav-link">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Dashboard
                            </p>
                        </router-link>
                    </li>

                    <li class="nav-item">
                        <router-link to="/admin/appointments" active-class="active" :class="$route.path.startsWith('/admin/appointments') ? 'active':''" class="nav-link">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p>
                                Appointments
                            </p>
                        </router-link>
                    </li>

                    <li class="nav-item">
                        <router-link to="/admin/users" active-class="active" class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                Users
                            </p>
                        </router-link>
                    </li>

                    <li class="nav-item">
                        <router-link to="/admin/settings" active-class="active" class="nav-link">
                            <i class="nav-icon fas fa-cog"></i>
                            <p>
                                Settings
                            </p>
                        </router-link>
                    </li>

                    <li class="nav-item">
                        <router-link to="/admin/profile" active-class="active" class="nav-link">
                            <i class="nav-icon fas fa-user-cog"></i>
                            <p>
                                Profile
                            </p>
                        </router-link>
                    </li>

                    <li class="nav-item">
                        <!--<form method="POST" action="{{ route('logout') }}" class="nav-link">-->
                        <form  class="nav-link">
                            <!-- @csrf comment when code from app.blade ported to App.vue (& form above & link below)-->
                            <!--<a href="#" onclick="event.preventDefault(); this.closest('form').submit();"> -->
                            <a href="#" @click.prevent="logout" >
                                <i class="nav-icon fas fa-sign-out-alt"></i>
                                <p>
                                    Logout
                                </p>
                            </a>
                        </form>
                    </li>

                </ul>
            </nav>

        </div>

    </aside>
</template>

