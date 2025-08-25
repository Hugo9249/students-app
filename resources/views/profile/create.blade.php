<x-app-layout>
    <div class="max-w-lg mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Crear Perfil</h1>

        <form action="{{ route('profiles.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <input type="text" name="first_name" placeholder="Nombre" class="input input-bordered w-full" required>
            <input type="text" name="last_name" placeholder="Apellido" class="input input-bordered w-full" required>
            <input type="text" name="dni" placeholder="DNI" class="input input-bordered w-full" required>
            <input type="text" name="career" placeholder="Carrera" class="input input-bordered w-full">
            <input type="text" name="commission" placeholder="Comisión" class="input input-bordered w-full">
            <input type="text" name="phone" placeholder="Teléfono" class="input input-bordered w-full">
            <textarea name="about" placeholder="Acerca de mí" class="textarea textarea-bordered w-full"></textarea>
            <input type="url" name="linkedin" placeholder="LinkedIn" class="input input-bordered w-full">
            <input type="url" name="github" placeholder="GitHub" class="input input-bordered w-full">
            <input type="file" name="photo" class="file-input file-input-bordered w-full">
            
            <button class="btn btn-primary w-full">Guardar</button>
        </form>
    </div>
</x-app-layout>
