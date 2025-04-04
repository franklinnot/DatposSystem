import { forwardRef, useEffect, useRef, useState } from "react";

export default forwardRef(function SelectInput(
    {
        options = [],
        value = "",
        className = "",
        placeholder = "Selecciona...",
        isDisabled = false,
        includeEmptyOption = true,
        emptyOptionText = "Ninguna opción",
        ...props
    },
    ref
) {
    const [isOpen, setIsOpen] = useState(false);
    const [selectedValue, setSelectedValue] = useState(value);
    const [searchTerm, setSearchTerm] = useState("");
    const [filteredOptions, setFilteredOptions] = useState(options);
    const [highlightedIndex, setHighlightedIndex] = useState(-1);
    const dropdownRef = useRef(null);
    const searchInputRef = useRef(null);
    const optionsListRef = useRef(null);

    // Add empty option to the beginning of the options list
    const allOptions = includeEmptyOption
        ? [{ id: "", name: emptyOptionText }, ...options]
        : options;

    useEffect(() => {
        setSelectedValue(value);
    }, [value]);

    useEffect(() => {
        setFilteredOptions(
            searchTerm.trim()
                ? allOptions.filter((option) =>
                      option.name
                          .toLowerCase()
                          .includes(searchTerm.toLowerCase())
                  )
                : allOptions
        );
    }, [searchTerm, options, includeEmptyOption, emptyOptionText]);

    useEffect(() => {
        if (isOpen) {
            setHighlightedIndex(-1);
        } else {
            setHighlightedIndex(-1);
        }
    }, [filteredOptions, isOpen]);

    useEffect(() => {
        if (isOpen && searchInputRef.current) {
            setTimeout(() => searchInputRef.current.focus(), 10);
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
            if (e.key === "Escape") setIsOpen(false);
        };

        document.addEventListener("keyup", handleKeyUp);
        return () => document.removeEventListener("keyup", handleKeyUp);
    }, [isOpen]);

    useEffect(() => {
        if (!isOpen) return;

        const handleKeyDown = (e) => {
            if (["ArrowDown", "Tab"].includes(e.key)) {
                e.preventDefault();
                if (filteredOptions.length === 0) return;
                setHighlightedIndex((prev) =>
                    prev === -1 ? 0 : (prev + 1) % filteredOptions.length
                );
            } else if (e.key === "ArrowUp") {
                e.preventDefault();
                if (filteredOptions.length === 0) return;
                setHighlightedIndex((prev) =>
                    prev === -1
                        ? filteredOptions.length - 1
                        : (prev - 1 + filteredOptions.length) %
                          filteredOptions.length
                );
            } else if (e.key === "Enter") {
                e.preventDefault();
                if (
                    highlightedIndex >= 0 &&
                    highlightedIndex < filteredOptions.length
                ) {
                    handleChange(filteredOptions[highlightedIndex].id);
                }
            }
        };

        document.addEventListener("keydown", handleKeyDown);
        return () => document.removeEventListener("keydown", handleKeyDown);
    }, [isOpen, filteredOptions, highlightedIndex]);

    useEffect(() => {
        if (highlightedIndex >= 0 && optionsListRef.current) {
            const options =
                optionsListRef.current.querySelectorAll('[role="option"]');
            if (options[highlightedIndex]) {
                options[highlightedIndex].scrollIntoView({ block: "nearest" });
            }
        }
    }, [highlightedIndex]);

    const handleChange = (newValue) => {
        setSelectedValue(newValue);
        props.onChange?.({ target: { value: newValue } });
        setIsOpen(false);
    };

    const selectedOption = allOptions.find((opt) => opt.id === selectedValue);
    const hasValue = Boolean(selectedValue) && selectedValue !== "";

    return (
        <div className={`relative ${className}`} ref={dropdownRef}>
            <div
                className={`
                    cursor-pointer flex items-center justify-between mt-1 py-3 px-4 max-h-10 outline-1 focus:outline focus:outline-[#0875E4] focus:ring-[#0875E4] rounded-lg 
                    ${hasValue ? "bg-[#e8f0fe]" : "bg-[#f2f2f2]"}
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
                        hasValue ? "text-inherit" : "text-gray-500"
                    }`}
                >
                    {selectedOption && selectedOption.id
                        ? selectedOption.name
                        : placeholder}
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
                            filteredOptions.map((option, index) => (
                                <div
                                    key={option.id}
                                    role="option"
                                    aria-selected={option.id === selectedValue}
                                    className={`px-4 py-3 hover:bg-[#e3ebff] text-gray-500 cursor-pointer ${
                                        index === highlightedIndex
                                            ? "bg-[#e3ebff]"
                                            : option.id === selectedValue &&
                                              index !== highlightedIndex
                                            ? "bg-[#f0f5ff]"
                                            : ""
                                    }`}
                                    onClick={() => handleChange(option.id)}
                                    onKeyDown={(e) => {
                                        if (
                                            e.key === "Enter" ||
                                            e.key === " "
                                        ) {
                                            e.preventDefault();
                                            handleChange(option.id);
                                        }
                                    }}
                                    tabIndex={0}
                                >
                                    {option.name}
                                </div>
                            ))
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
