<?php 
session_start();
require_once __DIR__ . '/config/db.php';

include 'includes/header.php'; 

// Updated Mock data for Indian menu
$menu_items = [
    ['name' => 'South Indian Filter Coffee', 'price' => '180', 'description' => 'Strong decoction blend with frothy milk.', 'category' => 'Coffee'],
    ['name' => 'Masala Chai Latte', 'price' => '220', 'description' => 'Warm spices and ginger blend with Assam tea.', 'category' => 'Tea'],
    ['name' => 'Cardamom Cold Brew', 'price' => '280', 'description' => 'Signature cold brew infused with green cardamom.', 'category' => 'Coffee'],
    ['name' => 'Vada Pav Sliders', 'price' => '250', 'description' => 'Classic Mumbai potato fritter in mini buns.', 'category' => 'Food'],
    ['name' => 'Bun Maska', 'price' => '150', 'description' => 'Irani cafe style soft bun with heavy butter.', 'category' => 'Food'],
    ['name' => 'Kashmiri Kahwa', 'price' => '240', 'description' => 'Saffron-infused green tea with almonds and spice.', 'category' => 'Tea'],
];
?>

<div class="container" style="padding: 10rem 5% 4rem;">
    <div style="text-align: center; margin-bottom: 5rem;" class="animate-up">
        <h1 style="font-size: clamp(2.5rem, 5vw, 4rem); color: var(--primary); margin-bottom: 1rem;">The Mumbai Menu</h1>
        <p style="color: var(--text-light); font-size: 1.1rem; max-width: 600px; margin: 0 auto;">Local flavors meet artisan brewing. From the hills of Chikmagalur to the streets of Bandra.</p>
        
        <div style="margin-top: 3rem; display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
            <button class="btn filter-btn" data-category="all" style="background: var(--accent); color: white;">All Items</button>
            <button class="btn filter-btn" data-category="Coffee" style="background: white;">Coffee</button>
            <button class="btn filter-btn" data-category="Tea" style="background: white;">Teas</button>
            <button class="btn filter-btn" data-category="Food" style="background: white;">Food</button>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 2.5rem;">
        <?php foreach($menu_items as $item): ?>
            <div class="card menu-card animate-up" data-category="<?php echo $item['category']; ?>">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1.5rem;">
                    <div>
                        <span style="font-size: 0.7rem; text-transform: uppercase; color: var(--accent); font-weight: 700; letter-spacing: 1px;">
                            <?php echo $item['category']; ?>
                        </span>
                        <h3 style="font-size: 1.3rem; color: var(--primary); margin-top: 0.3rem;"><?php echo $item['name']; ?></h3>
                    </div>
                    <span style="font-weight: 700; font-size: 1.2rem; color: var(--primary);">₹<?php echo $item['price']; ?></span>
                </div>
                <p style="color: var(--text-light); font-size: 0.95rem; line-height: 1.6;"><?php echo $item['description']; ?></p>
                <div style="margin-top: 2rem; border-top: 1px solid #f0f0f0; padding-top: 1rem;">
                    <a href="#" style="color: var(--accent); text-decoration: none; font-weight: 600; font-size: 0.85rem;">ORDER NOW &rarr;</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>