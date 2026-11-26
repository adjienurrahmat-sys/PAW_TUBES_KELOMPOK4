<?php
namespace Database\Seeders;

use App\Models\Meja;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Dua akun demo dengan hak akses berbeda (password: password)
        User::create(['name' => 'Pemilik Petak25', 'email' => 'pemilik@petak25.test', 'role' => 'pemilik', 'password' => 'password']);
        User::create(['name' => 'Staf Petak25', 'email' => 'staf@petak25.test', 'role' => 'staf', 'password' => 'password']);

        // Menu Petak25 (sumber: PETAK_25_Menu_2026.pdf). Harga dalam rupiah; stok awal 30,
        // Fresh Honey Lemon dibuat 0 (sold out) untuk demo.
        foreach ([
            ['Nasi Goreng Kemangi', 'Main Course', 50000, 30, 'House-made basil paste fried rice with anchovies, shredded chicken, meatballs, sausage, and a sunny-side up egg.'],
            ['Nasi Goreng Kampung', 'Main Course', 50000, 30, 'Classic-style fried rice with shredded chicken, meatballs, sausage, and a sunny-side up egg.'],
            ['Dory Sambal Ijo', 'Main Course', 62000, 30, 'Crispy dory fillet with sambal of your choice, served with rice and sautéed bok choy.'],
            ['Dory Sambal Matah', 'Main Course', 62000, 30, 'Crispy dory fillet with sambal of your choice, served with rice and sautéed bok choy.'],
            ['Ayam Sambal Ijo', 'Main Course', 55000, 30, 'Indonesian spiced fried chicken (breast or thigh), served with white rice and crackers.'],
            ['Ayam Sambal Matah', 'Main Course', 55000, 30, 'Indonesian spiced fried chicken (breast or thigh), served with white rice and crackers.'],
            ['Sapi Sambal Ijo', 'Main Course', 62000, 30, 'Tenderloin beef, paired with your choice of sambal, white rice, and sautéed bok choy.'],
            ['Sapi Sambal Matah', 'Main Course', 62000, 30, 'Tenderloin beef, paired with your choice of sambal, white rice, and sautéed bok choy.'],
            ['Sapi Lada Hitam', 'Main Course', 62000, 30, 'Tenderloin beef with black pepper sauce, served with rice and sautéed bok choy.'],
            ['Beef Teriyaki', 'Main Course', 62000, 30, 'Tenderloin beef glazed with teriyaki sauce, served with white rice and sautéed bok choy.'],
            ['Chicken Katsu', 'Main Course', 55000, 30, 'Crispy chicken breast with tangy spicy sauce, served with white rice and sautéed bok choy.'],
            ['Fish and Chips', 'Main Course', 65000, 30, 'Golden battered fish fillet with fries and tartar sauce.'],
            ['Omelette Plain', 'Breakfast', 30500, 30, 'Protein-rich. Cooked without oil. Choose cheese, mushroom, or plain.'],
            ['Omelette Cheese', 'Breakfast', 30500, 30, 'Protein-rich. Cooked without oil. Choose cheese, mushroom, or plain.'],
            ['Omelette Mushroom', 'Breakfast', 30500, 30, 'Protein-rich. Cooked without oil. Choose cheese, mushroom, or plain.'],
            ['Omelette Mix (+Rp4.000)', 'Breakfast', 34500, 30, 'Omelette campuran (mix), tambahan Rp4.000 dari harga omelette biasa.'],
            ['Omelette with Fries', 'Breakfast', 39000, 30, 'Packed with protein, cooked without oil. Served with a side of fries.'],
            ['Omelette with Fries Mix (+Rp4.000)', 'Breakfast', 43000, 30, 'Omelette campuran (mix) dengan kentang goreng, tambahan Rp4.000.'],
            ['Bubur Manado', 'Breakfast', 40000, 30, 'Manado-style rice porridge with vegetables. Light, savory, and comforting.'],
            ['Mie Yamin Asin', 'Noodles', 44000, 30, 'Bandung-style noodles with minced chicken and meatballs.'],
            ['Mie Yamin Manis', 'Noodles', 44000, 30, 'Bandung-style noodles with minced chicken and meatballs.'],
            ['Petak Pho', 'Noodles', 52000, 30, 'Rice noodles, sliced beef, and fresh coriander in a slow-simmered aromatic broth.'],
            ['Mie Godok', 'Noodles', 44000, 30, 'Indonesian-style boiled noodles with shredded chicken, egg, sliced meatballs, sausage, and veggies.'],
            ['Mie Goreng', 'Noodles', 44000, 30, 'Fried noodles loaded with shredded chicken, egg, meatballs, sausage and veggies.'],
            ['Siomay Bandung', 'Bites', 39500, 30, 'Classic siomay with peanut sauce, this really comes from Bandung.'],
            ['Onion Ring', 'Bites', 37000, 30, 'Golden Fried onion rings with a light, savory seasoning.'],
            ['Spring Roll', 'Bites', 39500, 30, 'Crispy rolls filled with minced chicken, vermicelli, and fresh vegetables.'],
            ['Fried Mini Wonton', 'Bites', 37000, 30, 'Crispy bite-sized wontons, perfect for snacking.'],
            ['Cireng', 'Bites', 37000, 30, 'Crispy cireng served with spicy-sweet rujak sauce.'],
            ['Singkong Goreng', 'Bites', 37000, 30, 'Classic fried cassava paired with flavorful sambal roa.'],
            ['Chicken Wings', 'Bites', 41000, 30, 'Crispy fried chicken wings tossed in our sweet and spicy signature sauce.'],
            ['Crinkle Fries', 'Bites', 37000, 30, 'Crinkle cut fries served with chili and mayo cheese sauce on the side.'],
            ['Pisang Goreng', 'Sweet Bites', 38500, 30, ''],
            ['Pisang Bakar Coklat Keju', 'Sweet Bites', 37000, 30, ''],
            ['Pisang Bakar Coklat', 'Sweet Bites', 38500, 30, ''],
            ['Pisang Bakar Keju', 'Sweet Bites', 38500, 30, ''],
            ['Roti Bakar Coklat Keju', 'Sweet Bites', 37000, 30, ''],
            ['Roti Bakar Coklat', 'Sweet Bites', 38500, 30, ''],
            ['Roti Bakar Keju', 'Sweet Bites', 38500, 30, ''],
            ['Telur Rebus', 'Additional', 10000, 30, ''],
            ['Telur Ceplok', 'Additional', 10000, 30, ''],
            ['Telur Dadar', 'Additional', 10000, 30, ''],
            ['Nasi Putih', 'Additional', 6000, 30, ''],
            ['Additional Fries', 'Additional', 12000, 30, ''],
            ['Gyunyu Artelate', 'Coffee Based', 31000, 30, 'Aren Latte.'],
            ['Sakura Berry', 'Coffee Based', 34000, 30, 'Cold Brew with Berry Syrup.'],
            ['Sunset Calamon', 'Coffee Based', 34000, 30, 'Cold Brew + Foam with Citrus and Cinnamon Hint.'],
            ['Summer Breeze Americano', 'Coffee Based', 36000, 30, 'Espresso, Honey, Lemon.'],
            ['Espresso', 'Coffee Based', 20000, 30, ''],
            ['Picollo', 'Coffee Based', 26000, 30, 'Double ristretto with a touch of milk.'],
            ['Signature Milk Brew', 'Coffee Based', 35000, 30, 'Coffee brewed directly with milk instead of water.'],
            ['Black', 'Coffee Based', 31000, 30, 'Full arabica espresso with water.'],
            ['White', 'Coffee Based', 33500, 30, 'Houseblend espresso with milk.'],
            ['Mochaccino', 'Coffee Based', 37500, 30, 'Espresso, chocolate, and milk.'],
            ['Caramel Macchiato', 'Coffee Based', 37500, 30, 'Milk with espresso, finished with milk foam and caramel drizzle.'],
            ['Magic', 'Coffee Based', 37000, 30, 'Double ristretto with steamed milk.'],
            ['Filter', 'Coffee Based', 39000, 30, 'Single origin, hand-brewed.'],
            ['Whole Milk', 'Milk & Chocolate', 26000, 30, 'A glass of fresh full-cream milk.'],
            ['Oreo Milk', 'Milk & Chocolate', 33500, 30, 'Milk with crushed Oreo.'],
            ['Marie Milk', 'Milk & Chocolate', 33500, 30, 'Milk with crushed Marie Regal.'],
            ['Dark Chocolate', 'Milk & Chocolate', 37000, 30, 'Milk based Chocolate drink.'],
            ['Fresh Honey Lemon', 'Refresher', 35000, 0, 'Lemon and honey in a simple, refreshing combination.'],
            ['Greeny Pop', 'Refresher', 35000, 30, 'Citrus blend syrup layered with soda.'],
            ['Grinch Lagoon', 'Refresher', 37000, 30, 'Citrus blend syrup with coconut water, topped with foam.'],
            ['Mystical Blue', 'Refresher', 35000, 30, 'Citrus syrup, lemon juice, and soda.'],
            ['Sweet Orange', 'Refresher', 37000, 30, 'Mandarin orange with caramel and oat milk.'],
            ['Sunrise Peach', 'Refresher', 37000, 30, 'Soda with peach syrup and double shot of espresso.'],
            ['Matcha Latte', 'Tea', 37000, 30, 'Matcha and milk.'],
            ['Chamomile Tea', 'Tea', 33500, 30, 'Pure chamomile tea. Served hot.'],
            ['Peppermint Tea', 'Tea', 33500, 30, 'A blend of green tea, peppermint, and lemongrass. Served hot.'],
            ['Classic Black Tea', 'Tea', 22000, 30, 'Black tea. With or without sugar.'],
            ['Lemon Tea', 'Tea', 31000, 30, 'Black tea with lemon juice.'],
            ['Lychee Tea', 'Tea', 31000, 30, 'Black tea with lychee syrup and fruit. Iced only.'],
            ['Earl Grey Milk Tea', 'Tea', 31000, 30, 'Creamy Earl Grey.'],
            ['Peach Tea', 'Tea', 31000, 30, 'Black tea with peach syrup and fruit. Iced only.'],
            ['Thai Tea', 'Tea', 31000, 30, 'Thai tea made with condensed and evaporated milk.'],
            ['Matcha Berry Latte', 'Tea', 39000, 30, 'Matcha Cloud with milk and strawberry puree'],
            ['Cocoberry Matcha', 'Tea', 39000, 30, 'Matcha Cloud with coconut water and a touch of berries.'],
            ['Add Espresso Shot', 'Others', 7000, 30, ''],
            ['Add On Oat Milk', 'Others', 8000, 30, ''],
            ['Mineral Water (600ml)', 'Others', 15500, 30, ''],
            ['Add Flavor (+Rp3.000)', 'Others', 3000, 30, 'Tambahan sirup rasa untuk menu kopi atau susu.'],
        ] as [$nama, $kategori, $harga, $stok, $deskripsi]) {
            Menu::create(['nama' => $nama, 'kategori' => $kategori, 'harga' => $harga, 'stok' => $stok, 'deskripsi' => $deskripsi ?: null]);
        }

        // 17 meja mengikuti susunan dan nomor di wireframe Figma (diukur dari gambar).
        // [nomor, kursi, lantai, area, x%, y%, lebar%, tinggi%] (x, y = titik tengah meja di dalam area)
        foreach ([
            // Lantai 1 - Ruang Tamu
            ['1', 6, 1, 'Ruang Tamu', 18.1, 21.7, 20, 31.7], ['2', 4, 1, 'Ruang Tamu', 80.8, 12.2, 28, 15],
            ['3', 4, 1, 'Ruang Tamu', 80.8, 33.4, 28, 14.5], ['4', 6, 1, 'Ruang Tamu', 17.9, 62.2, 19.8, 30.8],
            ['5', 6, 1, 'Ruang Tamu', 80.4, 62.2, 20, 30.8],
            // Lantai 2 - Meja Makan
            ['6', 4, 2, 'Meja Makan', 20.7, 12.4, 28.3, 15.4], ['7', 4, 2, 'Meja Makan', 77.9, 12.4, 29, 15.4],
            ['8', 4, 2, 'Meja Makan', 20.7, 33.4, 28.3, 14.5], ['9', 4, 2, 'Meja Makan', 77.9, 33.4, 29, 14.5],
            ['10', 6, 2, 'Meja Makan', 18.8, 64.8, 19.6, 31.4], ['11', 6, 2, 'Meja Makan', 78.3, 65.7, 19.6, 32],
            // Lantai 2 - Teras
            ['12', 2, 2, 'Teras', 23.9, 9.9, 33, 10.5], ['13', 2, 2, 'Teras', 75.4, 19.5, 33, 10],
            ['14', 2, 2, 'Teras', 23.9, 25.9, 33, 10], ['15', 2, 2, 'Teras', 23.9, 62.1, 33, 10.2],
            ['16', 2, 2, 'Teras', 75.4, 70.1, 33, 10], ['17', 2, 2, 'Teras', 23.9, 75.7, 33, 10.8],
        ] as [$no, $kap, $lt, $area, $x, $y, $w, $h]) {
            Meja::create(['nomor' => $no, 'kapasitas' => $kap, 'lantai' => $lt, 'area' => $area,
                'pos_x' => $x, 'pos_y' => $y, 'lebar' => $w, 'tinggi' => $h]);
        }
    }
}
