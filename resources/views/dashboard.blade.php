<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tableau de bord') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Message de bienvenue -->
            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-white">
                    <h2 class="text-2xl font-bold mb-2">
                        @auth
                            @if(auth()->user()->is_admin)
                                {{ __('Bienvenue, Administrateur !') }}
                            @else
                                {{ __('Bienvenue sur Favio !') }}
                            @endif
                        @else
                            {{ __('Bienvenue sur Favio !') }}
                        @endauth
                    </h2>
                    <p class="mb-4">
                        @auth
                            @if(auth()->user()->is_admin)
                                {{ __('Gérez les produits, les utilisateurs et consultez les statistiques de la plateforme.') }}
                            @else
                                {{ __('Découvrez notre sélection de produits et créez votre liste de favoris personnalisée.') }}
                            @endif
                        @else
                            {{ __('Découvrez notre sélection de produits et créez votre liste de favoris personnalisée.') }}
                        @endauth
                    </p>
                    @auth
                        @if(!auth()->user()->is_admin)
                            <p class="text-sm opacity-90">{{ __('Explorez, recherchez et sauvegardez vos produits préférés en un clic.') }}</p>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Actions rapides') }}</h3>
                    <div class="flex flex-wrap gap-4">
                        @auth
                            @if(auth()->user()->is_admin)
                                <a href="{{ route('admin.products.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <i class="fas fa-box mr-2"></i>
                                    {{ __('Gérer les produits') }}
                                </a>
                                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 focus:bg-green-500 active:bg-green-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <i class="fas fa-users mr-2"></i>
                                    {{ __('Gestion des utilisateurs') }}
                                </a>
                                <a href="{{ route('favorites.index') }}" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <i class="fas fa-heart mr-2"></i>
                                    {{ __('Tous les favoris') }}
                                </a>
                            @else
                                <a href="{{ route('products.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <i class="fas fa-search mr-2"></i>
                                    {{ __('Voir tous les produits') }}
                                </a>
                                <a href="{{ route('favorites.index') }}" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <i class="fas fa-heart mr-2"></i>
                                    {{ __('Mes favoris') }}
                                </a>
                            @endif
                        @else
                            <a href="{{ route('products.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <i class="fas fa-search mr-2"></i>
                                {{ __('Voir tous les produits') }}
                            </a>
                            <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <i class="fas fa-sign-in-alt mr-2"></i>
                                {{ __('Se connecter') }}
                            </a>
                        @endauth
                    </div>
                </div>
            </div>

            <!-- Statistiques -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @auth
                    @if(auth()->user()->is_admin)
                        <!-- Total de produits (admin) -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
                                        <i class="fas fa-box text-white text-xl"></i>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-500">{{ __('Total produits') }}</p>
                                        <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\Product::count() }}</p>
                                        <p class="text-xs text-gray-400 mt-1">{{ \App\Models\Product::where('est_actif', true)->count() }} actifs</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Total utilisateurs -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                                        <i class="fas fa-users text-white text-xl"></i>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-500">{{ __('Total utilisateurs') }}</p>
                                        <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\User::count() }}</p>
                                        <p class="text-xs text-gray-400 mt-1">{{ \App\Models\User::where('is_admin', true)->count() }} admins</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Total favoris -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 bg-red-500 rounded-md p-3">
                                        <i class="fas fa-heart text-white text-xl"></i>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-500">{{ __('Total favoris') }}</p>
                                        <p class="text-2xl font-semibold text-gray-900">{{ \DB::table('favorites')->count() }}</p>
                                        <p class="text-xs text-gray-400 mt-1">Tous les utilisateurs</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Total de produits -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
                                        <i class="fas fa-box text-white text-xl"></i>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-500">{{ __('Produits disponibles') }}</p>
                                        <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\Product::where('est_actif', true)->count() }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Mes favoris -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 bg-red-500 rounded-md p-3">
                                        <i class="fas fa-heart text-white text-xl"></i>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-500">{{ __('Mes favoris') }}</p>
                                        <p class="text-2xl font-semibold text-gray-900">{{ auth()->user()->favorites()->count() }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Catégories -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                                        <i class="fas fa-tag text-white text-xl"></i>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-500">{{ __('Catégories') }}</p>
                                        <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\Product::where('est_actif', true)->whereNotNull('categorie')->distinct()->count('categorie') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @else
                    <!-- Total de produits -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
                                    <i class="fas fa-box text-white text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-500">{{ __('Produits disponibles') }}</p>
                                    <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\Product::where('est_actif', true)->count() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Connectez-vous -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
                                    <i class="fas fa-user text-white text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-500">{{ __('Connectez-vous') }}</p>
                                    <p class="text-sm text-gray-900">{{ __('Pour gérer vos favoris') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Catégories -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                                    <i class="fas fa-tag text-white text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-500">{{ __('Catégories') }}</p>
                                    <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\Product::where('est_actif', true)->whereNotNull('categorie')->distinct()->count('categorie') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</x-app-layout>
