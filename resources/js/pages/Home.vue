<template>
  <div class="main-page">
    <Header @toggle-burger="handleBurgerToggle" />
    <PopupMenu v-if="isBurgerOpen" @close="handleBurgerToggle" />

    <main>
      <HeroSection />
      <ConstructionTypeSlider />
      <ServicesAdvantages />
      <OrderInstruction />
      <CasesBlock />
      <FeedbackSlider />
      <ClientsSlider />
    </main>

    <Footer />
  </div>
</template>

<script setup>
import { ref } from 'vue'
import Header from '@/entities/product/ui/Header.vue'
import Footer from '@/entities/product/ui/Footer.vue'
import PopupMenu from '@/entities/product/ui/PopupMenu.vue'
import HeroSection from '@/shared/ui/HeroSection.vue'
import ConstructionTypeSlider from '@/shared/ui/ConstructionTypeSlider.vue'
import ServicesAdvantages from '@/shared/ui/ServicesAdvantages.vue'
import OrderInstruction from '@/shared/ui/OrderInstruction.vue'
import CasesBlock from '@/shared/ui/CasesBlock.vue'
import FeedbackSlider from '@/shared/ui/FeedbackSlider.vue'
import ClientsSlider from '@/shared/ui/ClientsSlider.vue'

const isBurgerOpen = ref(false)

const handleBurgerToggle = () => {
  isBurgerOpen.value = !isBurgerOpen.value
}
</script>

<style>
/* Main page styles will be loaded from app.min.css */
</style>
