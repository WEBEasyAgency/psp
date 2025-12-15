import { ref, computed } from 'vue'

const STORAGE_KEY_ITEMS = 'psp_cart_items'
const STORAGE_KEY_CALC_ID = 'psp_calc_id'

// Глобальное состояние корзины
const cartItems = ref([])
const calcId = ref(null)

/**
 * Composable для управления корзиной
 * Все данные хранятся в localStorage
 */
export function useCart() {
    // Загрузка данных из localStorage при первом использовании
    if (cartItems.value.length === 0) {
        loadFromStorage()
    }

    /**
     * Загрузить корзину из localStorage
     */
    function loadFromStorage() {
        try {
            const itemsJson = localStorage.getItem(STORAGE_KEY_ITEMS)
            const calcIdValue = localStorage.getItem(STORAGE_KEY_CALC_ID)

            if (itemsJson) {
                cartItems.value = JSON.parse(itemsJson)
            }

            if (calcIdValue) {
                calcId.value = parseInt(calcIdValue)
            }
        } catch (error) {
            console.error('Error loading cart from localStorage:', error)
            cartItems.value = []
            calcId.value = null
        }
    }

    /**
     * Сохранить корзину в localStorage
     */
    function saveToStorage() {
        try {
            localStorage.setItem(STORAGE_KEY_ITEMS, JSON.stringify(cartItems.value))

            if (calcId.value) {
                localStorage.setItem(STORAGE_KEY_CALC_ID, calcId.value.toString())
            } else {
                localStorage.removeItem(STORAGE_KEY_CALC_ID)
            }
        } catch (error) {
            console.error('Error saving cart to localStorage:', error)
        }
    }

    /**
     * Генерация уникального ID для товара на frontend
     */
    function generateItemId() {
        return 'item_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9)
    }

    /**
     * Добавить товар в корзину
     * @param {Object} item - Данные товара
     * @param {number} item.calc_position_id - ID расчёта из API
     * @param {number} item.calculator_id - ID калькулятора (146, 155, etc.)
     * @param {number} item.price_good - Цена товара
     * @param {string} item.description - Описание товара
     * @param {Array} item.params - Параметры для кнопки "Изменить"
     * @param {Array} item.mat_select_params - Параметры материалов
     * @param {string} [item.image] - Путь к изображению
     * @param {number} [item.quantity] - Количество (по умолчанию 1)
     */
    function addItem(item) {
        const newItem = {
            id: generateItemId(),
            calc_position_id: item.calc_position_id,
            calculator_id: item.calculator_id,
            price_good: item.price_good,
            quantity: item.quantity || 1,
            description: item.description || 'Товар',
            params: item.params || [],
            mat_select_params: item.mat_select_params || [],
            image: item.image || '/img/dest/cart-img.jpg'
        }

        cartItems.value.push(newItem)
        saveToStorage()

        return newItem
    }

    /**
     * Удалить товар из корзины
     * @param {string} itemId - Frontend ID товара
     */
    function removeItem(itemId) {
        const index = cartItems.value.findIndex(item => item.id === itemId)

        if (index !== -1) {
            cartItems.value.splice(index, 1)
            saveToStorage()
            return true
        }

        return false
    }

    /**
     * Обновить количество товара
     * @param {string} itemId - Frontend ID товара
     * @param {number} quantity - Новое количество
     */
    function updateQuantity(itemId, quantity) {
        const item = cartItems.value.find(item => item.id === itemId)

        if (item && quantity > 0) {
            item.quantity = quantity
            saveToStorage()
            return true
        }

        return false
    }

    /**
     * Обновить товар (для кнопки "Изменить")
     * @param {string} itemId - Frontend ID товара
     * @param {Object} updates - Новые данные товара
     */
    function updateItem(itemId, updates) {
        const item = cartItems.value.find(item => item.id === itemId)

        if (item) {
            Object.assign(item, updates)
            saveToStorage()
            return true
        }

        return false
    }

    /**
     * Получить все товары
     */
    function getItems() {
        return cartItems.value
    }

    /**
     * Найти товар по ID
     * @param {string} itemId - Frontend ID товара
     */
    function getItem(itemId) {
        return cartItems.value.find(item => item.id === itemId)
    }

    /**
     * Очистить корзину
     */
    function clearCart() {
        cartItems.value = []
        calcId.value = null
        saveToStorage()
    }

    /**
     * Получить calc_id корзины
     */
    function getCartId() {
        return calcId.value
    }

    /**
     * Сохранить calc_id корзины
     * @param {number} id - ID корзины из API
     */
    function saveCartId(id) {
        calcId.value = id
        saveToStorage()
    }

    /**
     * Computed: общее количество товаров в корзине
     */
    const cartCount = computed(() => {
        return cartItems.value.reduce((sum, item) => sum + item.quantity, 0)
    })

    /**
     * Computed: общая стоимость корзины
     */
    const cartTotal = computed(() => {
        return cartItems.value.reduce((sum, item) => sum + (item.price_good * item.quantity), 0)
    })

    /**
     * Computed: количество уникальных позиций
     */
    const itemsCount = computed(() => {
        return cartItems.value.length
    })

    return {
        // Состояние
        cartItems: computed(() => cartItems.value),
        calcId: computed(() => calcId.value),

        // Методы
        addItem,
        removeItem,
        updateQuantity,
        updateItem,
        getItems,
        getItem,
        clearCart,
        getCartId,
        saveCartId,
        loadFromStorage,

        // Computed свойства
        cartCount,
        cartTotal,
        itemsCount
    }
}
