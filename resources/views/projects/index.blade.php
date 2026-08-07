<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-extrabold text-gray-900 leading-tight">Mes projets</h1>
                <p class="text-sm text-gray-500 mt-0.5">Gérez et suivez tous vos projets de recherche</p>
            </div>
            @can('create', App\Models\Project::class)
                <a href="{{ route('projects.create') }}"
                   class="inline-flex items-center gap-2 bg-teal-600 hover:bg-teal-700 text-white text-sm font-semibold px-4 py-2.5 rounded-lg shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Nouveau Projet
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-8 min-h-screen" style="background: linear-gradient(135deg, #f0fdfa 0%, #f8fafc 60%, #f0f9ff 100%);">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-5">

            {{-- Flash messages --}}
            @if(session('success'))
                <div class="flex items-center gap-3 bg-teal-50 border border-teal-200 text-teal-800 text-sm font-medium px-5 py-3.5 rounded-xl shadow-sm">
                    <svg class="w-4 h-4 shrink-0 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 text-sm font-medium px-5 py-3.5 rounded-xl shadow-sm">
                    <svg class="w-4 h-4 shrink-0 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('error') }}
                </div>
            @endif

            {{-- Table --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
                    <div>
                        <h3 class="text-base font-bold text-gray-900">Tous les projets</h3>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $projects->count() }} projet(s) trouvé(s)</p>
                    </div>
                    <a href="{{ route('projects.archived') }}"
                       class="flex items-center gap-1.5 text-sm text-gray-500 hover:text-teal-700 font-medium transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                        Projets archivés
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50/70 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                <th class="px-6 py-3.5 text-left">Projet</th>
                                <th class="px-6 py-3.5 text-left">Statut</th>
                                <th class="px-6 py-3.5 text-left">Avancement</th>
                                <th class="px-6 py-3.5 text-left">Mon rôle</th>
                                <th class="px-6 py-3.5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($projects as $project)
                                @php $myRole = $project->userRole(auth()->user()); @endphp
                                <tr class="hover:bg-teal-50/30 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 text-teal-700 text-sm font-bold" style="background:#f0fdfa;">
                                                {{ strtoupper(substr($project->title, 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="font-semibold text-gray-900">{{ $project->title }}</p>
                                                @if($project->description)
                                                    <p class="text-xs text-gray-400 mt-0.5 truncate max-w-xs">{{ Str::limit($project->description, 60) }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($project->status === 'encours')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-teal-50 text-teal-700 border border-teal-200">En cours</span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-500 border border-gray-200">Clôturé</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <div class="w-24 bg-gray-100 rounded-full h-1.5">
                                                <div class="h-1.5 rounded-full transition-all" style="width: {{ $project->avancement }}%; background: linear-gradient(90deg, #0d9488, #0891b2);"></div>
                                            </div>
                                            <span class="text-xs font-semibold text-gray-500">{{ $project->avancement }}%</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($myRole === 'responsable')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-violet-50 text-violet-700 border border-violet-200">Responsable</span>
                                        @elseif($myRole === 'chercheur')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-teal-50 text-teal-700 border border-teal-200">Chercheur</span>
                                        @elseif($myRole === 'etudiant_assistant')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">Étudiant assistant</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-3">
                                            <a href="{{ route('projects.show', $project) }}"
                                               class="text-xs font-semibold text-teal-600 hover:text-teal-800 transition">Voir</a>
                                            @can('update', $project)
                                                <a href="{{ route('projects.edit', $project) }}"
                                                   class="text-xs font-semibold text-gray-500 hover:text-gray-800 transition">Modifier</a>
                                            @endcan
                                            @can('delete', $project)
                                                <form action="{{ route('projects.destroy', $project) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" onclick="return confirm('Archiver ce projet ?')"
                                                            class="text-xs font-semibold text-red-500 hover:text-red-700 transition">
                                                        Archiver
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-16 text-center">
                                        <div class="w-14 h-14 rounded-full flex items-center justify-center mx-auto mb-4" style="background:#f0fdfa;">
                                            <svg class="w-7 h-7 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/></svg>
                                        </div>
                                        <p class="text-sm text-gray-500 mb-4">Aucun projet trouvé.</p>
                                        @can('create', App\Models\Project::class)
                                            <a href="{{ route('projects.create') }}"
                                               class="inline-flex items-center gap-2 bg-teal-600 hover:bg-teal-700 text-white px-5 py-2.5 rounded-lg text-sm font-semibold shadow-sm transition">
                                                Créer votre premier projet
                                            </a>
                                        @endcan
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
