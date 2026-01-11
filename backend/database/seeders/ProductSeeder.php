<?php

namespace Database\Seeders;

use App\Models\VintageProduct;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ID du vendeur approuvé
        $vendorId = '019ba1fd-2b09-72ec-9b78-5fe0bc3d31bc';
        
        // Vérifier que le vendeur existe
        $vendor = User::find($vendorId);
        
        if (!$vendor) {
            $this->command->error('❌ Vendeur non trouvé avec cet ID');
            return;
        }
        
        $this->command->info('✅ Vendeur trouvé: ' . $vendor->name . ' (' . $vendor->email . ')');
        
        // Supprimer les anciens produits
        VintageProduct::query()->delete();
        $this->command->info('🗑️  Anciens produits supprimés');

        // Tableau des produits avec images
        $products = [
            [
                'title' => 'Robe Vintage Années 50 Pin-Up',
                'description' => 'Magnifique robe vintage des années 50, style pin-up avec imprimé fleuri rouge et blanc. Taille ajustable à la taille avec ceinture incluse. Parfait état, tissu en coton de qualité. Idéale pour un look rétro élégant ou une soirée à thème.',
                'category' => 'mode',
                'price' => 89.99,
                'promotion' => 10,
                'condition' => 'excellent',
                'stock' => 5,
                'status' => 'active',
                'image_url' => 'https://images.unsplash.com/photo-1595777457583-95e059d581b8?w=800',
            ],
            [
                'title' => 'Vinyle The Beatles - Abbey Road (1969)',
                'description' => 'Album vinyle original The Beatles Abbey Road, première édition 1969. Pochette d\'origine avec quelques marques d\'usure naturelles. Disque en excellent état, testé et fonctionne parfaitement. Pièce de collection authentique pour les fans des Fab Four.',
                'category' => 'art',
                'price' => 150.00,
                'promotion' => 0,
                'condition' => 'tres_bon',
                'stock' => 2,
                'status' => 'active',
                'image_url' => 'https://images.unsplash.com/photo-1603048588665-791ca8aea617?w=800',
            ],
            [
                'title' => 'Appareil Photo Polaroid OneStep Vintage',
                'description' => 'Appareil photo Polaroid OneStep des années 80 entièrement fonctionnel. Livré avec étui en cuir d\'origine. Flash intégré opérationnel. Parfait pour la photographie instantanée rétro. Testé et garanti fonctionnel.',
                'category' => 'electronique_vintage',
                'price' => 120.00,
                'promotion' => 15,
                'condition' => 'bon',
                'stock' => 3,
                'status' => 'active',
                'image_url' => 'https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?w=800',
            ],
            [
                'title' => 'Lampe de Table Art Déco Bronze et Verre',
                'description' => 'Superbe lampe de table Art Déco originale des années 1930. Base en bronze patiné, abat-jour en verre taillé. Câblage électrique refait aux normes actuelles. Pièce authentique signée. État exceptionnel, aucune restauration nécessaire.',
                'category' => 'mobilier',
                'price' => 250.00,
                'promotion' => 0,
                'condition' => 'excellent',
                'stock' => 1,
                'status' => 'active',
                'image_url' => 'https://images.unsplash.com/photo-1507473885765-e6ed057f782c?w=800',
            ],
            [
                'title' => 'Blouson Cuir Véritable Style Biker Années 70',
                'description' => 'Authentique blouson en cuir véritable style biker des années 70. Cuir patiné avec caractère unique. Fermeture éclair YKK d\'origine parfaitement fonctionnelle. Doublure en satin bordeaux. Taille L (mesures détaillées disponibles). Pièce unique chargée d\'histoire.',
                'category' => 'mode',
                'price' => 180.00,
                'promotion' => 20,
                'condition' => 'tres_bon',
                'stock' => 4,
                'status' => 'active',
                'image_url' => 'https://images.unsplash.com/photo-1551028719-00167b16eac5?w=800',
            ],
            [
                'title' => 'Horloge Murale Bois Massif Vintage',
                'description' => 'Horloge murale ancienne en bois massif de chêne. Cadran authentique avec chiffres romains. Mécanisme à quartz moderne installé pour une fiabilité optimale. Dimensions: 35cm de diamètre. Fonctionne parfaitement, pile fournie.',
                'category' => 'mobilier',
                'price' => 65.00,
                'promotion' => 0,
                'condition' => 'bon',
                'stock' => 8,
                'status' => 'active',
                'image_url' => 'https://images.unsplash.com/photo-1563861826100-9cb868fdbe1c?w=800',
            ],
            [
                'title' => 'Sac à Main Hermès Kelly Vintage Authentique',
                'description' => 'Authentique sac à main Hermès Kelly des années 80. Cuir box marron cognac de qualité exceptionnelle. Excellent état général avec très peu de marques d\'usage. Certificat d\'authenticité inclus. Pochette et cadenas d\'origine. Investissement et pièce de collection.',
                'category' => 'accessoires',
                'price' => 4500.00,
                'promotion' => 10,
                'condition' => 'excellent',
                'stock' => 1,
                'status' => 'active',
                'image_url' => 'https://images.unsplash.com/photo-1584917865442-de89df76afd3?w=800',
            ],
            [
                'title' => 'Console Atari 2600 + 10 Jeux Classiques',
                'description' => 'Console de jeu Atari 2600 complète et parfaitement fonctionnelle. Livrée avec 2 manettes d\'origine, tous les câbles nécessaires et 10 jeux classiques (Pac-Man, Space Invaders, Asteroids, etc.). Testée et garantie. Parfaite pour les collectionneurs et nostalgiques.',
                'category' => 'electronique_vintage',
                'price' => 200.00,
                'promotion' => 25,
                'condition' => 'bon',
                'stock' => 2,
                'status' => 'active',
                'image_url' => 'https://images.unsplash.com/photo-1550745165-9bc0b252726f?w=800',
            ],
            [
                'title' => 'Fauteuil Scandinave Teck Années 60',
                'description' => 'Magnifique fauteuil design scandinave des années 60 en teck massif. Structure entièrement restaurée par un ébéniste professionnel. Tissu refait à neuf dans un textile d\'époque respectant le design original. Assise très confortable. Design iconique du mid-century modern.',
                'category' => 'mobilier',
                'price' => 380.00,
                'promotion' => 0,
                'condition' => 'excellent',
                'stock' => 3,
                'status' => 'active',
                'image_url' => 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=800',
            ],
            [
                'title' => 'Affiche Cinéma Pulp Fiction Originale Encadrée',
                'description' => 'Affiche originale du film culte Pulp Fiction de Quentin Tarantino (1994). Format cinéma authentique 60x40cm. Encadrée professionnellement sous verre avec passe-partout noir. Parfait état de conservation. Numérotée et authentifiée. Pièce de collection pour cinéphiles.',
                'category' => 'art',
                'price' => 95.00,
                'promotion' => 5,
                'condition' => 'tres_bon',
                'stock' => 6,
                'status' => 'active',
                'image_url' => 'https://images.unsplash.com/photo-1594908900066-3f47337549d8?w=800',
            ],
            [
                'title' => 'Montre Omega Seamaster Automatique Vintage',
                'description' => 'Montre Omega Seamaster des années 70, référence recherchée par les collectionneurs. Mouvement mécanique automatique Cal. 565 révisé. Boîtier acier inoxydable en excellent état. Révision horlogère complète effectuée récemment. Bracelet cuir neuf de qualité. Fonctionne parfaitement. Certificat d\'authenticité fourni.',
                'category' => 'accessoires',
                'price' => 1200.00,
                'promotion' => 0,
                'condition' => 'excellent',
                'stock' => 1,
                'status' => 'active',
                'image_url' => 'https://images.unsplash.com/photo-1523170335258-f5ed11844a49?w=800',
            ],
            [
                'title' => 'Machine à Écrire Remington Années 40',
                'description' => 'Machine à écrire Remington portable des années 40, modèle emblématique. Entièrement fonctionnelle, toutes les touches répondent parfaitement. Ruban neuf installé. Touches d\'origine en excellent état. Mallette de transport d\'origine incluse. Parfaite pour décoration vintage ou utilisation.',
                'category' => 'electronique_vintage',
                'price' => 140.00,
                'promotion' => 15,
                'condition' => 'bon',
                'stock' => 4,
                'status' => 'active',
                'image_url' => 'https://images.unsplash.com/photo-1530124566582-a618bc2615dc?w=800',
            ],
            [
                'title' => 'Vélo Peugeot Course Vintage 1970',
                'description' => 'Vélo de course Peugeot vintage des années 70, modèle de compétition. Cadre en acier chromé, très solide. Entièrement restauré mécaniquement: transmission, freins, roulements. Peinture d\'origine conservée avec patine authentique. Guidon cintre route, selle cuir Brooks. Prêt à rouler, fonctionne parfaitement.',
                'category' => 'autre',
                'price' => 320.00,
                'promotion' => 10,
                'condition' => 'tres_bon',
                'stock' => 2,
                'status' => 'active',
                'image_url' => 'https://images.unsplash.com/photo-1485965120184-e220f721d03e?w=800',
            ],
            [
                'title' => 'Service à Café Porcelaine Limoges Art Nouveau',
                'description' => 'Service à café complet en porcelaine de Limoges, époque Art Nouveau début XXe siècle. Comprend: 6 tasses avec soucoupes assorties, cafetière, sucrier et pot à lait. Décor floral doré finement exécuté. État impeccable, aucun éclat ni fêlure. Marquage au dos authentifiant l\'origine. Pièce de collection rare.',
                'category' => 'art',
                'price' => 280.00,
                'promotion' => 0,
                'condition' => 'excellent',
                'stock' => 1,
                'status' => 'active',
                'image_url' => 'https://images.unsplash.com/photo-1514228742587-6b1558fcca3d?w=800',
            ],
            [
                'title' => 'Miroir Barbier Pivotant Années 30',
                'description' => 'Miroir de barbier authentique sur pied pivotant des années 30. Double face: miroir normal et miroir grossissant x3. Monture circulaire en laiton patiné avec caractère. Pied en fonte assurant une stabilité parfaite. Mécanisme de pivotement fluide et silencieux. Pièce décorative authentique, parfaite pour salle de bain vintage.',
                'category' => 'mobilier',
                'price' => 175.00,
                'promotion' => 20,
                'condition' => 'bon',
                'stock' => 3,
                'status' => 'active',
                'image_url' => 'https://images.unsplash.com/photo-1618220179428-22790b461013?w=800',
            ],
        ];

        // Créer tous les produits
        $created = 0;
        foreach ($products as $productData) {
            VintageProduct::create([
                'vendeur_id' => $vendorId,
                'title' => $productData['title'],
                'description' => $productData['description'],
                'category' => $productData['category'],
                'price' => $productData['price'],
                'promotion' => $productData['promotion'],
                'condition' => $productData['condition'],
                'stock' => $productData['stock'],
                'status' => $productData['status'],
                'image_url' => $productData['image_url'],
            ]);
            $created++;
        }

        $this->command->info('✅ ' . $created . ' produits créés avec succès !');
        $this->command->info('🖼️  Toutes les images sont incluses');
        $this->command->info('🌐 Testez: http://localhost:8000/api/products');
        $this->command->info('🎨 Frontend: http://localhost:5173/products');
    }
}