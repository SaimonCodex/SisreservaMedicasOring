<table class="table table-hover">
    <thead>
        <tr>
            <th>Ciudad</th>
            <th>Estado</th>
            <th>Capital</th>
            <th class="w-24">Estatus</th>
            <th class="w-40">Acciones</th>
        </tr>
    </thead>
    <tbody>
        @forelse($data ?? [] as $ciudad)
        <tr>
            <td>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                        <i class="bi bi-buildings text-indigo-600"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">{{ $ciudad->ciudad }}</p>
                    </div>
                </div>
            </td>
            <td>
                <div class="flex items-center gap-2">
                    <i class="bi bi-map text-gray-400"></i>
                    <span class="text-gray-700">{{ $ciudad->estado->estado ?? 'N/A' }}</span>
                </div>
            </td>
            <td>
                @if($ciudad->capital)
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                    <i class="bi bi-star-fill text-[10px]"></i> Capital
                </span>
                @else
                <span class="text-gray-400">-</span>
                @endif
            </td>
            <td>
                @if($ciudad->status)
                <span class="badge badge-success">Activa</span>
                @else
                <span class="badge badge-danger">Inactiva</span>
                @endif
            </td>
            <td>
                <div class="flex gap-2">
                    @if(auth()->user()->administrador && auth()->user()->administrador->tipo_admin === 'Root')
                    <a href="{{ route('ubicacion.ciudades.edit', $ciudad->id_ciudad) }}" class="btn btn-sm btn-outline" title="Editar">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <form action="{{ route('ubicacion.ciudades.destroy', $ciudad->id_ciudad) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de que deseas desactivar esta ciudad?');">
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
                <p class="text-gray-500">No se encontraron ciudades</p>
            </td>
        </tr>
        @endforelse
    </tbody>
</table>
