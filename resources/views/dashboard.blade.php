<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-teal-700 bg-teal-50 border border-teal-200 px-3 py-1 rounded-full">
                        <span class="w-1.5 h-1.5 rounded-full bg-teal-500 inline-block"></span>
                        Vue d'ensemble
                    </span>
                </div>
                <h1 class="text-2xl font-extrabold text-gray-900 leading-tight">
                    Bonjour, {{ Auth::user()->name }} 👋
                </h1>
                <p class="text-sm text-gray-500 mt-0.5">Voici l'état de vos projets de recherche</p>
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
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-7">

            @php
                $myProjects = auth()->user()->projects;
                $totalProjects = $myProjects->count();
                $enCours = $myProjects->where('status', 'encours')->count();
                $avgAvancement = $totalProjects > 0 ? round($myProjects->avg('avancement')) : 0;
                $asResponsable = $myProjects->filter(fn($p) => $p->pivot->role === 'responsable')->count();
                $asChercheur = $myProjects->filter(fn($p) => $p->pivot->role === 'chercheur')->count();
                $asEtudiant = $myProjects->filter(fn($p) => $p->pivot->role === 'etudiant_assistant')->count();
            @endphp

            {{-- Stats --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-widest text-gray-400">Total projets</p>
                            <p class="text-4xl font-extrabold text-gray-900 mt-2 leading-none">{{ $totalProjects }}</p>
                        </div>
                        <div class="w-11 h-11 rounded-xl flex items-center justify-center shrink-0" style="background:#f0fdfa;">
                            <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/></svg>
                        </div>
                    </div>
                    <p class="mt-4 text-xs text-gray-400">Projets auxquels vous participez</p>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-widest text-gray-400">En cours</p>
                            <p class="text-4xl font-extrabold text-teal-600 mt-2 leading-none">{{ $enCours }}</p>
                        </div>
                        <div class="w-11 h-11 rounded-xl flex items-center justify-center shrink-0" style="background:#f0fdfa;">
                            <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                    </div>
                    <p class="mt-4 text-xs text-gray-400">Projets actifs en ce moment</p>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-widest text-gray-400">Avancement moyen</p>
                            <p class="text-4xl font-extrabold text-gray-900 mt-2 leading-none">{{ $avgAvancement }}<span class="text-xl font-semibold text-gray-400">%</span></p>
                        </div>
                        <div class="w-11 h-11 rounded-xl flex items-center justify-center shrink-0" style="background:#f0fdfa;">
                            <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        </div>
                    </div>
                    <div class="mt-4 w-full bg-gray-100 rounded-full h-1.5">
                        <div class="h-1.5 rounded-full transition-all" style="width: {{ $avgAvancement }}%; background: linear-gradient(90deg, #0d9488, #0891b2);"></div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-widest text-gray-400">Mes rôles</p>
                        </div>
                        <div class="w-11 h-11 rounded-xl flex items-center justify-center shrink-0" style="background:#f5f3ff;">
                            <svg class="w-5 h-5 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                    </div>
                    <div class="mt-3 flex flex-col gap-2">
                        @if($asResponsable > 0)
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-gray-500">Responsable</span>
                                <span class="text-xs font-bold text-violet-700 bg-violet-50 border border-violet-200 px-2 py-0.5 rounded-full">{{ $asResponsable }}</span>
                            </div>
                        @endif
                        @if($asChercheur > 0)
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-gray-500">Chercheur</span>
                                <span class="text-xs font-bold text-teal-700 bg-teal-50 border border-teal-200 px-2 py-0.5 rounded-full">{{ $asChercheur }}</span>
                            </div>
                        @endif
                        @if($asEtudiant > 0)
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-gray-500">Étudiant assistant</span>
                                <span class="text-xs font-bold text-amber-700 bg-amber-50 border border-amber-200 px-2 py-0.5 rounded-full">{{ $asEtudiant }}</span>
                            </div>
                        @endif
                        @if($totalProjects === 0)
                            <p class="text-xs text-gray-400">Aucun rôle assigné</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Projets récents --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
                    <div>
                        <h3 class="text-base font-bold text-gray-900">Mes projets récents</h3>
                        <p class="text-xs text-gray-400 mt-0.5">Les 5 derniers projets</p>
                    </div>
                    <a href="{{ route('projects.index') }}"
                       class="flex items-center gap-1 text-sm font-semibold text-teal-600 hover:text-teal-800 transition">
                        Voir tout
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>

                @if($myProjects->isEmpty())
                    <div class="py-16 text-center">
                        <div class="w-14 h-14 rounded-full flex items-center justify-center mx-auto mb-4" style="background:#f0fdfa;">
                            <svg class="w-7 h-7 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/></svg>
                        </div>
                        <p class="text-sm text-gray-500 mb-4">Vous n'avez pas encore de projet.</p>
                        <a href="{{ route('projects.create') }}"
                           class="inline-flex items-center gap-2 bg-teal-600 hover:bg-teal-700 text-white px-5 py-2.5 rounded-lg text-sm font-semibold shadow-sm transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Créer mon premier projet
                        </a>
                    </div>
                @else
                    <div class="divide-y divide-gray-50">
                        @foreach($myProjects->sortByDesc('created_at')->take(5) as $project)
                            <div class="flex items-center justify-between px-6 py-4 hover:bg-teal-50/30 transition-colors">
                                <div class="flex items-center gap-4 min-w-0">
                                    <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 text-teal-700 text-sm font-bold" style="background:#f0fdfa;">
                                        {{ strtoupper(substr($project->title, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <a href="{{ route('projects.show', $project) }}"
                                           class="font-semibold text-gray-900 hover:text-teal-700 transition truncate block">
                                            {{ $project->title }}
                                        </a>
                                        <div class="text-xs text-gray-400 mt-0.5">
                                            @if($project->pivot->role === 'responsable')
                                                <span class="text-violet-600 font-medium">Responsable</span>
                                            @elseif($project->pivot->role === 'chercheur')
                                                <span class="text-teal-600 font-medium">Chercheur</span>
                                            @else
                                                <span class="text-amber-600 font-medium">Étudiant assistant</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4 shrink-0">
                                    <div class="hidden sm:flex items-center gap-2">
                                        <div class="w-24 bg-gray-100 rounded-full h-1.5">
                                            <div class="h-1.5 rounded-full transition-all" style="width: {{ $project->avancement }}%; background: linear-gradient(90deg, #0d9488, #0891b2);"></div>
                                        </div>
                                        <span class="text-xs font-semibold text-gray-500 w-8 text-right">{{ $project->avancement }}%</span>
                                    </div>
                                    @if($project->status === 'encours')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-teal-50 text-teal-700 border border-teal-200">En cours</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-500 border border-gray-200">Clôturé</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
