<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Créer un produit') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Nom -->
                        <div>
                            <x-input-label for="nom" :value="__('Nom du produit')" />
                            <x-text-input id="nom" class="block mt-1 w-full" type="text" name="nom" :value="old('nom')" required autofocus />
                            <x-input-error :messages="$errors->get('nom')" class="mt-2" />
                        </div>

                        <!-- Description -->
                        <div class="mt-4">
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" name="description" rows="4" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <!-- Prix -->
                        <div class="mt-4">
                            <x-input-label for="prix" :value="__('Prix (MAD)')" />
                            <x-text-input id="prix" class="block mt-1 w-full" type="number" name="prix" step="0.01" min="0" :value="old('prix')" required />
                            <x-input-error :messages="$errors->get('prix')" class="mt-2" />
                        </div>

                        <!-- Catégorie -->
                        <div class="mt-4">
                            <x-input-label for="categorie" :value="__('Catégorie')" />
                            <select 
                                id="categorie" 
                                name="categorie" 
                                class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2 px-3 bg-white text-gray-900"
                            >
                                <option value="">{{ __('Sélectionner une catégorie') }}</option>
                                @foreach($categories as $categorie)
                                    <option value="{{ $categorie }}" {{ old('categorie') == $categorie ? 'selected' : '' }}>
                                        {{ $categorie }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('categorie')" class="mt-2" />
                        </div>

                        <!-- Image -->
                        <div class="mt-4">
                            <x-input-label for="image" :value="__('Image')" />
                            <input id="image" class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" type="file" name="image" accept="image/*" />
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                        </div>

                        <!-- Est actif -->
                        <div class="block mt-4">
                            <label for="est_actif" class="inline-flex items-center">
                                <input id="est_actif" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="est_actif" value="1" {{ old('est_actif', true) ? 'checked' : '' }}>
                                <span class="ms-2 text-sm text-gray-600">{{ __('Produit actif') }}</span>
                            </label>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('admin.products.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">
                                {{ __('Annuler') }}
                            </a>
                            <x-primary-button>
                                {{ __('Créer') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

