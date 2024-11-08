import type { DataController } from '../controllers/data.js';
import type { ComboRecord, FilteringOptions } from '../types.js';
export default class FilterDataOperation<T extends object> {
    protected normalize<T extends object>(string: string, { caseSensitive, matchDiacritics }: FilteringOptions<T>): string;
    apply(data: ComboRecord<T>[], controller: DataController<T>): ComboRecord<T>[];
}
