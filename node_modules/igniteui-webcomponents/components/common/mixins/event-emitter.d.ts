import type { LitElement } from 'lit';
import type { AbstractConstructor, Constructor } from './constructor.js';
export type UnpackCustomEvent<T> = T extends CustomEvent<infer U> ? U : never;
export declare class EventEmitterInterface<E> {
    addEventListener<K extends keyof M, M extends E & HTMLElementEventMap>(type: K, listener: (this: HTMLElement, ev: M[K]) => any, options?: boolean | AddEventListenerOptions): void;
    addEventListener(type: string, listener: EventListenerOrEventListenerObject, options?: boolean | AddEventListenerOptions): void;
    removeEventListener<K extends keyof M, M extends E & HTMLElementEventMap>(type: K, listener: (this: HTMLElement, ev: M[K]) => any, options?: boolean | EventListenerOptions): void;
    removeEventListener(type: string, listener: EventListenerOrEventListenerObject, options?: boolean | EventListenerOptions): void;
    emitEvent<K extends keyof E, D extends UnpackCustomEvent<E[K]>>(type: K, eventInitDict?: CustomEventInit<D>): boolean;
}
export declare function EventEmitterMixin<E, T extends AbstractConstructor<LitElement>>(superClass: T): Constructor<EventEmitterInterface<E>> & T;
