@extends('admin.master')

@section('title', 'Variant Create')

@push('styles')
    {{-- styles --}}
@endpush

@section('content')
    <section class="mt-16">
        <div class="container mx-auto p-6">
            <h1 class="text-2xl font-bold mb-6">Edit Role: {{ $role->name }}</h1>

            <!-- Form to update the role -->
            <form action="{{ route('roles.update', $role) }}" method="POST" class="mt-4">
                @csrf
                @method('PUT')

                <!-- Role Name -->
                <div class="mb-4">
                    <label for="name" class="block font-medium">Role Name</label>
                    <input type="text" id="name" name="name" value="{{ $role->name }}"
                        class="w-full p-2 border rounded">
                </div>

                <!-- Permissions -->
                <div class="mb-4">
                    <h3 class="font-medium">Permissions</h3>
                    @foreach ($permissions as $permission)
                        <div class="flex items-center">
                            <input type="checkbox" id="permission-{{ $permission->id }}" name="permissions[]"
                                value="{{ $permission->id }}" @if ($role->permissions->contains($permission)) checked @endif>
                            <label for="permission-{{ $permission->id }}" class="ml-2">{{ $permission->name }}</label>
                        </div>
                    @endforeach
                </div>

                <!-- Submit Button -->
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update Role</button>
            </form>
        </div>

    </section>
@endsection
