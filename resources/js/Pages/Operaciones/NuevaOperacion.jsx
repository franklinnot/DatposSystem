import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import TextInput from "@/Components/TextInput";
import MultiSelectInput from "@/Components/MultiSelectInput";
import InputError from "@/Components/InputError";
import InputLabel from "@/Components/InputLabel";
import SelectInput from "@/Components/SelectInput.jsx";
import { Head, useForm, usePage } from "@inertiajs/react";
import PrimaryButton from "@/Components/PrimaryButton";
import NumberInput from "@/Components/NumberInput.jsx";
import { useEffect, useState } from "react";
import { Inertia } from "@inertiajs/inertia";
import useToast from "@/Components/Toast";
import { ArrowDown } from "@/Components/Icons.jsx";

export default function NuevaOperacion({ auth }) {
    const { data, setData, post, reset, processing, errors } = useForm({
        id_tipo_operacion: "",
        id_asociado: "",
        id_almacen_origen: "",
        id_almacen_destino: "",
        detalle: [],
    });

    const { toast } = usePage().props;
    const { showToast, ToastComponent } = useToast();

    // Mostrar toast si hay respuesta del servidor
    useEffect(() => {
        if (toast && toast.message) {
            showToast(toast.message, toast.type);
        }
    }, [toast]);

    const lista_tipos = usePage().props.tipos_operacion;
    const lista_asociados = usePage().props.asociados;
    const lista_almacenes = usePage().props.almacenes;
    const lista_productos = usePage().props.productos;

    const [tipos, setTipos] = useState([]);
    const [asociados, setAsociados] = useState([]);
    const [almacenes, setAlmacenes] = useState([]);
    const [productos, setProductos] = useState([]);
    const [expandedItems, setExpandedItems] = useState([]);
    const [tipoSeleccionado, setTipoSeleccionado] = useState(false);
    const [tipoMov, setTipoMov] = useState();

    // Función para actualizar cantidad o costo_unitario en data.detalle
    const actualizarDetalle = (id, campo, valor) => {
        setData(
            "detalle",
            data.detalle.map((item) =>
                item.id === id ? { ...item, [campo]: valor } : item
            )
        );
    };

    const toggleItem = (id) => {
        setExpandedItems((prev) =>
            prev.includes(id)
                ? prev.filter((item) => item !== id)
                : [...prev, id]
        );
    };

    // Actualiza las listas de opciones
    useEffect(() => {
        setTipos(lista_tipos || []);
        setAsociados(lista_asociados || []);
        setAlmacenes(lista_almacenes || []);
        setProductos(lista_productos || []);
    }, []);

    // Cambio de tipo de operación
    const handleTipoChange = (e) => {
        const value = e.target.value;
        const tipo = lista_tipos.find((t) => t.id == value)?.tipo_movimiento;
        if (value && tipo) {
            setTipoMov(tipo == 1 ? "entrada" : "salida");
            setTipoSeleccionado(true);
        }
        else{
            setTipoSeleccionado(false);
        }
        setData("id_tipo_operacion", value);
    };

    // Cambio de asociado
    const handleAsociadoChange = (e) => {
        const value = e.target.value;
        setData("id_asociado", value);
    };

    // Cambio de almacén de origen
    const handleAlOrigenChange = (e) => {
        const value = e.target.value;
        setData("id_almacen_origen", value);
    };

    // Cambio de almacén de destino
    const handleAlDestinoChange = (e) => {
        const value = e.target.value;
        setData("id_almacen_destino", value);
    };

    // Cambio en los productos seleccionados: MultiSelectInput envía { target: { value: [array de IDs] } }
    const handleProductosChange = (e) => {
        const selectedOptions = e.target.value;
        console.log("Productos seleccionados:", selectedOptions);
        setData(
            "detalle",
            selectedOptions.map((id) => ({
                id,
                cantidad: "",
                costo_unitario: "",
            }))
        );
    };

    const submit = (e) => {
        e.preventDefault();

        // Validación simple en cliente
        if (!data.id_tipo_operacion) {
            showToast("Por favor, selecciona el tipo de operación.", "error");
            return;
        }

        // si es una entrada, debe seleccionar un almacén de destino
        if (
            lista_tipos.find((t) => t.id == data.id_tipo_operacion)
                .tipo_movimiento == 1 &&
            !data.id_almacen_destino
        ) {
            showToast(
                "Para realizar una operación de entrada, debes seleccionar el almacén de destino.",
                "error"
            );
            return;
        }

        // si es una salida, debe seleccionar un almacén de origen
        if (
            lista_tipos.find((t) => t.id == data.id_tipo_operacion)
                .tipo_movimiento == 2 &&
            !data.id_almacen_origen
        ) {
            showToast(
                "Para realizar una operación de salida, debes seleccionar el almacén de origen.",
                "error"
            );
            return;
        }

        // si ha seleccionado ambos almacenes, debe ser diferente
        if (data.id_almacen_origen == data.id_almacen_destino) {
            showToast(
                "No puedes seleccionar el mismo almacén de origen y destino.",
                "error"
            );
            return;
        }

        // verificar que el campo detalle no este vacío
        if (!data.detalle || data.detalle.length == 0) {
            showToast(
                "Por favor, selecciona los productos para el detalle de operación.",
                "error"
            );
            return;
        }

        // verificar que el campos cantidad no este vacío y sea mayor a 0
        for (let i = 0; i < data.detalle.length; i++) {
            if (!data.detalle[i].cantidad || !data.detalle[i].cantidad > 0) {
                showToast(
                    "Cada producto en el detalle debe tener una cantidad mayor a 0.",
                    "error"
                );
                return;
            }
        }

        post(route("operations/new"), {
            onSuccess: () => {
                reset();
            },
        });
    };

    return (
        <AuthenticatedLayout auth={auth}>
            <Head title="Nueva operación" />
            <ToastComponent />
            <div className="grid grid-rows-[auto_1fr] gap-2 sm:gap-6 lg:gap-10">
                <div className="grid mx-5 mt-4 h-14 border-b-2 border-gray-200 items-start">
                    <h2 className="text-xl font-semibold">Nueva operación</h2>
                </div>
                <form
                    onSubmit={submit}
                    className="grid gap-6 sm:gap-8 justify-self-center bg-white rounded-2xl max-sm:w-full max-sm:max-w-[448px] sm:w-[75%] sm:max-w-[612px] px-5 py-6"
                >
                    {/* Input de tipo de operación */}
                    <div>
                        <InputLabel
                            htmlFor="id_tipo_operacion"
                            value="Tipo de Operación"
                            className="font-normal text-[#2B2B2B]"
                        />
                        <SelectInput
                            id="id_tipo_operacion"
                            name="id_tipo_operacion"
                            options={tipos}
                            value={data.id_tipo_operacion}
                            onChange={handleTipoChange}
                            placeholder="Selecciona un tipo de operación"
                            closeOnSelect={true}
                        />
                        <InputError message={errors.id_tipo_operacion} />
                    </div>

                    {/* Input de tipo de asociado */}
                    <div>
                        <InputLabel
                            htmlFor="id_asociado"
                            value="Asociado (opcional)"
                            className="font-normal text-[#2B2B2B]"
                        />
                        <SelectInput
                            id="id_asociado"
                            name="id_asociado"
                            options={asociados}
                            value={data.id_asociado}
                            onChange={handleAsociadoChange}
                            placeholder="Selecciona un asociado"
                            closeOnSelect={true}
                        />
                        <InputError message={errors.id_asociado} />
                    </div>

                    {/* Input de almacén de origen */}
                    <div>
                        <InputLabel
                            htmlFor="id_almacen_origen"
                            value="Almacén de Origen"
                            className="font-normal text-[#2B2B2B]"
                        />
                        <SelectInput
                            id="id_almacen_origen"
                            name="id_almacen_origen"
                            options={almacenes}
                            value={data.id_almacen_origen}
                            onChange={handleAlOrigenChange}
                            placeholder="Selecciona un almacén de origen"
                            closeOnSelect={true}
                        />
                        <InputError message={errors.id_almacen_origen} />
                    </div>

                    {/* Input de almacén de destino */}
                    <div>
                        <InputLabel
                            htmlFor="id_almacen_destino"
                            value="Almacén de Destino"
                            className="font-normal text-[#2B2B2B]"
                        />
                        <SelectInput
                            id="id_almacen_destino"
                            name="id_almacen_destino"
                            options={almacenes}
                            value={data.id_almacen_destino}
                            onChange={handleAlDestinoChange}
                            placeholder="Selecciona un almacén de destino"
                            closeOnSelect={true}
                        />
                        <InputError message={errors.id_almacen_destino} />
                    </div>

                    {/* Detalle de productos */}
                    {tipoSeleccionado && (
                        <div>
                            <h3 className="text-lg font-semibold mb-2">
                                Detalle de la {tipoMov}
                            </h3>
                            <MultiSelectInput
                                id="detalle"
                                name="detalle"
                                options={productos}
                                value={data.detalle.map((item) => item.id)}
                                onChange={handleProductosChange}
                                placeholder="Selecciona los productos..."
                                closeOnSelect={true}
                            />

                            {/* Lista de productos seleccionados */}
                            {data.detalle.length > 0 && (
                                <div className="relative mt-4">
                                    <p className="text-sm font-normal text-[#2B2B2B]">
                                        Productos seleccionados:
                                    </p>

                                    <div className="mt-4 grid gap-3 items-center">
                                        {data.detalle.map((item, index) => {
                                            const producto =
                                                lista_productos.find(
                                                    (p) => p.id == item.id
                                                );
                                            const isExpanded =
                                                expandedItems.includes(item.id);
                                            return (
                                                <div
                                                    key={index}
                                                    className="w-full"
                                                >
                                                    {/* Botón de producto */}
                                                    <button
                                                        type="button"
                                                        onClick={() =>
                                                            toggleItem(item.id)
                                                        }
                                                        className="flex justify-between w-full py-2 px-4 rounded-lg bg-[#f1f2f5] text-[#2B2B2B] hover:bg-[#e3ebff] focus:outline-none"
                                                    >
                                                        <span>
                                                            {producto?.name ||
                                                                "No encontrado"}
                                                        </span>
                                                        <ArrowDown
                                                            size="16"
                                                            className={`relative top-1 transition-transform duration-300 ${
                                                                isExpanded
                                                                    ? "rotate-180"
                                                                    : "rotate-0"
                                                            }`}
                                                        />
                                                    </button>

                                                    {/* Contenedor de detalles con animación */}
                                                    <div
                                                        className={`overflow-hidden transition-[max-height] duration-300 ease-in-out ${
                                                            isExpanded
                                                                ? "max-h-40"
                                                                : "max-h-0"
                                                        }`}
                                                    >
                                                        <div className="px-6 py-4 mt-2 border rounded-lg bg-white shadow-md">
                                                            {/* <strong className="block text-base text-gray-800">
                                                            Datos
                                                        </strong> */}
                                                            <div className="mt-2 flex items-center gap-2">
                                                                <InputLabel
                                                                    value="Cantidad:"
                                                                    className="font-normal text-[#2B2B2B]"
                                                                />
                                                                <NumberInput
                                                                    min="0"
                                                                    value={
                                                                        item.cantidad
                                                                    }
                                                                    className="block w-full py-1 mr-6"
                                                                    onChange={(
                                                                        e
                                                                    ) =>
                                                                        actualizarDetalle(
                                                                            item.id,
                                                                            "cantidad",
                                                                            Number(
                                                                                e
                                                                                    .target
                                                                                    .value
                                                                            )
                                                                        )
                                                                    }
                                                                />
                                                            </div>
                                                            <div className="mt-2 flex items-center gap-2">
                                                                <InputLabel
                                                                    value="Costo unitario:"
                                                                    className="text-nowrap font-normal text-[#2B2B2B]"
                                                                />
                                                                <NumberInput
                                                                    min="0"
                                                                    value={
                                                                        item.costo_unitario
                                                                    }
                                                                    className="block w-full py-1 mr-6"
                                                                    onChange={(
                                                                        e
                                                                    ) =>
                                                                        actualizarDetalle(
                                                                            item.id,
                                                                            "costo_unitario",
                                                                            Number(
                                                                                e
                                                                                    .target
                                                                                    .value
                                                                            )
                                                                        )
                                                                    }
                                                                />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            );
                                        })}
                                    </div>
                                </div>
                            )}
                            <InputError
                                message={errors.detalle}
                                className="mt-2"
                            />
                        </div>
                    )}

                    {/* Botón de Registrar */}
                    <div className="grid grid-flow-row items-center">
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
