// Components/SelectInput.js
import { forwardRef, useEffect, useRef, useState } from "react";

export default forwardRef(function SelectInput(
    {
        options = [],
        value = "",
        className = "",
        placeholder = "Selecciona...",
        isDisabled = false,
        ...props
    },
    ref
) {
    const [isOpen, setIsOpen] = useState(false);
    const [selectedValue, setSelectedValue] = useState(value);
    const [hasValue, setHasValue] = useState(value !== "");
    const dropdownRef = useRef(null);

    // Cerrar el dropdown al hacer click fuera
    useEffect(() => {
        const handleClickOutside = (event) => {
            if (
                dropdownRef.current &&
                !dropdownRef.current.contains(event.target)
            ) {
                setIsOpen(false);
            }
        };

        document.addEventListener("mousedown", handleClickOutside);
        return () =>
            document.removeEventListener("mousedown", handleClickOutside);
    }, []);

    // Sincroniza con el valor de props
    useEffect(() => {
        setSelectedValue(value);
        setHasValue(value !== "");
    }, [value]);

    // Funcion para manejar el cambio de valor
    const handleChange = (newValue) => {
        setSelectedValue(newValue);
        setHasValue(newValue !== "");
        props.onChange?.({ target: { value: newValue } });
        setIsOpen(false);
    };

    return (
        <div ref={dropdownRef} className={`relative ${className}`}>
            <div
                className={`
          cursor-pointer flex items-center justify-between py-3 px-4 border-0 rounded-lg 
          ${hasValue ? "bg-[#e8f0fe] " : "bg-[#f2f2f2]"}
          ${isDisabled ? "opacity-50 cursor-not-allowed" : ""}
        `}
                onClick={() => !isDisabled && setIsOpen(!isOpen)}
            >
                <span
                    className={`truncate ${
                        hasValue ? "text-inherit" : "text-gray-500"
                    }`}
                >
                    {options.find((opt) => opt.id === selectedValue)?.name ||
                        placeholder}
                </span>
                <svg
                    className={`w-4 h-4 transition-transform ${
                        isOpen ? "rotate-180" : ""
                    }`}
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        strokeLinecap="round"
                        strokeLinejoin="round"
                        strokeWidth="2"
                        d="M19 9l-7 7-7-7"
                    />
                </svg>
            </div>

            <div
                className={` top-14
          absolute z-10 w-full bg-white rounded-lg shadow-lg border-[1px] border-gray-100
          overflow-y-auto
          transition-all duration-300
          ${isOpen ? "max-h-48 opacity-100" : "max-h-0 opacity-0 invisible"}
        `}
            >
                {options.map((option) => (
                    <div
                        key={option.id}
                        className={` 
              px-4 py-3 hover:bg-[#e3ebff] text-gray-500
              ${option.id === selectedValue ? "bg-[#e3ebff]" : ""}
            `}
                        onClick={() => handleChange(option.id)}
                    >
                        {option.name}
                    </div>
                ))}
            </div>
        </div>
    );
});
