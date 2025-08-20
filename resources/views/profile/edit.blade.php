{{-- resources/views/profile/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mi Perfil') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Profile Information -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">
                                {{ __('Información del Perfil') }}
                            </h2>

                            <p class="mt-1 text-sm text-gray-600">
                                {{ __('Actualiza la información de tu perfil y dirección de email.') }}
                            </p>
                        </header>

                        <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
                            @csrf
                            @method('patch')

                            <!-- Current Photo -->
                            @if($user->photo_path)
                                <div>
                                    <x-input-label for="current_photo" :value="__('Foto Actual')" />
                                    <div class="mt-2">
                                        <img src="{{ $user->photo_url }}" alt="{{ $user->name }}" class="h-20 w-20 rounded-full object-cover">
                                    </div>
                                </div>
                            @endif

                            <div>
                                <x-input-label for="name" :value="__('Nombre')" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>

                            <div>
                                <x-input-label for="email" :value="__('Email')" />
                                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
                                <x-input-error class="mt-2" :messages="$errors->get('email')" />

                                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                    <div>
                                        <p class="text-sm mt-2 text-gray-800">
                                            {{ __('Your email address is unverified.') }}

                                            <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                {{ __('Click here to re-send the verification email.') }}
                                            </button>
                                        </p>

                                        @if (session('status') === 'verification-link-sent')
                                            <p class="mt-2 font-medium text-sm text-green-600">
                                                {{ __('A new verification link has been sent to your email address.') }}
                                            </p>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <div>
                                <x-input-label for="phone" :value="__('Teléfono')" />
                                <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $user->phone)" />
                                <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                                @if($user->phone)
                                    <p class="mt-1 text-sm text-gray-600">
                                        <a href="{{ $user->whatsapp_link }}" target="_blank" class="text-green-600 hover:text-green-800">
                                            Abrir en WhatsApp
                                        </a>
                                    </p>
                                @endif
                            </div>

                            <div>
                                <x-input-label for="professional_url" :value="__('Red Profesional')" />
                                <x-text-input id="professional_url" name="professional_url" type="url" class="mt-1 block w-full" :value="old('professional_url', $user->professional_url)" />
                                <x-input-error class="mt-2" :messages="$errors->get('professional_url')" />
                                @if($user->professional_url)
                                    <p class="mt-1 text-sm text-gray-600">
                                        <a href="{{ $user->professional_url }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                            Ver perfil profesional
                                        </a>
                                    </p>
                                @endif
                            </div>

                            <div>
                                <x-input-label for="photo" :value="__('Nueva Foto de Perfil')" />
                                <input id="photo" name="photo" type="file" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" accept="image/*" />
                                <x-input-error class="mt-2" :messages="$errors->get('photo')" />
                                <p class="mt-1 text-sm text-gray-600">Dejar vacío para mantener la foto actual.</p>
                            </div>

                            <div class="flex items-center gap-4">
                                <x-primary-button>{{ __('Guardar') }}</x-primary-button>

                                @if (session('status') === 'profile-updated')
                                    <p
                                        x-data="{ show: true }"
                                        x-show="show"
                                        x-transition
                                        x-init="setTimeout(() => show = false, 2000)"
                                        class="text-sm text-gray-600"
                                    >{{ __('Guardado.') }}</p>
                                @endif
                            </div>
                        </form>
                    </section>
                </div>
            </div>

            <!-- Delete Account -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section class="space-y-6">
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">
                                {{ __('Eliminar Cuenta') }}
                            </h2>

                            <p class="mt-1 text-sm text-gray-600">
                                {{ __('Una vez eliminada tu cuenta, todos los recursos y datos serán eliminados permanentemente.') }}
                            </p>
                        </header>

                        <x-danger-button
                            x-data=""
                            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
                        >{{ __('Eliminar Cuenta') }}</x-danger-button>

                        <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
                            <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
                                @csrf
                                @method('delete')

                                <h2 class="text-lg font-medium text-gray-900">
                                    {{ __('¿Estás seguro de que quieres eliminar tu cuenta?') }}
                                </h2>

                                <p class="mt-1 text-sm text-gray-600">
                                    {{ __('Una vez eliminada tu cuenta, todos los recursos y datos serán eliminados permanentemente. Ingresa tu contraseña para confirmar.') }}
                                </p>

                                <div class="mt-6">
                                    <x-input-label for="password" value="{{ __('Contraseña') }}" class="sr-only" />

                                    <x-text-input
                                        id="password"
                                        name="password"
                                        type="password"
                                        class="mt-1 block w-3/4"
                                        placeholder="{{ __('Contraseña') }}"
                                    />

                                    <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
                                </div>

                                <div class="mt-6 flex justify-end">
                                    <x-secondary-button x-on:click="$dispatch('close')">
                                        {{ __('Cancelar') }}
                                    </x-secondary-button>

                                    <x-danger-button class="ml-3">
                                        {{ __('Eliminar Cuenta') }}
                                    </x-danger-button>
                                </div>
                            </form>
                        </x-modal>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>