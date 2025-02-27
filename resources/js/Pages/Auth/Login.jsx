import { useEffect } from "react";
import Checkbox from "@/Components/Checkbox";
import GuestLayout from "@/Layouts/GuestLayout";
import InputError from "@/Components/InputError";
import InputLabel from "@/Components/InputLabel";
import PrimaryButton from "@/Components/PrimaryButton";
import TextInput from "@/Components/TextInput";
import { Head, Link, useForm } from "@inertiajs/react";
import { Inertia } from "@inertiajs/inertia"; // Importa Inertia
import ApplicationLogo from "@/Components/ApplicationLogo";

export default function Login({ status }) {
    // quizas, en algun momento agregar canResetPassword como prop
    const { data, setData, post, processing, errors, reset } = useForm({
        email: "",
        password: "",
        remember: false,
    });

    //
    useEffect(() => {
        return () => {
            reset("password");
        };
    }, []);

    const submit = (e) => {
        e.preventDefault();

        post(route("login"), {
            onFinish: () => reset("password"),
        });
    };

    //#region Construir la URL completa para el mensaje de WhatsApp
    const telf = "941225240";
    const message =
        "¡Hola 👋! Estoy interesado en los servicios SaaS de Microservice y me gustaría saber más. ¿Podemos hablar?";
    const url_message =
        "https://web.whatsapp.com/send?phone=51" +
        encodeURIComponent(telf) +
        "&text=" +
        encodeURIComponent(message) +
        "&type=phone_number&app_absent=0";
    //#endregion

    return (
        <GuestLayout>
            <Head title={`Iniciar Sesión`} />

            {/* Contenedor de todo el login */}
            <div
                className="grid place-items-center px-6 py-12 overflow-hidden rounded-2xl gap-5 sm:gap-3
                            w-full max-w-md sm:bg-white sm:shadow-xl lg:shadow-none"
            >
                {/* Titulo y saludo */}
                <div className="grid place-items-center text-center gap-8">
                    <Link href={route("dashboard")}>
                        <ApplicationLogo size={72} />
                    </Link>
                    <div className="grid place-items-center">
                        <h2 className="text-[1.68rem] font-bold">
                            Inicia sesión en tu cuenta
                        </h2>
                        <p className="w-72 text-gray-700 ">
                            Bienvenido de nuevo! Por favor, ingresa tu
                            información
                        </p>
                    </div>
                </div>

                {status && (
                    <div className="mb-4 font-medium text-sm text-green-600">
                        {status}
                    </div>
                )}

                {/* Formulario */}
                <form
                    onSubmit={submit}
                    className="grid gap-2 bg-white shadow-md rounded-2xl sm:shadow-none w-full px-5 py-6"
                >
                    {/* Input de email */}
                    <div>
                        <InputLabel
                            htmlFor="email"
                            value="Correo"
                            className="font-semibold"
                        />

                        <TextInput
                            id="email"
                            type="email"
                            name="email"
                            value={data.email}
                            className="mt-1 block w-full"
                            autoComplete="username"
                            isFocused={true}
                            onChange={(e) => setData("email", e.target.value)}
                        />

                        <InputError message={errors.email} className="mt-2" />
                    </div>

                    {/* Input de contraseña */}
                    <div className="mt-4">
                        <InputLabel
                            htmlFor="password"
                            value="Contraseña"
                            className="font-semibold"
                        />

                        <TextInput
                            id="password"
                            type="password"
                            name="password"
                            value={data.password}
                            className="mt-1 block w-full"
                            autoComplete="current-password"
                            onChange={(e) =>
                                setData("password", e.target.value)
                            }
                        />

                        <InputError
                            message={errors.password}
                            className="mt-2"
                        />
                    </div>

                    {/* Checkbox de recordarme */}
                    <div className="block mt-4">
                        <label className="flex items-center">
                            <Checkbox
                                name="remember"
                                checked={data.remember}
                                onChange={(e) =>
                                    setData("remember", e.target.checked)
                                }
                            />
                            <span className="ms-2 text-sm font-medium text-gray-600">
                                Recuérdame
                            </span>
                        </label>
                    </div>

                    <div className="flex items-center justify-end mt-4">
                        {/* {canResetPassword && (
                        <Link
                            href={route("password.request")}
                            className="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        >
                            Forgot your password?
                        </Link>
                    )} */}

                        <PrimaryButton
                            className="font-medium text-md w-full justify-center rounded-lg"
                            disabled={processing}
                        >
                            Iniciar sesión
                        </PrimaryButton>
                    </div>
                </form>

                {/* Contacto */}
                <div className="text-center">
                    <p>¿Aún no tienes una cuenta?</p>
                    <a
                        className="text-[#0875E4] font-medium"
                        href={url_message}
                        target="_blank"
                        rel="noopener noreferrer"
                    >
                        ¡Escríbenos!
                    </a>
                </div>
            </div>
        </GuestLayout>
    );
}
