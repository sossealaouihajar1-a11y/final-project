<template>
  <Teleport to="body">
    <div v-if="notifications.length > 0" class="fixed inset-0 pointer-events-none z-50 flex flex-col justify-end items-end p-6">
      <div class="space-y-3">
        <transition-group name="slide" tag="div" class="space-y-3">
          <div
            v-for="notification in notifications"
            :key="notification.id"
            :class="getNotificationClass(notification.type)"
            class="rounded-lg shadow-lg p-4 min-w-[300px] max-w-[400px] flex items-start space-x-3 animate-slideIn pointer-events-auto"
          >
          <!-- Icon -->
          <div :class="getIconClass(notification.type)" class="flex-shrink-0 mt-0.5">
            <svg v-if="notification.type === 'success'" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            <svg v-else-if="notification.type === 'error'" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
            </svg>
            <svg v-else-if="notification.type === 'warning'" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            <svg v-else class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zm-11-1a1 1 0 11-2 0 1 1 0 012 0z" clip-rule="evenodd" />
            </svg>
          </div>

          <!-- Content -->
          <div class="flex-1">
            <h3 v-if="notification.title" :class="getTitleClass(notification.type)" class="font-semibold text-sm">
              {{ notification.title }}
            </h3>
            <p :class="getTextClass(notification.type)" class="text-sm mt-1">
              {{ notification.message }}
            </p>
          </div>

          <!-- Close button -->
          <button
            @click="removeNotification(notification.id)"
            :class="getCloseClass(notification.type)"
            class="flex-shrink-0 text-gray-400 hover:text-gray-600 transition"
          >
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
          </button>

          <!-- Progress bar -->
          <div class="absolute bottom-0 left-0 h-1 rounded-b-lg" :class="getProgressClass(notification.type)" :style="{ animation: `shrink ${notification.duration || 4000}ms linear` }"></div>
        </div>
      </transition-group>
      </div>
    </div>
  </Teleport>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const notifications = ref([])
let notificationId = 0

const addNotification = (options) => {
  const id = notificationId++
  const notification = {
    id,
    type: options.type || 'info',
    title: options.title,
    message: options.message,
    duration: options.duration || 4000
  }

  notifications.value.push(notification)

  // Auto-remove after duration
  setTimeout(() => {
    removeNotification(id)
  }, notification.duration)

  return id
}

const removeNotification = (id) => {
  notifications.value = notifications.value.filter(n => n.id !== id)
}

const getNotificationClass = (type) => {
  const classes = {
    success: 'bg-green-50 border border-green-200',
    error: 'bg-red-50 border border-red-200',
    warning: 'bg-yellow-50 border border-yellow-200',
    info: 'bg-blue-50 border border-blue-200'
  }
  return classes[type] || classes.info
}

const getIconClass = (type) => {
  const classes = {
    success: 'text-green-600',
    error: 'text-red-600',
    warning: 'text-yellow-600',
    info: 'text-blue-600'
  }
  return classes[type] || classes.info
}

const getTitleClass = (type) => {
  const classes = {
    success: 'text-green-900',
    error: 'text-red-900',
    warning: 'text-yellow-900',
    info: 'text-blue-900'
  }
  return classes[type] || classes.info
}

const getTextClass = (type) => {
  const classes = {
    success: 'text-green-700',
    error: 'text-red-700',
    warning: 'text-yellow-700',
    info: 'text-blue-700'
  }
  return classes[type] || classes.info
}

const getCloseClass = (type) => {
  const classes = {
    success: 'text-green-400 hover:text-green-600',
    error: 'text-red-400 hover:text-red-600',
    warning: 'text-yellow-400 hover:text-yellow-600',
    info: 'text-blue-400 hover:text-blue-600'
  }
  return classes[type] || classes.info
}

const getProgressClass = (type) => {
  const classes = {
    success: 'bg-green-400',
    error: 'bg-red-400',
    warning: 'bg-yellow-400',
    info: 'bg-blue-400'
  }
  return classes[type] || classes.info
}

// Expose to global
window.showNotification = addNotification

defineExpose({
  addNotification,
  removeNotification
})
</script>

<style scoped>
@keyframes slideIn {
  from {
    opacity: 0;
    transform: translateX(100px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

@keyframes shrink {
  from {
    width: 100%;
  }
  to {
    width: 0%;
  }
}

.animate-slideIn {
  animation: slideIn 0.3s ease-out;
}

.slide-enter-active,
.slide-leave-active {
  transition: all 0.3s ease;
}

.slide-enter-from {
  opacity: 0;
  transform: translateX(100px);
}

.slide-leave-to {
  opacity: 0;
  transform: translateX(100px);
}
</style>
