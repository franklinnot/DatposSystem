import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from "@inertiajs/react";
import { useEffect } from "react";
import { usePage } from "@inertiajs/react";
import { Inertia } from "@inertiajs/inertia";
import { useState } from "react";

export default function Dashboard({ auth }) {
    let usuario = auth?.user; // prop auth.user
    return (
        <AuthenticatedLayout auth={auth}>
            <Head title={`Dashboard`} />
            <p>Yupi yupi!!! Hola {usuario.nombre}!</p>
        </AuthenticatedLayout>
    );
}
