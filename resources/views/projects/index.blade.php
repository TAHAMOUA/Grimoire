<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Liste des projets
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @can('create', App\Models\Project::class)
                <a href="{{ route('projects.create') }}"
                   class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                    Nouveau projet
                </a>
            @endcan

            <div class="mt-6 bg-white shadow rounded-lg">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="p-3 text-left">Titre</th>
                            <th class="p-3 text-left">Status</th>
                            <th class="p-3 text-left">Avancement</th>
                            <th class="p-3 text-left">Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                    @forelse($projects as $project)

                        <tr class="border-b">

                            <td class="p-3">
                                {{ $project->title }}
                            </td>

                            <td class="p-3">
                                {{ $project->status }}
                            </td>

                            <td class="p-3">
                                {{ $project->avancement }}%
                            </td>

                            <td class="p-3">

                                <a href="{{ route('projects.show',$project) }}"
                                   class="text-blue-600">
                                    Voir
                                </a>

                                @can('update',$project)

                                    |

                                    <a href="{{ route('projects.edit',$project) }}"
                                       class="text-yellow-600">
                                        Modifier
                                    </a>

                                @endcan

                                @can('delete',$project)

                                    <form
                                        action="{{ route('projects.destroy',$project) }}"
                                        method="POST"
                                        class="inline">

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            onclick="return confirm('Archiver ce projet ?')"
                                            class="text-red-600">

                                            Supprimer

                                        </button>

                                    </form>

                                @endcan

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="4" class="p-4 text-center">
                                Aucun projet trouvé.
                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>
    </div>

</x-app-layout>