export default class CorbidevModal {

    static stack = []
    static queue = []
    static isOpen = false

    static i18n = {
        __: (text) => text
    }

    static setI18n(i18n) {
        this.i18n = i18n
    }

    static get root() {
        return document.getElementById('corbidev-ui-modal-root')
    }

    static open(options = {}) {
        return new Promise((resolve) => {
            const instance = new CorbidevModal(options, resolve)

            if (this.isOpen) {
                this.queue.push(instance)
            } else {
                this.isOpen = true
                instance.mount()
            }
        })
    }

    static confirm(options = {}) {
        const { __ } = this.i18n

        return this.open({
            ...options,
            buttons: options.buttons || [
                {
                    label: __('Cancel'),
                    type: 'secondary',
                    value: false
                },
                {
                    label: __('Confirm'),
                    type: 'danger',
                    value: true
                }
            ]
        })
    }

    constructor(options, resolver) {
        this.options = {
            title: '',
            message: '',
            buttons: [],
            closable: true,
            overlayClose: true,
            ...options
        }

        this.resolve = resolver

        this.root = CorbidevModal.root
        this.modal = this.root.querySelector('.corbidev-ui-modal-content')
        this.overlay = this.root.querySelector('.corbidev-ui-modal-overlay')
        this.title = this.root.querySelector('.corbidev-ui-modal-title')
        this.body = this.root.querySelector('.corbidev-ui-modal-body')
        this.footer = this.root.querySelector('.corbidev-ui-modal-footer')
        this.closeBtn = this.root.querySelector('.corbidev-ui-modal-close')
    }

    mount() {
        CorbidevModal.stack.push(this)

        this.render()
        this.bind()
        this.lockScroll()
        this.show()

        CorbidevUI?.emit?.('modal.open', this)
    }

    render() {
        this.title.textContent = this.options.title
        this.body.innerHTML = this.options.message || ''

        this.footer.innerHTML = ''

        this.options.buttons.forEach(btn => {
            const button = document.createElement('button')
            button.textContent = btn.label
            button.className = `corbidev-ui-btn corbidev-ui-btn-${btn.type || 'primary'}`

            button.onclick = () => {
                if (btn.action) btn.action(this)
                this.resolve(btn.value ?? true)
                this.close()
            }

            this.footer.appendChild(button)
        })
    }

    bind() {
        if (this.options.closable) {
            this.closeBtn.onclick = () => this.cancel()
        }

        if (this.options.overlayClose) {
            this.overlay.onclick = () => this.cancel()
        }

        document.addEventListener('keydown', this.handleKeydown)
    }

    show() {
        this.root.classList.remove('hidden')

        requestAnimationFrame(() => {
            this.overlay.classList.add('corbidev-ui-modal-overlay-show')
            this.modal.classList.remove('corbidev-ui-modal-enter')
            this.modal.classList.add('corbidev-ui-modal-show')
        })
    }

    close() {
        this.overlay.classList.remove('corbidev-ui-modal-overlay-show')
        this.modal.classList.remove('corbidev-ui-modal-show')
        this.modal.classList.add('corbidev-ui-modal-enter')

        setTimeout(() => {
            this.cleanup()
            this.next()
        }, 300)

        CorbidevUI?.emit?.('modal.close', this)
    }

    cancel() {
        this.resolve(false)
        this.close()
    }

    cleanup() {
        document.removeEventListener('keydown', this.handleKeydown)

        CorbidevModal.stack.pop()

        if (CorbidevModal.stack.length === 0) {
            this.root.classList.add('hidden')
            this.unlockScroll()
            CorbidevModal.isOpen = false
        }
    }

    next() {
        if (CorbidevModal.queue.length > 0) {
            const next = CorbidevModal.queue.shift()
            CorbidevModal.isOpen = true
            next.mount()
        }
    }

    lockScroll() {
        document.body.style.overflow = 'hidden'
    }

    unlockScroll() {
        document.body.style.overflow = ''
    }

    handleKeydown = (e) => {
        if (e.key === 'Escape') this.cancel()
    }
}