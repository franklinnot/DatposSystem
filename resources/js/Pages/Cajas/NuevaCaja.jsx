import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import TextInput from "@/Components/TextInput";
import InputError from "@/Components/InputError";
import InputLabel from "@/Components/InputLabel";
import SelectInput from "@/Components/SelectInput.jsx";
import { Head, useForm, usePage } from "@inertiajs/react";
import PrimaryButton from "@/Components/PrimaryButton";
import { useEffect, useState } from "react";
import { Inertia } from "@inertiajs/inertia";
import useToast from "@/Components/Toast";

export default function NuevaCaja({ auth }) {
    const { data, setData, post, reset, processing, errors } = useForm({
        nombre: "",
        codigo: "",
        id_sucursal: "",
    });

    const { toast } = usePage().props;
    const { showToast, ToastComponent } = useToast();

    // Mostrar toast si hay respuesta del servidor
    useEffect(() => {
        if (toast && toast.message) {
            showToast(toast.message, toast.type);
        }
    }, [toast]);

    const lista_sucursales = usePage()?.props?.sucursales;
    const [sucursales, setSucursales] = useState([]);

    useEffect(() => {
        setSucursales(lista_sucursales || []);
    }, []);

    // Se utiliza el manejo de cambios para la sucursal
    const handleSucursalChange = (e) => {
        const id_sucursal = e.target.value;
        setData("id_sucursal", id_sucursal);
    };

    const submit = (e) => {
        e.preventDefault();

        // Validación simple en cliente; si falta un campo obligatorio no se envía
        if (!data.nombre || !data.codigo || !data.id_sucursal) {
            showToast("Por favor, llena todos los campos.", "error");
            return;
        }

        post(route("cashregisters/new"), {
            onError: (serverErrors) => {
                // Manejar error duplicado de código con el campo correcto
                if (serverErrors.codigo) {
                    showToast(serverErrors.codigo, "error");
                }
            },
            onSuccess: () => {
                reset("nombre", "codigo");
            },
        });
    };

    return (
        <AuthenticatedLayout auth={auth}>
            <Head title="Nueva Caja" />
            <ToastComponent />
            <div className="grid grid-rows-[auto_1fr] gap-2 sm:gap-6 lg:gap-10">
                <div className="grid mx-5 mt-4 h-14 border-b-2 border-gray-200 items-start">
                    <h2 className="text-xl font-semibold">Nueva Caja</h2>
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

                    {/* Input de Sucursal */}
                    <div>
                        <InputLabel
                            htmlFor="sucursal"
                            value="Sucursal"
                            className="font-normal text-[#2B2B2B]"
                        />
                        <SelectInput
                            id="sucursal"
                            name="sucursal"
                            options={sucursales}
                            value={data.id_sucursal}
                            onChange={handleSucursalChange}
                            placeholder="Selecciona una sucursal"
                            closeOnSelect={true}
                        />
                        <InputError message={errors.id_sucursal} />
                    </div>

                    {/* Botón de Registrar */}
                    <div className="grid grid-flow-row items-center mt-3">
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
