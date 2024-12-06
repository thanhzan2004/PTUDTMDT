<?php
include 'database.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cosmetic Shop</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        :root {
            --primary-yellow: #ffc107;
            --dark-yellow: #ffa000;
            --light-yellow: #fff3cd;
            --background-gray: #f5f5f5;
            --text-gray: #555555;
        }

        .header {
            background: var(--primary-yellow);
            padding: 1rem;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .search-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logo {
            color: white;
            font-size: 2rem;
            font-weight: bold;
            text-decoration: none;
        }

        .search-box {
            flex: 1;
            display: flex;
        }

        .search-input {
            flex: 1;
            padding: 0.8rem 1rem;
            border: none;
            border-radius: 2px 0 0 2px;
            font-size: 0.9rem;
        }

        .search-button {
            padding: 0.8rem 1.5rem;
            background: var(--dark-yellow);
            border: none;
            color: white;
            border-radius: 0 2px 2px 0;
            cursor: pointer;
        }

        .main-container {
            max-width: 1200px;
            margin: 1rem auto;
            display: flex;
            gap: 1rem;
            padding: 0 1rem;
            background: var(--background-gray);
        }

        .filters {
            width: 190px;
            background: white;
            padding: 1rem;
            border-radius: 2px;
            height: fit-content;
        }

        .filter-header {
            font-size: 0.9rem;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 1rem;
            color: #333;
        }

        .filter-section {
            margin-bottom: 1.5rem;
            border-bottom: 1px solid #eee;
            padding-bottom: 1rem;
        }

        .filter-section:last-child {
            border-bottom: none;
        }

        .filter-title {
            font-size: 0.9rem;
            font-weight: bold;
            margin-bottom: 0.8rem;
            color: #333;
        }

        .checkbox-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
            color: var(--text-gray);
        }

        .checkbox-label:hover {
            color: var(--primary-yellow);
        }

        .products-container {
            flex: 1;
        }

        .sort-bar {
            background: white;
            padding: 0.8rem;
            margin-bottom: 1rem;
            border-radius: 2px;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .sort-label {
            color: var(--text-gray);
            font-size: 0.9rem;
        }

        .sort-button {
            padding: 0.5rem 1rem;
            background: white;
            border: 1px solid #ddd;
            border-radius: 2px;
            cursor: pointer;
            font-size: 0.9rem;
        }

        .sort-button:hover, .sort-button.active {
            background: var(--light-yellow);
            border-color: var(--primary-yellow);
            color: var(--dark-yellow);
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 0.5rem;
        }

        .product-card {
            background: white;
            border-radius: 2px;
            overflow: hidden;
            transition: transform 0.2s;
            box-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }

        .product-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .product-image {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }

        .product-info {
            padding: 0.5rem;
        }

        .product-name {
            font-size: 0.8rem;
            margin-bottom: 0.5rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            color: #333;
        }

        .product-price {
            color: var(--dark-yellow);
            font-size: 1rem;
            font-weight: bold;
        }

        .discount-badge {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            background: var(--dark-yellow);
            color: white;
            padding: 0.2rem 0.4rem;
            border-radius: 2px;
            font-size: 0.8rem;
        }

        .price-inputs {
            display: flex;
            gap: 0.5rem;
            align-items: center;
            margin-bottom: 1rem;
        }

        .price-input {
            width: 70px;
            padding: 0.3rem;
            border: 1px solid #ddd;
            border-radius: 2px;
            font-size: 0.9rem;
        }

        .rating {
            display: flex;
            align-items: center;
            gap: 0.3rem;
            font-size: 0.8rem;
            color: #666;
            margin-top: 0.3rem;
        }

        .star {
            color: var(--primary-yellow);
        }

        .sales {
            color: #666;
            font-size: 0.8rem;
        }
        .logo-image {
        height: 50px;
        width: auto;
        object-fit: contain;
    }
    
    .search-container {
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        gap: 2rem; /* Increased gap for better spacing with logo */
    }

    </style>
</head>
<body>
        <header class="header">
            <div class="search-container">
                <a href="#" class="logo"><img src="https://theme.hstatic.net/1000303351/1001070461/14/logo.png?v=1843" alt="Lemonade Logo" class="logo-image"></a>
                <div class="search-box">
                    <input type="text" class="search-input" id="searchInput" placeholder="Tìm kiếm mỹ phẩm...">
                    <button class="search-button">
                        <i class="search-icon">🔍</i>
                    </button>
                </div>
            </div>
        </header>
        

    <main class="main-container">
        <aside class="filters">
            <div class="filter-header">BỘ LỌC TÌM KIẾM</div>
            
            <div class="filter-section">
                <div class="filter-title">Theo Danh Mục</div>
                <div class="checkbox-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="category" value="skincare">
                        Chăm sóc da (1k2+)
                    </label>
                    <label class="checkbox-label">
                        <input type="checkbox" name="category" value="makeup">
                        Trang điểm (2k+)
                    </label>
                    <label class="checkbox-label">
                        <input type="checkbox" name="category" value="haircare">
                        Chăm sóc tóc (800+)
                    </label>
                </div>
            </div>

            <div class="filter-section">
                <div class="filter-title">Thương Hiệu</div>
                <div class="checkbox-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="brand" value="loreal">
                        L'Oreal Paris
                    </label>
                    <label class="checkbox-label">
                        <input type="checkbox" name="brand" value="maybelline">
                        Maybelline
                    </label>
                    <label class="checkbox-label">
                        <input type="checkbox" name="brand" value="innisfree">
                        Innisfree
                    </label>
                </div>
            </div>

            <div class="filter-section">
                <div class="filter-title">Khoảng Giá</div>
                <div class="price-inputs">
                    <input type="number" class="price-input" placeholder="₫ TỪ" id="minPrice">
                    <span>-</span>
                    <input type="number" class="price-input" placeholder="₫ ĐẾN" id="maxPrice">
                </div>
                <button class="search-button" style="width: 100%" onclick="applyPriceFilter()">ÁP DỤNG</button>
            </div>

            <div class="filter-section">
                <div class="filter-title">Đánh Giá</div>
                <div class="checkbox-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="rating" value="5" onclick="filterByRating(5)">
                        5 Sao
                    </label>
                    <label class="checkbox-label">
                        <input type="checkbox" name="rating" value="4" onclick="filterByRating(4)">
                        4 Sao trở lên
                    </label>
                    <label class="checkbox-label">
                        <input type="checkbox" name="rating" value="3" onclick="filterByRating(3)">
                        3 Sao trở lên
                    </label>
                </div>
            </div>
        </aside>

        <div class="products-container">
            <div class="sort-bar">
                <span class="sort-label">Sắp xếp theo</span>
                <button class="sort-button active" onclick="sortProducts('popular')">Phổ biến</button>
                <button class="sort-button" onclick="sortProducts('newest')">Mới nhất</button>
                <button class="sort-button" onclick="sortProducts('bestseller')">Bán chạy</button>
                <button class="sort-button" onclick="sortProducts('price')">Giá</button>
            </div>
            <div class="products-grid" id="productsGrid">
                <!-- Products will be dynamically inserted here -->
            </div>
        </div>
    </main>

    <script>
        // Sample product data
        const products = [
            {
                name: "Kem Chống Nắng Innisfree SPF50+",
                price: 235000,
                rating: 4.8,
                sales: 1200,
                image: "/api/placeholder/180/180",
                discount: "20%",
                date: "2024-01-01"
            },
            {
                name: "Son Lì Maybelline Superstay Matte Ink",
                price: 149000,
                rating: 4.9,
                sales: 2500,
                image: "/api/placeholder/180/180",
                discount: "15%",
                date: "2024-02-01"
            },
            {
                name: "Nước Tẩy Trang L'Oreal Paris",
                price: 159000,
                rating: 4.7,
                sales: 800,
                image: "/api/placeholder/180/180",
                discount: "25%",
                date: "2024-03-01"
            }
        ];

        let filteredProducts = [...products];

        // Function to render products
        function renderProducts(productsToRender) {
            const grid = document.getElementById('productsGrid');
            grid.innerHTML = productsToRender.map(product => `
                <div class="product-card">
                    <div style="position: relative;">
                        <img src="${product.image}" alt="${product.name}" class="product-image">
                        <span class="discount-badge">-${product.discount}</span>
                    </div>
                    <div class="product-info">
                        <div class="product-name">${product.name}</div>
                        <div class="product-price">₫${product.price.toLocaleString()}</div>
                        <div class="rating">
                            <span class="star">★</span>
                            <span>${product.rating}</span>
                            <span class="sales">| Đã bán ${product.sales}</span>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        // Search functionality
        const searchInput = document.getElementById('searchInput');
        searchInput.addEventListener('input', (e) => {
            const searchTerm = e.target.value.toLowerCase();
            filteredProducts = products.filter(product => 
                product.name.toLowerCase().includes(searchTerm)
            );
            renderProducts(filteredProducts);
        });

        // Price filter
        function applyPriceFilter() {
            const minPrice = parseFloat(document.getElementById('minPrice').value) || 0;
            const maxPrice = parseFloat(document.getElementById('maxPrice').value) || Infinity;
            
            filteredProducts = products.filter(product => 
                product.price >= minPrice && product.price <= maxPrice
            );
            renderProducts(filteredProducts);
        }

        // Rating filter
        function filterByRating(minRating) {
            filteredProducts = products.filter(product => product.rating >= minRating);
            renderProducts(filteredProducts);
        }

        // Sort functionality
        function sortProducts(criterion) {
            switch(criterion) {
                case 'popular':
                    filteredProducts.sort((a, b) => b.rating - a.rating);
                    break;
                case 'newest':
                    filteredProducts.sort((a, b) => new Date(b.date) - new Date(a.date));
                    break;
                case 'bestseller':
                    filteredProducts.sort((a, b) => b.sales - a.sales);
                    break;
                case 'price':
                    filteredProducts.sort((a, b) => a.price - b.price);
                    break;
            }
            renderProducts(filteredProducts);

            // Update active button
            document.querySelectorAll('.sort-button').forEach(button => {
                button.classList.remove('active');
            });
            event.target.classList.add('active');
        }

        // Initial render
        renderProducts(products);
    </script>
</body>
</html>