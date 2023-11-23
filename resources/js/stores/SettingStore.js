import { defineStore } from 'pinia';
import { ref } from 'vue';
import { useStorage } from '@vueuse/core/index';

export const useSettingStore = defineStore('SettingStore',() => {
    const setting = ref({
        app_name: '',
    });

    const theme = useStorage('SettingStore:theme', ref('light'));//ref('light');

    const changeTheme = () => {
        theme.value = (theme.value === 'light') ? 'dark' : 'light';
    };

    const getSetting = async () => {
        await axios.get('/api/settings')
            .then((response) => {
                setting.value = response.data;
            });
    };

    return { setting, getSetting, theme, changeTheme };
});
