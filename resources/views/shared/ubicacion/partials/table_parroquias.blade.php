<table class="table table-hover">
    <thead>
        <tr>
            <th>Parroquia</th>
            <th>Municipio</th>
            <th>Estado</th>
            <th class="w-24">Estatus</th>
            <th class="w-40">Acciones</th>
        </tr>
    </thead>
    <tbody>
        @forelse($data ?? [] as $parroquia)
        <tr>
            <td>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                        <i class="bi bi-building-check text-emerald-600"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">{{ $parroquia->parroquia }}</p>
                    </div>
                </div>
            </td>
            <td>
                <div class="flex items-center gap-2">
                    <i class="bi bi-geo-alt text-amber-600"></i>
                    <span class="text-gray-700">{{ $parroquia->municipio->municipio ?? 'N/A' }}</span>
                </div>
            </td>
            <td>
                <div class="flex items-center gap-2">
                    <i class="bi bi-map text-blue-600"></i>
                    <span class="text-gray-700">{{ $parroquia->municipio->estado->estado ?? 'N/A' }}</span>
                </div>
            </td>
            <td>
                @if($parroquia->status)
                <span class="badge badge-success">Activa</span>
                @else
                <span class="badge badge-danger">Inactiva</span>
                @endif
            </td>
            <td>
                <div class="flex gap-2">
                    <a href="{{ route('ubicacion.parroquias.edit', $parroquia->id_parroquia) }}" class="btn btn-sm btn-outline" title="Editar">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <form action="{{ route('ubicacion.parroquias.destroy', $parroquia->id_parroquia) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de que deseas desactivar esta parroquia?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline text-rose-600 hover:bg-rose-50" title="Eliminar">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5" class="text-center py-12">
                <i class="bi bi-inbox text-5xl text-gray-300 mb-3"></i>
                <p class="text-gray-500">No se encontraron parroquias</p>
            </td>
        </tr>
        @endforelse
    </tbody>
</table>
