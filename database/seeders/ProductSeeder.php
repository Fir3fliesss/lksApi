<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Wireless Bluetooth Headphones',
                'description' => 'Premium wireless headphones with active noise cancellation, 30-hour battery life, and ultra-comfortable ear cushions. Perfect for music lovers and professionals.',
                'image' => 'products/product_1.png',
                'price' => 450000,
                'stock' => 25,
            ],
            [
                'name' => 'Leather Bifold Wallet',
                'description' => 'Minimalist genuine leather wallet with RFID blocking technology. Features 6 card slots, 2 bill compartments, and a sleek slim profile.',
                'image' => 'products/product_2.png',
                'price' => 185000,
                'stock' => 50,
            ],
            [
                'name' => 'Stainless Steel Water Bottle',
                'description' => 'Double-wall vacuum insulated water bottle. Keeps drinks cold for 24 hours or hot for 12 hours. BPA-free, 750ml capacity.',
                'image' => 'products/product_3.png',
                'price' => 120000,
                'stock' => 100,
            ],
            [
                'name' => 'RGB Mechanical Keyboard',
                'description' => 'Compact 65% mechanical keyboard with hot-swappable switches, per-key RGB lighting, and PBT keycaps. USB-C connectivity.',
                'image' => 'products/product_4.png',
                'price' => 750000,
                'stock' => 15,
            ],
            [
                'name' => 'Smart Watch Pro',
                'description' => 'Feature-packed smartwatch with heart rate monitor, GPS tracking, sleep analysis, and 7-day battery life. Water resistant to 50m.',
                'image' => 'products/product_5.png',
                'price' => 1200000,
                'stock' => 20,
            ],
            [
                'name' => 'Canvas Travel Backpack',
                'description' => 'Durable canvas backpack with leather accents. Features padded laptop compartment (fits up to 15"), multiple organizer pockets, and roll-top closure.',
                'image' => 'products/product_6.png',
                'price' => 350000,
                'stock' => 30,
            ],
            [
                'name' => 'Wireless Earbuds',
                'description' => 'True wireless earbuds with active noise cancellation, transparency mode, and wireless charging case. IPX5 water resistant.',
                'image' => 'products/product_7.png',
                'price' => 280000,
                'stock' => 40,
            ],
            [
                'name' => 'Portable Bluetooth Speaker',
                'description' => 'Compact waterproof Bluetooth speaker with 360-degree sound, 20-hour battery life, and built-in microphone for hands-free calls.',
                'image' => 'products/product_8.png',
                'price' => 320000,
                'stock' => 35,
            ],
            [
                'name' => 'USB-C Braided Cable',
                'description' => 'Premium braided nylon USB-C to USB-C cable. Supports 100W fast charging and 10Gbps data transfer. 2 meter length.',
                'image' => 'products/product_9.png',
                'price' => 75000,
                'stock' => 200,
            ],
            [
                'name' => 'Ergonomic Wireless Mouse',
                'description' => 'Ergonomic wireless mouse with adjustable DPI (800-4000), silent clicks, and 6 programmable buttons. 2.4GHz and Bluetooth dual connectivity.',
                'image' => 'products/product_10.png',
                'price' => 250000,
                'stock' => 45,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
