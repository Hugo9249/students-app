<x-app-layout>
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-4">Perfiles</h1>
        <a href="{{ route('profiles.create') }}" class="btn btn-primary mb-4">+ Nuevo Perfil</a>

        <div class="grid md:grid-cols-3 gap-4">
            @foreach ($profiles as $profile)
                <div class="card shadow-lg bg-base-100">
                    <div class="card-body">
                        <h2 class="card-title">{{ $profile->first_name }} {{ $profile->last_name }}</h2>
                        <p><b>DNI:</b> {{ $profile->dni }}</p>
                        <p><b>Carrera:</b> {{ $profile->career }}</p>
                        <div class="flex gap-2 mt-2">
                            <a href="{{ route('profiles.edit', $profile) }}" class="btn btn-sm btn-info">Editar</a>
                            <form action="{{ route('profiles.destroy', $profile) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-error">Eliminar</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
