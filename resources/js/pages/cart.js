import { createApp } from 'vue'
import Cart from '@/widgets/cart/Cart.vue'

// Создаём Vue приложение для корзины
const app = createApp(Cart)

// Монтируем в DOM
app.mount('#cart-app')
