declare namespace JQuery {
    interface Select2Options {
        placeholder?: string;
        allowClear?: boolean;
        minimumInputLength?: number;
        maximumInputLength?: number;
        multiple?: boolean;
        ajax?: {
            url: string;
            dataType?: string;
            delay?: number;
            data?: (params: any) => any;
            processResults?: (data: any) => any;
        };
        [key: string]: any; // Permite extender con otras opciones personalizadas
    }
}
