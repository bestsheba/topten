@extends('admin.layouts.app')
@section('title')
    Attribute Management
@endsection
@section('page-header')
    @include('admin.layouts.page-header', [
        'title' => 'Attribute Management',
        'page' => 'Attributes',
    ])
@endsection
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .attribute-card {
            transition: all 0.3s ease;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        
        .attribute-card:hover {
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }
        
        .attribute-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            border-radius: 8px 8px 0 0;
            padding: 15px 20px;
        }
        
        .attribute-values {
            padding: 15px 20px;
        }
        
        .attribute-actions {
            padding: 10px 20px;
            background-color: #f8f9fa;
            border-top: 1px solid #dee2e6;
            border-radius: 0 0 8px 8px;
        }
        
        .badge-value {
            font-size: 0.85rem;
            padding: 0.4rem 0.6rem;
            margin-right: 8px;
            margin-bottom: 8px;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
        }
        
        .value-remove {
            margin-left: 5px;
            opacity: 0.7;
            transition: all 0.2s;
        }
        
        .value-remove:hover {
            opacity: 1;
        }
        
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
        }
        
        .modal-header {
            background-color: #f8f9fa;
        }
        
        .input-group-text {
            background-color: transparent;
        }
        
        .form-control:focus {
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
        }
        
        .btn-add-attribute {
            border-radius: 50px;
            padding: 8px 20px;
        }
    </style>
