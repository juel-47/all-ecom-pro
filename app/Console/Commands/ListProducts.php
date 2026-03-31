<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;

class ListProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all products in the system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $products = Product::all();

        if ($products->isEmpty()) {
            $this->info('No products found in the system.');
            return 0;
        }

        $this->info('Products in the system:');
        $this->table(
            ['ID', 'Name', 'Price', 'User ID', 'Image'],
            $products->map(function ($product) {
                return [
                    $product->id,
                    $product->name,
                    '$' . $product->price,
                    $product->user_id,
                    $product->image ?: 'No image'
                ];
            })
        );

        return 0;
    }
}
