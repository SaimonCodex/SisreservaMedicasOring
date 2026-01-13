<table class="table table-hover">
    <thead>
        <tr>
            <th>Estado</th>
            <th>ISO Code</th>
            <th class="w-48 text-center">Ciudades/Municipios</th>
            <th class="w-32">Estatus</th>
            <th class="w-40">Acciones</th>
        </tr>
    </thead>
    <tbody>
        @forelse($data ?? [] as $estado)
        <tr>
            <td>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                        <i class="bi bi-map text-indigo-600"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">{{ $estado->estado }}</p>
                    </div>
                </div>
            </td>
            <td>
                <span class="font-mono text-gray-600 bg-gray-100 px-2 py-1 rounded">{{ $estado->iso_3166_2 }}</span>
            </td>
            <td>
                <div class="flex items-center justify-center gap-4">
                    <div class="text-center">
                        <span class="block font-bold text-gray-900">{{ $estado->ciudades_count }}</span>
                        <span class="text-xs text-gray-500">Ciudades</span>
                    </div>
                    <div class="w-px h-8 bg-gray-200"></div>
                    <div class="text-center">
                        <span class="block font-bold text-gray-900">{{ $estado->municipios_count }}</span>
                        <span class="text-xs text-gray-500">Municipios</span>
                    </div>
                </div>
            </td>
            <td>
                @if($estado->status)
                <span class="badge badge-success">Activo</span>
                @else
                <span class="badge badge-danger">Inactivo</span>
                @endif
            </td>
            <td>
                <div class="flex gap-2">
                    <a href="{{ route('ubicacion.estados.edit', $estado->id_estado) }}" class="btn btn-sm btn-outline" title="Editar">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <form action="{{ route('ubicacion.estados.destroy', $estado->id_estado) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de que deseas desactivar este estado?');">
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
                <p class="text-gray-500">No se encontraron estados</p>
            </td>
        </tr>
        @endforelse
    </tbody>
</table>
