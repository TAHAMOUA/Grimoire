<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-extrabold text-gray-900 leading-tight">{{ $project->title }}</h1>
                <p class="text-sm text-gray-500 mt-0.5">Détails et gestion du projet</p>
            </div>
            <a href="{{ route('projects.index') }}"
               class="inline-flex items-center gap-1.5 text-sm font-semibold text-teal-600 hover:text-teal-800 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Retour aux projets
            </a>
        </div>
    </x-slot>

    <div class="py-8 min-h-screen" style="background: linear-gradient(135deg, #f0fdfa 0%, #f8fafc 60%, #f0f9ff 100%);">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

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

            {{-- Infos du projet --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <div class="flex justify-between items-start mb-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center text-teal-700 text-xl font-extrabold shrink-0" style="background:#f0fdfa;">
                            {{ strtoupper(substr($project->title, 0, 1)) }}
                        </div>
                        <div>
                            <h2 class="text-xl font-extrabold text-gray-900">{{ $project->title }}</h2>
                            @if($project->status === 'encours')
                                <span class="inline-flex items-center mt-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-teal-50 text-teal-700 border border-teal-200">En cours</span>
                            @else
                                <span class="inline-flex items-center mt-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-gray-100 text-gray-500 border border-gray-200">Clôturé</span>
                            @endif
                        </div>
                    </div>
                    @if($userRole === 'responsable')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-violet-50 text-violet-700 border border-violet-200">Responsable</span>
                    @elseif($userRole === 'chercheur')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-teal-50 text-teal-700 border border-teal-200">Chercheur</span>
                    @elseif($userRole === 'etudiant_assistant')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-amber-50 text-amber-700 border border-amber-200">Étudiant assistant</span>
                    @endif
                </div>

                <div class="mb-6">
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2">Description</p>
                    <p class="text-gray-700 text-sm leading-relaxed">{{ $project->description }}</p>
                </div>

                <div class="mb-6">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Avancement</p>
                        <span class="text-sm font-extrabold text-gray-800">{{ $project->avancement }}%</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2.5">
                        <div class="h-2.5 rounded-full transition-all" style="width: {{ $project->avancement }}%; background: linear-gradient(90deg, #0d9488, #0891b2);"></div>
                    </div>
                </div>

                @can('update', $project)
                    <div class="flex flex-wrap gap-3 pt-4 border-t border-gray-100">
                        <a href="{{ route('projects.edit', $project) }}"
                           class="inline-flex items-center gap-2 bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg text-sm font-semibold shadow-sm transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Modifier le projet
                        </a>
                        <form action="{{ route('projects.destroy', $project) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Voulez-vous archiver ce projet ?')"
                                    class="inline-flex items-center gap-2 bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 px-4 py-2 rounded-lg text-sm font-semibold transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                                Archiver
                            </button>
                        </form>
                    </div>
                @endcan
            </div>

            {{-- Mise à jour avancement (Chercheur) --}}
            @can('updateAvancement', $project)
                @if($userRole !== 'responsable')
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                        <h4 class="text-base font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            Mettre à jour l'avancement
                        </h4>
                        <form action="{{ route('projects.avancement.update', $project) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="flex items-center gap-4">
                                <input type="number" name="avancement" value="{{ $project->avancement }}"
                                       min="0" max="100"
                                       class="border border-gray-200 rounded-lg px-4 py-2.5 w-28 text-sm font-semibold focus:ring-2 focus:ring-teal-400 focus:border-teal-400 focus:outline-none">
                                <span class="text-gray-400 font-semibold">%</span>
                                <button type="submit"
                                        class="bg-teal-600 hover:bg-teal-700 text-white px-5 py-2.5 rounded-lg text-sm font-semibold shadow-sm transition">
                                    Mettre à jour
                                </button>
                            </div>
                            @error('avancement')
                                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </form>
                    </div>
                @endif
            @endcan

            {{-- Gestion des membres (Responsable) --}}
            @can('manageMember', $project)
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100">
                        <h4 class="text-base font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Membres du projet
                        </h4>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-gray-50/70 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                    <th class="px-6 py-3 text-left">Membre</th>
                                    <th class="px-6 py-3 text-left">Email</th>
                                    <th class="px-6 py-3 text-left">Rôle</th>
                                    <th class="px-6 py-3 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($project->users as $member)
                                    <tr class="hover:bg-teal-50/30 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-teal-700 text-xs font-bold shrink-0" style="background:#f0fdfa;">
                                                    {{ strtoupper(substr($member->name, 0, 1)) }}
                                                </div>
                                                <span class="font-semibold text-gray-900">{{ $member->name }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-gray-500">{{ $member->email }}</td>
                                        <td class="px-6 py-4">
                                            @if($member->pivot->role === 'responsable')
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-violet-50 text-violet-700 border border-violet-200">Responsable</span>
                                            @elseif($member->pivot->role === 'chercheur')
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-teal-50 text-teal-700 border border-teal-200">Chercheur</span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">Étudiant assistant</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            @if($member->id !== auth()->id())
                                                <form action="{{ route('projects.members.remove', [$project, $member]) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" onclick="return confirm('Retirer {{ $member->name }} du projet ?')"
                                                            class="text-xs font-semibold text-red-500 hover:text-red-700 transition">
                                                        Retirer
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-xs text-gray-400">Vous</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Formulaire ajout membre --}}
                    @if($allUsers->isNotEmpty())
                        <div class="px-6 py-5 border-t border-gray-100 bg-gray-50/50">
                            <p class="text-xs font-semibold text-gray-500 mb-3 uppercase tracking-wider">Ajouter un membre</p>
                            <form action="{{ route('projects.members.add', $project) }}" method="POST">
                                @csrf
                                <div class="flex flex-wrap gap-3">
                                    <select name="user_id"
                                            class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-teal-400 focus:border-teal-400 focus:outline-none bg-white">
                                        <option value="">-- Choisir un utilisateur --</option>
                                        @foreach($allUsers as $u)
                                            <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                                        @endforeach
                                    </select>
                                    <select name="role"
                                            class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-teal-400 focus:border-teal-400 focus:outline-none bg-white">
                                        <option value="chercheur">Chercheur</option>
                                        <option value="etudiant_assistant">Étudiant assistant</option>
                                    </select>
                                    <button type="submit"
                                            class="inline-flex items-center gap-1.5 bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg text-sm font-semibold shadow-sm transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                        Ajouter
                                    </button>
                                </div>
                                @error('user_id')<p class="text-red-500 text-xs mt-2">{{ $message }}</p>@enderror
                                @error('role')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </form>
                        </div>
                    @else
                        <p class="text-gray-400 text-sm px-6 py-4 border-t border-gray-100">Tous les utilisateurs sont déjà membres de ce projet.</p>
                    @endif
                </div>
            @endcan

            {{-- Vue membres lecture seule --}}
            @cannot('manageMember', $project)
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <h4 class="text-base font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Membres du projet
                    </h4>
                    <div class="flex flex-wrap gap-3">
                        @foreach($project->users as $member)
                            <div class="flex items-center gap-2.5 bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5">
                                <div class="w-7 h-7 rounded-full flex items-center justify-center text-teal-700 text-xs font-bold shrink-0" style="background:#f0fdfa;">
                                    {{ strtoupper(substr($member->name, 0, 1)) }}
                                </div>
                                <span class="font-semibold text-sm text-gray-800">{{ $member->name }}</span>
                                @if($member->pivot->role === 'responsable')
                                    <span class="text-xs font-bold text-violet-600 bg-violet-50 border border-violet-200 px-2 py-0.5 rounded-full">Resp.</span>
                                @elseif($member->pivot->role === 'chercheur')
                                    <span class="text-xs font-bold text-teal-600 bg-teal-50 border border-teal-200 px-2 py-0.5 rounded-full">Cherch.</span>
                                @else
                                    <span class="text-xs font-bold text-amber-600 bg-amber-50 border border-amber-200 px-2 py-0.5 rounded-full">Étud.</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endcannot

        </div>
    </div>
</x-app-layout>