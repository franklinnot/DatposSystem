import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import TextInput from "@/Components/TextInput";
import InputError from "@/Components/InputError";
import InputLabel from "@/Components/InputLabel";
import { Head, Link, useForm } from "@inertiajs/react";
import PrimaryButton from "@/Components/PrimaryButton";
import { useEffect } from "react";
import { usePage } from "@inertiajs/react";
import { Inertia } from "@inertiajs/inertia";
import { useState } from "react";

export default function NuevaSucursal({ auth }) {

    const [frontendErros, setFrontendErrors] = useState({});

    const { data, setData, post, processing, errors, reset } = useForm({
        // obligatorio
        nombre: "",
        codigo: "",
        // opcional
        departamento: "",
        ciudad: "",
        direccion: "",
        telefono: "",
    });

    const submit = (e) => {
        e.preventDefault();

        post(route("stores/new"), {
            onFinish: () => reset(),
        });
    };

    return (
        <AuthenticatedLayout auth={auth}>
            <Head title={`Dashboard`} />
            <div className="grid grid-rows-[auto_1fr] gap-2 sm:gap-6 lg:gap-8">
                <div className="grid mx-5 mt-4 h-14 border-b-2 border-gray-200 items-start">
                    <h2 className="text-xl font-semibold">Nueva Sucursal</h2>
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

                    {/* Input de Codigo */}
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
                            isFocused={true}
                            onChange={(e) => setData("codigo", e.target.value)}
                        />

                        <InputError message={errors.codigo} className="mt-2" />
                    </div>

                    {/* Input de Departamento */}
                    <div>
                        <InputLabel
                            htmlFor="departamento"
                            value="Departamento"
                            className="font-normal text-[#2B2B2B]"
                        />

                        <TextInput
                            id="departamento"
                            type="text"
                            name="departamento"
                            value={data.departamento}
                            className="mt-1 block w-full"
                            isFocused={true}
                            onChange={(e) =>
                                setData("departamento", e.target.value)
                            }
                        />

                        <InputError
                            message={errors.departamento}
                            className="mt-2"
                        />
                    </div>

                    {/* Input de Ciudad */}
                    <div>
                        <InputLabel
                            htmlFor="ciudad"
                            value="Ciudad"
                            className="font-normal text-[#2B2B2B]"
                        />

                        <TextInput
                            id="ciudad"
                            type="text"
                            name="ciudad"
                            value={data.ciudad}
                            className="mt-1 block w-full"
                            isFocused={true}
                            onChange={(e) => setData("ciudad", e.target.value)}
                        />

                        <InputError message={errors.ciudad} className="mt-2" />
                    </div>

                    {/* Input de Direccion */}
                    <div>
                        <InputLabel
                            htmlFor="direccion"
                            value="Direccion"
                            className="font-normal text-[#2B2B2B]"
                        />

                        <TextInput
                            id="direccion"
                            type="text"
                            name="direccion"
                            value={data.direccion}
                            className="mt-1 block w-full"
                            isFocused={true}
                            onChange={(e) =>
                                setData("direccion", e.target.value)
                            }
                        />

                        <InputError
                            message={errors.direccion}
                            className="mt-2"
                        />
                    </div>

                    {/* Input de Telefono */}
                    <div>
                        <InputLabel
                            htmlFor="telefono"
                            value="Teléfono (opcional)"
                            className="font-normal text-[#2B2B2B]"
                        />

                        <TextInput
                            id="telefono"
                            type="text"
                            name="telefono"
                            value={data.telefono}
                            className="mt-1 block w-full"
                            isFocused={true}
                            onChange={(e) =>
                                setData("telefono", e.target.value)
                            }
                        />

                        <InputError
                            message={errors.telefono}
                            className="mt-2"
                        />
                    </div>

                    {/* Boton de Registrar */}
                    <div className="flex items-center mt-3">
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
