<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource for admin.
     */
    public function adminIndex(Request $request): View
    {
        $query = Product::query();

        // Recherche par nom ou description
        if ($request->filled('recherche')) {
            $searchTerm = $request->recherche;
            $query->where(function($q) use ($searchTerm) {
                $q->where('nom', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%');
            });
        }

        // Filtre par catégorie
        if ($request->filled('categorie')) {
            $query->where('categorie', $request->categorie);
        }

        // Filtre par statut
        if ($request->filled('statut')) {
            if ($request->statut === 'actif') {
                $query->where('est_actif', true);
            } elseif ($request->statut === 'inactif') {
                $query->where('est_actif', false);
            }
        }

        $products = $query->latest()->paginate(15)->withQueryString();

        $categories = Product::whereNotNull('categorie')
            ->distinct()
            ->pluck('categorie')
            ->sort();

        return view('admin.products.index', [
            'products' => $products,
            'categories' => $categories,
            'currentSearch' => $request->recherche,
            'currentCategory' => $request->categorie,
            'currentStatut' => $request->statut,
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Product::where('est_actif', true);

        // Recherche par nom ou description
        if ($request->filled('recherche')) {
            $searchTerm = $request->recherche;
            $query->where(function($q) use ($searchTerm) {
                $q->where('nom', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%');
            });
        }

        // Filtre par catégorie
        if ($request->filled('categorie')) {
            $query->where('categorie', $request->categorie);
        }

        // Tri
        $sortBy = $request->get('tri', 'recent');
        switch ($sortBy) {
            case 'prix_croissant':
                $query->orderBy('prix', 'asc');
                break;
            case 'prix_decroissant':
                $query->orderBy('prix', 'desc');
                break;
            case 'nom':
                $query->orderBy('nom', 'asc');
                break;
            default:
                $query->latest();
        }

        $products = $query->paginate(12)->withQueryString();

        // Récupérer toutes les catégories distinctes pour le filtre
        $categories = Product::where('est_actif', true)
            ->whereNotNull('categorie')
            ->distinct()
            ->pluck('categorie')
            ->sort();

        // Récupérer les IDs des produits favoris de l'utilisateur connecté
        $favoriteIds = [];
        if (auth()->check()) {
            $favoriteIds = auth()->user()->favorites()->pluck('id_produit')->toArray();
        }

        return view('products.index', [
            'products' => $products,
            'categories' => $categories,
            'currentSearch' => $request->recherche,
            'currentCategory' => $request->categorie,
            'currentSort' => $sortBy,
            'favoriteIds' => $favoriteIds,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product): View
    {
        $isFavorite = false;
        
        if (auth()->check()) {
            $isFavorite = auth()->user()->favorites()->where('id_produit', $product->id)->exists();
        }

        return view('products.show', [
            'product' => $product,
            'isFavorite' => $isFavorite,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $categories = Product::whereNotNull('categorie')
            ->distinct()
            ->pluck('categorie')
            ->sort()
            ->toArray();

        return view('admin.products.create', [
            'categories' => $categories,
        ]);
    }

    /**
     * Generate image filename from product title.
     */
    private function generateImageFilename(string $productTitle, string $extension = 'jpg'): string
    {
        // Convert to lowercase
        $filename = mb_strtolower($productTitle, 'UTF-8');
        
        // Remove accents and special characters
        $filename = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $filename);
        
        // Replace spaces and special characters with hyphens
        $filename = preg_replace('/[^a-z0-9]+/', '-', $filename);
        
        // Remove leading/trailing hyphens
        $filename = trim($filename, '-');
        
        // Limit length to avoid filesystem issues
        $filename = substr($filename, 0, 100);
        
        return $filename . '.' . $extension;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'prix' => ['required', 'numeric', 'min:0'],
            'categorie' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'max:2048'],
            'est_actif' => ['boolean'],
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = $this->generateImageFilename($validated['nom'], $extension);
            
            // Store with the generated filename
            $validated['image'] = $file->storeAs('products', $filename, 'public');
        }

        $validated['est_actif'] = $request->has('est_actif');

        Product::create($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produit créé avec succès.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product): View
    {
        $categories = Product::whereNotNull('categorie')
            ->distinct()
            ->pluck('categorie')
            ->sort()
            ->toArray();

        return view('admin.products.edit', [
            'product' => $product,
            'categories' => $categories,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'prix' => ['required', 'numeric', 'min:0'],
            'categorie' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'max:2048'],
            'est_actif' => ['boolean'],
        ]);

        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = $this->generateImageFilename($validated['nom'], $extension);
            
            // Store with the generated filename
            $validated['image'] = $file->storeAs('products', $filename, 'public');
        }

        $validated['est_actif'] = $request->has('est_actif');

        $product->update($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produit mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product): RedirectResponse
    {
        // Supprimer l'image si elle existe
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produit supprimé avec succès.');
    }
}
