export interface AnimationReferenceMetadata {
    steps: Keyframe[];
    options?: KeyframeAnimationOptions;
}
export declare function animation(steps: Keyframe[], options?: KeyframeAnimationOptions): AnimationReferenceMetadata;
