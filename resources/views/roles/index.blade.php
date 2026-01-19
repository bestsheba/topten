@extends('admin.layouts.app')

@section('title')
    Brand
@endsection

@section('page-header')
    @include('admin.layouts.page-header', [
        'title' => 'Roles Management',
        'page' => 'Roles Management',
    ])
@endsection

@section('page')
    <section class="content">
        <div class="container-fluid">
            <div>
                <h2 class="text-xl font-semibold">Create New Role</h2>
                <form action="{{ route('admin.roles.store') }}" method="POST" class="mt-4">
                    @csrf
                    <div class="mb-4">
                        <label for="name" class="block font-medium">Role Name</label>
                        <input type="text" id="name" name="name" class="w-full p-2 border rounded">
                    </div>

                    <div class="mb-4">
                        <h3 class="font-medium">Permissions</h3>
                        @foreach ($permissions as $permission)
                            <div class="flex items-center">
                                <input type="checkbox" id="permission-{{ $permission->id }}" name="permissions[]"
                                    value="{{ $permission->id }}">
                                <label for="permission-{{ $permission->id }}" class="ml-2">{{ $permission->name }}</label>
                            </div>
                        @endforeach
                    </div>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Create Role</button>
                </form>
            </div>

            <h2 class="text-xl font-semibold mt-6">Existing Roles</h2>
            <table class="w-full mt-4">
                <thead>
                    <tr>
                        <th class="text-left">Role</th>
                        <th class="text-left">Permissions</th>
                        <th class="text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($roles as $role)
                        <tr>
                            <td>{{ $role->name }}</td>
                            <td>
                                @foreach ($role->permissions as $permission)
                                    <span
                                        class="inline-block bg-gray-200 p-1 my-0.5 ml-0.5 rounded">{{ $permission->name }}</span>
                                @endforeach
                            </td>
                            <td>
                                <a href="{{ route('admin.roles.edit', $role) }}" class="text-blue-500">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endsection
