<template>
  <Teleport to="body">
    <Transition name="fade">
      <div v-if="isVisible" class="fixed inset-0 z-50 flex items-center justify-center">
        <!-- Backdrop -->
        <div 
          class="fixed inset-0 bg-black bg-opacity-50"
          @click="cancel"
        />
        
        <!-- Dialog -->
        <div class="relative bg-[#fbfaf7] rounded-lg shadow-xl max-w-sm w-full mx-4 border border-[#d6cdbf]">
          <!-- Content -->
          <div class="p-6">
            <!-- Icon -->
            <div v-if="iconType" class="flex justify-center mb-4">
              <div 
                :class="[
                  'w-12 h-12 rounded-full flex items-center justify-center',
                  iconType === 'warning' ? 'bg-yellow-100' :
                  iconType === 'error' ? 'bg-red-100' : 'bg-blue-100'
                ]"
              >
                <svg 
                  v-if="iconType === 'warning'"
                  class="w-6 h-6 text-yellow-600"
                  fill="currentColor"
                  viewBox="0 0 20 20"
                >
                  <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                <svg 
                  v-else-if="iconType === 'error'"
                  class="w-6 h-6 text-red-600"
                  fill="currentColor"
                  viewBox="0 0 20 20"
                >
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
                <svg 
                  v-else
                  class="w-6 h-6 text-blue-600"
                  fill="currentColor"
                  viewBox="0 0 20 20"
                >
                  <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zm-11-1a1 1 0 11-2 0 1 1 0 012 0zm3 0a1 1 0 11-2 0 1 1 0 012 0zm3 0a1 1 0 11-2 0 1 1 0 012 0z" clip-rule="evenodd" />
                </svg>
              </div>
            </div>

            <!-- Title -->
            <h3 class="text-lg font-semibold text-center text-[#4a3728] mb-2">
              {{ title }}
            </h3>

            <!-- Message -->
            <p class="text-center text-[#5a564f] text-sm mb-6">
              {{ message }}
            </p>
          </div>

          <!-- Buttons -->
          <div class="flex gap-3 px-6 pb-6">
            <button
              @click="cancel"
              class="flex-1 px-4 py-2 rounded-lg border border-[#d6cdbf] text-[#4a3728] 
                     font-medium hover:bg-[#f4f1eb] transition-colors"
            >
              {{ cancelText || 'Annuler' }}
            </button>
            <button
              @click="confirm"
              :class="[
                'flex-1 px-4 py-2 rounded-lg font-medium text-white transition-colors',
                dangerMode ? 'bg-red-600 hover:bg-red-700' : 'bg-[#6b7b4b] hover:bg-[#556b2f]'
              ]"
            >
              {{ confirmText || 'Confirmer' }}
            </button>
          </div>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
import { ref, reactive } from 'vue'

const isVisible = ref(false)
const state = reactive({
  title: '',
  message: '',
  confirmText: 'Confirmer',
  cancelText: 'Annuler',
  iconType: 'warning', // 'warning', 'error', 'info'
  dangerMode: false,
  resolve: null
})

const title = ref('')
const message = ref('')
const confirmText = ref('')
const cancelText = ref('')
const iconType = ref('warning')
const dangerMode = ref(false)

const showConfirm = (options) => {
  return new Promise((resolve) => {
    title.value = options.title || 'Confirmation'
    message.value = options.message || 'Êtes-vous sûr?'
    confirmText.value = options.confirmText || 'Confirmer'
    cancelText.value = options.cancelText || 'Annuler'
    iconType.value = options.iconType || 'warning'
    dangerMode.value = options.dangerMode || false
    
    state.resolve = resolve
    isVisible.value = true
  })
}

const confirm = () => {
  isVisible.value = false
  if (state.resolve) state.resolve(true)
}

const cancel = () => {
  isVisible.value = false
  if (state.resolve) state.resolve(false)
}

defineExpose({
  showConfirm
})
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
