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
            dni: "",
            nombre: "",
            id_rol: "",
            email: "",
            password: "",
        });

    const { toast } = usePage().props;
    const { showToast, ToastComponent } = useToast();

    // Mostrar toast si hay respuesta del servidor
    useEffect(() => {
        if (toast && toast.message) {
            showToast(toast.message, toast.type);
        }
    }, [toast]);

    const lista_roles = usePage()?.props?.roles;
    const [roles, setRoles] = useState([]);

    useEffect(() => {
        setRoles(lista_roles || []);
        setData({
            ...data,
            email: "",
            password: "",
        });
    }, []);

    // Se utiliza el manejo de cambios propuesto para el departmento.
    const handleRolChange = (e) => {
        const id_rol = e.target.value;
        setData("id_rol", id_rol);
    };

    const submit = (e) => {
        e.preventDefault();

        // Validación simple en cliente; si falta un campo obligatorio no se envía
        if (
            !data.dni ||
            !data.nombre ||
            !data.id_rol ||
            !data.email ||
            !data.password
        ) {
            showToast("Por favor, llena todos los campos.", "error");
            return;
        }

        post(route("users/new"), {
            onError: (serverErrors) => {
                // Manejar error duplicado de código con el campo correcto
                if (serverErrors.dni) {
                    showToast(serverErrors.dni, "error");
                }
                if (serverErrors.email) {
                    showToast(serverErrors.email, "error");
                }
            },
            onSuccess: () => {
                reset("nombre", "dni", "email", "password");
            },
        });
    };

    return (
        <AuthenticatedLayout auth={auth}>
            <Head title="Nuevo Usuario" />
            <ToastComponent />
            <div className="grid grid-rows-[auto_1fr] gap-2 sm:gap-6 lg:gap-10">
                <div className="grid mx-5 mt-4 h-14 border-b-2 border-gray-200 items-start">
                    <h2 className="text-xl font-semibold">Nuevo Usuario</h2>
                </div>
                <form
                    onSubmit={submit}
                    className="grid gap-6 sm:gap-8 justify-self-center bg-white rounded-2xl max-sm:w-full max-sm:max-w-[448px] sm:w-[75%] sm:max-w-[612px] px-5 py-6"
                >
                    {/* Input de DNI */}
                    <div>
                        <InputLabel
                            htmlFor="dni"
                            value="DNI"
                            className="font-normal text-[#2B2B2B]"
                        />
                        <TextInput
                            id="dni"
                            type="text"
                            maxLength="8"
                            name="dni"
                            value={data.dni}
                            className="mt-1 block w-full"
                            isFocused={true}
                            onChange={(e) => setData("dni", e.target.value)}
                        />
                        <InputError message={errors.dni} className="mt-2" />
                    </div>

                    {/* Input de Nombre */}
                    <div>
                        <InputLabel
                            htmlFor="nombre"
                            value="Apellidos y Nombres"
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

                    {/* Input de Roles */}
                    <div>
                        <InputLabel
                            htmlFor="rol"
                            value="Rol"
                            className="font-normal text-[#2B2B2B]"
                        />
                        <SelectInput
                            id="rol"
                            name="rol"
                            options={roles}
                            value={data.rol}
                            onChange={handleRolChange}
                            placeholder="Selecciona un Rol"
                            closeOnSelect={true}
                        />
                        <InputError message={errors.rol} className="mt-2" />
                    </div>

                    {/* Input de Correo */}
                    <div>
                        <InputLabel
                            htmlFor="email"
                            value="Correo"
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

                    {/* Input de contraseña */}
                    <div>
                        <InputLabel
                            htmlFor="password"
                            value="Contraseña"
                            className="font-normal text-[#2B2B2B]"
                        />

                        <TextInput
                            id="password"
                            type="password"
                            name="password"
                            value={data.password}
                            className="mt-1 block w-full"
                            onChange={(e) =>
                                setData("password", e.target.value)
                            }
                            autoComplete="new-password"
                        />

                        <InputError
                            message={errors.password}
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
