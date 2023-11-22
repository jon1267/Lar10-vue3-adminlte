import { defineStore } from 'pinia';
import { ref } from 'vue';

export const useSettingStore = defineStore('SettingStore',() => {
    const setting = ref({
        app_name: '',
    });

    const getSetting = async () => {
        await axios.get('/api/settings')
            .then((response) => {
                setting.value = response.data;
            });
    };

    return { setting, getSetting };
});
