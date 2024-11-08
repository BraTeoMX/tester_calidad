export type IgniteComponent = CustomElementConstructor & {
    tagName: string;
    register: () => void;
};
export declare function registerComponent(component: IgniteComponent, ...dependencies: IgniteComponent[]): void;
