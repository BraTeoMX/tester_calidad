export function watch(propName, options) {
    return (protoOrDescriptor, name) => {
        const { willUpdate } = protoOrDescriptor;
        const watchOptions = Object.assign({ waitUntilFirstUpdate: false }, options);
        protoOrDescriptor.willUpdate = function (changedProps) {
            willUpdate.call(this, changedProps);
            if (changedProps.has(propName)) {
                const oldValue = changedProps.get(propName);
                const newValue = this[propName];
                if (oldValue !== newValue) {
                    if (!watchOptions?.waitUntilFirstUpdate || this.hasUpdated) {
                        this[name].call(this, oldValue, newValue);
                    }
                }
            }
        };
    };
}
//# sourceMappingURL=watch.js.map