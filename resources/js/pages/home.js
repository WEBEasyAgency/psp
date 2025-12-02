import { createApp } from 'vue';

// Пример простого Vue компонента для главной страницы
const HomeApp = {
    data() {
        return {
            message: 'Vue 3 работает на Laravel!'
        }
    },
    mounted() {
        console.log('Home page Vue app mounted');
    }
};

const app = createApp(HomeApp);
app.mount('#home-app');
