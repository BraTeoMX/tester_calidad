export function registerComponent(component, ...dependencies) {
    for (const dependency of dependencies) {
        dependency.register();
    }
    if (!customElements.get(component.tagName)) {
        customElements.define(component.tagName, component);
    }
}
//# sourceMappingURL=register.js.map