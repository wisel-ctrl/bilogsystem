<?php
header('Content-Type: application/json');
require_once "../../db_connect.php";

try {
    /* ----------------------- HELPER ----------------------- */
    function generateCodenamePDO(PDO $conn, string $dishCategory): string
    {
        // 1) Category → prefix map
        $prefixes = [
            'main_course'   => 'mc',
            'best_seller'   => 'bs',
            'italian_dish'  => 'it',
            'spanish_dish'  => 'sp',
            'house_salad'   => 'hs',
            'burger_pizza'  => 'bp',
            'pasta'         => 'ps',
            'pasta_caza'    => 'pc',
            'desserts'      => 'ds',
            'drinks'        => 'dr',
            'coffee'        => 'cf'
        ];

        // Normalise key (e.g. “Pasta” → “pasta”)
        $key = strtolower($dishCategory);
        $prefix = $prefixes[$key] ?? 'xx';

        // 2) How many dishes already in this category?
        $stmt = $conn->prepare(
            "SELECT COUNT(*) AS cnt
             FROM   dishes_tb
             WHERE  LOWER(dish_category) = :cat"
        );
        $stmt->execute([':cat' => $key]);
        $count = (int)$stmt->fetchColumn() + 1;  // next position

        return "{$prefix}-{$count}";
    }

    /* --------------------- MAIN SCRIPT -------------------- */
    $conn->beginTransaction();

    /* --- 1. Image upload --- */
    $imageUrl = null;
    if (!empty($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../../images/dish_images/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $fileName   = uniqid() . '_' . basename($_FILES['image']['name']);
        $targetPath = $uploadDir . $fileName;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            $imageUrl = $targetPath;
        }
    }

    /* --- 2. Generate codename --- */
    $dishCategory = $_POST['category'];
    $codename     = generateCodenamePDO($conn, $dishCategory);

    /* --- 3. Insert dish --- */
    $insertDish = $conn->prepare("
        INSERT INTO dishes_tb
            (dish_name, dish_description, dish_category, price, capital, dish_pic_url, codename)
        VALUES
            (:name, :description, :category, :price, :capital, :image_url, :codename)
    ");

    $insertDish->execute([
        ':name'        => $_POST['name'],
        ':description' => $_POST['description'] ?? null,
        ':category'    => $dishCategory,
        ':price'       => $_POST['price'],
        ':capital'     => $_POST['capital'],
        ':image_url'   => $imageUrl,
        ':codename'    => $codename
    ]);

    $dishId = $conn->lastInsertId();

    /* --- 4. Insert ingredients --- */
    $ingredients   = json_decode($_POST['ingredients'], true) ?? [];
    $insertIngr = $conn->prepare("
        INSERT INTO dish_ingredients (dish_id, ingredient_id, quantity_grams)
        VALUES (:dish_id, :ingredient_id, :quantity_kg)
    ");

    foreach ($ingredients as $ing) {
        $insertIngr->execute([
            ':dish_id'       => $dishId,
            ':ingredient_id' => $ing['ingredient_id'],
            ':quantity_kg'   => $ing['quantity_kg']
        ]);
    }

    /* --- 5. Commit --- */
    $conn->commit();
    echo json_encode(['success' => true,
                      'message' => 'Dish added successfully',
                      'codename' => $codename]);
} catch (Throwable $e) {
    if ($conn->inTransaction()) $conn->rollBack();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
