import { useState, useRef, useEffect, forwardRef } from "react";

// Iconos SVG
const PlusIcon = () => (
    <svg
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 24 24"
        strokeWidth={1.5}
        stroke="currentColor"
        className="size-6"
    >
        <path
            strokeLinecap="round"
            strokeLinejoin="round"
            d="M12 4.5v15m7.5-7.5h-15"
        />
    </svg>
);

const ArrowIcon = ({ isExpanded }) => (
    <svg
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 24 24"
        strokeWidth={1.5}
        stroke="currentColor"
        className={`size-6 transition-transform ${
            isExpanded ? "rotate-180" : ""
        }`}
    >
        <path
            strokeLinecap="round"
            strokeLinejoin="round"
            d="m19.5 8.25-7.5 7.5-7.5-7.5"
        />
    </svg>
);

const DeleteIcon = () => (
    <svg
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 24 24"
        strokeWidth={1.5}
        stroke="currentColor"
        className="size-6"
    >
        <path
            strokeLinecap="round"
            strokeLinejoin="round"
            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"
        />
    </svg>
);

// Componente de entrada mejorado
const TextInput = forwardRef(
    (
        {
            type = "text",
            className = "",
            isFocused = false,
            value = "",
            onChange,
            ...props
        },
        ref
    ) => {
        const inputRef = useRef();
        const [hasValue, setHasValue] = useState(value.length > 0);

        const handleChange = (e) => {
            onChange?.(e);
            setHasValue(e.target.value.length > 0);
        };

        useEffect(() => {
            setHasValue(value.length > 0);
            if (isFocused) (ref || inputRef).current?.focus();
        }, [value, isFocused, ref]);

        return (
            <input
                {...props}
                ref={ref || inputRef}
                type={type}
                value={value}
                onChange={handleChange}
                className={`transition-colors duration-200 ${
                    hasValue ? "bg-[#e8f0fe]" : "bg-[#f2f2f2] text-gray-500"
                } border-0 focus:ring-[#0875E4] rounded-lg shadow-sm ${className}`}
            />
        );
    }
);

