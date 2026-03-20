export default class CorbidevModal {

    constructor() {

        this.modal = document.getElementById('corbidev-modal')

        if (!this.modal) {
            this.create()
        }

        // 🔥 IMPORTANT : cacher au chargement
        this.hide()

        this.bind()
    }

    create() {

        const wrapper = document.createElement('div')
        wrapper.id = 'corbidev-modal'

        // 🔥 IMPORTANT : hidden par défaut
        wrapper.classList.add('hidden')

        wrapper.innerHTML = `
            <div class="cdr-modal-overlay">
                <div class="cdr-modal">
                    <div id="cdr-modal-message" class="cdr-modal-message"></div>
                    <div class="cdr-modal-actions">
                        <button id="cdr-modal-close" class="cdr-btn">
                            OK
                        </button>
                    </div>
                </div>
            </div>
        `

        document.body.appendChild(wrapper)

        this.modal = wrapper
    }

    bind() {

        document.addEventListener('click', (e) => {
            if (e.target.id === 'cdr-modal-close') {
                this.hide()
            }
        })
    }

    show(message, type = 'error') {

        const msg = this.modal.querySelector('#cdr-modal-message')

        if (msg) {
            msg.textContent = message
        }

        this.modal.classList.remove('hidden')
        this.modal.dataset.type = type
    }

    hide() {
        if (!this.modal) return
        this.modal.classList.add('hidden')
    }
}