<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Laundry Shops - Dry Zone Cantilan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        .search-container {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 30px;
            max-width: 1300px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .search-sidebar {
            background: white;
            border-radius: 12px;
            padding: 25px;
            height: fit-content;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            position: sticky;
            top: 100px;
        }

        .filter-group {
            margin-bottom: 25px;
            padding-bottom: 25px;
            border-bottom: 1px solid #e2e8f0;
        }

        .filter-group:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .filter-group h4 {
            margin: 0 0 15px 0;
            color: #0f172a;
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: bold;
        }

        .filter-input, .filter-select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 0.9rem;
            margin-bottom: 8px;
            transition: all 0.2s;
        }

        .filter-input:focus, .filter-select:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .price-range-slider {
            margin: 15px 0;
        }

        .price-inputs {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }

        .price-inputs input {
            padding: 8px 10px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 0.85rem;
        }

        .rating-filter {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px;
            cursor: pointer;
            border-radius: 6px;
            transition: all 0.2s;
            margin-bottom: 8px;
        }

        .rating-filter:hover {
            background: #f1f5f9;
        }

        .rating-filter input[type="radio"] {
            cursor: pointer;
        }

        .rating-filter label {
            margin: 0;
            cursor: pointer;
            flex: 1;
        }

        .filter-btn-group {
            display: flex;
            gap: 8px;
            margin-top: 20px;
        }

        .filter-btn {
            flex: 1;
            padding: 10px 15px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s;
            font-size: 0.9rem;
        }

        .btn-search {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            color: white;
        }

        .btn-search:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }

        .btn-clear {
            background: #f1f5f9;
            color: #334155;
            border: 1px solid #cbd5e1;
        }

        .btn-clear:hover {
            background: #e2e8f0;
        }

        .search-results {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .results-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .results-header h2 {
            margin: 0;
            color: #0f172a;
            font-size: 1.5rem;
        }

        .results-count {
            color: #64748b;
            font-size: 0.9rem;
        }

        .sort-select {
            padding: 10px 15px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            background: white;
            cursor: pointer;
        }

        .shop-result-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            display: grid;
            grid-template-columns: 150px 1fr 150px;
            gap: 20px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            align-items: center;
        }

        .shop-result-card:hover {
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }

        .shop-image {
            width: 150px;
            height: 150px;
            border-radius: 8px;
            object-fit: cover;
            background: #f1f5f9;
        }

        .shop-result-info h3 {
            margin: 0 0 8px 0;
            font-size: 1.2rem;
            color: #0f172a;
        }

        .shop-result-info .shop-rating {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 8px;
            color: #f59e0b;
        }

        .shop-result-info .shop-rating strong {
            color: #0f172a;
        }

        .shop-result-info .location {
            color: #64748b;
            font-size: 0.9rem;
            margin-bottom: 8px;
        }

        .shop-result-info .service-tag {
            display: inline-block;
            background: #dbeafe;
            color: #1e40af;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            margin-right: 6px;
            margin-bottom: 8px;
        }

        .shop-result-actions {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .shop-result-actions a {
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 8px;
            text-align: center;
            font-weight: bold;
            transition: all 0.3s;
            font-size: 0.9rem;
        }

        .btn-view {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            color: white;
        }

        .btn-view:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }

        .btn-chat-result {
            background: #f1f5f9;
            color: #2563eb;
            border: 1px solid #cbd5e1;
        }

        .btn-chat-result:hover {
            background: #e0f2fe;
        }

        .empty-results {
            grid-column: 1 / -1;
            text-align: center;
            padding: 60px 20px;
            color: #64748b;
        }

        .empty-results i {
            font-size: 3rem;
            color: #cbd5e1;
            margin-bottom: 20px;
        }

        .empty-results h3 {
            color: #334155;
            margin-bottom: 10px;
        }

        .loading {
            text-align: center;
            padding: 40px 20px;
            color: #64748b;
        }

        .spinner {
            display: inline-block;
            width: 30px;
            height: 30px;
            border: 3px solid #e2e8f0;
            border-radius: 50%;
            border-top-color: #2563eb;
            animation: spin 1s linear infinite;
            margin-bottom: 15px;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        @media (max-width: 1024px) {
            .search-container {
                grid-template-columns: 1fr;
            }

            .search-sidebar {
                position: static;
            }

            .shop-result-card {
                grid-template-columns: 1fr;
            }

            .shop-image {
                width: 100%;
                height: 200px;
            }
        }

        @media (max-width: 600px) {
            .search-container {
                padding: 0 15px;
            }

            .results-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .shop-result-actions {
                flex-direction: row;
            }
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>

<div class="search-container">
    <!-- Sidebar Filters -->
    <div class="search-sidebar">
        <h2 style="margin: 0 0 25px 0; color: #0f172a;">
            <i class="fas fa-filter"></i> Filters
        </h2>

        <!-- Search -->
        <div class="filter-group">
            <h4>Search</h4>
            <input 
                type="text" 
                id="searchInput" 
                class="filter-input" 
                placeholder="Shop name, location..."
            >
        </div>

        <!-- Service Type -->
        <div class="filter-group">
            <h4>Service Type</h4>
            <select id="serviceSelect" class="filter-select">
                <option value="">All Services</option>
                <option value="wash">Wash & Fold</option>
                <option value="dry cleaning">Dry Cleaning</option>
                <option value="ironing">Ironing</option>
                <option value="mattress">Mattress Cleaning</option>
                <option value="express">Express Service</option>
            </select>
        </div>

        <!-- Location -->
        <div class="filter-group">
            <h4>Location</h4>
            <select id="locationSelect" class="filter-select">
                <option value="">All Locations</option>
                <option value="Poblacion">Poblacion</option>
                <option value="Magosilom">Magosilom</option>
                <option value="San Pedro">San Pedro</option>
                <option value="Lininti-an">Lininti-an</option>
                <option value="Pag-antayan">Pag-antayan</option>
                <option value="Calagdaan">Calagdaan</option>
            </select>
        </div>

        <!-- Price Range -->
        <div class="filter-group">
            <h4>Price Range (₱/kg)</h4>
            <div class="price-inputs">
                <input type="number" id="minPrice" placeholder="Min" value="0" step="5">
                <input type="number" id="maxPrice" placeholder="Max" value="500" step="5">
            </div>
        </div>

        <!-- Rating -->
        <div class="filter-group">
            <h4>Minimum Rating</h4>
            <div>
                <div class="rating-filter">
                    <input type="radio" id="rating-all" name="rating" value="0" checked>
                    <label for="rating-all">All Ratings</label>
                </div>
                <div class="rating-filter">
                    <input type="radio" id="rating-4" name="rating" value="4">
                    <label for="rating-4">
                        <i class="fas fa-star"></i> 4.0+ Stars
                    </label>
                </div>
                <div class="rating-filter">
                    <input type="radio" id="rating-45" name="rating" value="4.5">
                    <label for="rating-45">
                        <i class="fas fa-star"></i> 4.5+ Stars
                    </label>
                </div>
                <div class="rating-filter">
                    <input type="radio" id="rating-5" name="rating" value="5">
                    <label for="rating-5">
                        <i class="fas fa-star"></i> 5.0 Stars
                    </label>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="filter-btn-group">
            <button class="filter-btn btn-search" onclick="applyFilters()">
                <i class="fas fa-search"></i> Search
            </button>
            <button class="filter-btn btn-clear" onclick="clearFilters()">
                <i class="fas fa-redo"></i> Reset
            </button>
        </div>
    </div>

    <!-- Results Area -->
    <div class="search-results">
        <div class="results-header">
            <div>
                <h2>Results</h2>
                <div class="results-count">
                    Showing <span id="resultCount">0</span> shops
                </div>
            </div>
            <select id="sortSelect" class="sort-select" onchange="applyFilters()">
                <option value="rating">Highest Rated</option>
                <option value="popular">Most Popular</option>
                <option value="price_asc">Price: Low to High</option>
                <option value="price_desc">Price: High to Low</option>
                <option value="name">Name (A-Z)</option>
            </select>
        </div>

        <div id="resultsContainer">
            <div class="empty-results">
                <i class="fas fa-search"></i>
                <h3>Enter search criteria</h3>
                <p>Use the filters to find laundry shops</p>
            </div>
        </div>
    </div>
</div>

<footer>
    <div class="container">
        <div class="footer-content">
            <div class="footer-section">
                <h3>Dry Zone - Cantilan</h3>
                <p>Your directory for laundry services in Cantilan, Surigao del Sur.</p>
            </div>
        </div>
    </div>
</footer>

<script>
function applyFilters() {
    const search = document.getElementById('searchInput').value;
    const service = document.getElementById('serviceSelect').value;
    const location = document.getElementById('locationSelect').value;
    const minPrice = document.getElementById('minPrice').value;
    const maxPrice = document.getElementById('maxPrice').value;
    const rating = document.querySelector('input[name="rating"]:checked').value;
    const sort = document.getElementById('sortSelect').value;

    const params = new URLSearchParams({
        search: search,
        service: service,
        location: location,
        min_price: minPrice,
        max_price: maxPrice,
        min_rating: rating,
        sort: sort
    });

    showLoading();

    fetch(`search_shops.php?${params}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayResults(data.shops);
                document.getElementById('resultCount').textContent = data.count;
            } else {
                showError(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('Failed to search shops');
        });
}

function displayResults(shops) {
    const container = document.getElementById('resultsContainer');
    
    if (shops.length === 0) {
        container.innerHTML = `
            <div class="empty-results">
                <i class="fas fa-inbox"></i>
                <h3>No shops found</h3>
                <p>Try adjusting your filters</p>
            </div>
        `;
        return;
    }

    container.innerHTML = shops.map(shop => `
        <div class="shop-result-card">
            <img src="./${shop.name.toLowerCase().replace(/[^a-z0-9]/g, '')}.png" alt="${shop.name}" class="shop-image" onerror="this.src='https://via.placeholder.com/150'">
            
            <div class="shop-result-info">
                <h3>${shop.name}</h3>
                <div class="shop-rating">
                    <i class="fas fa-star"></i>
                    <strong>${shop.rating}</strong>
                    <span>(${shop.reviews} reviews)</span>
                </div>
                <div class="location">
                    <i class="fas fa-map-marker-alt"></i> ${shop.location}
                </div>
                <p style="margin: 8px 0; color: #64748b; font-size: 0.9rem;">${shop.description}</p>
                <div>
                    <span class="service-tag">${shop.service}</span>
                    ${shop.pickup_delivery ? '<span class="service-tag">Pickup & Delivery</span>' : ''}
                </div>
                <div style="color: #0f172a; font-weight: bold; margin-top: 8px;">
                    Avg. Price: <span style="color: #2563eb;">₱${shop.avg_price}/kg</span>
                </div>
            </div>

            <div class="shop-result-actions">
                <a href="shop1.php" class="btn-view">
                    <i class="fas fa-eye"></i> View Shop
                </a>
                <a href="chat.php?shop=${encodeURIComponent(shop.name)}" class="btn-chat-result">
                    <i class="fas fa-comments"></i> Chat
                </a>
            </div>
        </div>
    `).join('');
}

function showLoading() {
    document.getElementById('resultsContainer').innerHTML = `
        <div class="loading">
            <div class="spinner"></div>
            <p>Searching shops...</p>
        </div>
    `;
}

function showError(message) {
    document.getElementById('resultsContainer').innerHTML = `
        <div class="empty-results">
            <i class="fas fa-exclamation-circle"></i>
            <h3>Error</h3>
            <p>${message}</p>
        </div>
    `;
}

function clearFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('serviceSelect').value = '';
    document.getElementById('locationSelect').value = '';
    document.getElementById('minPrice').value = '0';
    document.getElementById('maxPrice').value = '500';
    document.getElementById('rating-all').checked = true;
    document.getElementById('sortSelect').value = 'rating';
    document.getElementById('resultsContainer').innerHTML = `
        <div class="empty-results">
            <i class="fas fa-search"></i>
            <h3>Enter search criteria</h3>
            <p>Use the filters to find laundry shops</p>
        </div>
    `;
    document.getElementById('resultCount').textContent = '0';
}

// Auto-search on parameter changes (debounce)
let searchTimeout;
['searchInput', 'serviceSelect', 'locationSelect', 'minPrice', 'maxPrice'].forEach(id => {
    const element = document.getElementById(id);
    if (element) {
        element.addEventListener('change', () => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(applyFilters, 500);
        });
    }
});
</script>
</body>
</html>
