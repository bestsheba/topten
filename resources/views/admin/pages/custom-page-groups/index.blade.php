@extends('admin.layouts.app')

@section('title')
    Page Groups
@endsection

@section('page-header')
    @include('admin.layouts.page-header', [
        'title' => 'Page Groups',
        'page' => 'Page Groups',
    ])
@endsection

@section('page')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <a href="{{ route('admin.custom-page.index') }}" class="btn btn-outline-secondary mr-3">
                                    <i class="fas fa-arrow-left mr-1"></i> Back to Custom Pages
                                </a>
                                <h3 class="card-title mb-0">Manage Page Groups</h3>
                            </div>
                            <form class="form-inline mt-1 mt-md-0" method="POST" action="{{ route('admin.custom-page-group.store') }}">
                                @csrf
                                <div class="input-group">
                                    <input type="text" name="name" class="form-control mr-2" placeholder="New group name" required>
                                    <span class="input-group-append">
                                        <button class="btn btn-primary" type="submit">
                                            <i class="fas fa-plus mr-1"></i> Add Group
                                        </button>
                                    </span>
                                </div>
                            </form>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-hover text-nowrap table-bordered" id="sortable-groups">
                                <thead>
                                    <tr>
                                        <th width="50" class="text-center">Order</th>
                                        <th>Group Name</th>
                                        <th width="100" class="text-center">Pages Count</th>
                                        <th width="250" class="text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($groups as $group)
                                        <tr data-id="{{ $group->id }}">
                                            <td class="handle text-center" style="cursor: move;">
                                                <i class="fas fa-grip-vertical"></i>
                                            </td>
                                            <td>{{ $group->name }}</td>
                                            <td class="text-center">{{ $group->pages_count }}</td>
                                            <td class="text-right">
                                                <div class="btn-group" role="group">
                                                    <form class="d-inline mr-2" method="POST" action="{{ route('admin.custom-page-group.update', $group->id) }}">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="input-group">
                                                            <input type="text" name="name" class="form-control form-control-sm" value="{{ $group->name }}">
                                                            <div class="input-group-append">
                                                                <button class="btn btn-sm btn-info" type="submit">
                                                                    <i class="fas fa-save"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                    <form class="d-inline" method="POST" action="{{ route('admin.custom-page-group.destroy', $group->id) }}" onsubmit="return confirm('Are you sure?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-sm btn-danger" type="submit">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @if($groups->isEmpty())
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">
                                                <i class="fas fa-folder-open fa-3x mb-3"></i>
                                                <p class="mb-0">No page groups have been created yet.</p>
                                                <small class="text-muted">Use the form above to add your first group.</small>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script>
$(function(){
  $('#sortable-groups tbody').sortable({
    handle: '.handle',
    placeholder: 'ui-state-highlight',
    axis: 'y',
    update: function(event, ui) {
      var order = [];
      $('#sortable-groups tbody tr').each(function(index){
        order.push({id: $(this).data('id'), position: index+1});
      });

      $.ajax({
        url: '{{ route('admin.custom-page-group.reorder') }}',
        type: 'POST',
        data: { 
          _token: '{{ csrf_token() }}', 
          order: order 
        },
        success: function(){ 
          toastr.success('Group order saved automatically'); 
        },
        error: function(){ 
          toastr.error('Failed to save group order'); 
        }
      });
    }
  });
});
</script>
@endsection


