@extends('layouts.app') {{-- Usa tu layout principal --}}

@section('content')
<div class="max-w-3xl mx-auto bg-white p-8 rounded-2xl shadow-lg">
    <h2 class="text-2xl font-bold mb-6">Editar Perfil</h2>

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Foto de perfil --}}
        <div class="flex items-center space-x-6">
            <img id="profile-image" 
                 src="{{ Auth::user()->profile_photo_url ?? 'https://via.placeholder.com/150' }}" 
                 alt="Foto de perfil" 
                 class="w-24 h-24 rounded-full border object-cover">
            
            <div>
                <label for="profile-upload" class="block text-sm font-medium text-gray-700">Cambiar foto</label>
                <input type="file" 
                       name="profile_photo" 
                       id="profile-upload" 
                       accept="image/*" 
                       class="mt-2 text-sm text-gray-600">
            </div>
        </div>

        {{-- Nombre --}}
        <div>
            <label for="first_name" class="block text-sm font-medium text-gray-700">Nombre</label>
            <input type="text" 
                   name="first_name" 
                   id="first_name" 
                   value="{{ old('first_name', Auth::user()->first_name) }}"
                   class="mt-2 w-full p-3 border rounded-lg focus:ring focus:ring-indigo-300">
        </div>

        {{-- Apellido --}}
        <div>
            <label for="last_name" class="block text-sm font-medium text-gray-700">Apellido</label>
            <input type="text" 
                   name="last_name" 
                   id="last_name" 
                   value="{{ old('last_name', Auth::user()->last_name) }}"
                   class="mt-2 w-full p-3 border rounded-lg focus:ring focus:ring-indigo-300">
        </div>

        {{-- DNI --}}
        <div>
            <label for="dni" class="block text-sm font-medium text-gray-700">DNI</label>
            <input type="text" 
                   name="dni" 
                   id="dni" 
                   value="{{ old('dni', Auth::user()->dni) }}"
                   class="mt-2 w-full p-3 border rounded-lg focus:ring focus:ring-indigo-300">
        </div>

        {{-- Teléfono --}}
        <div>
            <label for="phone" class="block text-sm font-medium text-gray-700">Teléfono</label>
            <input type="text" 
                   name="phone" 
                   id="phone" 
                   value="{{ old('phone', Auth::user()->phone) }}"
                   class="mt-2 w-full p-3 border rounded-lg focus:ring focus:ring-indigo-300">
        </div>

        {{-- Acerca de mí --}}
        <div>
            <label for="about" class="block text-sm font-medium text-gray-700">Acerca de mí</label>
            <textarea name="about" 
                      id="about" 
                      rows="4"
                      class="mt-2 w-full p-3 border rounded-lg focus:ring focus:ring-indigo-300">{{ old('about', Auth::user()->about) }}</textarea>
        </div>

        {{-- Botón guardar --}}
        <div>
            <button type="submit" 
                    class="w-full bg-indigo-600 text-white font-semibold py-3 px-6 rounded-xl hover:bg-indigo-700 transition">
                Guardar cambios
            </button>
        </div>
    </form>
</div>
@endsection

{{-- Importar el JS de preview --}}
@vite('resources/js/profile.js')
