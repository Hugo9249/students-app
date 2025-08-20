{{-- resources/views/admin/users/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detalle de Usuario') }}
            </h2>
            <a href="{{ route('admin.users.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                ← Volver al Listado
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Foto y información básica -->
                        <div class="md:col-span-1">
                            <div class="text-center">
                                @if($user->photo_path)
                                    <img src="{{ $user->photo_url }}" alt="{{ $user->name }}" 
                                         class="h-32 w-32 mx-auto rounded-full object-cover shadow-lg">
                                @else
                                    <div class="h-32 w-32 mx-auto rounded-full bg-gray-300 flex items-center justify-center shadow-lg">
                                        <span class="text-gray-600 text-2xl font-medium">
                                            {{ substr($user->name, 0, 2) }}
                                        </span>
                                    </div>
                                @endif
                                
                                <div class="mt-4">
                                    @if($user->is_admin)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                            👨‍🏫 Administrador
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                            👨‍🎓 Alumno
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Información detallada -->
                        <div class="md:col-span-2">
                            <div class="space-y-6">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Información Personal</h3>
                                    
                                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">Nombre Completo</dt>
                                            <dd class="mt-1 text-sm text-gray-900">{{ $user->name }}</dd>
                                        </div>
                                        
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">Correo Electrónico</dt>
                                            <dd class="mt-1 text-sm text-gray-900">
                                                <a href="mailto:{{ $user->email }}" class="text-blue-600 hover:text-blue-800">
                                                    {{ $user->email }}
                                                </a>
                                            </dd>
                                        </div>
                                        
                                        @if($user->phone)
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">Teléfono</dt>
                                            <dd class="mt-1 text-sm text-gray-900">
                                                <div class="flex items-center space-x-2">
                                                    <span>{{ $user->phone }}</span>
                                                    <a href="{{ $user->whatsapp_link }}" target="_blank" 
                                                       class="inline-flex items-center px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full hover:bg-green-200">
                                                        📱 WhatsApp
                                                    </a>
                                                </div>
                                            </dd>
                                        </div>
                                        @endif
                                        
                                        @if($user->professional_url)
                                        <div class="sm:col-span-2">
                                            <dt class="text-sm font-medium text-gray-500">Red Profesional</dt>
                                            <dd class="mt-1 text-sm text-gray-900">
                                                <a href="{{ $user->professional_url }}" target="_blank" 
                                                   class="inline-flex items-center text-blue-600 hover:text-blue-800">
                                                    {{ $user->professional_url }}
                                                    <svg class="ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                    </svg>
                                                </a>
                                            </dd>
                                        </div>
                                        @endif
                                    </dl>
                                </div>

                                <div class="border-t border-gray-200 pt-6">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Información de Cuenta</h3>
                                    
                                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">Fecha de Registro</dt>
                                            <dd class="mt-1 text-sm text-gray-900">
                                                {{ $user->created_at->format('d/m/Y H:i') }}
                                                <span class="text-gray-500 text-xs">
                                                    ({{ $user->created_at->diffForHumans() }})
                                                </span>
                                            </dd>
                                        </div>
                                        
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">Última Actualización</dt>
                                            <dd class="mt-1 text-sm text-gray-900">
                                                {{ $user->updated_at->format('d/m/Y H:i') }}
                                                <span class="text-gray-500 text-xs">
                                                    ({{ $user->updated_at->diffForHumans() }})
                                                </span>
                                            </dd>
                                        </div>
                                        
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">Estado de Email</dt>
                                            <dd class="mt-1 text-sm">
                                                @if($user->email_verified_at)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        ✓ Verificado
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        ⏳ Pendiente
                                                    </span>
                                                @endif
                                            </dd>
                                        </div>
                                        
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">ID de Usuario</dt>
                                            <dd class="mt-1 text-sm text-gray-900 font-mono">#{{ $user->id }}</dd>
                                        </div>
                                    </dl>
                                </div>

                                @if(!$user->phone && !$user->professional_url)
                                <div class="border-t border-gray-200 pt-6">
                                    <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                                        <div class="flex">
                                            <div class="flex-shrink-0">
                                                <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <h3 class="text-sm font-medium text-yellow-800">
                                                    Perfil Incompleto
                                                </h3>
                                                <div class="mt-2 text-sm text-yellow-700">
                                                    <p>Este usuario no ha completado su información de contacto profesional.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>