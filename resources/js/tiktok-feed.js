(function () {
    'use strict'

    // Shared by the TikTok, Instagram and Facebook feed plugins; only these three lines differ.
    const PREFIX = 'tf'
    const WIDGET_ID = 'tiktok-feed-widget'
    const STORAGE_KEY = PREFIX + '_widget_position'
    const AUTO_OPEN_KEY = PREFIX + '_widget_auto_opened'
    const DRAG_THRESHOLD = 6

    function cls(name) {
        return '.' + PREFIX + '-' + name
    }

    function ready(fn) {
        if (document.readyState !== 'loading') {
            fn()
        } else {
            document.addEventListener('DOMContentLoaded', fn)
        }
    }

    function clamp(value, min, max) {
        return Math.min(Math.max(value, min), max)
    }

    function applyPosition(widget, left, top) {
        widget.style.left = left + 'px'
        widget.style.top = top + 'px'
        widget.style.bottom = 'auto'
        widget.style.insetInlineStart = 'auto'
        widget.style.insetInlineEnd = 'auto'
        widget.style.right = 'auto'
    }

    function initDrag(widget, toggle) {
        if (widget.dataset.draggable !== '1') {
            return
        }

        let startX = 0
        let startY = 0
        let originLeft = 0
        let originTop = 0
        let dragging = false
        let moved = false
        let hasCustomPosition = false

        try {
            const saved = JSON.parse(localStorage.getItem(STORAGE_KEY) || 'null')
            if (saved && typeof saved.left === 'number' && typeof saved.top === 'number') {
                const rect = widget.getBoundingClientRect()
                applyPosition(
                    widget,
                    clamp(saved.left, 0, Math.max(window.innerWidth - rect.width, 0)),
                    clamp(saved.top, 0, Math.max(window.innerHeight - rect.height, 0))
                )
                hasCustomPosition = true
            }
        } catch (e) {
            // ignore malformed storage
        }

        toggle.addEventListener('pointerdown', function (e) {
            const rect = widget.getBoundingClientRect()
            startX = e.clientX
            startY = e.clientY
            originLeft = rect.left
            originTop = rect.top
            dragging = true
            moved = false

            toggle.setPointerCapture(e.pointerId)
        })

        toggle.addEventListener('pointermove', function (e) {
            if (!dragging) {
                return
            }

            const dx = e.clientX - startX
            const dy = e.clientY - startY

            if (Math.abs(dx) > DRAG_THRESHOLD || Math.abs(dy) > DRAG_THRESHOLD) {
                moved = true
            }

            if (!moved) {
                return
            }

            widget.classList.add(PREFIX + '-dragging')

            const rect = widget.getBoundingClientRect()
            const maxLeft = window.innerWidth - rect.width
            const maxTop = window.innerHeight - rect.height

            applyPosition(
                widget,
                clamp(originLeft + dx, 0, Math.max(maxLeft, 0)),
                clamp(originTop + dy, 0, Math.max(maxTop, 0))
            )
        })

        function endDrag(e) {
            if (!dragging) {
                return
            }

            dragging = false

            if (moved) {
                const rect = widget.getBoundingClientRect()

                try {
                    localStorage.setItem(STORAGE_KEY, JSON.stringify({ left: rect.left, top: rect.top }))
                } catch (err) {
                    // ignore storage errors (private mode, quota, etc.)
                }

                hasCustomPosition = true
                widget.classList.remove(PREFIX + '-dragging')

                e.preventDefault()
                e.stopPropagation()
            }
        }

        toggle.addEventListener('pointerup', endDrag)
        toggle.addEventListener('pointercancel', endDrag)

        toggle.addEventListener('click', function (e) {
            if (moved) {
                e.preventDefault()
                e.stopPropagation()
                moved = false
            }
        }, true)

        window.addEventListener('resize', function () {
            if (!hasCustomPosition) {
                return
            }

            const rect = widget.getBoundingClientRect()
            const maxLeft = window.innerWidth - rect.width
            const maxTop = window.innerHeight - rect.height

            applyPosition(widget, clamp(rect.left, 0, Math.max(maxLeft, 0)), clamp(rect.top, 0, Math.max(maxTop, 0)))
        })
    }

    // Embeds are only loaded when the visitor opens the panel or moves to a slide,
    // so no third-party request is made on page load. In privacy mode a cover stands in
    // for each embed until the visitor taps it.
    function loadFrame(slide) {
        const frame = slide ? slide.querySelector('iframe[data-src]') : null

        if (frame && !frame.getAttribute('src')) {
            frame.setAttribute('src', frame.dataset.src)
        }
    }

    function loadSlide(slides, index) {
        const slide = slides[index]

        if (slide && !slide.querySelector(cls('facade') + ':not(.is-loaded)')) {
            loadFrame(slide)
        }
    }

    function initFacades(widget) {
        widget.querySelectorAll(cls('facade')).forEach(function (facade) {
            facade.addEventListener('click', function () {
                facade.classList.add('is-loaded')
                loadFrame(facade.closest(cls('slide')))
            })
        })
    }

    function initCarousel(widget) {
        const track = widget.querySelector(cls('track'))
        const slides = widget.querySelectorAll(cls('slide'))
        const dots = widget.querySelectorAll(cls('dot'))
        const prevBtn = widget.querySelector(cls('nav-prev'))
        const nextBtn = widget.querySelector(cls('nav-next'))
        const shopBtn = widget.querySelector(cls('shop-btn'))

        let index = 0

        const carousel = {
            loadCurrent: function () {
                loadSlide(slides, index)
            },
        }

        if (!track || slides.length < 2) {
            return carousel
        }

        function goTo(newIndex) {
            index = (newIndex + slides.length) % slides.length
            track.style.transform = 'translateX(' + (document.dir === 'rtl' ? '' : '-') + index * 100 + '%)'
            loadSlide(slides, index)

            dots.forEach(function (dot, i) {
                dot.classList.toggle('is-active', i === index)
            })

            if (shopBtn && dots[index]) {
                const shopUrl = dots[index].dataset.shopUrl || shopBtn.dataset.defaultUrl
                if (shopUrl) {
                    shopBtn.setAttribute('href', shopUrl)
                }
            }
        }

        prevBtn && prevBtn.addEventListener('click', function () {
            goTo(index - 1)
        })

        nextBtn && nextBtn.addEventListener('click', function () {
            goTo(index + 1)
        })

        dots.forEach(function (dot) {
            dot.addEventListener('click', function () {
                goTo(parseInt(dot.dataset.index, 10) || 0)
            })
        })

        return carousel
    }

    function initToggle(widget, toggle, closeBtn, carousel) {
        function open() {
            carousel.loadCurrent()
            widget.classList.add('is-open')
            toggle.setAttribute('aria-expanded', 'true')
        }

        function close() {
            widget.classList.remove('is-open')
            toggle.setAttribute('aria-expanded', 'false')
        }

        toggle.addEventListener('click', function () {
            if (widget.classList.contains('is-open')) {
                close()
            } else {
                open()
            }
        })

        closeBtn.addEventListener('click', close)

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && widget.classList.contains('is-open')) {
                close()
            }
        })

        return { open: open, close: close }
    }

    function initAutoOpen(widget, controls) {
        if (widget.dataset.autoOpen !== '1') {
            return
        }

        let alreadyOpened = false

        try {
            alreadyOpened = sessionStorage.getItem(AUTO_OPEN_KEY) === '1'
        } catch (e) {
            // ignore
        }

        if (alreadyOpened) {
            return
        }

        const delay = (parseInt(widget.dataset.autoOpenDelay, 10) || 5) * 1000

        setTimeout(function () {
            controls.open()

            try {
                sessionStorage.setItem(AUTO_OPEN_KEY, '1')
            } catch (e) {
                // ignore
            }
        }, delay)
    }

    // The TikTok, Instagram and Facebook widgets stack instead of overlapping when they share a corner.
    function stackWithSiblingWidgets(widget) {
        const position = widget.dataset.stackPosition

        if (!position) {
            return
        }

        const group = Array.prototype.slice.call(
            document.querySelectorAll('[data-stack-position="' + position + '"]')
        )
        const index = group.indexOf(widget)

        if (index > 0) {
            const toggle = widget.querySelector(cls('toggle'))
            const size = toggle ? toggle.getBoundingClientRect().height : 40
            const side = position.indexOf('top') === 0 ? 'top' : 'bottom'
            const base = parseFloat(window.getComputedStyle(widget)[side]) || 20

            widget.style[side] = base + index * (size + 16) + 'px'
        }
    }

    ready(function () {
        const widget = document.getElementById(WIDGET_ID)

        if (!widget) {
            return
        }

        const toggle = widget.querySelector(cls('toggle'))
        const closeBtn = widget.querySelector(cls('close'))

        if (!toggle || !closeBtn) {
            return
        }

        initFacades(widget)

        const carousel = initCarousel(widget)
        const controls = initToggle(widget, toggle, closeBtn, carousel)

        stackWithSiblingWidgets(widget)
        initDrag(widget, toggle)
        initAutoOpen(widget, controls)
    })
})()
