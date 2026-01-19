@extends('admin.layouts.app')

@section('title', 'Google Tag Manager')

@section('page')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Google Tag Manager</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.update-google-tag-manager') }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="google_tag_manager_id">GTM Container ID (e.g. GTM-XXXXXXX)</label>
                                    <input type="text" name="google_tag_manager_id" id="google_tag_manager_id"
                                        class="form-control" value="{{ old('google_tag_manager_id', $settings->google_tag_manager_id ?? '') }}"
                                        placeholder="GTM-XXXXXXX">
                                    @error('google_tag_manager_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection


