<template>
  <div class="flex items-center space-x-3">
    <div class="flex-1">
      <div class="flex justify-between mb-1">
        <span class="text-sm font-medium text-gray-700">{{ label }}</span>
        <span class="text-sm text-gray-500">{{ value }}/{{ total }}</span>
      </div>
      <div class="w-full bg-gray-200 rounded-full h-2">
        <div :style="{ width: (value/total)*100 + '%' }" class="bg-blue-600 h-2 rounded-full"></div>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  label: String,
  value: Number,
  total: Number
})
</script>
