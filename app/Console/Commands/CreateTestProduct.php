<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\User;

class CreateTestProduct extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:create-test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a test product';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get the first user or create one
        $user = User::first();
        
        if (!$user) {
            $this->error('No users found. Please create a user first.');
            return 1;
        }

        // Create a test product
        $product = Product::create([
            'name' => 'Test Product',
            'price' => 99.99,
            'user_id' => $user->id,
            'image' => null,
        ]);

        $this->info("Test product created successfully with ID: {$product->id}");
        $this->info("Product: {$product->name} - \${$product->price}");

        return 0;
    }
}
