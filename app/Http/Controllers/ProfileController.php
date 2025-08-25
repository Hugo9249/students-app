<?php
// app/Http/Controllers/ProfileController.php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use App\Models\Profile;

class ProfileController extends Controller
{
    public function login(Request $request)
{
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        return redirect()->intended('/dashboard');
    }

    return back()->withErrors([
        'email' => 'Las credenciales no son válidas.',
    ]);
}

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $file = $request->file('photo');
        $hash = hash('sha256', $file->getContent());
        $extension = $file->getClientOriginalExtension();
        $filename = $hash . '.' . $extension;
        
        $path = $file->storeAs('profile-photos', $filename, 'public');
        
        auth()->user()->update([
            'photo_path' => $path
        ]);

        return response()->json(['success' => true, 'path' => $path]);
    }
    
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $user = $request->user();
        
        $data = $request->validated();
        
        if ($request->hasFile('photo')) {
            // Eliminar foto anterior si existe
            if ($user->photo_path && Storage::disk('public')->exists($user->photo_path)) {
                Storage::disk('public')->delete($user->photo_path);
            }
            
            $data['photo_path'] = $request->file('photo')->store('photos', 'public');
        }
        
        $user->fill($data);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }
    
    public function index()
    {
        $profiles = Profile::all();
        return view('profiles.index', compact('profiles'));
    }

    public function create()
    {
        return view('profiles.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required',
            'last_name'  => 'required',
            'dni'        => 'required|unique:profiles',
            'career'     => 'nullable',
            'commission' => 'nullable',
            'phone'      => 'nullable',
            'about'      => 'nullable',
            'linkedin'   => 'nullable|url',
            'github'     => 'nullable|url',
            'photo'      => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('profiles', 'public');
        }

        $data['user_id'] = Auth::id();
        Profile::create($data);

        return redirect()->route('profiles.index')->with('success', 'Perfil creado correctamente.');
    }

    public function show(Profile $profile)
    {
        return view('profiles.show', compact('profile'));
    }

    public function destroy(Profile $profile)
    {
        $profile->delete();
        return redirect()->route('profiles.index')->with('success', 'Perfil eliminado correctamente.');
    }
}