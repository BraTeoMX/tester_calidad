import { css } from 'lit';
import { styles as indigo } from './shared/item.indigo.css.js';
import { styles as material } from './shared/item.material.css.js';
const light = {
    material: css `
    ${material}
  `,
    indigo: css `
    ${indigo}
  `,
};
const dark = {
    material: css `
    ${material}
  `,
    indigo: css `
    ${indigo}
  `,
};
export const all = { light, dark };
//# sourceMappingURL=item.js.map