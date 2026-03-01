<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            @if(isset($isAdmin) && $isAdmin)
                {{ __('Tous les favoris') }}
            @else
                {{ __('Mes favoris') }}
            @endif
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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

            <!-- Statistiques des favoris -->
            @if($favorites->count() > 0)
                <div class="bg-gradient-to-r from-red-500 to-pink-600 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-xl font-bold mb-2">
                                    @if(isset($isAdmin) && $isAdmin)
                                        {{ __('Tous les produits favoris') }}
                                    @else
                                        {{ __('Vos produits favoris') }}
                                    @endif
                                </h3>
                                <p class="text-sm opacity-90">
                                    @if(isset($isAdmin) && $isAdmin)
                                        {{ __('Total : :count produit(s) favori(s)', ['count' => $favorites->total()]) }}
                                    @else
                                        {{ __('Vous avez :count produit(s) dans vos favoris', ['count' => $favorites->total()]) }}
                                    @endif
                                </p>
                            </div>
                            <div class="bg-white bg-opacity-20 rounded-full p-4">
                                <i class="fas fa-heart text-3xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if($favorites->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($favorites as $product)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-red-100 relative">
                            <!-- Badge favori -->
                            <div class="absolute top-2 left-2 z-10">
                                <span class="px-2 py-1 text-xs font-semibold text-white bg-red-500 rounded-full shadow-lg flex items-center">
                                    <i class="fas fa-heart text-xs mr-1"></i>
                                    {{ __('Favori') }}
                                </span>
                            </div>
                            <a href="{{ route('products.show', $product) }}" class="block">
                                <div class="relative overflow-hidden">
                                    @if($product->hasImage())
                                        <img src="{{ $product->image_url }}" alt="{{ $product->nom }}" class="w-full h-48 object-cover transition-transform duration-300 hover:scale-110">
                                    @else
                                        <div class="w-full h-48 bg-gradient-to-br from-red-50 to-pink-50 flex items-center justify-center">
                                            <i class="fas fa-image text-gray-400 text-4xl"></i>
                                        </div>
                                    @endif
                                    @if($product->categorie)
                                        <div class="absolute top-2 right-2">
                                            <span class="px-2 py-1 text-xs font-semibold text-white bg-indigo-600 rounded-full shadow-lg">
                                                {{ $product->categorie }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                                <div class="p-4">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2 hover:text-indigo-600 transition-colors">{{ $product->nom }}</h3>
                                    @if($product->description)
                                        <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ Str::limit($product->description, 100) }}</p>
                                    @endif
                                    <div class="flex items-center justify-between pt-3 border-t border-gray-100 mb-3">
                                        <div>
                                            <p class="text-xs text-gray-500 mb-1">{{ __('Prix') }}</p>
                                            <span class="text-2xl font-bold text-indigo-600">{{ number_format($product->prix, 2) }} MAD</span>
                                        </div>
                                    </div>
                                    @if(isset($isAdmin) && $isAdmin)
                                        <div class="bg-gray-50 p-3 rounded mb-3">
                                            <p class="text-xs text-gray-500 mb-1">{{ __('Ajouté par') }}</p>
                                            <p class="text-sm font-medium text-gray-900">
                                                {{ $product->favoritedBy->pluck('name')->join(', ') }}
                                                <span class="text-gray-500">({{ $product->favoritedBy->count() }} {{ __('utilisateur(s)') }})</span>
                                            </p>
                                        </div>
                                    @else
                                        <form action="{{ route('favorites.remove', $product) }}" method="POST" onclick="event.stopPropagation();">
                                            @csrf
                                            @method('DELETE')
                                            <x-primary-button type="submit" class="w-full bg-red-600 hover:bg-red-700 focus:bg-red-700 active:bg-red-900">
                                                <i class="fas fa-heart mr-2"></i>
                                                {{ __('Retirer des favoris') }}
                                            </x-primary-button>
                                        </form>
                                    @endif
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $favorites->links() }}
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 text-center">
                        <i class="far fa-heart text-gray-400 text-6xl mb-4"></i>
                        <p class="text-lg mb-4">
                            @if(isset($isAdmin) && $isAdmin)
                                {{ __('Aucun produit n\'a été ajouté aux favoris.') }}
                            @else
                                {{ __('Vous n\'avez aucun produit dans vos favoris.') }}
                            @endif
                        </p>
                        <a href="{{ route('products.index') }}">
                            <x-primary-button>{{ __('Découvrir les produits') }}</x-primary-button>
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
