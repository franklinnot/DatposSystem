import { forwardRef, useEffect, useRef, useState } from "react";

export default forwardRef(function NumberInput(
    { type = "number", className = "", isFocused = false, ...props },
    ref
) {
    const inputRef = ref || useRef();
    const [inputValue, setInputValue] = useState(props.value ?? "");
    const [hasValue, setHasValue] = useState(!!props.value);

    // Sincroniza el valor interno con props.value
    useEffect(() => {
        setInputValue(props.value ?? "");
        setHasValue(!!props.value);
    }, [props.value]);

    // Enfoca el input si se solicita
    useEffect(() => {
        if (isFocused) {
            inputRef.current.focus();
        }
    }, [isFocused, inputRef]);

    // Maneja el cambio del input
    const handleChange = (e) => {
        const value = e.target.value;
        setInputValue(value);
        setHasValue(value !== "");
        props.onChange?.(e);
    };

    return (
        <input
            {...props}
            type={type}
            ref={inputRef}
            value={inputValue}
            onChange={handleChange}
            className={`
        ${hasValue ? "bg-[#e8f0fe]" : "bg-[#f2f2f2]"} 
        border-0 focus:border-[#0875E4] focus:ring-[#0875E4] 
        rounded-lg shadow-sm ${className}
      `}
            style={{
                // Remueve los spinners en navegadores basados en Webkit y Firefox
                WebkitAppearance: "none",
                MozAppearance: "textfield",
                ...props.style,
            }}
        />
    );
});
