<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nom',
        'description',
        'prix',
        'image',
        'categorie',
        'est_actif',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'prix' => 'decimal:2',
            'est_actif' => 'boolean',
        ];
    }

    /**
     * The users that have favorited this product.
     */
    public function favoritedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorites', 'id_produit', 'id_utilisateur')
                    ->withTimestamps();
    }

    /**
     * Get the image URL for the product.
     * Returns the storage path if image exists, or a placeholder if not.
     * Gère uniquement les fichiers locaux dans storage/app/public/products/
     */
    public function getImageUrlAttribute(): string
    {
        if ($this->image) {
            // Vérifier si le fichier existe dans le storage
            $imagePath = storage_path('app/public/' . $this->image);
            if (file_exists($imagePath)) {
                // Retourner l'URL publique de l'image
                return asset('storage/' . $this->image);
            }
        }
        
        // Image placeholder par défaut si aucune image n'est trouvée
        return 'https://via.placeholder.com/400x300/e5e7eb/9ca3af?text=' . urlencode($this->nom);
    }

    /**
     * Check if the product has an image file that exists.
     */
    public function hasImage(): bool
    {
        if (empty($this->image)) {
            return false;
        }
        
        $filePath = storage_path('app/public/' . $this->image);
        return file_exists($filePath);
    }
    
    /**
     * Get the full path to the image file.
     */
    public function getImagePathAttribute(): ?string
    {
        if (empty($this->image)) {
            return null;
        }
        
        $filePath = storage_path('app/public/' . $this->image);
        return file_exists($filePath) ? $filePath : null;
    }

    /**
     * Get number of favorites (hearts)
     * Returns the count of users who favorited this product
     */
    public function getNombreFavorisAttribute(): int
    {
        return $this->favoritedBy()->count();
    }
}
