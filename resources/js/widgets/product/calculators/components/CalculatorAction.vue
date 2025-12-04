<template>
    <div class="calculator__action" :class="{ 'calculator__action--with-result': result }">
        <div class="calculator__info">Проверьте параметры и нажмите «Рассчитать стоимость».</div>

        <div class="calculator__action-buttons">
            <!-- Update Button (Visible only when result exists) -->
            <BaseButton 
                v-if="result" 
                @click="$emit('calculate')" 
                :loading="loading" 
                variant="outline"
            >
                <span v-if="loading">Обновление...</span>
                <span v-else>Обновить расчет</span>
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
                Заказать
            </BaseButton>
        </div>
    </div>
</template>

<script setup>
import BaseButton from '@/shared/ui/Button/BaseButton.vue'

defineProps({
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
</script>