// Componente principal
const ProductVariants = ({ onVariantsChange }) => {
    const [variants, setVariants] = useState([]);
    const [newVariant, setNewVariant] = useState("");
    const [listExpanded, setListExpanded] = useState(false);
    const [expandedVariants, setExpandedVariants] = useState([]);
    const newVariantRef = useRef();

    useEffect(() => {
        onVariantsChange(variants.map(({ id, ...rest }) => rest));
    }, [variants, onVariantsChange]);

    const handleAddVariant = (e) => {
        e.preventDefault();
        if (newVariant.trim()) {
            const newVariantData = {
                id: Date.now(),
                name: newVariant.trim(),
                details: [],
            };
            setVariants((prev) => {
                const updated = [...prev, newVariantData];
                return updated;
            });
            setExpandedVariants((prev) => [...prev, false]);
            setNewVariant("");
            setListExpanded(true);
        }
    };

    const toggleVariantExpansion = (index) => {
        setExpandedVariants((prev) =>
            prev.map((state, i) => (i === index ? !state : state))
        );
    };

    const handleVariantChange = (index, value) => {
        setVariants((prev) => {
            const updated = prev.map((v, i) =>
                i === index ? { ...v, name: value } : v
            );
            return updated;
        });
    };

    const handleAddDetail = (variantIndex) => {
        setVariants((prev) => {
            const updated = [...prev];
            updated[variantIndex].details.push("");
            return updated;
        });
    };

    const handleDetailChange = (variantIndex, detailIndex, value) => {
        setVariants((prev) => {
            const updated = [...prev];
            updated[variantIndex].details[detailIndex] = value;
            return updated;
        });
    };

    const handleDeleteVariant = (index) => {
        setVariants((prev) => {
            const updated = prev.filter((_, i) => i !== index);
            return updated;
        });
        setExpandedVariants((prev) => prev.filter((_, i) => i !== index));
    };

    const handleDeleteDetail = (variantIndex, detailIndex) => {
        setVariants((prev) => {
            const updated = [...prev];
            updated[variantIndex].details = updated[
                variantIndex
            ].details.filter((_, i) => i !== detailIndex);
            return updated;
        });
    };

    return (
        <div className="mt-1 space-y-3">
            <div className="flex items-center gap-2 mb-4">
                <TextInput
                    ref={newVariantRef}
                    value={newVariant}
                    onChange={(e) => setNewVariant(e.target.value)}
                    placeholder="Añadir variante"
                    className="flex-1"
                    aria-label="Nueva variante"
                />
                <button
                    onClick={handleAddVariant}
                    disabled={!newVariant.trim()}
                    className="disabled:opacity-50 disabled:cursor-not-allowed"
                    aria-label="Añadir variante"
                >
                    <PlusIcon />
                </button>
                <button
                    onClick={(e) => {
                        e.preventDefault();
                        variants.length > 0 && setListExpanded(!listExpanded);
                    }}
                    disabled={variants.length === 0}
                    className="disabled:opacity-50 disabled:cursor-not-allowed"
                    aria-label={
                        listExpanded ? "Contraer todo" : "Expandir todo"
                    }
                >
                    <ArrowIcon isExpanded={listExpanded} />
                </button>
            </div>

            {listExpanded && variants.length > 0 && (
                <div className="space-y-4">
                    {variants.map((variant, index) => (
                        <div
                            key={variant.id}
                            className="bg-[#fafafa] p-4 rounded-lg shadow"
                        >
                            <div className="flex items-center gap-2">
                                <TextInput
                                    value={variant.name}
                                    onChange={(e) =>
                                        handleVariantChange(
                                            index,
                                            e.target.value
                                        )
                                    }
                                    className="flex-1"
                                    aria-label="Nombre de variante"
                                />
                                <button
                                    onClick={(e) => {
                                        e.preventDefault();
                                        handleAddDetail(index);
                                        if (!expandedVariants[index]) {
                                            toggleVariantExpansion(index);
                                        }
                                    }}
                                    aria-label="Añadir detalle"
                                >
                                    <PlusIcon />
                                </button>
                                <button
                                    onClick={(e) => {
                                        e.preventDefault();
                                        toggleVariantExpansion(index);
                                    }}
                                    aria-label={
                                        expandedVariants[index]
                                            ? "Contraer detalles"
                                            : "Expandir detalles"
                                    }
                                >
                                    <ArrowIcon
                                        isExpanded={expandedVariants[index]}
                                    />
                                </button>
                                <button
                                    onClick={(e) => {
                                        e.preventDefault();
                                        handleDeleteVariant(index);
                                    }}
                                    aria-label="Eliminar variante"
                                >
                                    <DeleteIcon />
                                </button>
                            </div>

                            {expandedVariants[index] && (
                                <div className="mt-3 ml-6 space-y-3">
                                    {variant.details.map(
                                        (detail, detailIndex) => (
                                            <div
                                                key={detailIndex}
                                                className="flex items-center gap-2"
                                            >
                                                <TextInput
                                                    value={detail}
                                                    onChange={(e) =>
                                                        handleDetailChange(
                                                            index,
                                                            detailIndex,
                                                            e.target.value
                                                        )
                                                    }
                                                    className="flex-1"
                                                    aria-label="Detalle de variante"
                                                />
                                                <button
                                                    onClick={(e) => {
                                                        e.preventDefault();
                                                        handleDeleteDetail(
                                                            index,
                                                            detailIndex
                                                        );
                                                    }}
                                                    aria-label="Eliminar detalle"
                                                >
                                                    <DeleteIcon />
                                                </button>
                                            </div>
                                        )
                                    )}
                                </div>
                            )}
                        </div>
                    ))}
                </div>
            )}
        </div>
    );
};

export default ProductVariants;
