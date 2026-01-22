<template>
  <div class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-lg transition-shadow">
    <div>
      <p class="text-gray-600 text-sm uppercase tracking-wider mb-2">{{ title }}</p>
      <p class="text-3xl font-serif font-bold text-gray-900">{{ value }}</p>
    </div>
  </div>
</template>

<script setup>
defineProps({
  title: String,
  value: [String, Number],
  color: {
    type: String,
    default: 'blue'
  }
})
</script>
