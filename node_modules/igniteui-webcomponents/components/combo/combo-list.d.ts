import { LitVirtualizer } from '@lit-labs/virtualizer/LitVirtualizer.js';
export default class IgcComboListComponent extends LitVirtualizer {
    static readonly tagName = "igc-combo-list";
    scroller: boolean;
    static register(): void;
}
declare global {
    interface HTMLElementTagNameMap {
        'igc-combo-list': IgcComboListComponent;
    }
}
