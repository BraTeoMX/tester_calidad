import { LitElement } from 'lit';
/**
 *
 * @element igc-focus-trap
 *
 * @slot - The content of the focus trap component
 */
export default class IgcFocusTrapComponent extends LitElement {
    static readonly tagName = "igc-focus-trap";
    static styles: import("lit").CSSResult;
    static register(): void;
    protected _focused: boolean;
    /**
     * Whether to manage focus state for the slotted children.
     * @attr disabled
     */
    disabled: boolean;
    /**
     * Whether focus in currently inside the trap component.
     */
    get focused(): boolean;
    /** An array of focusable elements including elements in Shadow roots */
    get focusableElements(): HTMLElement[];
    constructor();
    private onFocusIn;
    private onFocusOut;
    focusFirstElement(): void;
    focusLastElement(): void;
    protected render(): import("lit-html").TemplateResult<1>;
}
declare global {
    interface HTMLElementTagNameMap {
        'igc-focus-trap': IgcFocusTrapComponent;
    }
}
