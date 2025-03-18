import { forwardRef, useEffect, useRef, useState } from "react";

export default forwardRef(function ColorInput(
    { className = "", isFocused = false, ...props },
    ref
) {
    const inputRef = ref ? ref : useRef();
    const [color, setColor] = useState(props.value ?? "#ffffff");

    // Sincroniza el estado con props.value
    useEffect(() => {
        setColor(props.value ?? "#ffffff");
    }, [props.value]);

    // Enfoca el input al montar si se requiere
    useEffect(() => {
        if (isFocused) {
            inputRef.current.focus();
        }
    }, []);

    // Maneja cambios de color
    const handleChange = (e) => {
        const newColor = e.target.value;
        setColor(newColor);
        props.onChange?.(e); // Propaga el cambio al componente padre
    };

    return (
        <div
            className={`relative flex items-center border border-gray-300 focus-within:border-[#0875E4] focus-within:ring-[#0875E4] rounded-lg shadow-sm bg-white px-4 py-2 ${className}`}
        >
            {/* Círculo de color */}
            <div
                className="w-7 h-7 rounded-full border border-gray-400 cursor-pointer"
                style={{ backgroundColor: color }}
                onClick={() => inputRef.current.click()}
            ></div>

            {/* Código hexadecimal */}
            <span
                className="ml-3 text-sm font-medium text-gray-700 cursor-pointer"
                onClick={() => inputRef.current.click()}
            >
                {color.toLowerCase()}
            </span>

            {/* Input de color (ahora solo invisible, no hidden) */}
            <input
                {...props}
                type="color"
                ref={inputRef}
                value={color}
                onChange={handleChange}
                className="absolute left-[-4px] top-4 mt-1 opacity-0 w-full h-8 cursor-pointer"
            />
        </div>
    );
});
