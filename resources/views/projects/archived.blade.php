<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-extrabold text-gray-900 leading-tight">Projets archivés</h1>
                <p class="text-sm text-gray-500 mt-0.5">Historique des projets clôturés et archivés</p>
            </div>
            <a href="{{ route('projects.index') }}"
               class="inline-flex items-center gap-1.5 text-sm font-semibold text-teal-600 hover:text-teal-800 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Retour aux projets
            </a>
        </div>
    </x-slot>

    <div class="py-8 min-h-screen" style="background: linear-gradient(135deg, #f0fdfa 0%, #f8fafc 60%, #f0f9ff 100%);">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
                    <div>
                        <h3 class="text-base font-bold text-gray-900">Projets archivés</h3>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $projects->count() }} projet(s) archivé(s)</p>
                    </div>
                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-500 bg-gray-100 border border-gray-200 px-3 py-1 rounded-full">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                        Archives
                    </span>
                </div>

                @if($projects->isEmpty())
                    <div class="py-16 text-center">
                        <div class="w-14 h-14 rounded-full flex items-center justify-center mx-auto mb-4" style="background:#f9fafb;">
                            <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                        </div>
                        <p class="text-sm text-gray-400">Aucun projet archivé pour le moment.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-gray-50/70 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                    <th class="px-6 py-3.5 text-left">Projet</th>
                                    <th class="px-6 py-3.5 text-left">Statut</th>
                                    <th class="px-6 py-3.5 text-left">Avancement</th>
                                    <th class="px-6 py-3.5 text-left">Archivé le</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($projects as $project)
                                    <tr class="hover:bg-gray-50/50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-9 h-9 rounded-xl flex items-center justify-center text-gray-500 text-sm font-bold shrink-0 bg-gray-100">
                                                    {{ strtoupper(substr($project->title, 0, 1)) }}
                                                </div>
                                                <span class="font-semibold text-gray-700">{{ $project->title }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-500 border border-gray-200">
                                                {{ $project->status === 'encours' ? 'En cours' : 'Clôturé' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <div class="w-20 bg-gray-100 rounded-full h-1.5">
                                                    <div class="h-1.5 rounded-full bg-gray-300" style="width: {{ $project->avancement }}%;"></div>
                                                </div>
                                                <span class="text-xs font-semibold text-gray-400">{{ $project->avancement }}%</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-xs text-gray-400">
                                            {{ \Carbon\Carbon::parse($project->deleted_at)->format('d/m/Y') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>