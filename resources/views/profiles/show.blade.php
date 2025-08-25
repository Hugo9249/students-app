@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Perfil de {{ $profile->first_name ?? 'Usuario' }} {{ $profile->last_name ?? '' }}</h4>
                    <div>
                        {{-- Comentamos temporalmente las políticas hasta que estén configuradas --}}
                        {{-- @can('update', $profile) --}}
                            <a href="{{ route('profiles.edit', $profile) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                        {{-- @endcan --}}
                        <a href="{{ route('profiles.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <!-- Foto de perfil -->
                        <div class="col-md-4 text-center mb-4">
                            @if($profile->photo && \Illuminate\Support\Facades\Storage::exists('public/' . $profile->photo))
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($profile->photo) }}" 
                                     alt="Foto de {{ $profile->first_name ?? 'Usuario' }}" 
                                     class="img-fluid rounded-circle profile-photo"
                                     style="width: 200px; height: 200px; object-fit: cover;">
                            @else
                                <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center text-white mx-auto"
                                     style="width: 200px; height: 200px; font-size: 60px;">
                                    {{ strtoupper(substr($profile->first_name ?? 'U', 0, 1)) }}{{ strtoupper(substr($profile->last_name ?? 'S', 0, 1)) }}
                                </div>
                            @endif
                        </div>

                        <!-- Información personal -->
                        <div class="col-md-8">
                            <h2 class="mb-3">{{ $profile->first_name ?? 'Sin nombre' }} {{ $profile->last_name ?? '' }}</h2>
                            
                            <div class="row mb-3">
                                <div class="col-sm-4"><strong>DNI:</strong></div>
                                <div class="col-sm-8">{{ $profile->dni ?? 'No especificado' }}</div>
                            </div>

                            @if($profile->career)
                                <div class="row mb-3">
                                    <div class="col-sm-4"><strong>Carrera:</strong></div>
                                    <div class="col-sm-8">{{ $profile->career }}</div>
                                </div>
                            @endif

                            @if($profile->commission)
                                <div class="row mb-3">
                                    <div class="col-sm-4"><strong>Comisión:</strong></div>
                                    <div class="col-sm-8">{{ $profile->commission }}</div>
                                </div>
                            @endif

                            @if($profile->phone)
                                <div class="row mb-3">
                                    <div class="col-sm-4"><strong>Teléfono:</strong></div>
                                    <div class="col-sm-8">
                                        <a href="tel:{{ $profile->phone }}" class="text-decoration-none">
                                            <i class="fas fa-phone"></i> {{ $profile->phone }}
                                        </a>
                                    </div>
                                </div>
                            @endif

                            <!-- Enlaces sociales -->
                            @if($profile->linkedin || $profile->github)
                                <div class="row mb-3">
                                    <div class="col-sm-4"><strong>Enlaces:</strong></div>
                                    <div class="col-sm-8">
                                        @if($profile->linkedin)
                                            <a href="{{ $profile->linkedin }}" target="_blank" class="btn btn-outline-primary btn-sm me-2">
                                                <i class="fab fa-linkedin"></i> LinkedIn
                                            </a>
                                        @endif
                                        @if($profile->github)
                                            <a href="{{ $profile->github }}" target="_blank" class="btn btn-outline-dark btn-sm">
                                                <i class="fab fa-github"></i> GitHub
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <!-- Fecha de creación con protección contra null -->
                            <div class="row mb-3">
                                <div class="col-sm-4"><strong>Miembro desde:</strong></div>
                                <div class="col-sm-8">
                                    @if($profile->created_at)
                                        {{ $profile->created_at->format('d/m/Y') }}
                                    @else
                                        Fecha no disponible
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Acerca de -->
                    @if($profile->about)
                        <div class="row mt-4">
                            <div class="col-12">
                                <h5><i class="fas fa-user"></i> Acerca de</h5>
                                <div class="card">
                                    <div class="card-body">
                                        <p class="mb-0">{{ $profile->about }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Comentamos temporalmente las políticas hasta que estén configuradas --}}
                {{-- @can('delete', $profile) --}}
                    <div class="card-footer">
                        <form action="{{ route('profiles.destroy', $profile) }}" method="POST" class="d-inline" 
                              onsubmit="return confirm('¿Estás seguro de que quieres eliminar este perfil?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i> Eliminar perfil
                            </button>
                        </form>
                    </div>
                {{-- @endcan --}}
            </div>
        </div>
    </div>
</div>

<style>
.profile-photo {
    border: 4px solid #fff;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.card {
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.btn-outline-primary:hover {
    color: #fff;
}

.btn-outline-dark:hover {
    color: #fff;
}
</style>
@endsection