@extends('admin.layouts.app')

@section('title')
    Profile
@endsection

@section('page-header')
    @include('admin.layouts.page-header', [
        'title' => 'Profile',
        'page' => 'Profile',
    ])
@endsection

@section('page')
    <section class="content">
        <div class="container-fluid">

            <div class="row justify-content-center">
                <div class="col-12 col-md-4">
                    <x-admin.success />
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Update Admin Info</h3>
                        </div>
                        @php
                            $user = auth()->user('admin');
                        @endphp
                        <form action="{{ route('admin.profile.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name" class="col-form-label">
                                        Your Name
                                    </label>
                                    <input type="text" id="name" value="{{ old('name', $user?->name) }}"
                                        name="name" class="form-control @error('name') is-invalid @enderror"
                                        placeholder="Your Name">
                                    @error('name')
                                        <span class="error invalid-feedback">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="email" class="col-form-label">
                                        Email Address
                                    </label>
                                    <input type="email" name="email" value="{{ old('email', $user?->email) }}"
                                        id="email" class="form-control @error('email') is-invalid @enderror"
                                        placeholder="Email Address">
                                    @error('email')
                                        <span class="error invalid-feedback">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="password" class="col-form-label">
                                        Password
                                    </label>
                                    <input type="password" name="password" value="" id="password"
                                        class="form-control @error('password') is-invalid @enderror" placeholder="Password">
                                    @error('password')
                                        <span class="error invalid-feedback">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
