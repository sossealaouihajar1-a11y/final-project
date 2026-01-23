<template>
  <div id="app">
    <RouterView />
    <Notification />
    <ConfirmDialog ref="confirmDialogRef" />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { RouterView } from 'vue-router'
import Notification from '@/components/Notification.vue'
import ConfirmDialog from '@/components/ConfirmDialog.vue'
import { setConfirmDialogRef } from '@/composables/useConfirmDialog'

const confirmDialogRef = ref(null)

onMounted(() => {
  if (confirmDialogRef.value) {
    setConfirmDialogRef(confirmDialogRef.value)
  }
})
</script>

<style>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
}

#app {
  min-height: 100vh;
}
</style>