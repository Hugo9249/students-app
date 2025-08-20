{{-- resources/views/admin/users/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión de Usuarios') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Lista de Usuarios Registrados</h3>
                        <p class="text-sm text-gray-600">Total de usuarios: {{ $users->total() }}</p>
                    </div>

                    @if($users->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($users as $user)
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 hover:shadow-md transition-shadow">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0">
                                            @if($user->photo_path)
                                                <img src="{{ $user->photo_url }}" alt="{{ $user->name }}" 
                                                     class="h-12 w-12 rounded-full object-cover">
                                            @else
                                                <div class="h-12 w-12 rounded-full bg-gray-300 flex items-center justify-center">
                                                    <span class="text-gray-600 text-sm font-medium">
                                                        {{ substr($user->name, 0, 2) }}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between">
                                                <h4 class="text-sm font-medium text-gray-900 truncate">
                                                    {{ $user->name }}
                                                </h4>
                                                @if($user->is_admin)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        Admin
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        Alumno
                                                    </span>
                                                @endif
                                            </div>
                                            
                                            <p class="text-sm text-gray-500 truncate">{{ $user->email }}</p>
                                            
                                            <div class="mt-2 flex space-x-2">
                                                @if($user->phone)
                                                    <a href="{{ $user->whatsapp_link }}" target="_blank" 
                                                       class="text-green-600 hover:text-green-800 text-xs">
                                                        📱 WhatsApp
                                                    </a>
                                                @endif
                                                
                                                @if($user->professional_url)
                                                    <a href="{{ $user->professional_url }}" target="_blank" 
                                                       class="text-blue-600 hover:text-blue-800 text-xs">
                                                        🔗 Perfil
                                                    </a>
                                                @endif
                                            </div>
                                            
                                            <div class="mt-3">
                                                <a href="{{ route('admin.users.show', $user) }}" 
                                                   class="inline-flex items-center px-3 py-1 border border-transparent text-xs leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo active:bg-indigo-700 transition ease-in-out duration-150">
                                                    Ver Detalle
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-3 text-xs text-gray-400">
                                        Registrado: {{ $user->created_at->format('d/m/Y H:i') }}
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Paginación -->
                        <div class="mt-6">
                            {{ $users->links() }}
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="text-gray-400 text-lg mb-2">📚</div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No hay usuarios registrados</h3>
                            <p class="text-gray-500">Cuando los usuarios se registren, aparecerán aquí.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>