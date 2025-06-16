<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Admin Profile</h4>
        </div>
        <div class="card-body">
            <form wire:submit="updateProfile">
                <div class="mb-3">
                    <label>Name</label>
                    <input type="text" class="form-control" wire:model="name">
                    @error('name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" class="form-control" wire:model="email">
                    @error('email')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <div class="row g-3">
                        <div class="col-md-9">
                            <label class="form-label">Profile Photo</label>
                            <input type="file" class="form-control" wire:model="profile_photo">
                        </div>
                        <div class="col-md-2 align-self-end">
                            @if ($profile_photo)
                                <img src="{{ $profile_photo->temporaryUrl() }}" class="img-thumbnail mt-2"
                                    style="max-width: 100px;">
                            @elseif ($existing_photo)
                                <img src="{{ asset($existing_photo) }}" class="img-thumbnail mt-2"
                                    style="max-width: 100px;">
                            @else
                                <span class="text-muted">No Profile Photo</span>
                            @endif
                        </div>
                    </div>

                    @error('profile_photo')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <hr>
                <h5>Change Password</h5>
                <div class="mb-3">
                    <label>New Password</label>
                    <input type="password" class="form-control" wire:model="password">
                    @error('password')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label>Confirm Password</label>
                    <input type="password" class="form-control" wire:model="password_confirmation">
                </div>

                <button type="submit" class="btn btn-success">
                    Save Changes
                    <i class="spinner-border spinner-border-sm" wire:loading></i>
                </button>
            </form>
        </div>
    </div>
</div>
