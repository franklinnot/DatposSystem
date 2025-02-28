import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import DeleteUserForm from "./Partials/DeleteUserForm";
import UpdatePasswordForm from "./Partials/UpdatePasswordForm";
import UpdateProfileInformationForm from "./Partials/UpdateProfileInformationForm";
import { Head } from "@inertiajs/react";

export default function Edit({ auth, mustVerifyEmail, status }) {
    let usuario = auth?.user;
    return (
        <AuthenticatedLayout auth={auth}>
            <Head title={`Perfil`} />
            <p>Yupi yupi!!! Hola {usuario.nombre}! Quieres ver tu perfil?</p>
        </AuthenticatedLayout>
    );
}
