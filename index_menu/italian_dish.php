<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Italian Dish Menu</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Libre+Baskerville&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Libre Baskerville', serif;
            background: linear-gradient(to bottom, #FFF8E7, #FFE4B5);
        }
        .container {
            max-width: 7xl;
            margin: 0 auto;
            padding: 2rem 1rem;
        }
        .header {
            text-align: center;
            margin-bottom: 3rem;
        }
        .header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 3rem;
            font-weight: 700;
            color: #3C2F2F;
            margin-bottom: 1.5rem;
        }
        .header-divider {
            width: 6rem;
            height: 0.125rem;
            background: linear-gradient(to right, #6B4E31, #8B5A2B);
            margin: 0 auto;
        }
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            padding: 0 1rem;
        }
        .menu-card {
            background: #FFF;
            border-radius: 0.75rem;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
        }
        .menu-card:hover {
            transform: scale(1.03);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
        .menu-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .menu-card-content {
            padding: 1.5rem;
        }
        .menu-card h3 {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: #3C2F2F;
            margin: 0 0 0.5rem;
        }
        .menu-card .price {
            font-family: 'Libre Baskerville', serif;
            font-size: 1.1rem;
            color: #8B5A2B;
            margin-bottom: 0.75rem;
        }
        .menu-card p {
            font-family: 'Libre Baskerville', serif;
            font-size: 0.9rem;
            color: #4A3728;
            line-height: 1.5;
            margin: 0;
        }
        @media (max-width: 640px) {
            .header h1 {
                font-size: 2.5rem;
            }
            .menu-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <section id="italian-dish" class="py-20">
        <div class="container">
            <div class="header fade-in">
                <h1>Italian Dish Menu</h1>
                <div class="header-divider"></div>
            </div>
            <div class="menu-grid">
                <!-- Menu Item 1 -->
                <div class="menu-card">
                    <img src="images/italian/margherita-pizza.jpg" alt="Margherita Pizza">
                    <div class="menu-card-content">
                        <h3>Margherita Pizza</h3>
                        <div class="price">$12.99</div>
                        <p>Classic Italian pizza with fresh tomatoes, mozzarella, basil, and a drizzle of olive oil.</p>
                    </div>
                </div>
                <!-- Menu Item 2 -->
                <div class="menu-card">
                    <img src="images/italian/spaghetti-carbonara.jpg" alt="Spaghetti Carbonara">
                    <div class="menu-card-content">
                        <h3>Spaghetti Carbonara</h3>
                        <div class="price">$14.50</div>
                        <p>Creamy pasta with pancetta, egg, Parmesan cheese, and a touch of black pepper.</p>
                    </div>
                </div>
                <!-- Menu Item 3 -->
                <div class="menu-card">
                    <img src="images/italian/lasagna.jpg" alt="Lasagna">
                    <div class="menu-card-content">
                        <h3>Lasagna</h3>
                        <div class="price">$16.75</div>
                        <p>Layers of pasta, rich meat sauce, béchamel, and melted mozzarella cheese.</p>
                    </div>
                </div>
                <!-- Menu Item 4 -->
                <div class="menu-card">
                    <img src="images/italian/risotto-mushroom.jpg" alt="Mushroom Risotto">
                    <div class="menu-card-content">
                        <h3>Mushroom Risotto</h3>
                        <div class="price">$15.25</div>
                        <p>Creamy Arborio rice with wild mushrooms, Parmesan, and a hint of white wine.</p>
                    </div>
                </div>
                <!-- Menu Item 5 -->
                <div class="menu-card">
                    <img src="images/italian/tiramisu.jpg" alt="Tiramisu">
                    <div class="menu-card-content">
                        <h3>Tiramisu</h3>
                        <div class="price">$8.99</div>
                        <p>Traditional Italian dessert with layers of coffee-soaked ladyfingers and mascarpone cream.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
</html>