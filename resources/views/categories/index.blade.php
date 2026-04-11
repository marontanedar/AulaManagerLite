@extends('layouts.app')

@section('content')
<div class="card-panel">
    <div class="section-header">
        <div>
            <h5 class="section-title">Categorías</h5>
            <p class="section-subtitle">Gestión de categorías de recursos</p>
        </div>
        <a href="{{ route('categories.create') }}" class="btn btn-dark btn-sm">
            <i class="bi bi-plus me-1"></i>Nueva categoría
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-sm table-hover table-bordered m-0">
            <thead>
                <tr>
                    <th style="width:50px;" class="text-center">ID</th>
                    <th>Nombre</th>
                    <th style="width:120px;" class="text-center">Recursos</th>
                    <th style="width:130px;" class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    <tr>
                        <td class="text-center text-muted">{{ $category->category_id }}</td>
                        <td class="fw-bold text-uppercase" style="font-size:0.85rem;">
                            {{ $category->name }}
                        </td>
                        <td class="text-center">
                            <span class="badge bg-secondary" style="font-size:0.7rem;">
                                {{ $category->resources_count }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="d-flex gap-1 justify-content-center">
                                <a href="{{ route('categories.edit', $category->category_id) }}"
                                   class="btn btn-outline-dark btn-sm py-0 px-2">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST"
                                      action="{{ route('categories.destroy', $category->category_id) }}"
                                      onsubmit="return confirm('¿Eliminar categoría {{ $category->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm py-0 px-2">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-muted text-uppercase"
                            style="font-size:0.75rem;">
                            No hay categorías registradas.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection