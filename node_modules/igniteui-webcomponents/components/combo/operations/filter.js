export default class FilterDataOperation {
    normalize(string, { caseSensitive, matchDiacritics }) {
        const str = caseSensitive ? string : string.toLocaleLowerCase();
        return matchDiacritics ? str : str.normalize('NFKD').replace(/\p{M}/gu, '');
    }
    apply(data, controller) {
        const { searchTerm, filteringOptions } = controller;
        const { filterKey: key } = filteringOptions;
        if (!searchTerm)
            return data;
        const term = this.normalize(searchTerm, filteringOptions);
        return data.filter(({ value }) => {
            const string = key ? `${value[key]}` : `${value}`;
            return this.normalize(string, filteringOptions).includes(term);
        });
    }
}
//# sourceMappingURL=filter.js.map