@csrf

<div class="space-y-5">

    <div>
        <label for="title" class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1.5">
            Titre du projet
        </label>
        <input type="text" name="title" id="title"
               value="{{ old('title', $project->title ?? '') }}"
               placeholder="Ex: Analyse des données climatiques..."
               class="block w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 font-medium placeholder-gray-400 focus:bg-white focus:border-teal-400 focus:ring-2 focus:ring-teal-100 focus:outline-none transition">
        @error('title')
            <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="description" class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1.5">
            Description
        </label>
        <textarea name="description" id="description" rows="5"
                  placeholder="Décrivez les objectifs, la méthodologie et le contexte du projet..."
                  class="block w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 placeholder-gray-400 focus:bg-white focus:border-teal-400 focus:ring-2 focus:ring-teal-100 focus:outline-none transition resize-none">{{ old('description', $project->description ?? '') }}</textarea>
        @error('description')
            <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div>
            <label for="status" class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1.5">
                Statut
            </label>
            <select name="status" id="status"
                    class="block w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 font-medium focus:bg-white focus:border-teal-400 focus:ring-2 focus:ring-teal-100 focus:outline-none transition">
                <option value="encours" {{ old('status', $project->status ?? '') == 'encours' ? 'selected' : '' }}>En cours</option>
                <option value="cloture" {{ old('status', $project->status ?? '') == 'cloture' ? 'selected' : '' }}>Clôturé</option>
            </select>
            @error('status')
                <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="avancement" class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1.5">
                Avancement (%)
            </label>
            <input type="number" name="avancement" id="avancement"
                   min="0" max="100"
                   value="{{ old('avancement', $project->avancement ?? 0) }}"
                   class="block w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 font-medium focus:bg-white focus:border-teal-400 focus:ring-2 focus:ring-teal-100 focus:outline-none transition">
            @error('avancement')
                <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="flex justify-end pt-2">
        <button type="submit"
                class="inline-flex items-center gap-2 bg-teal-600 hover:bg-teal-700 text-white px-6 py-2.5 rounded-xl text-sm font-semibold shadow-sm transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            Enregistrer
        </button>
    </div>

</div>