<?php

namespace App\Livewire\Admin\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin-app')]
class AdminProfileForm extends Component
{
    use WithFileUploads;

    public $name, $email, $password, $password_confirmation, $profile_photo, $existing_photo;
    public $admin;

    public function mount()
    {
        $this->admin = Auth::guard('admin')->user();

        $this->name = $this->admin->name;
        $this->email = $this->admin->email;
        $this->existing_photo = $this->admin->profile_photo;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,' . $this->admin->id,
            'password' => 'nullable|min:6|confirmed',
            'profile_photo' => 'nullable|image|max:2048',
        ];
    }

    public function updateProfile()
    {
        $this->validate();

        if ($this->profile_photo) {
            $path = $this->profile_photo->store('uploads/admin-profiles', 'public_root');
            $this->existing_photo = $path;
        }

        $this->admin->update([
            'name' => $this->name,
            'email' => $this->email,
            'profile_photo' => $this->existing_photo,
            'password' => $this->password ? Hash::make($this->password) : $this->admin->password,
        ]);

        $this->dispatch('swal:toast', [
            'type' => 'success',
            'message' => 'Profile updated successfully.',
        ]);
    }

    public function render()
    {
        return view('livewire.admin.auth.admin-profile-form');
    }
}
