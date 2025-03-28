import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import TextInput from "@/Components/TextInput";
import InputError from "@/Components/InputError";
import InputLabel from "@/Components/InputLabel";
import NumberInput from "@/Components/NumberInput";
import SelectInput from "@/Components/SelectInput.jsx";
import { Head, useForm, usePage } from "@inertiajs/react";
import PrimaryButton from "@/Components/PrimaryButton";
import { useEffect, useState, useCallback } from "react";
import Checkbox from "@/Components/Checkbox";
import { Inertia } from "@inertiajs/inertia";
import ProductVariants from "@/Components/ProductVariants";
import ImageInput from "@/Components/ImageInput";
import useToast from "@/Components/Toast";

export default function NuevoProducto({ auth }) {
    const { data, setData, post, reset, processing, transform, errors } =
        useForm({
            nombre: "",
            codigo: "",
            id_familia: "",
            imagen: "",
            isc: "",
            tiene_igv: true,
            //
            id_unidad_medida: "",
            stock_minimo: "",
            stock_maximo: "",
            alerta_stock: "",
            alerta_vencimiento: "",
            //
            variantes: [],
        });

    // Aquí definimos un estado local "paralelo" para las variantes
    const [variants, setVariants] = useState([]);

    // Esta función se llama desde ProductVariants cuando cambian las variantes
    // Memorízala con useCallback para que no se cree en cada render.
    const handleVariantsChange = useCallback(
        (newVariants) => {
            // Actualiza el estado local
            setVariants(newVariants);
            // Actualiza el data.variantes del formulario (si lo necesitas)
            setData("variantes", newVariants);
        },
        [setData]
    );

    useEffect(() => {
        if (data.variantes?.length > 0) {
            setVariants(data.variantes);
        }
    }, []);

    // Estado para la URL de la imagen de vista previa
    const [imagenPreview, setImagenPreview] = useState(data.imagen);

    // Función para manejar el cambio de imagen
    const handleImageChange = (file, previewURL) => {
        setData("imagen", file);
        setImagenPreview(previewURL);
    };

    // Manejo de toast, familias, etc. (no modificado)
    const { toast } = usePage().props;
    const { showToast, ToastComponent } = useToast();
    const lista_familias = usePage()?.props?.familias;
    const lista_unidades = usePage()?.props?.unidades;
    const [familias, setFamilias] = useState(lista_familias || []);
    const [unidades, setUnidades] = useState(lista_unidades || []);
    const [es_bien, setEsBien] = useState(false);

    useEffect(() => {
        if (toast && toast.message) {
            showToast(toast.message, toast.type);
        }
    }, [toast]);

    const handleFamiliaChange = (e) => {
        const id_familia = e.target.value;
        setData("id_familia", id_familia);
        let familia = familias.find((item) => item.id === id_familia);
        if (familia && familia.tipo === "bien") {
            setEsBien(true);
        } else {
            setEsBien(false);
            reset(
                "id_unidad_medida",
                "stock_minimo",
                "stock_maximo",
                "alerta_stock",
                "alerta_vencimiento"
            );
        }
    };

    // NOTA: Evita reusar la misma función para unidad de medida:
    const handleUnidadMedidaChange = (e) => {
        setData("id_unidad_medida", e.target.value);
    };

    const submit = (e) => {
        e.preventDefault();
        //alert(JSON.stringify(data.variantes));

        // Validación simple en cliente; si falta un campo obligatorio no se envía
        if (!data.nombre || !data.codigo || !data.id_familia) {
            showToast(
                "Por favor, llena todos los campos obligatorios.",
                "error"
            );
            return;
        }

        if (es_bien && !data.id_unidad_medida) {
            showToast(
                "Por favor, llena todos los campos obligatorios.",
                "error"
            );
            return;
        }

        if(data.alerta_stock && (!data.stock_minimo || !data.stock_maximo)){
            showToast("Para recibir alertas de stock, debes ingresar el stock mínimo y máximo.", "error");
            return;
        }

        if (es_bien && data.stock_maximo < (data.stock_minimo * 2)) {
            showToast(
                "El stock máximo debe ser igual o mayor al doble del stock mínimo.",
                "error"
            );
            return;
        }

        // Set para detectar variantes duplicadas (ignorando mayúsculas/minúsculas)
        const variantSet = new Set();

        // Recorrer cada elemento en data.variantes para realizar las validaciones
        for (let i = 0; i < data.variantes.length; i++) {
            const item = data.variantes[i];

            // Convertimos el valor de variante a minúsculas y eliminamos espacios en blanco
            const variantValue = item.variante
                ? item.variante.trim().toLowerCase()
                : "";
            const details = item.detalles; // se espera que sea un array

            // Validaciones de campos vacíos:
            // 2. Si 'variante' está vacío pero 'detalles' tiene contenido:
            if (!variantValue && details && details.length > 0) {
                showToast(
                    "Para registrar una variante, debes ingresar su nombre.",
                    "error"
                );
                return;
            }
            // 3. Si 'variante' está lleno pero 'detalles' está vacío:
            if (variantValue && (!details || details.length === 0)) {
                showToast(
                    "Para registrar una variante, debes ingresar sus detalles.",
                    "error"
                );
                return;
            }

            // Validación: No se deben repetir las variantes (ignorando mayúsculas/minúsculas)
            if (variantSet.has(variantValue)) {
                showToast(
                    `La variante '${item.variante}' está duplicada.`,
                    "error"
                );
                return;
            }
            variantSet.add(variantValue);

            // Validación en 'detalles': Si hay más de un elemento, no se deben repetir los detalles (ignorar mayúsculas/minúsculas)
            const detailSet = new Set();
            for (let j = 0; j < details.length; j++) {
                const detailValue = details[j].detalle
                    ? details[j].detalle.trim().toLowerCase()
                    : "";
                if (detailSet.has(detailValue)) {
                    showToast(
                        `El detalle '${details[j].detalle}' de la variante '${item.variante}' está duplicado.`,
                        "error"
                    );
                    return;
                }
                detailSet.add(detailValue);
            }
        }

        post(route("products/new"), {
            onError: (serverErrors) => {
                // Manejar error duplicado de código con el campo correcto
                if (serverErrors.codigo) {
                    showToast(serverErrors.codigo, "error");
                }
            },
            onSuccess: () => {
                setUnidades(lista_unidades);
                setFamilias(lista_familias);

                if (!es_bien) {
                    reset("id_unidad_medida");
                }

                reset(
                    "nombre",
                    "codigo",
                    "imagen",
                    "isc",
                    "tiene_igv",
                    "stock_minimo",
                    "stock_maximo",
                    "alerta_stock",
                    "alerta_vencimiento",
                    "variantes"
                );
                setVariants([]);
                setImagenPreview("");
            },
        });
    };

    return (
        <AuthenticatedLayout auth={auth}>
            <Head title="Nuevo Producto" />
            <ToastComponent />
            <div className="grid grid-rows-[auto_1fr] gap-2 sm:gap-6 lg:gap-10">
                <div className="grid mx-5 mt-4 h-14 border-b-2 border-gray-200 items-start">
                    <h2 className="text-xl font-semibold">Nuevo Producto</h2>
                </div>
                <form
                    onSubmit={submit}
                    className="grid gap-6 sm:gap-8 justify-self-center bg-white rounded-2xl max-sm:w-full max-sm:max-w-[448px] sm:w-[75%] sm:max-w-[612px] px-5 py-6 pt-2"
                >
                    {/* Input de Nombre */}
                    <div>
                        <InputLabel
                            htmlFor="nombre"
                            value="Nombre"
                            className="font-normal text-[#2B2B2B]"
                        />
                        <TextInput
                            id="nombre"
                            type="text"
                            name="nombre"
                            value={data.nombre}
                            className="mt-1 block w-full"
                            isFocused={true}
                            onChange={(e) => setData("nombre", e.target.value)}
                        />
                        <InputError message={errors.nombre} className="mt-2" />
                    </div>

                    {/* Input de Código */}
                    <div>
                        <InputLabel
                            htmlFor="codigo"
                            value="Código"
                            className="font-normal text-[#2B2B2B]"
                        />
                        <TextInput
                            id="codigo"
                            type="text"
                            name="codigo"
                            value={data.codigo}
                            className="mt-1 block w-full"
                            onChange={(e) =>
                                setData("codigo", e.target.value.toUpperCase())
                            }
                        />
                        <InputError message={errors.codigo} className="mt-2" />
                    </div>

                    {/* Selector Familia */}
                    <div>
                        <InputLabel
                            htmlFor="familia"
                            value="Familia"
                            className="font-normal text-[#2B2B2B]"
                        />
                        <SelectInput
                            id="familia"
                            name="familia"
                            options={familias}
                            value={data.id_familia}
                            onChange={handleFamiliaChange}
                            placeholder="Selecciona una Familia"
                            closeOnSelect={true}
                        />
                        <InputError message={errors.id_familia} />
                    </div>

                    {/* Input de Imagen */}
                    <div>
                        <InputLabel
                            htmlFor="imagen"
                            value="Imagen (opcional)"
                            className="font-normal text-[#2B2B2B]"
                        />
                        <ImageInput
                            id="imagen"
                            name="imagen"
                            className="mt-1 block w-full"
                            value={imagenPreview}
                            onChange={handleImageChange}
                        />
                        <InputError message={errors.imagen} className="mt-2" />
                    </div>

                    {/* Input de isc */}
                    <div>
                        <InputLabel
                            htmlFor="isc"
                            value="ISC total (opcional)"
                            className="font-normal text-[#2B2B2B]"
                        />
                        <NumberInput
                            id="isc"
                            name="isc"
                            min="0"
                            value={data.isc}
                            className="mt-1 block w-full"
                            onChange={(e) => setData("isc", e.target.value)}
                        />
                        <InputError message={errors.isc} className="mt-2" />
                    </div>

                    {/* Checkbox para indicar si tiene igv */}
                    <div>
                        <label className="flex items-center">
                            <Checkbox
                                name="tiene_igv"
                                checked={data.tiene_igv}
                                className="border-gray-400"
                                onChange={(e) =>
                                    setData("tiene_igv", e.target.checked)
                                }
                            />
                            <span className="ms-2 text-sm font-normal text-[#2B2B2B]">
                                Tiene IGV
                            </span>
                        </label>
                    </div>

                    {es_bien && (
                        <>
                            {/* Selector Unidad de Medida */}
                            <div>
                                <InputLabel
                                    htmlFor="unidad_medida"
                                    value="Unidad de Medida"
                                    className="font-normal text-[#2B2B2B]"
                                />
                                <SelectInput
                                    id="unidad_medida"
                                    name="unidad_medida"
                                    options={unidades}
                                    value={data.id_unidad_medida}
                                    onChange={handleUnidadMedidaChange}
                                    placeholder="Selecciona una Unidad de Medida"
                                    closeOnSelect={true}
                                />
                                <InputError message={errors.id_unidad_medida} />
                            </div>

                            {/* stock minimo */}
                            <div>
                                <InputLabel
                                    htmlFor="stock_minimo"
                                    value="Stock mínimo (opcional)"
                                    className="font-normal text-[#2B2B2B]"
                                />
                                <NumberInput
                                    id="stock_minimo"
                                    name="stock_minimo"
                                    min="0"
                                    value={data.stock_minimo}
                                    className="mt-1 block w-full"
                                    onChange={(e) =>
                                        setData("stock_minimo", e.target.value)
                                    }
                                />
                                <InputError
                                    message={errors.stock_minimo}
                                    className="mt-2"
                                />
                            </div>

                            {/* stock maximo */}
                            <div>
                                <InputLabel
                                    htmlFor="stock_maximo"
                                    value="Stock máximo (opcional)"
                                    className="font-normal text-[#2B2B2B]"
                                />
                                <NumberInput
                                    id="stock_maximo"
                                    name="stock_maximo"
                                    min="0"
                                    value={data.stock_maximo}
                                    className="mt-1 block w-full"
                                    onChange={(e) =>
                                        setData("stock_maximo", e.target.value)
                                    }
                                />
                                <InputError
                                    message={errors.stock_maximo}
                                    className="mt-2"
                                />
                            </div>

                            {/* Checkbox para recibir alertas de stock */}
                            <div>
                                <label className="flex items-center">
                                    <Checkbox
                                        name="alerta_stock"
                                        checked={data.alerta_stock}
                                        className="border-gray-400"
                                        onChange={(e) =>
                                            setData(
                                                "alerta_stock",
                                                e.target.checked
                                            )
                                        }
                                    />
                                    <span className="ms-2 text-sm font-normal text-[#2B2B2B]">
                                        Recibir alertas de stock
                                    </span>
                                </label>
                            </div>

                            {/* Checkbox para recibir alertas por vencimiento */}
                            <div>
                                <label className="flex items-center">
                                    <Checkbox
                                        name="alerta_vencimiento"
                                        checked={data.alerta_vencimiento}
                                        className="border-gray-400"
                                        onChange={(e) =>
                                            setData(
                                                "alerta_vencimiento",
                                                e.target.checked
                                            )
                                        }
                                    />
                                    <span className="ms-2 text-sm font-normal text-[#2B2B2B]">
                                        Recibir alertas por vencimiento
                                    </span>
                                </label>
                            </div>
                        </>
                    )}

                    {/* ProductVariants controlado */}
                    <div className="mt-4">
                        <h3 className="text-lg font-semibold mb-2">
                            Variantes
                        </h3>
                        <ProductVariants
                            // Pasamos las variantes desde el padre
                            variants={variants}
                            // Pasamos la función para actualizarlas
                            onVariantsChange={handleVariantsChange}
                        />
                        {/* Aquí no hay setData("variantes") porque ya se maneja en handleVariantsChange */}
                        <InputError message={errors.variantes} />
                    </div>

                    {/* Botón de Registrar */}
                    <div className="grid grid-flow-row items-center mt-2">
                        <PrimaryButton
                            className="font-medium text-md w-full justify-center rounded-lg bg-gradient-to-r from-[#0B6ACB] via-[#0875E4] to-[#0B6ACB] hover:bg-[#3c78fa] focus:ring-[#3c78fa]"
                            disabled={processing}
                        >
                            Registrar
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </AuthenticatedLayout>
    );
}
