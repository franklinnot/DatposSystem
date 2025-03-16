import { forwardRef, useEffect, useRef, useState } from "react";

export default forwardRef(function MultiSelectInput(
    {
        options = [],
        value = [],
        className = "",
        placeholder = "Selecciona...",
        isDisabled = false,
        ...props
    },
    ref
) {
    const [isOpen, setIsOpen] = useState(false);
    const [selectedValues, setSelectedValues] = useState(value);
    const [searchTerm, setSearchTerm] = useState("");
    const [filteredOptions, setFilteredOptions] = useState(options);
    const [highlightedIndex, setHighlightedIndex] = useState(-1);
    const dropdownRef = useRef(null);
    const searchInputRef = useRef(null);
    const optionsListRef = useRef(null);

    useEffect(() => {
        setSelectedValues(value);
    }, [value]);

    useEffect(() => {
        if (!searchTerm.trim()) {
            setFilteredOptions(options);
            return;
        }

        const filtered = options.filter((option) =>
            option.name.toLowerCase().includes(searchTerm.toLowerCase())
        );
        setFilteredOptions(filtered);
    }, [options, searchTerm]);

    useEffect(() => {
        if (isOpen) {
            if (filteredOptions.length === 0) {
                setHighlightedIndex(-1);
                return;
            }
            // Eliminamos la lógica que establecía el índice a 0
            setHighlightedIndex(-1); // Inicializamos sin ningún ítem destacado
        } else {
            setHighlightedIndex(-1);
        }
    }, [filteredOptions, isOpen]);

    useEffect(() => {
        if (isOpen && searchInputRef.current) {
            setTimeout(() => {
                searchInputRef.current.focus();
            }, 10);
        } else {
            setSearchTerm("");
        }
    }, [isOpen]);

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

    useEffect(() => {
        const handleKeyUp = (e) => {
            if (e.key === "Escape") {
                setIsOpen(false);
            }
        };

        document.addEventListener("keyup", handleKeyUp);
        return () => document.removeEventListener("keyup", handleKeyUp);
    }, [isOpen]);

    useEffect(() => {
        if (!isOpen) return;

        const handleKeyDown = (e) => {
            if (e.key === "ArrowDown") {
                e.preventDefault();
                if (filteredOptions.length === 0) return;
                setHighlightedIndex((prev) => {
                    if (prev === -1) return 0; // Primer elemento al presionar flecha abajo
                    return prev >= filteredOptions.length - 1 ? 0 : prev + 1;
                });
            } else if (e.key === "ArrowUp") {
                e.preventDefault();
                if (filteredOptions.length === 0) return;
                setHighlightedIndex((prev) => {
                    if (prev === -1) return filteredOptions.length - 1; // Último elemento al presionar flecha arriba
                    return prev <= 0 ? filteredOptions.length - 1 : prev - 1;
                });
            }
            // Resto del código...
        };

        document.addEventListener("keydown", handleKeyDown);
        return () => document.removeEventListener("keydown", handleKeyDown);
    }, [isOpen, filteredOptions, highlightedIndex]);

    useEffect(() => {
        if (highlightedIndex >= 0 && optionsListRef.current) {
            const options =
                optionsListRef.current.querySelectorAll('[role="option"]');
            if (options[highlightedIndex]) {
                options[highlightedIndex].scrollIntoView({
                    block: "nearest",
                });
            }
        }
    }, [highlightedIndex]);

    const toggleSelection = (valueId) => {
        const newValues = selectedValues.includes(valueId)
            ? selectedValues.filter((id) => id !== valueId)
            : [...selectedValues, valueId];

        setSelectedValues(newValues);
        props.onChange?.({ target: { value: newValues } });
    };

    const selectedOptions = options.filter((opt) =>
        selectedValues.includes(opt.id)
    );
    const hasValues = selectedValues.length > 0;

    return (
        <div className={`relative ${className}`} ref={dropdownRef}>
            <div
                className={`
                    cursor-pointer flex items-center justify-between mt-1 py-3 px-4 max-h-10 outline-1 focus:outline focus:outline-[#0875E4] focus:ring-[#0875E4] rounded-lg 
                    ${hasValues ? "bg-[#e8f0fe]" : "bg-[#f2f2f2]"}
                    ${isDisabled ? "opacity-50 cursor-not-allowed" : ""}
                `}
                onClick={() => !isDisabled && setIsOpen(!isOpen)}
                role="combobox"
                aria-expanded={isOpen}
                aria-haspopup="listbox"
                aria-controls="select-dropdown"
                tabIndex={isDisabled ? -1 : 0}
                onKeyDown={(e) => {
                    if (!isDisabled && (e.key === "Enter" || e.key === " ")) {
                        e.preventDefault();
                        setIsOpen(!isOpen);
                    }
                }}
            >
                <span
                    className={`truncate ${
                        "text-gray-500"
                    }`}
                >
                    {placeholder}
                </span>

                <svg
                    className={`w-4 h-4 transition-transform ${
                        isOpen ? "rotate-180" : ""
                    }`}
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    aria-hidden="true"
                >
                    <path
                        strokeLinecap="round"
                        strokeLinejoin="round"
                        strokeWidth="2"
                        d="M19 9l-7 7-7-7"
                    />
                </svg>
            </div>

            {isOpen && (
                <div
                    id="select-dropdown"
                    className="absolute top-14 z-10 w-full bg-white rounded-lg shadow-lg border-[1px] border-gray-100 overflow-hidden"
                >
                    <div className="sticky top-0 p-2 bg-white border-b border-gray-100">
                        <input
                            ref={searchInputRef}
                            type="text"
                            placeholder="Buscar..."
                            value={searchTerm}
                            onChange={(e) => setSearchTerm(e.target.value)}
                            className="w-full px-3 py-2 border border-gray-200 rounded-md focus:outline-none focus:border-blue-500"
                            onClick={(e) => e.stopPropagation()}
                            onKeyDown={(e) => {
                                if (e.key === "Escape") {
                                    e.stopPropagation();
                                    setSearchTerm("");
                                }
                            }}
                        />
                    </div>

                    <div
                        role="listbox"
                        className="max-h-40 overflow-y-auto"
                        ref={optionsListRef}
                    >
                        {filteredOptions.length > 0 ? (
                            filteredOptions.map((option, index) => {
                                const isSelected = selectedValues.includes(
                                    option.id
                                );
                                return (
                                    <div
                                        key={option.id}
                                        role="option"
                                        aria-selected={isSelected}
                                        className={`
                                            px-4 py-3 hover:bg-[#e3ebff] text-gray-500 cursor-pointer
                                            flex items-center
                                            ${
                                                index === highlightedIndex
                                                    ? "bg-[#e3ebff]"
                                                    : ""
                                            }
                                        `}
                                        onClick={() =>
                                            toggleSelection(option.id)
                                        }
                                        onKeyDown={(e) => {
                                            if (
                                                e.key === "Enter" ||
                                                e.key === " "
                                            ) {
                                                e.preventDefault();
                                                toggleSelection(option.id);
                                            }
                                        }}
                                        tabIndex={0}
                                    >
                                        <input
                                            type="checkbox"
                                            checked={isSelected}
                                            readOnly
                                            className={
                                                "w-4 h-4 mr-3 rounded border-gray-300 text-[#0875E4] shadow-sm focus:ring-[#0875E4] " +
                                                (index === highlightedIndex
                                                    ? "bg-gray-100"
                                                    : "")
                                            }
                                        />
                                        {option.name}
                                    </div>
                                );
                            })
                        ) : (
                            <div className="px-4 py-3 text-gray-500 italic text-center">
                                No se encontraron resultados
                            </div>
                        )}
                    </div>
                </div>
            )}
        </div>
    );
});
