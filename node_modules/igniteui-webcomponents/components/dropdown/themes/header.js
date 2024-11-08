import { css } from 'lit';
import { styles as fluent } from './shared/header/dropdown-header.fluent.css.js';
import { styles as indigo } from './shared/header/dropdown-header.indigo.css.js';
const light = {
    indigo: css `
    ${indigo}
  `,
    fluent: css `
    ${fluent}
  `,
};
const dark = {
    indigo: css `
    ${indigo}
  `,
    fluent: css `
    ${fluent}
  `,
};
export const all = { light, dark };
//# sourceMappingURL=header.js.map