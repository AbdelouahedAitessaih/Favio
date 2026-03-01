<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Produits') }}
        </h2>
    </x-slot>

    <div class="py-6 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-4 lg:px-6">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('info'))
                <div class="mb-4 bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('info') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Barre de recherche et filtres -->
            <div class="bg-white shadow-sm sm:rounded-lg mb-4 p-4">
                <form method="GET" action="{{ route('products.index') }}" id="filterForm" class="space-y-4 md:space-y-0 md:flex md:items-end md:gap-4">
                    <!-- Recherche -->
                    <div class="flex-1">
                        <x-input-label for="recherche" :value="__('Rechercher un produit')" class="mb-2" />
                        <x-text-input 
                            id="recherche" 
                            name="recherche" 
                            type="text" 
                            class="block w-full" 
                            :value="old('recherche', $currentSearch)"
                            placeholder="{{ __('Nom ou description...') }}"
                        />
                    </div>

                    <!-- Filtre par catégorie -->
                    <div class="md:w-48">
                        <x-input-label for="categorie" :value="__('Catégorie')" class="mb-2" />
                        <select 
                            id="categorie" 
                            name="categorie" 
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                            <option value="">{{ __('Toutes les catégories') }}</option>
                            @foreach($categories as $categorie)
                                <option value="{{ $categorie }}" {{ $currentCategory == $categorie ? 'selected' : '' }}>
                                    {{ $categorie }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tri -->
                    <div class="md:w-48">
                        <x-input-label for="tri" :value="__('Trier par')" class="mb-2" />
                        <select 
                            id="tri" 
                            name="tri" 
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                            <option value="recent" {{ $currentSort == 'recent' ? 'selected' : '' }}>{{ __('Plus récent') }}</option>
                            <option value="prix_croissant" {{ $currentSort == 'prix_croissant' ? 'selected' : '' }}>{{ __('Prix croissant') }}</option>
                            <option value="prix_decroissant" {{ $currentSort == 'prix_decroissant' ? 'selected' : '' }}>{{ __('Prix décroissant') }}</option>
                            <option value="nom" {{ $currentSort == 'nom' ? 'selected' : '' }}>{{ __('Nom (A-Z)') }}</option>
                        </select>
                    </div>

                    <!-- Bouton Réinitialiser -->
                    @if($currentSearch || $currentCategory || $currentSort != 'recent')
                        <div class="flex items-end">
                            <a href="{{ route('products.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:bg-gray-300 active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
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
                    const triSelect = document.getElementById('tri');
                    
                    let searchTimeout;
                    
                    // Auto-submit pour les selects (catégorie et tri)
                    categorieSelect.addEventListener('change', function() {
                        form.submit();
                    });
                    
                    triSelect.addEventListener('change', function() {
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

            @if($products->count() > 0)
                <div class="mb-3 text-sm text-gray-600">
                    {{ __('Affichage de :count produit(s)', ['count' => $products->total()]) }}
                    @if($currentSearch)
                        {{ __('pour la recherche : :search', ['search' => $currentSearch]) }}
                    @endif
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3">
                    @foreach($products as $product)
                        <div class="bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-200 group relative" style="border-width: 1px;">
                            <a href="{{ route('products.show', $product) }}" class="block h-full flex flex-col">
                                <!-- Image Container -->
                                <div class="relative bg-white aspect-square overflow-hidden">
                                    @if($product->hasImage())
                                        <img src="{{ $product->image_url }}" alt="{{ $product->nom }}" class="w-full h-full object-contain p-2 group-hover:scale-105 transition-transform duration-300">
                                    @else
                                        <div class="w-full h-full bg-gray-100 flex items-center justify-center">
                                            <i class="fas fa-image text-gray-400 text-5xl"></i>
                                        </div>
                                    @endif
                                    
                                    <!-- Favorite Indicator (Top Right) -->
                                    @auth
                                        @if(!auth()->user()->is_admin)
                                            @php
                                                $isFavorite = in_array($product->id, $favoriteIds);
                                            @endphp
                                            @if($isFavorite)
                                                <div class="absolute top-2 right-2 z-20">
                                                    <div class="bg-white rounded-full p-1.5 shadow-md flex items-center justify-center">
                                                        <i class="fas fa-heart text-red-500" style="font-size: 0.875rem;"></i>
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                    @endauth
                                    
                                    <!-- Heart Icon for Favorites (Action Button) -->
                                    @auth
                                        @if(!auth()->user()->is_admin)
                                            <div class="absolute bottom-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-10">
                                                <form action="{{ $isFavorite ? route('favorites.remove', $product) : route('favorites.add', $product) }}" method="POST" onclick="event.stopPropagation();" class="inline">
                                                @csrf
                                                @if($isFavorite)
                                                    @method('DELETE')
                                                @endif
                                                <input type="hidden" name="redirect_back" value="1">
                                                @if($currentSearch)
                                                    <input type="hidden" name="recherche" value="{{ $currentSearch }}">
                                                @endif
                                                @if($currentCategory)
                                                    <input type="hidden" name="categorie" value="{{ $currentCategory }}">
                                                @endif
                                                @if($currentSort && $currentSort != 'recent')
                                                    <input type="hidden" name="tri" value="{{ $currentSort }}">
                                                @endif
                                                <button type="submit" class="bg-red-500 text-white rounded-full p-2 shadow-lg hover:bg-red-600 cursor-pointer transition-colors flex items-center justify-center">
                                                    @if($isFavorite)
                                                        <i class="fas fa-heart" style="font-size: 1rem;"></i>
                                                    @else
                                                        <i class="far fa-heart" style="font-size: 1rem;"></i>
                                                    @endif
                                                </button>
                                            </form>
                                        </div>
                                        @endif
                                    @else
                                        <div class="absolute bottom-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-10">
                                            <a href="{{ route('login') }}" onclick="event.stopPropagation();" class="bg-red-500 text-white rounded-full p-2 shadow-lg hover:bg-red-600 cursor-pointer transition-colors inline-flex items-center justify-center">
                                                <i class="far fa-heart" style="font-size: 1rem;"></i>
                                            </a>
                                        </div>
                                    @endauth


                                </div>

                                <!-- Product Info -->
                                <div class="p-3 flex-grow flex flex-col">
                                    <!-- Product Title -->
                                    <h3 class="text-sm text-gray-800 mb-2 line-clamp-2 min-h-[2.5rem] group-hover:text-red-500 transition-colors">
                                        {{ Str::limit($product->nom, 60) }}
                                    </h3>

                                    <!-- Category -->
                                    @if($product->categorie)
                                        <div class="mb-2">
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold text-indigo-700 bg-indigo-100 rounded-full">
                                                <i class="fas fa-tag text-xs mr-1"></i>
                                                {{ $product->categorie }}
                                            </span>
                                        </div>
                                    @endif

                                    <!-- Price -->
                                    <div class="mb-2">
                                        <span class="text-lg font-bold text-red-500">{{ number_format($product->prix, 2) }} MAD</span>
                                    </div>

                                    <!-- Hearts (Favorites) -->
                                    @php
                                        $favoritesCount = $product->nombre_favoris;
                                    @endphp
                                    <div class="flex items-center gap-1.5">
                                        <div class="flex items-center">
                                            @php
                                                $maxHearts = 5;
                                                $heartsToShow = min($favoritesCount, $maxHearts);
                                            @endphp
                                            @for($i = 1; $i <= $maxHearts; $i++)
                                                @if($i <= $heartsToShow)
                                                    <i class="fas fa-heart text-red-500" style="font-size: 0.75rem;"></i>
                                                @else
                                                    <i class="far fa-heart text-gray-300" style="font-size: 0.75rem;"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <span class="text-xs text-gray-600">{{ $favoritesCount }} {{ __('favoris') }}</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $products->links() }}
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 text-center">
                        <p class="text-lg">{{ __('Aucun produit disponible pour le moment.') }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
