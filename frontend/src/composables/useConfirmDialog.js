import { ref } from 'vue'

// Global reference to ConfirmDialog component
let confirmDialogRef = null

export const setConfirmDialogRef = (ref) => {
  confirmDialogRef = ref
}

export const useConfirmDialog = () => {
  const showConfirm = async (options = {}) => {
    if (!confirmDialogRef || !confirmDialogRef.showConfirm) {
      console.error('ConfirmDialog not available')
      return false
    }
    
    return confirmDialogRef.showConfirm({
      title: options.title || 'Confirmation',
      message: options.message || 'Êtes-vous sûr?',
      confirmText: options.confirmText || 'Confirmer',
      cancelText: options.cancelText || 'Annuler',
      iconType: options.iconType || 'warning',
      dangerMode: options.dangerMode !== undefined ? options.dangerMode : true
    })
  }

  // Helper methods
  const confirm = (message, title = 'Confirmation', options = {}) => {
    return showConfirm({
      title,
      message,
      ...options
    })
  }

  const confirmDelete = (itemName = 'cet élément') => {
    return showConfirm({
      title: 'Supprimer?',
      message: `Êtes-vous sûr de vouloir supprimer ${itemName}? Cette action est irréversible.`,
      confirmText: 'Supprimer',
      cancelText: 'Annuler',
      iconType: 'error',
      dangerMode: true
    })
  }

  const confirmAction = (action, itemName = 'cet élément') => {
    return showConfirm({
      title: `${action}?`,
      message: `Êtes-vous sûr de vouloir ${action.toLowerCase()} ${itemName}?`,
      confirmText: action,
      cancelText: 'Annuler',
      iconType: 'warning',
      dangerMode: false
    })
  }

  return {
    showConfirm,
    confirm,
    confirmDelete,
    confirmAction
  }
}

// Expose globally for quick access
window.useConfirmDialog = useConfirmDialog
