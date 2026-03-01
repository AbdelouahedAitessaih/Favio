<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestion des produits') }}
            </h2>
            <a href="{{ route('admin.products.create') }}">
                <x-primary-button>
                    <i class="fas fa-plus mr-2"></i>
                    {{ __('Nouveau produit') }}
                </x-primary-button>
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Filtres -->
            <div class="bg-white shadow-sm sm:rounded-lg mb-4 p-4">
                <form method="GET" action="{{ route('admin.products.index') }}" id="filterForm" class="space-y-4 md:space-y-0 md:flex md:items-end md:gap-4">
                    <div class="flex-1">
                        <x-input-label for="recherche" :value="__('Rechercher')" class="mb-2" />
                        <x-text-input id="recherche" name="recherche" type="text" class="block w-full" :value="old('recherche', $currentSearch)" placeholder="{{ __('Nom ou description...') }}" />
                    </div>
                    <div class="md:w-48">
                        <x-input-label for="categorie" :value="__('Catégorie')" class="mb-2" />
                        <select id="categorie" name="categorie" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">{{ __('Toutes') }}</option>
                            @foreach($categories as $categorie)
                                <option value="{{ $categorie }}" {{ $currentCategory == $categorie ? 'selected' : '' }}>{{ $categorie }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:w-48">
                        <x-input-label for="statut" :value="__('Statut')" class="mb-2" />
                        <select id="statut" name="statut" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">{{ __('Tous') }}</option>
                            <option value="actif" {{ $currentStatut == 'actif' ? 'selected' : '' }}>{{ __('Actifs') }}</option>
                            <option value="inactif" {{ $currentStatut == 'inactif' ? 'selected' : '' }}>{{ __('Inactifs') }}</option>
                        </select>
                    </div>
                    <!-- Bouton Réinitialiser -->
                    @if($currentSearch || $currentCategory || $currentStatut)
                        <div class="flex items-end">
                            <a href="{{ route('admin.products.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:bg-gray-300 active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <i class="fas fa-redo mr-2"></i>
                                {{ __('Réinitialiser') }}
                            </a>
                        </div>
                    @endif
                </form>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const form = document.getElementById('filterForm');
                    const rechercheInput = document.getElementById('recherche');
                    const categorieSelect = document.getElementById('categorie');
                    const statutSelect = document.getElementById('statut');
                    
                    let searchTimeout;
                    
                    // Auto-submit pour les selects (catégorie et statut)
                    categorieSelect.addEventListener('change', function() {
                        form.submit();
                    });
                    
                    statutSelect.addEventListener('change', function() {
                        form.submit();
                    });
                    
                    // Auto-submit pour la recherche avec délai (debounce)
                    rechercheInput.addEventListener('input', function() {
                        clearTimeout(searchTimeout);
                        searchTimeout = setTimeout(function() {
                            form.submit();
                        }, 500); // Attendre 500ms après la dernière frappe
                    });
                });
            </script>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="overflow-x-auto w-full">
                        <table class="w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catégorie</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prix</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($products as $product)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($product->hasImage())
                                                <img src="{{ $product->image_url }}" alt="{{ $product->nom }}" class="h-16 w-16 object-cover rounded">
                                            @else
                                                <div class="h-16 w-16 bg-gray-200 rounded flex items-center justify-center">
                                                    <i class="fas fa-image text-gray-400"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $product->nom }}</div>
                                            @if($product->description)
                                                <div class="text-sm text-gray-500">{{ Str::limit($product->description, 50) }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            @if($product->categorie)
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-indigo-100 text-indigo-800">{{ $product->categorie }}</span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ number_format($product->prix, 2) }} MAD</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($product->est_actif)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    {{ __('Actif') }}
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    {{ __('Inactif') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('products.show', $product) }}" class="text-indigo-600 hover:text-indigo-900 mr-3" title="{{ __('Voir') }}">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.products.edit', $product) }}" class="text-blue-600 hover:text-blue-900 mr-3" title="{{ __('Modifier') }}">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" 
                                                        onclick="window.dispatchEvent(new CustomEvent('open-confirmation', { detail: { form: this.closest('form'), message: 'Êtes-vous sûr de vouloir supprimer ce produit ?' } }))"
                                                        class="text-red-600 hover:text-red-900" 
                                                        title="{{ __('Supprimer') }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                            {{ __('Aucun produit trouvé.') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

