<template>
    <!-- FAQ (moc3) -->
    <div v-if="items && items.length" class="faq__section">
        <div class="faq__container">
            <div class="faq__card">
                <div class="faq__column-left">
                    <div class="faq__header">
                        <div class="faq__badge">

                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M14 15C14 15.5523 14.4477 16 15 16C15.5523 16 16 15.5523 16 15C16 14.4477 15.5523 14 15 14C14.4477 14 14 14.4477 14 15Z"
                                    stroke="#3C7BBB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path
                                    d="M8 15C8 15.5523 8.44772 16 9 16C9.55228 16 10 15.5523 10 15C10 14.4477 9.55228 14 9 14C8.44772 14 8 14.4477 8 15Z"
                                    stroke="#3C7BBB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path
                                    d="M14 9C14 9.55228 14.4477 10 15 10C15.5523 10 16 9.55228 16 9C16 8.44772 15.5523 8 15 8C14.4477 8 14 8.44772 14 9Z"
                                    stroke="#3C7BBB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path
                                    d="M8 9C8 9.55228 8.44772 10 9 10C9.55228 10 10 9.55228 10 9C10 8.44772 9.55228 8 9 8C8.44772 8 8 8.44772 8 9Z"
                                    stroke="#3C7BBB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>

                            <div class="faq__badge-text">Вопрос-ответ</div>
                        </div>
                        <div class="faq__title-wrapper"><span class="faq__title-primary">Ответы на </span><span
                            class="faq__title-secondary">часто задаваемые вопросы</span></div>
                    </div>
                    <div class="faq__description">Мы собрали самые популярные вопросы и подготовили ответы на них. Если
                        у вас есть дополнительные вопросы, напишите нам.
                    </div>
                </div>
                <div class="faq__list">
                    <div v-for="(item, index) in items" :key="index" class="faq__item"
                         :class="{ 'is-open': openItems[index] }" @click="toggle(index)">
                        <div class="faq__summary">
                            <div class="faq__icon">
                                <div data-svg-wrapper class="relative">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path d="M16 10L12 14L8 10" stroke="#334155" stroke-width="1.5"
                                              stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="faq__question">{{ item.question }}</div>
                        </div>
                        <div class="faq__answer-wrapper">
                            <div class="faq__answer-inner">
                                <div class="faq__answer-container">
                                    <div class="faq__answer-text">{{ item.answer }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'Faq',
    props: {
        items: {
            type: Array,
            default: () => []
        }
    },
    data() {
        return {
            openItems: []
        }
    },
    watch: {
        items: {
            immediate: true,
            handler(newVal) {
                if (newVal) {
                    this.openItems = new Array(newVal.length).fill(false);
                }
            }
        }
    },
    methods: {
        toggle(index) {
            this.openItems[index] = !this.openItems[index];
        }
    }
}
</script>

<style scoped>
.faq__item {
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.faq__item:hover {
    background-color: #f0f5fa; /* A light blue color */
}

.faq__item.is-open .faq__icon, .faq__item.is-open:hover .faq__icon svg, .faq__item.is-open:hover .faq__icon {
    background-color: #244D80 !important;
}
</style>
