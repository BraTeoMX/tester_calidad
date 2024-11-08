import { LitElement } from 'lit';
/** A container for card's media - could be an image, gif, video
 * @element igc-card-media
 *
 * @slot - Renders the card media content
 */
export default class IgcCardMediaComponent extends LitElement {
    static readonly tagName = "igc-card-media";
    static styles: import("lit").CSSResult;
    static register(): void;
    protected render(): import("lit-html").TemplateResult<1>;
}
declare global {
    interface HTMLElementTagNameMap {
        'igc-card-media': IgcCardMediaComponent;
    }
}
