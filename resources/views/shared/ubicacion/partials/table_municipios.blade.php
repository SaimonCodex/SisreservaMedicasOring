<table class="table table-hover">
    <thead>
        <tr>
            <th>Municipio</th>
            <th>Estado</th>
            <th class="w-32">Parroquias</th>
            <th class="w-24">Estatus</th>
            <th class="w-40">Acciones</th>
        </tr>
    </thead>
    <tbody>
        @forelse($data ?? [] as $municipio)
        <tr>
            <td>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                        <i class="bi bi-geo-alt text-amber-600"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">{{ $municipio->municipio }}</p>
                    </div>
                </div>
            </td>
            <td>
                <div class="flex items-center gap-2">
                    <i class="bi bi-map text-blue-600"></i>
                    <span class="text-gray-700">{{ $municipio->estado->estado ?? 'N/A' }}</span>
                </div>
            </td>
            <td class="text-center">
                <span class="text-gray-700 font-semibold">{{ $municipio->parroquias_count ?? 0 }}</span>
            </td>
            <td>
                @if($municipio->status)
                <span class="badge badge-success">Activo</span>
                @else
                <span class="badge badge-danger">Inactivo</span>
                @endif
            </td>
            <td>
                <div class="flex gap-2">
                    @if(auth()->user()->administrador && auth()->user()->administrador->tipo_admin === 'Root')
                    <a href="{{ route('ubicacion.municipios.edit', $municipio->id_municipio) }}" class="btn btn-sm btn-outline" title="Editar">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <form action="{{ route('ubicacion.municipios.destroy', $municipio->id_municipio) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de que deseas desactivar este municipio?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline text-rose-600 hover:bg-rose-50" title="Eliminar">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                    @endif
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5" class="text-center py-12">
                <i class="bi bi-inbox text-5xl text-gray-300 mb-3"></i>
                <p class="text-gray-500">No se encontraron municipios</p>
            </td>
        </tr>
        @endforelse
    </tbody>
</table>
