<?php

// app/Http/Controllers/AdminController.php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);

        $users = User::query()
            ->when($request->search, function ($query, $search) {
                $query->search($search);
            })
            ->when($request->role, function ($query, $role) {
                $query->byRole($role);
            })
            ->latest()
            ->paginate(12);

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $this->authorize('view', $user);
        return view('admin.users.show', compact('user'));
    }

    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
        
        // No permitir eliminar administradores
        if ($user->is_admin) {
            return response()->json(['error' => 'No se puede eliminar un administrador'], 403);
        }

        $user->delete();
        return response()->json(['message' => 'Usuario eliminado correctamente']);
    }
}

// app/Http/Controllers/ProfileController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ProfileUpdateRequest;

class ProfileController extends Controller
{
    public function show()
    {
        $user = auth()->user();
        return view('profile.show', compact('user'));
    }

    public function edit()
    {
        $user = auth()->user();
        return view('profile.edit', compact('user'));
    }

    public function update(ProfileUpdateRequest $request)
    {
        $user = auth()->user();
        
        $validated = $request->validated();
        
        // Handle photo upload if present
        if ($request->hasFile('photo')) {
            $validated['photo_path'] = $this->handlePhotoUpload($request->file('photo'), $user);
        }

        $user->update($validated);

        return redirect()->route('profile.show')->with('success', 'Perfil actualizado correctamente');
    }

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = auth()->user();
        $photoPath = $this->handlePhotoUpload($request->file('photo'), $user);
        
        $user->update(['photo_path' => $photoPath]);

        return response()->json([
            'success' => true,
            'photo_url' => $user->fresh()->profilePhotoUrl
        ]);
    }

    private function handlePhotoUpload($file, $user)
    {
        // Delete old photo if exists
        if ($user->photo_path) {
            Storage::disk('public')->delete($user->photo_path);
        }

        // Generate hash for the new file
        $hash = hash('sha256', $file->getContent());
        $extension = $file->getClientOriginalExtension();
        $filename = $hash . '.' . $extension;

        // Store file with hashed name
        return $file->storeAs('profile-photos', $filename, 'public');
    }
}

// app/Http/Requests/ProfileUpdateRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($this->user()->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'professional_url' => ['nullable', 'url', 'max:255'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ];
    }
}

// app/Policies/UserPolicy.php
namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->is_admin;
    }

    public function view(User $user, User $model)
    {
        return $user->is_admin || $user->id === $model->id;
    }

    public function update(User $user, User $model)
    {
        return $user->id === $model->id;
    }

    public function delete(User $user, User $model)
    {
        return $user->is_admin && !$model->is_admin && $user->id !== $model->id;
    }
}

// app/Http/Middleware/UpdateLastSeen.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UpdateLastSeen
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            Auth::user()->updateLastSeen();
        }

        return $next($request);
    }
}

// database/migrations/xxxx_xx_xx_xxxxxx_add_fields_to_users_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_admin')->default(false);
            $table->string('phone')->nullable();
            $table->string('professional_url')->nullable();
            $table->string('photo_path')->nullable();
            $table->timestamp('last_seen')->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_admin', 'phone', 'professional_url', 'photo_path', 'last_seen']);
        });
    }
};

// database/seeders/AdminUserSeeder.php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@studentsapp.com',
            'password' => Hash::make('admin123'),
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);
    }
}

// routes/web.php
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo.update');

    // Admin routes
    Route::middleware('can:viewAny,App\Models\User')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/users', [AdminController::class, 'index'])->name('users.index');
        Route::get('/users/{user}', [AdminController::class, 'show'])->name('users.show');
        Route::delete('/users/{user}', [AdminController::class, 'destroy'])->name('users.destroy');
    });
});

// config/app.php (agregar esta línea en el array de configuración)
'whatsapp_country_prefix' => env('WHATSAPP_COUNTRY_PREFIX', '54'),

// .env (agregar estas líneas)
WHATSAPP_COUNTRY_PREFIX=54

// app/Providers/AppServiceProvider.php
namespace App\Providers;

use App\Models\User;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        User::class => UserPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
}

// resources/js/profile.js (Archivo JavaScript para manejo de imágenes)
document.addEventListener('DOMContentLoaded', function() {
    // Profile photo upload
    const profileUpload = document.getElementById('profile-upload');
    const profileImage = document.getElementById('profile-image');
    
    if (profileUpload && profileImage) {
        profileUpload.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Validate file size (2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('El archivo es demasiado grande. Máximo 2MB.');
                    return;
                }
                
                // Validate file type
                if (!['image/jpeg', 'image/jpg', 'image/png', 'image/gif'].includes(file.type)) {
                    alert('Tipo de archivo no válido. Solo se permiten imágenes JPEG, PNG y GIF.');
                    return;
                }
                
                // Preview image
                const reader = new FileReader();
                reader.onload = function(e) {
                    profileImage.src = e.target.result;
                };
                reader.readAsDataURL(file);
                
                // Upload via AJAX
                uploadProfilePhoto(file);
            }
        });
    }
    
    // Register form photo upload
    const registerPhoto = document.getElementById('register-photo');
    if (registerPhoto) {
        registerPhoto.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                const container = this.parentElement.querySelector('div');
                
                reader.onload = function(e) {
                    container.innerHTML = `<img src="${e.target.result}" class="w-24 h-24 rounded-full object-cover">`;
                };
                reader.readAsDataURL(file);
            }
        });
    }
});

function uploadProfilePhoto(file) {
    const formData = new FormData();
    formData.append('photo', file);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    
    // Show loading state
    const uploadButton = document.querySelector('.profile-upload-btn');
    if (uploadButton) {
        uploadButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Subiendo...';
        uploadButton.disabled = true;
    }
    
    fetch('/profile/photo', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            showNotification('Foto de perfil actualizada correctamente', 'success');
        } else {
            throw new Error(data.message || 'Error al subir la foto');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error al subir la foto: ' + error.message, 'error');
    })
    .finally(() => {
        // Restore button state
        if (uploadButton) {
            uploadButton.innerHTML = '<i class="fas fa-camera mr-2"></i>Cambiar foto';
            uploadButton.disabled = false;
        }
    });
}

function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 ${
        type === 'success' ? 'bg-green-500 text-white' : 
        type === 'error' ? 'bg-red-500 text-white' : 
        'bg-blue-500 text-white'
    }`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    // Remove after 3 seconds
    setTimeout(() => {
        notification.remove();
    }, 3000);
}