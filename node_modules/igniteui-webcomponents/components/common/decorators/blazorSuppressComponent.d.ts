import type { AbstractConstructor, Constructor } from '../mixins/constructor.js';
/**
 * Indicates a class should not be exposed to blazor Blazor.
 */
export declare function blazorSuppressComponent(_constructor: Constructor | AbstractConstructor): void;
