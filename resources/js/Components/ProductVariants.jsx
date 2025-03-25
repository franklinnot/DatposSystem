import { useState, useRef, forwardRef } from "react";

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
            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21
               c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673
               a2.25 2.25 0 0 1-2.244 2.077H8.084
               a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79
               m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12
               .562c.34-.059.68-.114 1.022-.165m0
               0a48.11 48.11 0 0 1 3.478-.397m7.5
               0v-.916c0-1.18-.91-2.164-2.09-2.201
               a51.964 51.964 0 0 0-3.32 0
               c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5
               0a48.667 48.667 0 0 0-7.5 0"
        />
    </svg>
);

// TextInput para simplificar
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
        return (
            <input
                {...props}
                ref={ref}
                type={type}
                value={value}
                onChange={onChange}
                className={`transition-colors duration-200 bg-[#f2f2f2] text-gray-700
                            border-0 focus:ring-[#0875E4] rounded-lg shadow-sm ${className}`}
            />
        );
    }
);

export default function ProductVariants({ variants, onVariantsChange }) {
    // El componente ya no maneja un state propio de `variants`,
    // sino que los recibe del padre y los modifica a través de onVariantsChange.

    const [newVariant, setNewVariant] = useState("");
    const [expandedVariants, setExpandedVariants] = useState([]);
    const newVariantRef = useRef();

    // Añadir una nueva variante
    const handleAddVariant = (e) => {
        e.preventDefault();
        if (newVariant.trim()) {
            const newVariantData = {
                id: Date.now(), // Para key interna
                variante: newVariant.trim(),
                detalles: [], // Arreglo de objetos { detalle: "..." }
            };

            // Creamos la nueva lista
            const updatedVariants = [...variants, newVariantData];
            onVariantsChange(updatedVariants);

            // Manejo local de expandir/colapsar
            setExpandedVariants((prev) => [...prev, false]);
            setNewVariant("");
        }
    };

    // Alternar expandir una variante
    const toggleVariantExpansion = (index) => {
        setExpandedVariants((prev) =>
            prev.map((expanded, i) => (i === index ? !expanded : expanded))
        );
    };

    // Expandir/colapsar todas
    const toggleAllDetails = (e) => {
        e.preventDefault();
        if (variants.length === 0) return;
        const allExpanded = expandedVariants.every(Boolean);
        setExpandedVariants(expandedVariants.map(() => !allExpanded));
    };

    // Cambiar el texto de la variante
    const handleVariantChange = (index, value) => {
        const updatedVariants = variants.map((v, i) =>
            i === index ? { ...v, variante: value } : v
        );
        onVariantsChange(updatedVariants);
    };

    // Agregar un detalle
    const handleAddDetail = (variantIndex) => {
        const updatedVariants = variants.map((v, i) => {
            if (i === variantIndex) {
                return {
                    ...v,
                    detalles: [...v.detalles, { detalle: "" }],
                };
            }
            return v;
        });
        onVariantsChange(updatedVariants);

        // Si estaba colapsado, lo abrimos
        setExpandedVariants((prev) =>
            prev.map((expanded, i) => (i === variantIndex ? true : expanded))
        );
    };

    // Cambiar el texto de un detalle
    const handleDetailChange = (variantIndex, detailIndex, value) => {
        const updatedVariants = variants.map((v, i) => {
            if (i === variantIndex) {
                const newDetalles = v.detalles.map((d, j) =>
                    j === detailIndex ? { detalle: value } : d
                );
                return { ...v, detalles: newDetalles };
            }
            return v;
        });
        onVariantsChange(updatedVariants);
    };

    // Eliminar una variante
    const handleDeleteVariant = (index) => {
        const updatedVariants = variants.filter((_, i) => i !== index);
        onVariantsChange(updatedVariants);

        setExpandedVariants((prev) => prev.filter((_, i) => i !== index));
    };

    // Eliminar un detalle
    const handleDeleteDetail = (variantIndex, detailIndex) => {
        const updatedVariants = variants.map((v, i) => {
            if (i === variantIndex) {
                const newDetalles = v.detalles.filter(
                    (_, j) => j !== detailIndex
                );
                return { ...v, detalles: newDetalles };
            }
            return v;
        });
        onVariantsChange(updatedVariants);
    };

    // Determina si todos están expandidos
    const allExpanded = variants.length > 0 && expandedVariants.every(Boolean);

    return (
        <div className="mt-1 space-y-3">
            {/* Campo para añadir nuevas variantes */}
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
                    onClick={toggleAllDetails}
                    disabled={variants.length === 0}
                    className="disabled:opacity-50 disabled:cursor-not-allowed"
                    aria-label={allExpanded ? "Contraer todo" : "Expandir todo"}
                >
                    <ArrowIcon isExpanded={!allExpanded} />
                </button>
            </div>

            {/* Listado de variantes */}
            {variants.length > 0 && (
                <div className="space-y-4">
                    {variants.map((variant, index) => (
                        <div
                            key={variant.id}
                            className="bg-[#fafafa] p-4 rounded-lg shadow"
                        >
                            {/* Encabezado de cada variante */}
                            <div className="flex items-center gap-2">
                                <TextInput
                                    value={variant.variante}
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

                            {/* Detalles de la variante */}
                            {expandedVariants[index] && (
                                <div className="mt-3 ml-6 space-y-3">
                                    {variant.detalles.map(
                                        (detalleObj, detailIndex) => (
                                            <div
                                                key={`detail-${variant.id}-${detailIndex}`}
                                                className="flex items-center gap-2"
                                            >
                                                <TextInput
                                                    value={detalleObj.detalle}
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
}
