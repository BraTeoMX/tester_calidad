import { LitElement } from 'lit';
/**
 *
 * Used when a custom icon/symbol/element needs to be passed to the igc-rating component.
 *
 * @element igc-rating-symbol
 *
 * @slot - Default slot for projected full symbols/icons.
 * @slot empty - Default slot for projected empty symbols/icons.
 *
 * @csspart symbol - The symbol wrapping container.
 * @csspart full - The full symbol wrapping container.
 * @csspart empty - The empty symbol wrapping container.
 */
export default class IgcRatingSymbolComponent extends LitElement {
    static readonly tagName = "igc-rating-symbol";
    static styles: import("lit").CSSResult[];
    static register(): void;
    connectedCallback(): void;
    protected render(): import("lit-html").TemplateResult<1>;
}
declare global {
    interface HTMLElementTagNameMap {
        'igc-rating-symbol': IgcRatingSymbolComponent;
    }
}
