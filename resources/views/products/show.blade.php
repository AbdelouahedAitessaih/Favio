<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Détails du produit') }}
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

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Image du produit -->
                        <div>
                            @if($product->hasImage())
                                <img src="{{ $product->image_url }}" alt="{{ $product->nom }}" class="w-full h-auto rounded-lg shadow-md">
                            @else
                                <div class="w-full h-96 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex items-center justify-center">
                                    <div class="text-center">
                                        <i class="fas fa-image text-gray-400 text-6xl mb-4"></i>
                                        <p class="text-gray-500 text-sm">{{ __('Aucune image disponible') }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Informations du produit -->
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $product->nom }}</h1>
                            
                            @if($product->categorie)
                                <div class="mb-4">
                                    @auth
                                        @if(auth()->user()->is_admin)
                                            <a href="{{ route('admin.products.index', ['categorie' => $product->categorie]) }}" class="inline-flex items-center px-3 py-1 text-sm font-semibold text-indigo-700 bg-indigo-100 rounded-full hover:bg-indigo-200 transition-colors cursor-pointer">
                                                <i class="fas fa-tag text-sm mr-1"></i>
                                                {{ $product->categorie }}
                                            </a>
                                        @else
                                            <a href="{{ route('products.index', ['categorie' => $product->categorie]) }}" class="inline-flex items-center px-3 py-1 text-sm font-semibold text-indigo-700 bg-indigo-100 rounded-full hover:bg-indigo-200 transition-colors cursor-pointer">
                                                <i class="fas fa-tag text-sm mr-1"></i>
                                                {{ $product->categorie }}
                                            </a>
                                        @endif
                                    @else
                                        <a href="{{ route('products.index', ['categorie' => $product->categorie]) }}" class="inline-flex items-center px-3 py-1 text-sm font-semibold text-indigo-700 bg-indigo-100 rounded-full hover:bg-indigo-200 transition-colors cursor-pointer">
                                            <i class="fas fa-tag text-sm mr-1"></i>
                                            {{ $product->categorie }}
                                        </a>
                                    @endauth
                                </div>
                            @endif

                            <div class="mb-6 p-4 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-lg border border-indigo-200">
                                <p class="text-sm text-gray-600 mb-1">{{ __('Prix') }}</p>
                                <span class="text-4xl font-bold text-indigo-600">{{ number_format($product->prix, 2) }} MAD</span>
                            </div>

                            @if($product->description)
                                <div class="mb-6">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Description') }}</h3>
                                    <p class="text-gray-700 leading-relaxed">{{ $product->description }}</p>
                                </div>
                            @endif

                            @auth
                                @if(!auth()->user()->is_admin)
                                    <div class="mt-6">
                                        @if($isFavorite)
                                            <form action="{{ route('favorites.remove', $product) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <x-primary-button type="submit" class="bg-red-600 hover:bg-red-700">
                                                    <i class="fas fa-heart mr-2"></i>
                                                    {{ __('Retirer des favoris') }}
                                                </x-primary-button>
                                            </form>
                                        @else
                                            <form action="{{ route('favorites.add', $product) }}" method="POST" class="inline">
                                                @csrf
                                                <x-primary-button type="submit">
                                                    <i class="far fa-heart mr-2"></i>
                                                    {{ __('Ajouter aux favoris') }}
                                                </x-primary-button>
                                            </form>
                                        @endif
                                    </div>
                                @endif
                            @else
                                <div class="mt-6">
                                    <p class="text-sm text-gray-600 mb-4">{{ __('Connectez-vous pour ajouter ce produit à vos favoris.') }}</p>
                                    <a href="{{ route('login') }}" class="inline-block">
                                        <x-primary-button>{{ __('Se connecter') }}</x-primary-button>
                                    </a>
                                </div>
                            @endauth

                            <div class="mt-6">
                                @auth
                                    @if(auth()->user()->is_admin)
                                        <a href="{{ route('admin.products.index') }}" class="text-indigo-600 hover:text-indigo-800 font-medium">
                                            ← {{ __('Retour à la liste des produits') }}
                                        </a>
                                    @else
                                        <a href="{{ route('products.index') }}" class="text-indigo-600 hover:text-indigo-800 font-medium">
                                            ← {{ __('Retour à la liste des produits') }}
                                        </a>
                                    @endif
                                @else
                                    <a href="{{ route('products.index') }}" class="text-indigo-600 hover:text-indigo-800 font-medium">
                                        ← {{ __('Retour à la liste des produits') }}
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
