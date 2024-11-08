import type IgcRadioComponent from './radio.js';
type RadioQueryResult = {
    /** Radio components under the same group name */
    radios: IgcRadioComponent[];
    /** Radio components under the same group name that are not disabled */
    active: IgcRadioComponent[];
    /** Radio components under the same group name sans the radio member passed in `getGroup` */
    siblings: IgcRadioComponent[];
    /** Radio components under the same group name that are marked as checked */
    checked: IgcRadioComponent[];
};
export declare function getGroup(member: IgcRadioComponent): RadioQueryResult;
export {};