@endsection
@section('page')
    <section class="content">
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Product Attributes</h4>
                        <button class="btn btn-primary btn-add-attribute" data-bs-toggle="modal" data-bs-target="#createAttributeModal">
                            <i class="bi bi-plus-lg me-1"></i> Add New Attribute
                        </button>
                    </div>
                </div>
            </div>

            <div class="row">
                @if(count($attributes) > 0)
                    @foreach ($attributes as $attribute)
                        <div class="col-md-6 col-lg-4">
                            <div class="attribute-card">
                                <div class="attribute-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">{{ $attribute->name }}</h5>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <button class="dropdown-item edit-attribute" data-bs-toggle="modal"
                                                        data-bs-target="#editAttributeModal" 
                                                        data-attribute="{{ $attribute }}"
                                                        data-values="{{ $attribute->values }}">
                                                    <i class="bi bi-pencil me-2"></i> Edit
                                                </button>
                                            </li>
                                            <li>
                                                <form action="{{ route('admin.attributes.destroy', $attribute) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger"
                                                            onclick="return confirm('Are you sure you want to delete this attribute?')">
                                                        <i class="bi bi-trash me-2"></i> Delete
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="attribute-values">
                                    @if(count($attribute->values) > 0)
                                        @foreach ($attribute->values as $value)
                                            <span class="badge bg-primary badge-value">
                                                {{ $value->value }}
                                                <a href="{{ route('attribute-values.destroy', $value) }}"
                                                    class="text-white value-remove" 
                                                    onclick="event.preventDefault(); if(confirm('Are you sure?')) document.getElementById('delete-value-{{ $value->id }}').submit();">
                                                    <i class="bi bi-x"></i>
                                                </a>
                                                <form id="delete-value-{{ $value->id }}" action="{{ route('attribute-values.destroy', $value) }}" method="POST" class="d-none">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </span>
                                        @endforeach
                                    @else
                                        <p class="text-muted mb-0">No values added yet</p>
                                    @endif
                                </div>
                                {{-- <div class="attribute-actions">
                                    <button class="btn btn-sm btn-outline-primary add-value-btn" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#addValueModal"
                                            data-attribute-id="{{ $attribute->id }}"
                                            data-attribute-name="{{ $attribute->name }}">
                                        <i class="bi bi-plus-lg"></i> Add Value
                                    </button>
                                </div> --}}
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-12">
                        <div class="empty-state">
                            <i class="bi bi-tags fs-1 text-muted mb-3"></i>
                            <h5>No Attributes Found</h5>
                            <p class="text-muted">Create your first attribute to define product properties.</p>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createAttributeModal">
                                <i class="bi bi-plus-lg me-1"></i> Add Attribute
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Create Attribute Modal -->
        <div class="modal fade" id="createAttributeModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('admin.attributes.store') }}" method="POST" id="createAttributeForm">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Create New Attribute</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="name" class="form-label">Attribute Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                                <div class="form-text">Example: Color, Size, Material</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Values</label>
                                <div id="value-inputs">
                                    <div class="input-group mb-2">
                                        <span class="input-group-text"><i class="bi bi-tag"></i></span>
                                        <input type="text" class="form-control" name="values[]" placeholder="Enter value" required>
                                        <button type="button" class="btn btn-outline-danger remove-value" disabled>
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-secondary mt-2" id="add-value">
                                    <i class="bi bi-plus-lg"></i> Add Another Value
                                </button>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Create Attribute</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Attribute Modal -->
        <div class="modal fade" id="editAttributeModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="editAttributeForm" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Attribute</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="edit_name" class="form-label">Attribute Name</label>
                                <input type="text" class="form-control" id="edit_name" name="name" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Values</label>
                                <div id="edit-value-inputs">
                                    <!-- Values will be added here via JavaScript -->
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-secondary mt-2" id="add-edit-value">
                                    <i class="bi bi-plus-lg"></i> Add New Value
                                </button>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Update Attribute</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Add Value Modal -->
        <div class="modal fade" id="addValueModal" tabindex="-1">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <form id="addValueForm" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Add Value to <span id="attribute-name-display"></span></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="new_value" class="form-label">Value</label>
                                <input type="text" class="form-control" id="new_value" name="value" required>
                                <input type="hidden" id="attribute_id" name="attribute_id">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Add Value</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('script')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous">
    </script>
    <script>
        $(document).ready(function() {
            // Create attribute modal functionality
            $('#add-value').click(function() {
                const inputGroup = `
                    <div class="input-group mb-2">
                        <span class="input-group-text"><i class="bi bi-tag"></i></span>
                        <input type="text" class="form-control" name="values[]" placeholder="Enter value" required>
                        <button type="button" class="btn btn-outline-danger remove-value">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                `;
                $('#value-inputs').append(inputGroup);
                
                // Enable all remove buttons when we have more than one value
                if ($('#value-inputs .input-group').length > 1) {
                    $('.remove-value').prop('disabled', false);
                }
            });

            // Remove value input
            $(document).on('click', '.remove-value', function() {
                $(this).closest('.input-group').remove();
                
                // If only one value input remains, disable its remove button
                if ($('#value-inputs .input-group').length === 1) {
                    $('#value-inputs .remove-value').prop('disabled', true);
                }
                
                // If only one value input remains in edit modal, disable its remove button
                if ($('#edit-value-inputs .input-group').length === 1) {
                    $('#edit-value-inputs .remove-value').prop('disabled', true);
                }
            });

            // Edit attribute modal setup
            $('.edit-attribute').click(function() {
                const attribute = $(this).data('attribute');
                const values = $(this).data('values');
                
                $('#edit_name').val(attribute.name);
                $('#editAttributeForm').attr('action', `/admin/attributes/${attribute.id}`);
                
                $('#edit-value-inputs').empty();
                
                if (values.length > 0) {
                    values.forEach((value, index) => {
                        const inputGroup = `
                            <div class="input-group mb-2">
                                <span class="input-group-text"><i class="bi bi-tag"></i></span>
                                <input type="hidden" name="value_ids[]" value="${value.id}">
                                <input type="text" class="form-control" name="values[]" value="${value.value}" required>
                                <button type="button" class="btn btn-outline-danger remove-value" ${values.length === 1 ? 'disabled' : ''}>
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        `;
                        $('#edit-value-inputs').append(inputGroup);
                    });
                } else {
                    // Add empty value input if no values exist
                    const inputGroup = `
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="bi bi-tag"></i></span>
                            <input type="text" class="form-control" name="values[]" required>
                            <button type="button" class="btn btn-outline-danger remove-value" disabled>
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    `;
                    $('#edit-value-inputs').append(inputGroup);
                }
            });

            // Add value input in edit modal
            $('#add-edit-value').click(function() {
                const inputGroup = `
                    <div class="input-group mb-2">
                        <span class="input-group-text"><i class="bi bi-tag"></i></span>
                        <input type="text" class="form-control" name="values[]" placeholder="Enter new value" required>
                        <button type="button" class="btn btn-outline-danger remove-value">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                `;
                $('#edit-value-inputs').append(inputGroup);
                
                // Enable all remove buttons when we have more than one value
                if ($('#edit-value-inputs .input-group').length > 1) {
                    $('#edit-value-inputs .remove-value').prop('disabled', false);
                }
            });
            
            // Setup add value modal
            $('.add-value-btn').click(function() {
                const attributeId = $(this).data('attribute-id');
                const attributeName = $(this).data('attribute-name');
                
                $('#attribute_id').val(attributeId);
                $('#attribute-name-display').text(attributeName);
                $('#addValueForm').attr('action', `/admin/attributes/${attributeId}/values`);
            });
            
            // Form validation
            $('#createAttributeForm').submit(function(e) {
                const nameInput = $('#name').val().trim();
                const valueInputs = $('input[name="values[]"]');
                let valid = true;
                
                // Check for empty values
                valueInputs.each(function() {
                    if ($(this).val().trim() === '') {
                        valid = false;
                        $(this).addClass('is-invalid');
                    } else {
                        $(this).removeClass('is-invalid');
                    }
                });
                
                // Check for duplicate values
                const values = [];
                let hasDuplicates = false;
                
                valueInputs.each(function() {
                    const value = $(this).val().trim().toLowerCase();
                    if (value !== '' && values.includes(value)) {
                        hasDuplicates = true;
                        $(this).addClass('is-invalid');
                    } else if (value !== '') {
                        values.push(value);
                    }
                });
                
                if (nameInput === '') {
                    $('#name').addClass('is-invalid');
                    valid = false;
                } else {
                    $('#name').removeClass('is-invalid');
                }
                
                if (!valid || hasDuplicates) {
                    e.preventDefault();
                    if (hasDuplicates) {
                        alert('Duplicate values are not allowed.');
                    }
                }
            });
            
            // Similar validation for edit form
            $('#editAttributeForm').submit(function(e) {
                const nameInput = $('#edit_name').val().trim();
                const valueInputs = $('#edit-value-inputs input[name="values[]"]');
                let valid = true;
                
                // Check for empty values
                valueInputs.each(function() {
                    if ($(this).val().trim() === '') {
                        valid = false;
                        $(this).addClass('is-invalid');
                    } else {
                        $(this).removeClass('is-invalid');
                    }
                });
                
                // Check for duplicate values
                const values = [];
                let hasDuplicates = false;
                
                valueInputs.each(function() {
                    const value = $(this).val().trim().toLowerCase();
                    if (value !== '' && values.includes(value)) {
                        hasDuplicates = true;
                        $(this).addClass('is-invalid');
                    } else if (value !== '') {
                        values.push(value);
                    }
                });
                
                if (nameInput === '') {
                    $('#edit_name').addClass('is-invalid');
                    valid = false;
                } else {
                    $('#edit_name').removeClass('is-invalid');
                }
                
                if (!valid || hasDuplicates) {
                    e.preventDefault();
                    if (hasDuplicates) {
                        alert('Duplicate values are not allowed.');
                    }
                }
            });
        });
    </script>
@endsection