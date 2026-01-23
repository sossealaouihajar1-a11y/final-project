export const useNotification = () => {
  const showNotification = (options) => {
    // Support both object and string syntax
    if (typeof options === 'string') {
      window.showNotification?.({
        type: 'info',
        message: options,
        duration: 3000
      })
    } else {
      window.showNotification?.(options)
    }
  }

  const showSuccess = (message, title = 'Succès') => {
    showNotification({
      type: 'success',
      title,
      message,
      duration: 3000
    })
  }

  const showError = (message, title = 'Erreur') => {
    showNotification({
      type: 'error',
      title,
      message,
      duration: 4000
    })
  }

  const showWarning = (message, title = 'Attention') => {
    showNotification({
      type: 'warning',
      title,
      message,
      duration: 3500
    })
  }

  const showInfo = (message, title = 'Information') => {
    showNotification({
      type: 'info',
      title,
      message,
      duration: 3000
    })
  }

  return {
    showNotification,
    showSuccess,
    showError,
    showWarning,
    showInfo
  }
}
