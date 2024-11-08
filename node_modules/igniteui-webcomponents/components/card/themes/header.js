import { css } from 'lit';
import { styles as bootstrap } from './shared/header/card.header.bootstrap.css.js';
import { styles as fluent } from './shared/header/card.header.fluent.css.js';
import { styles as indigo } from './shared/header/card.header.indigo.css.js';
const light = {
    bootstrap: css `
    ${bootstrap}
  `,
    fluent: css `
    ${fluent}
  `,
    indigo: css `
    ${indigo}
  `,
};
const dark = {
    bootstrap: css `
    ${bootstrap}
  `,
    fluent: css `
    ${fluent}
  `,
    indigo: css `
    ${indigo}
  `,
};
export const all = { light, dark };
//# sourceMappingURL=header.js.map