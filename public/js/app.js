/*
 * GP247-EC interaction JS — plain ES, no bundling: Alpine.js ships inside
 * Livewire's bundle (@livewireScripts in layout.blade.php), so this file only
 * registers extra Alpine.data() components on `alpine:init` and is loaded as a
 * plain <script>. Language, currency, auth and cart use GP247's real
 * components and services, so no client-side store is needed.
 */
document.addEventListener('alpine:init', () => {
    /**
     * Sticky header state: `scrolled` (adds a shadow) and the mobile menu drawer.
     */
    const header = () => ({
        scrolled: false,
        mobileMenuOpen: false,
        init() {
            this.scrolled = window.scrollY > 80;
            window.addEventListener('scroll', () => { this.scrolled = window.scrollY > 80; }, { passive: true });
        },
    });

    /**
     * Home hero banner slider (blocks/banner_image.blade.php): autoplay, dot
     * navigation and mouse / touch drag-to-swipe. `isRtl` comes from the server
     * (gp247ec_is_rtl()) because RTL follows the admin language setting.
     */
    const bannerSlider = (count = 1, isRtl = false) => ({
        count,
        index: 0,
        dirSign: isRtl ? -1 : 1,
        dragging: false,
        dragStartX: 0,
        dragDeltaX: 0,
        dragWidth: 1,
        wasDragged: false,
        init() {
            if (this.count <= 1) {
                return;
            }
            setInterval(() => {
                if (!this.dragging) {
                    this.index = (this.index + 1) % this.count;
                }
            }, 5000);
        },
        /**
         * Inline transform for the slide track, following the pointer in
         * real time while dragging and snapping between slides otherwise.
         */
        get trackStyle() {
            const basePct = -(this.index * 100);
            const dragPct = this.dragWidth ? (this.dragDeltaX / this.dragWidth) * 100 : 0;
            return `transform: translateX(${this.dirSign * (basePct + dragPct)}%); transition: ${this.dragging ? 'none' : 'transform 500ms ease'}`;
        },
        /**
         * Start tracking a mouse or touch drag on the slider.
         */
        dragStart(event) {
            this.dragging = true;
            this.wasDragged = false;
            this.dragDeltaX = 0;
            this.dragStartX = event.touches ? event.touches[0].clientX : event.clientX;
            this.dragWidth = event.currentTarget.offsetWidth || 1;
        },
        /**
         * Track pointer movement during a drag, flagging it as a real drag
         * once it crosses a small threshold so slide links aren't
         * accidentally followed after a swipe.
         */
        dragMove(event) {
            if (!this.dragging) {
                return;
            }
            const clientX = event.touches ? event.touches[0].clientX : event.clientX;
            this.dragDeltaX = clientX - this.dragStartX;
            if (Math.abs(this.dragDeltaX) > 5) {
                this.wasDragged = true;
            }
        },
        /**
         * End the drag gesture, advancing to the next/previous slide when
         * the pointer moved past the snap threshold.
         */
        dragEnd() {
            if (!this.dragging) {
                return;
            }
            const threshold = this.dragWidth * 0.15;
            const signedDelta = this.dragDeltaX * this.dirSign;
            if (signedDelta < -threshold) {
                this.index = (this.index + 1) % this.count;
            } else if (signedDelta > threshold) {
                this.index = (this.index - 1 + this.count) % this.count;
            }
            this.dragging = false;
            this.dragDeltaX = 0;
        },
        /**
         * Suppress a slide's link/CTA click when it was triggered right
         * after a drag gesture, so swiping doesn't also navigate away.
         *
         * @param {MouseEvent} event - Click event on a slide link or CTA.
         */
        linkClick(event) {
            if (this.wasDragged) {
                event.preventDefault();
            }
        },
    });

    /**
     * Countdown to a server-computed target (epoch ms): exposes d (days) and
     * padded h / m / s, so a block renders a real deadline. gp247ecCountdown
     * splits days off (d + h 0-23); gp247frontCountdown keeps h = total hours.
     */
    // splitDays: GP247-EC shows d + h(0-23); the GP247Front name keeps its
    // original contract (h = total hours) for plugin views written against it.
    const countdown = (splitDays) => (targetMs) => ({
        targetMs,
        d: 0,
        h: '00',
        m: '00',
        s: '00',
        timer: null,
        init() {
            this.tick();
            this.timer = setInterval(() => this.tick(), 1000);
        },
        /**
         * Recompute the displayed h/m/s from the remaining time, stopping
         * the interval once the target is reached.
         */
        tick() {
            const remainingSeconds = Math.max(0, Math.floor((this.targetMs - Date.now()) / 1000));
            const pad = (n) => String(n).padStart(2, '0');
            this.d = splitDays ? Math.floor(remainingSeconds / 86400) : 0;
            this.h = pad(Math.floor((splitDays ? remainingSeconds % 86400 : remainingSeconds) / 3600));
            this.m = pad(Math.floor((remainingSeconds % 3600) / 60));
            this.s = pad(remainingSeconds % 60);
            if (remainingSeconds <= 0 && this.timer) {
                clearInterval(this.timer);
            }
        },
        destroy() {
            if (this.timer) {
                clearInterval(this.timer);
            }
        },
    });

    Alpine.data('gp247ecHeader', header);
    Alpine.data('gp247ecBannerSlider', bannerSlider);
    Alpine.data('gp247ecCountdown', countdown(true));
    // WHY: plugin storefront views rendered inside this layout use the GP247Front
    // component names (class/JS contract shared by every template).
    Alpine.data('gp247frontHeader', header);
    Alpine.data('gp247frontBannerSlider', bannerSlider);
    Alpine.data('gp247frontCountdown', countdown(false));
});
