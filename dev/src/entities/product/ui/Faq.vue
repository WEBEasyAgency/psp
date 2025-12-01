<template>
  <!-- FAQ (moc3) -->
    <div class="mb-12">
        <div class="w-full max-w-[1400px] mx-auto px-4 lg:px-0">
            <div class="self-stretch flex flex-col lg:flex-row lg:justify-between lg:items-start gap-8 lg:gap-16 py-4 lg:py-16 px-4 lg:px-16 bg-white rounded-3xl lg:rounded-[32px]">
                <div class="flex flex-col justify-start items-start gap-5 lg:w-[520px]">
                    <div class="self-stretch flex flex-col justify-start items-start gap-4">
                        <div class="px-2 py-1 bg-[#e6eef8] rounded-xl inline-flex justify-center items-center gap-1">

                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M14 15C14 15.5523 14.4477 16 15 16C15.5523 16 16 15.5523 16 15C16 14.4477 15.5523 14 15 14C14.4477 14 14 14.4477 14 15Z" stroke="#3C7BBB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M8 15C8 15.5523 8.44772 16 9 16C9.55228 16 10 15.5523 10 15C10 14.4477 9.55228 14 9 14C8.44772 14 8 14.4477 8 15Z" stroke="#3C7BBB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M14 9C14 9.55228 14.4477 10 15 10C15.5523 10 16 9.55228 16 9C16 8.44772 15.5523 8 15 8C14.4477 8 14 8.44772 14 9Z" stroke="#3C7BBB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M8 9C8 9.55228 8.44772 10 9 10C9.55228 10 10 9.55228 10 9C10 8.44772 9.55228 8 9 8C8.44772 8 8 8.44772 8 9Z" stroke="#3C7BBB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>

                            <div class="justify-start text-[#1e3552] text-xs font-normal font-['Inter'] leading-[14.40px]">Вопрос-ответ</div>
                        </div>
                        <div class="self-stretch justify-start"><span class="text-[#1e3552] text-2xl lg:text-5xl font-medium font-['Inter'] leading-6 lg:leading-[48px]">Ответы на </span><span class="text-[#2c619d] text-2xl lg:text-5xl font-medium font-['Inter'] leading-6 lg:leading-[48px]">часто задаваемые вопросы</span></div>
                    </div>
                    <div class="self-stretch justify-start text-slate-500 text-base lg:text-lg font-normal font-['Inter'] leading-[22.40px] lg:leading-[25.20px] line-clamp-4">Мы собрали самые популярные вопросы и подготовили ответы на них. Если у вас есть дополнительные вопросы, напишите нам.</div>
                </div>
                <div class="self-stretch flex flex-col justify-start items-start gap-4 lg:w-[630px]">
                    <!-- FAQ Item 1 -->
                    <details class="faq-item self-stretch px-4 lg:px-8 py-4 lg:py-6 rounded-3xl bg-white outline outline-1 outline-offset-[-1px] outline-slate-200 inline-flex justify-start items-center gap-4">
                        <summary class="self-stretch inline-flex justify-start items-center gap-4 cursor-pointer list-none">
                            <div class="faq-icon size-9 bg-[#f6f6f6] pt-1 rounded-xl flex justify-center items-center gap-2.5 transition-colors flex-shrink-0">
                                <div data-svg-wrapper class="relative">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M16 10L12 14L8 10" stroke="#334155" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1 justify-start text-[#170f49] text-base lg:text-lg font-medium font-['Inter'] leading-[22.40px] lg:leading-[25.20px]">Как долго изготавливаются объемные буквы?</div>
                        </summary>
                        <div class="faq-answer self-stretch pl-[52px] inline-flex justify-start items-start gap-2.5">
                            <div class="flex-1 justify-start text-slate-900 text-sm lg:text-base font-normal font-['Inter'] leading-5 lg:leading-[22.40px]">Срок изготовления составляет от 5 до 10 рабочих дней в зависимости от сложности и объема заказа.</div>
                        </div>
                    </details>

                    <!-- FAQ Item 2 -->
                    <details class="faq-item self-stretch px-4 lg:px-8 py-4 lg:py-6 rounded-3xl bg-white outline outline-1 outline-offset-[-1px] outline-slate-200 inline-flex justify-start items-center gap-4">
                        <summary class="self-stretch inline-flex justify-start items-center gap-4 cursor-pointer list-none">
                            <div class="faq-icon size-9 bg-[#f6f6f6] pt-1 rounded-xl flex justify-center items-center gap-2.5 transition-colors flex-shrink-0">
                                <div data-svg-wrapper class="relative">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M16 10L12 14L8 10" stroke="#334155" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1 justify-start text-[#170f49] text-base lg:text-lg font-medium font-['Inter'] leading-[22.40px] lg:leading-[25.20px]">Можно ли установить буквы самостоятельно?</div>
                        </summary>
                        <div class="faq-answer self-stretch pl-[52px] inline-flex justify-start items-start gap-2.5">
                            <div class="flex-1 justify-start text-slate-900 text-sm lg:text-base font-normal font-['Inter'] leading-5 lg:leading-[22.40px]">Да, мы предоставляем подробную инструкцию по монтажу. Также можем предложить услуги профессиональных монтажников.</div>
                        </div>
                    </details>

                    <!-- FAQ Item 3 -->
                    <details class="faq-item self-stretch px-4 lg:px-8 py-4 lg:py-6 rounded-3xl bg-white outline outline-1 outline-offset-[-1px] outline-slate-200 inline-flex justify-start items-center gap-4">
                        <summary class="self-stretch inline-flex justify-start items-center gap-4 cursor-pointer list-none">
                            <div class="faq-icon size-9 bg-[#f6f6f6] pt-1 rounded-xl flex justify-center items-center gap-2.5 transition-colors flex-shrink-0">
                                <div data-svg-wrapper class="relative">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M16 10L12 14L8 10" stroke="#334155" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1 justify-start text-[#170f49] text-base lg:text-lg font-medium font-['Inter'] leading-[22.40px] lg:leading-[25.20px]">Какая гарантия на изделие?</div>
                        </summary>
                        <div class="faq-answer self-stretch pl-[52px] inline-flex justify-start items-start gap-2.5">
                            <div class="flex-1 justify-start text-slate-900 text-sm lg:text-base font-normal font-['Inter'] leading-5 lg:leading-[22.40px]">Гарантия на объемные буквы составляет 12 месяцев с момента установки.</div>
                        </div>
                    </details>

                    <!-- FAQ Item 4 -->
                    <details class="faq-item self-stretch px-4 lg:px-8 py-4 lg:py-6 rounded-3xl bg-white outline outline-1 outline-offset-[-1px] outline-slate-200 inline-flex justify-start items-center gap-4">
                        <summary class="self-stretch inline-flex justify-start items-center gap-4 cursor-pointer list-none">
                            <div class="faq-icon size-9 bg-[#f6f6f6] pt-1 rounded-xl flex justify-center items-center gap-2.5 transition-colors flex-shrink-0">
                                <div data-svg-wrapper class="relative">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M16 10L12 14L8 10" stroke="#334155" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1 justify-start text-[#170f49] text-base lg:text-lg font-medium font-['Inter'] leading-[22.40px] lg:leading-[25.20px]">Какие материалы используются для изготовления?</div>
                        </summary>
                        <div class="faq-answer self-stretch pl-[52px] inline-flex justify-start items-start gap-2.5">
                            <div class="flex-1 justify-start text-slate-900 text-sm lg:text-base font-normal font-['Inter'] leading-5 lg:leading-[22.40px]">Используем только высококачественные материалы: акриловое стекло для лицевой части, алюминий для борта и ПВХ пластик для задней части.</div>
                        </div>
                    </details>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
  name: 'Faq'
}
</script>

<style scoped>
/* Стили можно добавить здесь, если они специфичны для этого компонента */
</style>