<template>
  <div class="cart-inner">
    <!-- Пустая корзина -->
    <div v-if="items.length === 0" class="empty-cart">
      <h2>Корзина пуста</h2>
      <p>Добавьте товары из каталога</p>
      <a href="/" class="btn">На главную</a>
    </div>

    <!-- Корзина с товарами -->
    <div v-else class="grid">
      <div class="product-list-block">
        <div class="title-block">
          <div class="cart-quantity">Товаров в корзине: {{ totalQuantity }} шт</div>
          <div class="clear-cart">
            <a href="#" @click.prevent="confirmClearCart" class="clear">
              Очистить корзину
              <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 12L8.00001 8.00001M8.00001 8.00001L4 4M8.00001 8.00001L12 4M8.00001 8.00001L4 12" stroke="#334155" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </a>
          </div>
        </div>

        <div class="product-list">
          <div v-for="item in items" :key="item.id" class="product-item">
            <div class="name-block flex">
              <div class="name">
                <a :href="`/product/${item.calculator_id}`" class="flex">
                  <div class="img"><img :src="item.image" alt=""></div>
                  <div class="text">{{ item.description }}</div>
                </a>
              </div>
              <a href="#" @click.prevent="editItem(item)" class="btn btn-change">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M9.33333 9.9987H14M2 9.9987H3.33333M3.33333 9.9987C3.33333 10.9192 4.07953 11.6654 5 11.6654C5.92047 11.6654 6.66667 10.9192 6.66667 9.9987C6.66667 9.07822 5.92047 8.33203 5 8.33203C4.07953 8.33203 3.33333 9.07822 3.33333 9.9987ZM13.3333 5.9987H14M2 5.9987H6.66667M11 7.66536C10.0795 7.66536 9.33333 6.91917 9.33333 5.9987C9.33333 5.07822 10.0795 4.33203 11 4.33203C11.9205 4.33203 12.6667 5.07822 12.6667 5.9987C12.6667 6.91917 11.9205 7.66536 11 7.66536Z" stroke="#F6F6F6" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Изменить
              </a>
              <a href="#" @click.prevent="removeItem(item.id)" class="btn btn-delete">
                Удалить
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M12 12L8.00001 8.00001M8.00001 8.00001L4 4M8.00001 8.00001L12 4M8.00001 8.00001L4 12" stroke="#3C7BBB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              </a>
            </div>

            <div class="quantity-price flex">
              <div class="number-input__wrapper">
                <button
                  type="button"
                  class="number-input__button"
                  @click="decreaseQuantity(item)"
                  :disabled="item.quantity <= 1"
                >
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6 12H18" stroke="#334155" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                  </svg>
                </button>
                <div class="number-input__field-wrapper">
                  <input
                    type="number"
                    class="number-input__field"
                    :value="item.quantity"
                    @input="updateQuantityInput(item, $event)"
                    min="1"
                  >
                </div>
                <button
                  type="button"
                  class="number-input__button"
                  @click="increaseQuantity(item)"
                >
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6 12H12M12 12H18M12 12V18M12 12V6" stroke="#334155" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                  </svg>
                </button>
              </div>
              <div class="price">
                <span class="val">{{ formatPrice(item.price_good * item.quantity) }}</span>
                <span class="currency">₽</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="btn-area">
        <div class="inner">
          <div class="full-price">
            <div class="caption">Итого</div>
            <div class="val">{{ formatPrice(totalPrice) }} ₽</div>
          </div>
          <div class="list">
            <div class="item">
              <div class="name">Кол-во товаров</div>
              <div class="val">{{ items.length }}</div>
            </div>
          </div>
          <div class="btn-block">
            <a href="#" @click.prevent="goToCheckout" class="btn">Оформить заказ</a>
            <a href="/" class="btn btn-white">Продолжить покупки</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useCart } from '@/shared/composables/useCart'

const { cartItems, updateQuantity, removeItem: removeCartItem, clearCart } = useCart()

const items = computed(() => cartItems.value)

const totalQuantity = computed(() => {
  return items.value.reduce((sum, item) => sum + item.quantity, 0)
})

const totalPrice = computed(() => {
  return items.value.reduce((sum, item) => sum + (item.price_good * item.quantity), 0)
})

function formatPrice(value) {
  if (value === null || value === undefined) {
    return '0'
  }
  return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ')
}

function increaseQuantity(item) {
  updateQuantity(item.id, item.quantity + 1)
}

function decreaseQuantity(item) {
  if (item.quantity > 1) {
    updateQuantity(item.id, item.quantity - 1)
  }
}

function updateQuantityInput(item, event) {
  const value = parseInt(event.target.value)
  if (value > 0) {
    updateQuantity(item.id, value)
  } else {
    event.target.value = item.quantity
  }
}

function removeItem(itemId) {
  if (confirm('Удалить товар из корзины?')) {
    removeCartItem(itemId)
  }
}

function confirmClearCart() {
  if (confirm('Очистить всю корзину?')) {
    clearCart()
  }
}

function editItem(item) {
  // TODO: После ответа клиента на вопрос 3
  // Пока просто переход на страницу калькулятора
  window.location.href = `/product/${item.calculator_id}`
}

function goToCheckout() {
  // TODO: После ответа клиента на вопросы 2, 4, 6
  // Пока просто переход на order с флагом
  window.location.href = '/order?from_cart=1'
}
</script>

<style scoped>
.empty-cart {
  text-align: center;
  padding: 80px 20px;
}

.empty-cart h2 {
  font-size: 32px;
  margin-bottom: 16px;
  color: #334155;
}

.empty-cart p {
  font-size: 18px;
  color: #64748b;
  margin-bottom: 32px;
}

.empty-cart .btn {
  display: inline-block;
  padding: 12px 32px;
}
</style>
