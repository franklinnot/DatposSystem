import { forwardRef, useEffect, useRef, useState } from "react";

export default forwardRef(function TextInput(
    { type = "text", className = "", isFocused = false, ...props },
    ref
) {
    const input = ref ? ref : useRef();
    const [inputValue, setInputValue] = useState(props.value ?? "");
    const [hasValue, setHasValue] = useState(props.value?.length > 0 ?? false);

    // Sincroniza con props.value
    useEffect(() => {
        setInputValue(props.value ?? "");
        setHasValue((props.value ?? "").length > 0);
    }, [props.value]);

    // Enfoca al montar
    useEffect(() => {
        if (isFocused) {
            input.current.focus();
        }
    }, []);

    // Maneja cambios
    const handleChange = (e) => {
        const value = e.target.value;
        setInputValue(value);
        setHasValue(value.length > 0);
        props.onChange?.(e); // <-- Propaga a componente padre
    };

    return (
        <input
            {...props}
            type={type}
            ref={input}
            value={inputValue}
            onChange={handleChange}
            className={`
                ${hasValue ? "bg-[#e8f0fe]" : "bg-[#f2f2f2]"} 
                border-0 focus:border-[#0875E4] focus:ring-[#0875E4] 
                rounded-lg shadow-sm ${className}
            `}
        />
    );
});
