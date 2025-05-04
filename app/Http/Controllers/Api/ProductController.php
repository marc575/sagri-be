<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $products = $user->products;
        return ProductResource::collection($products);
    }
    
    public function all(Request $request)
    {
        $products = Product::query()
            ->with(['user:id,name', 'project:id,name']) // Chargement optimisé des relations
            ->when($request->status, fn($q, $status) => $q->where('status', $status))
            ->when($request->category, fn($q, $category) => $q->where('category', $category))
            ->when($request->organic, fn($q) => $q->where('is_organic', true))
            ->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 20);
    
        return ProductResource::collection($products);
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'quantity_available' => 'required|integer',
            'unit' => 'required|string|max:50',
            'price_per_unit' => 'required|numeric',
            'category' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
            'project_id' => 'nullable|exists:projects,id',
            'status' => 'nullable|string',
            'is_organic' => 'required|integer|in:0,1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Supprime l'ancienne photo si elle existe
            if ($request->image) {
                Storage::disk('public')->delete($request->image);
            }
            
            $path = $request->file('image')->store('image', 'public');
            $validated['image'] = $path;
        }

        $product = Product::create($validated);

        return new ProductResource($product);
    }

    public function show(Product $product)
    {
        return new ProductResource($product);
    }
    
    public function update(UpdateProductRequest $request, Product $product)
    {
        $validated = $request->validated();
    
        DB::transaction(function () use ($request, $product, &$validated) {
            try {
                // Gestion de l'image
                if ($request->hasFile('image')) {
                    $this->handleImageUpload($request, $product, $validated);
                }
    
                // Mise à jour des timestamps manuellement si nécessaire
                $validated['updated_at'] = now();
    
                // Protection contre la modification de certains champs
                unset($validated['user_id']); // L'utilisateur ne peut pas être modifié
                unset($validated['created_at']); // La date de création est immuable
                unset($validated['id']); // L'ID ne doit jamais être modifié
    
                // Mise à jour du produit
                $product->update($validated);
    
            } catch (\Exception $e) {
                throw $e; // Important pour rollback la transaction
            }
        });
    
        return new ProductResource($product->fresh());
    }
    
    protected function handleImageUpload($request, $product, &$validated)
    {
        $storage = Storage::disk('public');
        
        // Validation du fichier image
        $request->validate([
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        // Suppression de l'ancienne image
        if ($product->image) {
            try {
                $storage->delete($product->image);
            } catch (\Exception $e) {
                throw $e;
            }
        }
    
        // Enregistrement de la nouvelle image
        $file = $request->file('image');
        $filename = 'product_'.$product->id.'_'.time().'.'.$file->guessExtension();
        $path = $file->storeAs('products', $filename, 'public');
        
        $validated['image'] = $path;
    
        // Nettoyage des anciennes images du même produit
        $this->cleanupOldProductImages($product->id, $filename);
    }
    
    protected function cleanupOldProductImages($productId, $currentFilename)
    {
        $storage = Storage::disk('public');
        $files = $storage->files('products');
        
        foreach ($files as $file) {
            if (str_starts_with($file, "product_{$productId}_") && $file !== "products/{$currentFilename}") {
                try {
                    $storage->delete($file);
                } catch (\Exception $e) {
                    throw $e;
                }
            }
        }
    }
    public function destroy(Product $product)
    {
        DB::transaction(function () use ($product) {
            try {
                // 1. Suppression de l'image associée
                $this->deleteProductImage($product);
                
                // 2. Suppression des éventuelles relations (si nécessaire)
                // Exemple: $product->orders()->detach();
                // ou: $product->categories()->detach();
                
                // 3. Suppression du produit lui-même
                $product->delete();
                
                // 4. Nettoyage des fichiers résiduels
                $this->cleanupAllProductImages($product->id);
                
            } catch (\Exception $e) {
                throw $e;
            }
        });
    
        return response()->json([
            'message' => 'Product and all associated data deleted successfully',
            'deleted_id' => $product->id
        ]);
    }
    
    protected function deleteProductImage(Product $product)
    {
        if ($product->image) {
            try {
                $storage = Storage::disk('public');
                
                // Suppression du fichier principal
                if ($storage->exists($product->image)) {
                    $storage->delete($product->image);
                }
                
                // Optionnel: Suppression des thumbnails si vous en générez
                // $this->deleteThumbnails($product->image);
                
            } catch (\Exception $e) {
                throw $e;
                // On ne throw pas pour ne pas bloquer la suppression du produit
            }
        }
    }
    
    protected function cleanupAllProductImages($productId)
    {
        $storage = Storage::disk('public');
        $files = $storage->files('products');
        
        foreach ($files as $file) {
            if (str_starts_with($file, "product_{$productId}_")) {
                try {
                    $storage->delete($file);
                } catch (\Exception $e) {
                    throw $e;
                }
            }
        }
    }
}
