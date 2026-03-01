<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class FavoriteController extends Controller
{
    /**
     * Afficher la liste des produits favoris de l'utilisateur.
     */
    public function index(): View
    {
        $user = Auth::user();
        
        // Si l'utilisateur est admin, afficher tous les favoris
        if ($user->is_admin) {
            $favorites = Product::whereHas('favoritedBy')->with('favoritedBy')->paginate(12);
            $isAdmin = true;
        } else {
            $favorites = $user->favorites()->paginate(12);
            $isAdmin = false;
        }

        return view('favorites.index', [
            'favorites' => $favorites,
            'isAdmin' => $isAdmin,
        ]);
    }

    /**
     * Ajouter un produit aux favoris.
     */
    public function add(Product $product): RedirectResponse
    {
        $user = Auth::user();

        // Les admins ne peuvent pas ajouter de favoris
        if ($user->is_admin) {
            return redirect()->back()->with('error', 'Les administrateurs ne peuvent pas ajouter de favoris.');
        }

        // Vérifier si le produit n'est pas déjà dans les favoris
        if (!$user->favorites()->where('id_produit', $product->id)->exists()) {
            $user->favorites()->attach($product->id);
            if (request()->has('redirect_back')) {
                return $this->redirectWithFilters('success', 'Produit ajouté aux favoris avec succès.');
            }
            return redirect()->back()->with('success', 'Produit ajouté aux favoris avec succès.');
        }

        if (request()->has('redirect_back')) {
            return $this->redirectWithFilters('info', 'Ce produit est déjà dans vos favoris.');
        }
        return redirect()->back()->with('info', 'Ce produit est déjà dans vos favoris.');
    }

    /**
     * Retirer un produit des favoris.
     */
    public function remove(Product $product): RedirectResponse
    {
        $user = Auth::user();

        // Les admins ne peuvent pas retirer de favoris
        if ($user->is_admin) {
            return redirect()->back()->with('error', 'Les administrateurs ne peuvent pas retirer de favoris.');
        }

        if ($user->favorites()->where('id_produit', $product->id)->exists()) {
            $user->favorites()->detach($product->id);
            if (request()->has('redirect_back')) {
                return $this->redirectWithFilters('success', 'Produit retiré des favoris avec succès.');
            }
            return redirect()->back()->with('success', 'Produit retiré des favoris avec succès.');
        }

        if (request()->has('redirect_back')) {
            return $this->redirectWithFilters('error', 'Ce produit n\'est pas dans vos favoris.');
        }
        return redirect()->back()->with('error', 'Ce produit n\'est pas dans vos favoris.');
    }

    /**
     * Rediriger vers la liste des produits en préservant les filtres actifs.
     */
    private function redirectWithFilters(string $type, string $message): RedirectResponse
    {
        $queryParams = [];
        
        // Préserver les paramètres de filtre s'ils existent
        if (request()->has('recherche') && request()->get('recherche')) {
            $queryParams['recherche'] = request()->get('recherche');
        }
        
        if (request()->has('categorie') && request()->get('categorie')) {
            $queryParams['categorie'] = request()->get('categorie');
        }
        
        if (request()->has('tri') && request()->get('tri') && request()->get('tri') != 'recent') {
            $queryParams['tri'] = request()->get('tri');
        }
        
        return redirect()->route('products.index', $queryParams)->with($type, $message);
    }
}
