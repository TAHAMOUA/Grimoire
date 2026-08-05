<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">

@foreach($projects as $project)

<div class="bg-zinc-900 border border-red-800 rounded-2xl p-6 hover:border-red-500 transition">

<h2 class="text-2xl text-white font-bold">

{{ $project->title }}

</h2>

<p class="text-gray-400 mt-3">

{{ Str::limit($project->description,80) }}

</p>

<div class="mt-5">

<a
href="{{ route('projects.show',$project) }}"
class="text-red-400">

Voir →

</a>

</div>

</div>

@endforeach

</div><x-app-layout>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">
                Liste des projets
            </h2>

            @can('create', App\Models\Project::class)
                <a href="{{ route('projects.create') }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                    Nouveau Projet
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow rounded-lg">

                <table class="min-w-full border-collapse">

                    <thead class="bg-gray-100">

                        <tr>
                            <th class="px-6 py-3 text-left">Titre</th>
                            <th class="px-6 py-3 text-left">Status</th>
                            <th class="px-6 py-3 text-left">Avancement</th>
                            <th class="px-6 py-3 text-center">Actions</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($projects as $project)

                            <tr class="border-t">

                                <td class="px-6 py-4">
                                    {{ $project->title }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ $project->status }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ $project->avancement }}%
                                </td>

                                <td class="px-6 py-4 text-center">

                                    <a href="{{ route('projects.show', $project) }}"
                                       class="text-blue-600 hover:underline">
                                        Voir
                                    </a>

                                    @can('update', $project)
                                        |
                                        <a href="{{ route('projects.edit', $project) }}"
                                           class="text-yellow-600 hover:underline">
                                            Modifier
                                        </a>
                                    @endcan

                                    @can('delete', $project)
                                        |
                                        <form action="{{ route('projects.destroy', $project) }}"
                                              method="POST"
                                              class="inline">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    onclick="return confirm('Archiver ce projet ?')"
                                                    class="text-red-600 hover:underline">
                                                Archiver
                                            </button>

                                        </form>
                                    @endcan

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="4" class="text-center py-6 text-gray-500">
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