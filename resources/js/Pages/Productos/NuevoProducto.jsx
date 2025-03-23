import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import TextInput from "@/Components/TextInput";
import InputError from "@/Components/InputError";
import InputLabel from "@/Components/InputLabel";
import NumberInput from "@/Components/NumberInput";
import SelectInput from "@/Components/SelectInput.jsx";
import { Head, useForm, usePage } from "@inertiajs/react";
import PrimaryButton from "@/Components/PrimaryButton";
import { useEffect, useState } from "react";
import Checkbox from "@/Components/Checkbox";
import { Inertia } from "@inertiajs/inertia";
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
            fecha_vencimiento: "",
            alerta_stock: "",
            alerta_vencimiento: "",
        });

    const { toast } = usePage().props;
    const { showToast, ToastComponent } = useToast();

    // Mostrar toast si hay respuesta del servidor
    useEffect(() => {
        if (toast && toast.message) {
            showToast(toast.message, toast.type);
        }
    }, [toast]);

    const lista_familias = usePage()?.props?.familias;
    const lista_unidades = usePage()?.props?.unidades;
    const [familias, setFamilias] = useState([]);
    const [unidades, setUnidades] = useState([]);
    const [es_bien, setEsBien] = useState(false);

    useEffect(() => {
        setFamilias(lista_familias || []);
        setUnidades(lista_unidades || []);
    }, []);

    // Se utiliza el manejo de cambios propuesto para el departmento.
    const handleFamiliaChange = (e) => {
        const id_familia = e.target.value;
        setData("id_familia", id_familia);
        let familia = lista_familias.find((item) => item.id === id_familia);
        if (familia && familia.tipo == "bien") {
            setEsBien(true);
        } else {
            setEsBien(false);
        }
    };

    const submit = (e) => {
        e.preventDefault();

        // Validación simple en cliente; si falta un campo obligatorio no se envía
        if (!data.nombre || !data.codigo || !data.id_familia) {
            showToast(
                "Por favor, llena todos los campos obligatorios.",
                "error"
            );
            return;
        }

        if(data.id_familia && es_bien && !data.id_unidad_medida){
            showToast(
                "Por favor, llena todos los campos obligatorios.",
                "error"
            );
            return;
        }

        // Se transforman los datos para un registro correcto
        transform((data) => ({
            ...data,
        }));

        post(route("products/new"), {
            onError: (serverErrors) => {
                // Manejar error duplicado de código con el campo correcto
                if (serverErrors.codigo) {
                    showToast(serverErrors.codigo, "error");
                }
            },
            onSuccess: () => {
                reset(
                    "nombre",
                    "codigo",
                    "imagen",
                    "isc",
                    "tiene_igv",
                    "id_unidad_medida",
                    "stock_minimo",
                    "stock_maximo",
                    "fecha_vencimiento",
                    "alerta_stock",
                    "alerta_vencimiento",
                );
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
                    className="grid gap-6 sm:gap-8 justify-self-center bg-white rounded-2xl max-sm:w-full max-sm:max-w-[448px] sm:w-[75%] sm:max-w-[612px] px-5 py-6"
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

                    {/* Input de Familia */}
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
                            // Ahora el componente retorna el objeto File, no un string Base64
                            onChange={(file) => setData("imagen", file)}
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
                            {/* Input de Unidad de medida */}
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
                                    onChange={handleFamiliaChange}
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

                            {/* Fecha de Vencimiento */}
                            <div>
                                <InputLabel
                                    htmlFor="fecha_vencimiento"
                                    value="Fecha de vencimiento (opcional)"
                                    className="font-normal text-[#2B2B2B]"
                                />
                                <TextInput
                                    id="fecha_vencimiento"
                                    type="date"
                                    name="fecha_vencimiento"
                                    value={data.fecha_vencimiento}
                                    className="mt-1 block w-full"
                                    onChange={(e) =>
                                        setData(
                                            "fecha_vencimiento",
                                            e.target.value
                                        )
                                    }
                                />
                                <InputError
                                    message={errors.codigo}
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
