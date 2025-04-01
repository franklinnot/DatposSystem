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

export default function NuevoAsociado({ auth }) {
    const { data, setData, post, reset, processing, errors } = useForm({
        nombre: "",
        tipo_asociado: "",
        ruc: "",
        dni: "",
        telefono: "",
        email: "",
    });

    const { toast } = usePage().props;
    const { showToast, ToastComponent } = useToast();

    // Mostrar toast si hay respuesta del servidor
    useEffect(() => {
        if (toast && toast.message) {
            showToast(toast.message, toast.type);
        }
    }, [toast]);

    const lista_tipos = [
        { id: 1, name: "Cliente" },
        { id: 2, name: "Proveedor" },
    ];
    const [tipos, setTipos] = useState([]);

    useEffect(() => {
        setTipos(lista_tipos || []);
    }, []);

    // Se utiliza el manejo de cambios 
    const handleTipoChange = (e) => {
        const value = e.target.value;
        setData("tipo_asociado", value);
    };

    const submit = (e) => {
        e.preventDefault();

        // Validación simple en cliente; si falta un campo obligatorio no se envía
        if (!data.nombre || !data.tipo_asociado) {
            showToast("Por favor, llena todos los campos obligatorios.", "error");
            return;
        }

        if (data.ruc == "" && data.dni == "") {
            showToast(
                "Por favor, ingresa al menos un RUC o DNI.",
                "error"
            );
            return;
        }

        post(route("partners/new"), {
            onError: (serverErrors) => {
                // Manejar error duplicado de código con el campo correcto
                if (serverErrors.ruc) {
                    showToast(serverErrors.ruc, "error");
                }
            },
            onSuccess: () => {
                reset();
            },
        });
    };

    return (
        <AuthenticatedLayout auth={auth}>
            <Head title="Nuevo Asociado" />
            <ToastComponent />
            <div className="grid grid-rows-[auto_1fr] gap-2 sm:gap-6 lg:gap-10">
                <div className="grid mx-5 mt-4 h-14 border-b-2 border-gray-200 items-start">
                    <h2 className="text-xl font-semibold">Nuevo Asociado</h2>
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

                    {/* Input de tipo de asociado */}
                    <div>
                        <InputLabel
                            htmlFor="tipo_asociado"
                            value="Tipo de Asociado"
                            className="font-normal text-[#2B2B2B]"
                        />
                        <SelectInput
                            id="tipo_asociado"
                            name="tipo_asociado"
                            options={tipos}
                            value={data.tipo_asociado}
                            onChange={handleTipoChange}
                            placeholder="Selecciona un tipo de asociado"
                            closeOnSelect={true}
                        />
                        <InputError message={errors.tipo_asociado} />
                    </div>

                    {/* Input de ruc */}
                    <div>
                        <InputLabel
                            htmlFor="ruc"
                            value="RUC (opcional)"
                            className="font-normal text-[#2B2B2B]"
                        />
                        <TextInput
                            id="ruc"
                            type="text"
                            name="ruc"
                            maxLength="11"
                            value={data.ruc}
                            className="mt-1 block w-full"
                            onChange={(e) => setData("ruc", e.target.value)}
                        />
                        <InputError message={errors.ruc} className="mt-2" />
                    </div>

                    {/* Input de dni */}
                    <div>
                        <InputLabel
                            htmlFor="dni"
                            value="DNI (opcional)"
                            className="font-normal text-[#2B2B2B]"
                        />
                        <TextInput
                            id="dni"
                            type="text"
                            name="dni"
                            maxLength="8"
                            value={data.dni}
                            className="mt-1 block w-full"
                            onChange={(e) => setData("dni", e.target.value)}
                        />
                        <InputError message={errors.dni} className="mt-2" />
                    </div>

                    {/* Input de Correo */}
                    <div>
                        <InputLabel
                            htmlFor="email"
                            value="Correo (opcional)"
                            className="font-normal text-[#2B2B2B]"
                        />
                        <TextInput
                            id="email"
                            type="email"
                            name="email"
                            value={data.email}
                            className="mt-1 block w-full"
                            onChange={(e) => setData("email", e.target.value)}
                            autoComplete="off"
                        />
                        <InputError message={errors.email} className="mt-2" />
                    </div>

                    {/* Input de telefono */}
                    <div>
                        <InputLabel
                            htmlFor="telefono"
                            value="Teléfono (opcional)"
                            className="font-normal text-[#2B2B2B]"
                        />
                        <TextInput
                            id="telefono"
                            type="tel"
                            name="telefono"
                            value={data.telefono}
                            className="mt-1 block w-full"
                            onChange={(e) =>
                                setData("telefono", e.target.value)
                            }
                        />
                        <InputError
                            message={errors.telefono}
                            className="mt-2"
                        />
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

                    <p className="block text-sm font-light text-gray-500">
                        Debe ingresar al menos un RUC o DNI*
                    </p>

                </form>
            </div>
        </AuthenticatedLayout>
    );
}
