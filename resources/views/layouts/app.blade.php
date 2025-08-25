{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'StudentsApp') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .gradient-bg { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .card-hover { transition: all 0.3s ease; }
        .card-hover:hover { transform: translateY(-5px); box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); }
        .profile-pic { width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 4px solid #3b82f6; }
        .status-online { background: #10b981; width: 16px; height: 16px; border: 3px solid white; border-radius: 50%; position: absolute; bottom: 5px; right: 5px; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <x-navigation />
    
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @yield('content')
    </main>
    
    @stack('scripts')
</body>
</html>

{{-- resources/views/components/navigation.blade.php --}}
<nav class="bg-white shadow-lg border-b-4 border-blue-500">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <h1 class="text-2xl font-bold text-blue-600">
                        <i class="fas fa-graduation-cap mr-2"></i>StudentsApp
                    </h1>
                </div>
                <div class="hidden md:block ml-10">
                    <div class="flex items-baseline space-x-4">
                        <a href="{{ route('dashboard') }}" class="nav-link text-blue-600 px-3 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-home mr-2"></i>Inicio
                        </a>
                        @can('viewAny', App\Models\User::class)
                            <a href="{{ route('admin.users.index') }}" class="nav-link text-gray-600 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">
                                <i class="fas fa-users mr-2"></i>Estudiantes
                            </a>
                        @endcan
                        <a href="{{ route('profile.show') }}" class="nav-link text-gray-600 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-user-circle mr-2"></i>Mi Perfil
                        </a>
                    </div>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <img class="h-10 w-10 rounded-full border-2 border-blue-400" 
                         src="{{ Auth::user()->profilePhotoUrl }}" 
                         alt="{{ Auth::user()->name }}">
                    <span class="status-online"></span>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-200">
                        <i class="fas fa-sign-out-alt mr-2"></i>Salir
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>

{{-- resources/views/components/student-card.blade.php --}}
<div class="bg-gradient-to-br from-blue-50 to-indigo-100 rounded-lg p-6 card-hover cursor-pointer border border-blue-200">
    <div class="flex items-center mb-4">
        <div class="relative">
            <img class="profile-pic" 
                 src="{{ $user->profilePhotoUrl }}" 
                 alt="{{ $user->name }}">
            @if($user->isOnline())
                <span class="status-online"></span>
            @endif
        </div>
        <div class="ml-4">
            <h3 class="text-lg font-semibold text-gray-800">{{ $user->name }}</h3>
            <p class="text-sm text-gray-600">
                <i class="fas fa-graduation-cap mr-1"></i>
                {{ $user->is_admin ? 'Administrador' : 'Estudiante' }}
            </p>
            <p class="text-xs {{ $user->isOnline() ? 'text-green-600 font-medium' : 'text-gray-500' }}">
                <i class="fas fa-circle text-xs mr-1"></i>
                {{ $user->isOnline() ? 'En línea' : 'Hace ' . $user->last_seen?->diffForHumans() }}
            </p>
        </div>
    </div>
    <div class="space-y-2 text-sm">
        <p class="text-gray-700">
            <i class="fas fa-envelope text-blue-500 mr-2"></i>
            {{ $user->email }}
        </p>
        @if($user->phone)
            <p class="text-gray-700">
                <i class="fab fa-whatsapp text-green-500 mr-2"></i>
                <a href="{{ $user->whatsappUrl }}" class="text-green-600 hover:underline" target="_blank">
                    {{ $user->phone }}
                </a>
            </p>
        @endif
        @if($user->professional_url)
            <p class="text-gray-700">
                <i class="{{ $user->professionalIconClass }} mr-2"></i>
                <a href="{{ $user->professional_url }}" class="text-blue-600 hover:underline" target="_blank">
                    {{ $user->professionalPlatformName }}
                </a>
            </p>
        @endif
    </div>
    <div class="mt-4 flex space-x-2">
        <a href="{{ route('admin.users.show', $user) }}" 
           class="flex-1 bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-lg text-sm transition duration-200 text-center">
            <i class="fas fa-eye mr-2"></i>Ver Perfil
        </a>
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="bg-gray-200 hover:bg-gray-300 text-gray-700 py-2 px-3 rounded-lg text-sm transition duration-200">
                <i class="fas fa-ellipsis-v"></i>
            </button>
            <div x-show="open" @click.away="open = false" 
                 class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10 border">
                <a href="{{ route('admin.users.edit', $user) }}" 
                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-edit mr-2"></i>Editar
                </a>
                @if(!$user->is_admin)
                    <button onclick="confirmDelete({{ $user->id }})" 
                            class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                        <i class="fas fa-trash mr-2"></i>Eliminar
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- resources/views/admin/users/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="bg-white rounded-xl shadow-lg p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-users-cog text-blue-500 mr-3"></i>Panel de Administración
        </h2>
        <div class="flex space-x-4">
            <form method="GET" action="{{ route('admin.users.index') }}" class="flex space-x-4">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Buscar estudiante..." 
                       class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <select name="role" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Todos los roles</option>
                    <option value="student" {{ request('role') == 'student' ? 'selected' : '' }}>Estudiantes</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Administradores</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($users as $user)
            <x-student-card :user="$user" />
        @empty
            <div class="col-span-full text-center py-12">
                <i class="fas fa-users text-gray-300 text-6xl mb-4"></i>
                <p class="text-gray-500 text-lg">No se encontraron estudiantes</p>
            </div>
        @endforelse
    </div>
    
    <div class="mt-8">
        {{ $users->links() }}
    </div>
</div>
@endsection

{{-- resources/views/profile/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <!-- Header del perfil con gradiente -->
    <div class="gradient-bg h-32 relative">
        <div class="absolute inset-0 bg-black opacity-20"></div>
        @can('update', Auth::user())
            <a href="{{ route('profile.edit') }}" 
               class="absolute top-4 right-4 bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg transition duration-200">
                <i class="fas fa-edit mr-2"></i>Editar Perfil
            </a>
        @endcan
    </div>
    
    <!-- Información del perfil -->
    <div class="px-8 pb-8">
        <div class="flex flex-col md:flex-row items-start md:items-end -mt-16 mb-8">
            <div class="relative mb-4 md:mb-0">
                <img class="w-32 h-32 rounded-full border-4 border-white shadow-lg object-cover" 
                     src="{{ $user->profilePhotoUrl }}" 
                     alt="{{ $user->name }}">
            </div>
            <div class="md:ml-8 flex-1">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $user->name }}</h1>
                <p class="text-lg text-blue-600 mb-4">
                    <i class="fas fa-graduation-cap mr-2"></i>
                    {{ $user->is_admin ? 'Administrador' : 'Estudiante de Programación' }}
                </p>
                <div class="flex flex-wrap gap-4 text-sm text-gray-600">
                    <div class="flex items-center">
                        <i class="fas fa-calendar-alt text-blue-500 mr-2"></i>
                        Miembro desde {{ $user->created_at->format('F Y') }}
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-map-marker-alt text-red-500 mr-2"></i>
                        Salta, Argentina
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Información de contacto -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-6 mb-8">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">
                <i class="fas fa-address-card text-blue-500 mr-2"></i>Información de Contacto
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex items-center p-3 bg-white rounded-lg shadow-sm">
                    <i class="fas fa-envelope text-blue-500 mr-3 text-lg"></i>
                    <div>
                        <p class="text-sm text-gray-600">Email</p>
                        <p class="font-medium text-gray-800">{{ $user->email }}</p>
                    </div>
                </div>
                @if($user->phone)
                    <div class="flex items-center p-3 bg-white rounded-lg shadow-sm">
                        <i class="fab fa-whatsapp text-green-500 mr-3 text-lg"></i>
                        <div>
                            <p class="text-sm text-gray-600">WhatsApp</p>
                            <a href="{{ $user->whatsappUrl }}" 
                               class="font-medium text-green-600 hover:underline" target="_blank">
                                {{ $user->phone }}
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Enlaces profesionales -->
        @if($user->professional_url)
            <div class="bg-gradient-to-r from-gray-50 to-blue-50 rounded-lg p-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">
                    <i class="fas fa-link text-blue-500 mr-2"></i>Enlaces Profesionales
                </h3>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ $user->professional_url }}" 
                       target="_blank" 
                       class="flex items-center {{ $user->professionalButtonClass }} text-white px-6 py-3 rounded-lg transition duration-200 shadow-md">
                        <i class="{{ $user->professionalIconClass }} mr-2"></i>
                        {{ $user->professionalPlatformName }}
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    function confirmDelete(userId) {
        if (confirm('¿Estás seguro de que deseas eliminar este usuario?')) {
            // Implementar lógica de eliminación
            fetch(`/admin/users/${userId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
            }).then(response => {
                if (response.ok) {
                    location.reload();
                }
            });
        }
    }
</script>
@endpush