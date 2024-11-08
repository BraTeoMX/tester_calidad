class RootScrollController {
    constructor(host, config) {
        this.host = host;
        this.config = config;
        this._cache = new WeakMap();
        this.host.addController(this);
    }
    configureListeners() {
        this.host.open ? this.addEventListeners() : this.removeEventListeners();
    }
    hide() {
        this.config?.hideCallback
            ? this.config.hideCallback.call(this.host)
            : this.host.hide();
    }
    addEventListeners() {
        if (this.host.scrollStrategy !== 'scroll') {
            document.addEventListener('scroll', this, { capture: true });
        }
    }
    removeEventListeners() {
        document.removeEventListener('scroll', this, { capture: true });
        this._cache = new WeakMap();
    }
    handleEvent(event) {
        this.host.scrollStrategy === 'close' ? this.hide() : this._block(event);
    }
    _block(event) {
        event.preventDefault();
        const element = event.target;
        const cache = this._cache;
        if (!cache.has(element)) {
            cache.set(element, {
                scrollTop: element.firstElementChild?.scrollTop ?? element.scrollTop,
                scrollLeft: element.firstElementChild?.scrollLeft ?? element.scrollLeft,
            });
        }
        const record = cache.get(element);
        Object.assign(element, record);
        if (element.firstElementChild) {
            Object.assign(element.firstElementChild, record);
        }
    }
    update(config) {
        if (config) {
            this.config = { ...this.config, ...config };
        }
        if (config?.resetListeners) {
            this.removeEventListeners();
        }
        this.configureListeners();
    }
    hostConnected() {
        this.configureListeners();
    }
    hostDisconnected() {
        this.removeEventListeners();
    }
}
export function addRootScrollHandler(host, config) {
    return new RootScrollController(host, config);
}
//# sourceMappingURL=root-scroll.js.map