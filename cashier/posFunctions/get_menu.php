<?php
require_once '../../db_connect.php';

header('Content-Type: application/json');

try {
    // Prepare and execute the query
    $stmt = $conn->prepare("SELECT dish_id, codename, dish_name, dish_description, dish_category, price, dish_pic_url FROM dishes_tb WHERE status = 'active'");
    $stmt->execute();
    
    // Fetch all results as associative array
    $dishes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Format the data to match the expected structure in your JavaScript
    $menuItems = array_map(function($dish) {
        // Map database categories to the categories used in your frontend
        $categoryMap = [
            'main_course' => 'main-course',
            'best_seller' => 'best-sellers',
            'desserts' => 'desserts',
            'drinks' => 'Drinks',
            'appetizer' => 'appetizer', // Not present in buttons, kept from original
            'italian_dish' => 'italian-dish',
            'spanish_dish' => 'spanish-dish',
            'house_salad' => 'house-salad',
            // 'pizza' => 'Pizza',
            'burger_pizza' => 'burger-pizza',
            'pasta' => 'pasta',
            'pasta_caza' => 'pasta_caza',
            'coffee' => 'coffee'
        ];
        
        // Default to 'main' if category not found in map
        $category = $categoryMap[strtolower($dish['dish_category'])] ?? 'main';
        //italian-dish, spanish-dish,house-salad, pizza, burgers, Pasta, pasta_caza, desserts, main-course, drinks, coffee
        
        return [
            'id' => (int)$dish['dish_id'],
            'name' => $dish['dish_name'],
            'codename' => $dish['codename'],
            'description' => $dish['dish_description'],
            'price' => (float)$dish['price'],
            'category' => $category,
            'image' => $dish['dish_pic_url'] ?: 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80' // default image if none provided
        ];
    }, $dishes);
    
    // Return the JSON response
    echo json_encode($menuItems);
    
} catch(PDOException $e) {
    // Handle database errors
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}