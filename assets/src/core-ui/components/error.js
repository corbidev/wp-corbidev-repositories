export function handle(error) {
    console.error(error)

    if (window.CorbidevUI?.toast) {
        CorbidevUI.toast.show(error.message, 'error')
    } else {
        CorbidevUI.banner.show(error.message, 'error')
    }
}
