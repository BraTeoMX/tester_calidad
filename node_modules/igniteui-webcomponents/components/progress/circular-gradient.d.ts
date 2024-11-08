import { LitElement } from 'lit';
/**
 * Used for defining gradient stops in the igc-circular-progress.
 * For each `igc-circular-gradient` defined as `gradient` slot of `igc-circular-progress` element would be created a SVG stop element.
 * The values passed as `color`, `offset` and `opacity` would be set as
 * `stop-color`, `offset` and `stop-opacity` of the SVG element without further validations.
 *
 * @element igc-circular-gradient
 *
 */
export default class IgcCircularGradientComponent extends LitElement {
    static readonly tagName = "igc-circular-gradient";
    static register(): void;
    /**
     * Defines where the gradient stop is placed along the gradient vector
     * @attr
     */
    offset: string;
    /**
     * Defines the color of the gradient stop
     * @attr
     */
    color: string;
    /**
     * Defines the opacity of the gradient stop
     * @attr
     */
    opacity: number;
    protected render(): symbol;
}
declare global {
    interface HTMLElementTagNameMap {
        'igc-circular-gradient': IgcCircularGradientComponent;
    }
}
