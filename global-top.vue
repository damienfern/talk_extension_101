<script setup>
import { useNav } from '@slidev/client'
const { currentSlideNo } = useNav()
</script>

<template>
  <div v-if="currentSlideNo > 1" class="absolute bottom-4 right-4 flex items-center gap-2">
    <span class="handwritten-sm opacity-50">@damienfern</span>
    <!-- <span class="text-sm opacity-50"><SlideCurrentNo /></span> -->
  </div>

  <div v-if="currentSlideNo > 1" class="absolute bottom-4 left-4 flex items-center gap-1">
    <img src="/apip-logo.svg" class="h-5" />
    <div class="flex items-center gap-0.25">
      <div class="w-px self-stretch" style="background: #2fc1c1;"></div>
      <span class="tracking-widest" style="writing-mode: vertical-rl; transform: rotate(180deg); color: #2fc1c1; font-size: 8px; font-family: 'Nunito', 'Trebuchet MS', sans-serif;">2026</span>
    </div>
  </div>
</template>
