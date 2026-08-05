<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Projets archivés
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-sm rounded-lg p-6">

                @if($projects->isEmpty())

                    <p class="text-gray-500">
                        Aucun projet archivé.
                    </p>

                @else

                    <table class="min-w-full border border-gray-300">

                        <thead class="bg-gray-100">

                            <tr>
                                <th class="border px-4 py-2 text-left">Titre</th>
                                <th class="border px-4 py-2 text-left">Status</th>
                                <th class="border px-4 py-2 text-left">Avancement</th>
                                <th class="border px-4 py-2 text-left">Date d'archivage</th>
                            </tr>

                        </thead>

                        <tbody>

                            @foreach($projects as $project)

                                <tr>

                                    <td class="border px-4 py-2">
                                        {{ $project->title }}
                                    </td>

                                    <td class="border px-4 py-2">
                                        {{ $project->status }}
                                    </td>

                                    <td class="border px-4 py-2">
                                        {{ $project->avancement }}%
                                    </td>

                                    <td class="border px-4 py-2">
                                        {{ $project->deleted_at }}
                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                @endif

                <div class="mt-6">

                    <a href="{{ route('projects.index') }}"
                       class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">
                        Retour
                    </a>

                </div>

            </div>

        </div>
    </div>

</x-app-layout>