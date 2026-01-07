<template>
  <div class="bg-white rounded-lg shadow p-6">
    <div class="flex items-start justify-between">
      <div>
        <p class="text-gray-600 text-sm">{{ title }}</p>
        <p class="text-3xl font-bold text-gray-900 mt-2">{{ value }}</p>
      </div>
      <div :class="`text-4xl p-3 rounded-lg ${colorClasses[color]}`">
        {{ icon }}
      </div>
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
  },
  icon: String
})

const colorClasses = {
  blue: 'bg-blue-100',
  green: 'bg-green-100',
  yellow: 'bg-yellow-100',
  purple: 'bg-purple-100',
  red: 'bg-red-100'
}
</script>
