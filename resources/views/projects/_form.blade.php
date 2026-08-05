@csrf

<div class="mb-4">
    <label for="title" class="block text-sm font-medium text-gray-700">
        Titre
    </label>

    <input
        type="text"
        name="title"
        id="title"
        value="{{ old('title', $project->title ?? '') }}"
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">

    @error('title')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>

<div class="mb-4">
    <label for="description" class="block text-sm font-medium text-gray-700">
        Description
    </label>

    <textarea
        name="description"
        id="description"
        rows="5"
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $project->description ?? '') }}</textarea>

    @error('description')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>

<div class="mb-4">
    <label for="status" class="block text-sm font-medium text-gray-700">
        Statut
    </label>

    <select
        name="status"
        id="status"
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">

        <option value="encours"
            {{ old('status', $project->status ?? '') == 'encours' ? 'selected' : '' }}>
            En cours
        </option>

        <option value="cloture"
            {{ old('status', $project->status ?? '') == 'cloture' ? 'selected' : '' }}>
            Clôturé
        </option>

    </select>

    @error('status')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>

<div class="mb-6">
    <label for="avancement" class="block text-sm font-medium text-gray-700">
        Avancement (%)
    </label>

    <input
        type="number"
        name="avancement"
        id="avancement"
        min="0"
        max="100"
        value="{{ old('avancement', $project->avancement ?? 0) }}"
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">

    @error('avancement')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>

<div class="flex justify-end">

    <button
        type="submit"
        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">

        Enregistrer

    </button>

</div>