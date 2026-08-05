<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Détails du projet
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-sm rounded-lg p-6">

                <h3 class="text-2xl font-bold mb-6">
                    {{ $project->title }}
                </h3>

                <div class="mb-4">
                    <strong>Description :</strong>
                    <p class="mt-2">
                        {{ $project->description }}
                    </p>
                </div>

                <div class="mb-4">
                    <strong>Statut :</strong>
                    {{ ucfirst($project->status) }}
                </div>

                <div class="mb-6">
                    <strong>Avancement :</strong>
                    {{ $project->avancement }} %
                </div>

                <div class="flex gap-3">

                    <a href="{{ route('projects.index') }}"
                       class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                        Retour
                    </a>

                    @can('update', $project)
                        <a href="{{ route('projects.edit', $project) }}"
                           class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded">
                            Modifier
                        </a>
                    @endcan

                    @can('delete', $project)
                        <form action="{{ route('projects.destroy', $project) }}"
                              method="POST">

                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    onclick="return confirm('Voulez-vous archiver ce projet ?')"
                                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">
                                Archiver
                            </button>

                        </form>
                    @endcan

                </div>

            </div>

        </div>
    </div>

</x-app-layout>