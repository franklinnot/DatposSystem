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

export default function NuevaUnidadMedida({ auth }) {
    const { data, setData, post, reset, processing, transform, errors } =
        useForm({
            codigo: "",
            nombre: "",
            descripcion: "",
        });

    const { toast } = usePage().props;
    const { showToast, ToastComponent } = useToast();

    // Mostrar toast si hay respuesta del servidor
    useEffect(() => {
        if (toast && toast.message) {
            showToast(toast.message, toast.type);
        }
    }, [toast]);

    const submit = (e) => {
        e.preventDefault();

        // Validación simple en cliente; si falta un campo obligatorio no se envía
        if (
            !data.codigo ||
            !data.nombre
        ) {
            showToast("Por favor, llena todos los campos obligatorios.", "error");
            return;
        }

        post(route("units/new"), {
            onError: (serverErrors) => {
                // Manejar error duplicado de código con el campo correcto
                if (serverErrors.codigo) {
                    showToast(serverErrors.codigo, "error");
                }
            },
            onSuccess: () => {
                reset();
            },
        });
    };

    return (
        <AuthenticatedLayout auth={auth}>
            <Head title="Nueva Unidad de Medida" />
            <ToastComponent />
            <div className="grid grid-rows-[auto_1fr] gap-2 sm:gap-6 lg:gap-10">
                <div className="grid mx-5 mt-4 h-14 border-b-2 border-gray-200 items-start">
                    <h2 className="text-xl font-semibold">
                        Nueva Unidad de Medida
                    </h2>
                </div>
                <form
                    onSubmit={submit}
                    className="grid gap-6 sm:gap-8 justify-self-center bg-white rounded-2xl max-sm:w-full max-sm:max-w-[448px] sm:w-[75%] sm:max-w-[612px] px-5 py-6"
                >
                    {/* Input de Codigo */}
                    <div>
                        <InputLabel
                            htmlFor="codigo"
                            value="Código"
                            className="font-normal text-[#2B2B2B]"
                        />
                        <TextInput
                            id="codigo"
                            name="codigo"
                            type="text"
                            maxLength="8"
                            value={data.codigo}
                            className="mt-1 block w-full"
                            isFocused={true}
                            onChange={(e) =>
                                setData("codigo", e.target.value.toUpperCase())
                            }
                        />
                        <InputError message={errors.codigo} className="mt-2" />
                    </div>

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
                            onChange={(e) => setData("nombre", e.target.value)}
                        />
                        <InputError message={errors.nombre} className="mt-2" />
                    </div>

                    {/* Input de Descripcion */}
                    <div>
                        <InputLabel
                            htmlFor="descripcion"
                            value="Descripción (opcional)"
                            className="font-normal text-[#2B2B2B]"
                        />
                        <TextInput
                            id="descripcion"
                            type="text"
                            name="descripcion"
                            value={data.descripcion}
                            className="mt-1 block w-full"
                            onChange={(e) =>
                                setData("descripcion", e.target.value)
                            }
                        />
                        <InputError
                            message={errors.descripcion}
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
                </form>
            </div>
        </AuthenticatedLayout>
    );
}
