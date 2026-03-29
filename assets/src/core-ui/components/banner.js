export default class CorbidevBanner {

    static stack = []
    static queue = []
    static max = 5

    static i18n = {
        __: (text) => text
    }

    static setI18n(i18n) {
        this.i18n = i18n
    }

    static get root() {
        return document.getElementById('corbidev-ui-banner-root')
    }

    static show(options = {}) {

        const existing = this.stack.find(
            b => b.options.message === options.message
        )

        if (existing) {
            existing.increment()
            return existing
        }

        const instance = new CorbidevBanner(options)

        if (this.stack.length >= this.max) {
            this.queue.push(instance)
        } else {
            this.stack.unshift(instance)
            instance.mount()
        }

        return instance
    }

    constructor(options) {
        this.options = {
            message: '',
            type: 'info',
            position: 'top',
            closable: true,
            autoClose: true,
            delay: 4,
            priority: 0,
            ...options
        }

        this.root = CorbidevBanner.root
        this.el = null
        this.timer = null
        this.count = 1
    }

    mount() {
        this.render()
        this.applyPosition()
        this.animateIn()
        this.bind()

        if (this.options.autoClose) {
            this.startProgress()
        }

        CorbidevUI?.emit?.('banner.open', this)
    }

    render() {
        const { __ } = CorbidevBanner.i18n

        this.el = document.createElement('div')

        this.el.className = `
            corbidev-ui-banner-item
            corbidev-ui-banner-enter
            corbidev-ui-banner-${this.options.type}
        `

        const row = document.createElement('div')
        row.className = 'corbidev-ui-banner-row'

        this.content = document.createElement('div')
        this.content.textContent = this.options.message

        row.appendChild(this.content)

        if (this.options.closable) {
            const btn = document.createElement('button')
            btn.type = 'button'
            btn.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                    <line x1="18" y1="6" x2="6" y2="18" />
                    <line x1="6" y1="6" x2="18" y2="18" />
                </svg>
            `
            btn.setAttribute('aria-label', __('Close'))
            btn.className = 'corbidev-ui-banner-close'

            btn.onclick = () => this.close()

            row.appendChild(btn)
        }

        this.el.appendChild(row)

        this.progress = document.createElement('div')
        this.progress.className = 'corbidev-ui-banner-progress'

        this.bar = document.createElement('div')
        this.bar.className = 'corbidev-ui-banner-bar'

        this.progress.appendChild(this.bar)
        this.el.appendChild(this.progress)

        this.root.prepend(this.el)
    }

    applyPosition() {
        if (this.options.position === 'bottom') {
            this.root.classList.remove('top-0')
            this.root.classList.add('bottom-0', 'flex-col-reverse')
        } else {
            this.root.classList.remove('bottom-0')
            this.root.classList.add('top-0', 'flex-col')
        }
    }

    animateIn() {
        requestAnimationFrame(() => {
            this.el.classList.remove('corbidev-ui-banner-enter')
            this.el.classList.add('corbidev-ui-banner-show')
        })
    }

    startProgress() {
        const duration = this.options.delay * 1000

        this.bar.style.transitionDuration = `${duration}ms`
        this.bar.style.width = '0%'

        this.timer = setTimeout(() => this.close(), duration)
    }

    pause() {
        clearTimeout(this.timer)
    }

    resume() {
        if (this.options.autoClose) {
            this.startProgress()
        }
    }

    increment() {
        this.count++
        this.content.textContent = `${this.options.message} (${this.count})`
    }

    close() {
        clearTimeout(this.timer)

        this.el.classList.remove('corbidev-ui-banner-show')
        this.el.classList.add('corbidev-ui-banner-enter')

        setTimeout(() => {
            this.el.remove()
            this.cleanup()
            this.next()
        }, 300)

        CorbidevUI?.emit?.('banner.close', this)
    }

    cleanup() {
        const index = CorbidevBanner.stack.indexOf(this)
        if (index !== -1) CorbidevBanner.stack.splice(index, 1)
    }

    next() {
        if (CorbidevBanner.queue.length > 0) {
            const next = CorbidevBanner.queue.shift()
            CorbidevBanner.stack.unshift(next)
            next.mount()
        }
    }

    bind() {
        this.el.addEventListener('mouseenter', () => this.pause())
        this.el.addEventListener('mouseleave', () => this.resume())
    }
}