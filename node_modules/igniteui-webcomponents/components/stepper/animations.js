import { EaseOut } from '../../animations/easings.js';
import { animation, } from '../../animations/types.js';
const baseOptions = {
    duration: 320,
    easing: EaseOut.Quad,
};
const fadeIn = (options = { keyframe: baseOptions }) => animation([{ opacity: 0 }, { opacity: 1 }], options.keyframe);
const fadeOut = (options = { keyframe: baseOptions }) => animation([{ opacity: 1 }, { opacity: 0 }], options.keyframe);
const slideInHor = (options = {
    keyframe: baseOptions,
}) => animation([{ transform: 'translateX(100%)' }, { transform: 'translateX(0)' }], options.keyframe);
const slideOutHor = (options = {
    keyframe: baseOptions,
}) => animation([{ transform: 'translateX(0)' }, { transform: 'translateX(-100%)' }], options.keyframe);
const growVerIn = (options = {
    keyframe: baseOptions,
    step: {},
}) => animation([
    { opacity: 1, ...options.step },
    { opacity: 1, height: 'auto' },
], options.keyframe);
const growVerOut = (options = {
    keyframe: baseOptions,
    step: {},
}) => animation([
    { opacity: 1, height: 'auto' },
    { opacity: 1, ...options.step },
], options.keyframe);
const noopAnimation = () => animation([], {});
const animationPair = (animations) => {
    return new Map(Object.entries({
        in: animations.in,
        out: animations.out,
    }));
};
export const bodyAnimations = new Map(Object.entries({
    grow: animationPair({
        in: growVerIn,
        out: growVerOut,
    }),
    fade: animationPair({
        in: noopAnimation,
        out: noopAnimation,
    }),
    slide: animationPair({
        in: slideInHor,
        out: slideOutHor,
    }),
    none: animationPair({
        in: noopAnimation,
        out: noopAnimation,
    }),
}));
export const contentAnimations = new Map(Object.entries({
    grow: animationPair({
        in: fadeIn,
        out: fadeOut,
    }),
    fade: animationPair({
        in: fadeIn,
        out: fadeOut,
    }),
    slide: animationPair({
        in: fadeIn,
        out: fadeOut,
    }),
    none: animationPair({
        in: noopAnimation,
        out: noopAnimation,
    }),
}));
//# sourceMappingURL=animations.js.map