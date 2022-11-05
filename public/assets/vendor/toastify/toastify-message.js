let duration = 3000

window.addEventListener('toastify_success', event => {
    Toastify({
        text: event.detail.message,
        duration: duration,
        close: true,
        // avatar: 'bi bi-check-lg',
        gravity: "bottom",
        position: "right",
        style: {
            background: 'linear-gradient(to right, #09c6f9, #045de9)'
        }
    }).showToast()
})

window.addEventListener('toastify_error', event => {
    Toastify({
        text: event.detail.message,
        duration: duration,
        close: true,
        // avatar: 'bi bi-exclamation-circle-fill',
        gravity: "bottom",
        position: "right",
        style: {
            background: 'linear-gradient(to right, #f85032, #e73827)'
        }
    }).showToast()
})