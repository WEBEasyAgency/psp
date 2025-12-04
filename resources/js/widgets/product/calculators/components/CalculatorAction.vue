<template>
    <div class="calculator__action" :class="{ 'calculator__action--with-result': result }">
        <div class="calculator__info">Убедитесь, что все фильтры выбраны верно и нажмите «Рассчитать стоимость». При
            каждом изменении, нажимайте «Обновить расчет».
        </div>

        <div class="calculator__action-buttons">
            <!-- Update Button (Visible only when result exists) -->
            <BaseButton
                v-if="result"
                @click="$emit('calculate')"
                :loading="loading"
                variant="outline"
            >
                <span v-if="loading">Обновление...</span>

                <div class="inline-flex gap-1 w-full items-center justify-center" v-else>


                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M10 16H5V21M14 8H19V3M4.58301 9.0034C5.14369 7.61566 6.08244 6.41304 7.29255 5.53223C8.50266 4.65141 9.93686 4.12752 11.4298 4.02051C12.9227 3.9135 14.4147 4.2274 15.7381 4.92661C17.0615 5.62582 18.1612 6.68254 18.9141 7.97612M19.4176 14.9971C18.8569 16.3848 17.9181 17.5874 16.708 18.4682C15.4979 19.3491 14.0652 19.8723 12.5723 19.9793C11.0794 20.0863 9.58606 19.7725 8.2627 19.0732C6.93933 18.374 5.83882 17.3175 5.08594 16.0239"
                            stroke="#2C619D" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>


                    <span class="">
                        Обновить расчет
                    </span>
                </div>
            </BaseButton>

            <!-- Result Box -->
            <div v-if="result" class="result-box">
                <div class="result-row">
                    <div class="result-label">Итого:</div>
                    <div class="result-value">{{ result.price_good }} ₽</div>
                </div>
            </div>

            <!-- Calculate Button (Visible only when NO result) -->
            <BaseButton
                v-if="!result"
                @click="$emit('calculate')"
                :loading="loading"
            >
                <span v-if="loading">Расчёт...</span>
                <span v-else>Рассчитать стоимость</span>
            </BaseButton>

            <!-- Order Button -->
            <BaseButton
                v-if="result"
                :href="orderLink"
            >
                <div class="inline-flex justify-center items-center gap-2">
                    <div>
                        Оформить сейчас
                    </div>


                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7 12H17M17 12L13 8M17 12L13 16" stroke="#F6F6F6" stroke-width="1.5"
                              stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>

                </div>

            </BaseButton>
            <!-- Add to Cart Button -->
            <BaseButton 
                v-if="result && !isAddedToCart" 
                variant="filled"
                @click="addToCart"
            >
                <!-- SVG icon -->
                <slot name="icon-left">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3 3H3.26835C3.74213 3 3.97943 3 4.17267 3.08548C4.34304 3.16084 4.48871 3.28218 4.59375 3.43604C4.71269 3.61026 4.75564 3.8429 4.84137 4.30727L7.00004 16L17.4218 16C17.875 16 18.1023 16 18.29 15.9199C18.4559 15.8492 18.5989 15.7346 18.7051 15.5889C18.8252 15.4242 18.8761 15.2037 18.9777 14.7631L18.9785 14.76L20.5477 7.95996L20.5481 7.95854C20.7023 7.29016 20.7796 6.95515 20.6947 6.69238C20.6202 6.46182 20.4635 6.26634 20.2556 6.14192C20.0184 6 19.6758 6 18.9887 6H5.5M18 21C17.4477 21 17 20.5523 17 20C17 19.4477 17.4477 19 18 19C18.5523 19 19 19.4477 19 20C19 20.5523 18.5523 21 18 21ZM8 21C7.44772 21 7 20.5523 7 20C7 19.4477 7.44772 19 8 19C8.55228 19 9 19.4477 9 20C9 20.5523 8.55228 21 8 21Z" stroke="#334155" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </slot>
                Добавить в корзину
            </BaseButton>

            <!-- Go to Cart Button (Success) -->
            <BaseButton 
                v-if="result && isAddedToCart" 
                variant="success"
                @click="goToCart"
            >
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6 12.0005L10.2426 16.2431L18.727 7.75781" stroke="#F6F6F6" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Перейти в корзину
            </BaseButton>
        </div>
    </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import BaseButton from '@/shared/ui/Button/BaseButton.vue'

const props = defineProps({
    result: {
        type: Object,
        default: null
    },
    loading: {
        type: Boolean,
        default: false
    },
    orderLink: {
        type: String,
        default: '#'
    }
})

defineEmits(['calculate'])

const isAddedToCart = ref(false)

const addToCart = () => {
    console.log('Added to cart')
    isAddedToCart.value = true
}

const goToCart = () => {
    console.log('Go to cart clicked')
    // Тут будет логика перехода, пока просто лог
}

// Сбрасываем состояние кнопки при изменении результата расчета
watch(() => props.result, () => {
    isAddedToCart.value = false
})
</script>
