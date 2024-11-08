export function EventEmitterMixin(superClass) {
    class EventEmitterElement extends superClass {
        addEventListener(type, listener, options) {
            super.addEventListener(type, listener, options);
        }
        removeEventListener(type, listener, options) {
            super.removeEventListener(type, listener, options);
        }
        emitEvent(type, eventInitDict) {
            return this.dispatchEvent(new CustomEvent(type, Object.assign({
                bubbles: true,
                cancelable: false,
                composed: true,
                detail: {},
            }, eventInitDict)));
        }
    }
    return EventEmitterElement;
}
//# sourceMappingURL=event-emitter.js.map