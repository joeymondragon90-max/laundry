<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$shopNameParam = isset($_GET['shop']) && $_GET['shop'] !== ''
    ? $_GET['shop']
    : 'General Laundry Shop';

$returnUrl = isset($_GET['return']) && $_GET['return'] !== ''
    ? $_GET['return']
    : 'index.php';

// Basic sanitization to avoid external redirects
if (!preg_match('/^[a-zA-Z0-9_\-\/\.]+$/', $returnUrl)) {
    $returnUrl = 'index.php';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Place Order - Dry Zone Cantilan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        :root {
            --primary: #0a7075;
            --secondary: #0c969c;
            --accent: #6ba3be;
            --light: #f5f7fa;
            --dark: #031716;
            --mid-dark: #032f30;
            --medium: #274d60;
            --warning: #f39c12;
            --danger: #e74c3c;
            --card-bg: #f8fbfd;
            --card-accent: #6ba3be;
            --card-hover: #e8f2f6;
            --text-light: #274d60;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        html { scroll-behavior: smooth; }
        body { background-color: var(--light); color: var(--dark); line-height: 1.6; overflow-x: hidden; }

        .container { width: 100%; max-width: 1300px; margin: 0 auto; padding: 0 15px; }

        /* Order Form Styles */
        .order-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .order-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .order-header h1 {
            font-size: 2.5rem;
            color: var(--primary);
            margin-bottom: 10px;
        }

        .order-header p {
            font-size: 1.1rem;
            color: var(--medium);
        }

        .order-form {
            background: var(--card-bg);
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 6px 20px rgba(39,77,96,0.06);
            border: 1px solid rgba(3,23,22,0.03);
        }

        .form-section {
            margin-bottom: 30px;
        }

        .form-section h3 {
            color: var(--primary);
            margin-bottom: 15px;
            font-size: 1.3rem;
            border-bottom: 2px solid var(--accent);
            padding-bottom: 8px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--mid-dark);
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e4eef1;
            border-radius: 8px;
            font-size: 1rem;
            background: #fff;
            transition: border-color 0.3s;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        .service-options {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .service-option {
            background: #fff;
            border: 2px solid #e4eef1;
            border-radius: 8px;
            padding: 15px;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .service-option:hover {
            border-color: var(--accent);
        }

        .service-option.selected {
            border-color: var(--primary);
            background: var(--card-hover);
        }

        .service-option input[type="checkbox"] {
            margin-right: 0;
            cursor: pointer;
        }

        .submit-btn {
            background: var(--primary);
            color: #fff;
            border: none;
            padding: 15px 40px;
            border-radius: 25px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .submit-btn:hover {
            background: var(--secondary);
        }

        .submit-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        .back-link {
            margin-bottom: 20px;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
        }

        .back-btn i {
            font-size: 0.95rem;
        }

        .back-btn:hover {
            color: var(--secondary);
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .service-options {
                grid-template-columns: 1fr;
            }
            
            .order-form {
                padding: 20px;
            }
            
            .order-header h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>

<div class="order-container">
    <div class="back-link">
        <a href="<?php echo htmlspecialchars($returnUrl); ?>" class="back-btn">
            <i class="fas fa-arrow-left"></i>
            Back to <?php echo htmlspecialchars($shopNameParam); ?>
        </a>
    </div>
    <div class="order-header">
        <h1><i class="fas fa-shopping-cart"></i> Place Your Order</h1>
        <p>Fill out the form below to place your laundry order</p>
    </div>

    <form class="order-form" id="orderForm">
        <!-- Customer Information -->
        <div class="form-section">
            <h3><i class="fas fa-user"></i> Customer Information</h3>
            <div class="form-row">
                <div class="form-group">
                    <label for="customerName">Full Name *</label>
                    <input type="text" id="customerName" name="customerName" required>
                </div>
                <div class="form-group">
                    <label for="customerPhone">Phone Number *</label>
                    <input type="tel" id="customerPhone" name="customerPhone" required>
                </div>
            </div>
            <div class="form-group">
                <label for="customerEmail">Email Address *</label>
                <input type="email" id="customerEmail" name="customerEmail" required>
            </div>
            <div class="form-group">
                <label for="customerAddress">Pickup/Delivery Address *</label>
                <textarea id="customerAddress" name="customerAddress" placeholder="Enter your complete address" required></textarea>
            </div>
        </div>

        <!-- Service Selection -->
        <div class="form-section">
            <h3><i class="fas fa-concierge-bell"></i> Services Needed</h3>
            <div class="service-options">
                <label class="service-option">
                    <input type="checkbox" name="services" value="wash">
                    <i class="fas fa-tint"></i> Washing
                </label>
                <label class="service-option">
                    <input type="checkbox" name="services" value="dry">
                    <i class="fas fa-wind"></i> Drying
                </label>
                <label class="service-option">
                    <input type="checkbox" name="services" value="fold">
                    <i class="fas fa-layer-group"></i> Folding
                </label>
                <label class="service-option">
                    <input type="checkbox" name="services" value="iron">
                    <i class="fas fa-fire"></i> Ironing
                </label>
                <label class="service-option">
                    <input type="checkbox" name="services" value="dryclean">
                    <i class="fas fa-gem"></i> Dry Cleaning
                </label>
                <label class="service-option">
                    <input type="checkbox" name="services" value="express">
                    <i class="fas fa-bolt"></i> Express Service
                </label>
            </div>
        </div>

        <!-- Order Details (per-kilo / pack) -->
        <div class="form-section">
            <h3><i class="fas fa-list"></i> Order Details</h3>
            <div class="form-row">
                <div class="form-group">
                    <label>Pricing Mode *</label>
                    <div style="display:flex;gap:12px;align-items:center;">
                        <label><input type="radio" name="pricingMode" value="per_kg" checked> Per Kilo</label>
                        <label><input type="radio" name="pricingMode" value="per_8kg"> Per 8kg Pack</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="urgency">Urgency Level</label>
                    <select id="urgency" name="urgency">
                        <option value="normal">Normal (2-3 days)</option>
                        <option value="urgent">Urgent (1 day)</option>
                        <option value="express">Express (Same day)</option>
                    </select>
                </div>
            </div>

            <div id="perKgSection">
                <div class="form-row">
                    <div class="form-group">
                        <label for="weightKg">Weight (kg) *</label>
                        <input type="number" id="weightKg" name="weightKg" min="0.1" step="0.1" value="1">
                    </div>
                    <div class="form-group">
                        <label for="pricePerKg">Price per kg (₱) *</label>
                        <input type="number" id="pricePerKg" name="pricePerKg" min="0" step="0.01" value="33">
                    </div>
                </div>
            </div>

            <div id="per8kgSection" style="display:none;">
                <div class="form-row">
                    <div class="form-group">
                        <label for="numPacks">Number of 8kg Packs *</label>
                        <input type="number" id="numPacks" name="numPacks" min="1" step="1" value="1">
                    </div>
                    <div class="form-group">
                        <label for="rateType">Rate Type</label>
                        <select id="rateType" name="rateType">
                            <option value="normal">Normal</option>
                            <option value="student">Student</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="pricePer8kg">Price per 8kg pack (₱)</label>
                        <input type="number" id="pricePer8kg" name="pricePer8kg" min="0" step="0.01" value="180">
                    </div>
                    <div class="form-group">
                        <label for="pricePer8kgStudent">Student price per 8kg (₱)</label>
                        <input type="number" id="pricePer8kgStudent" name="pricePer8kgStudent" min="0" step="0.01" value="140">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="specialInstructions">Special Instructions</label>
                <textarea id="specialInstructions" name="specialInstructions" placeholder="Any special care instructions or notes..."></textarea>
            </div>
        </div>

        <!-- Scheduling -->
        <div class="form-section">
            <h3><i class="fas fa-calendar"></i> Scheduling</h3>
            <div class="form-row">
                <div class="form-group">
                    <label for="pickupDate">Preferred Pickup Date *</label>
                    <input type="date" id="pickupDate" name="pickupDate" required>
                </div>
                <div class="form-group">
                    <label for="pickupTime">Preferred Pickup Time *</label>
                    <select id="pickupTime" name="pickupTime" required>
                        <option value="">Select Time</option>
                        <option value="08:00">8:00 AM</option>
                        <option value="09:00">9:00 AM</option>
                        <option value="10:00">10:00 AM</option>
                        <option value="11:00">11:00 AM</option>
                        <option value="12:00">12:00 PM</option>
                        <option value="13:00">1:00 PM</option>
                        <option value="14:00">2:00 PM</option>
                        <option value="15:00">3:00 PM</option>
                        <option value="16:00">4:00 PM</option>
                        <option value="17:00">5:00 PM</option>
                        <option value="18:00">6:00 PM</option>
                    </select>
                </div>
            </div>
        </div>

        <button type="submit" class="submit-btn">
            <i class="fas fa-paper-plane"></i> Submit Order
        </button>
    </form>
</div>

<script>
    // Set minimum date to today
    document.getElementById('pickupDate').min = new Date().toISOString().split('T')[0];

    // Service option selection styling
    document.querySelectorAll('.service-option').forEach(option => {
        option.addEventListener('click', function() {
            const checkbox = this.querySelector('input[type="checkbox"]');
            checkbox.checked = !checkbox.checked;
            
            if (checkbox.checked) {
                this.classList.add('selected');
            } else {
                this.classList.remove('selected');
            }
        });
    });

    // Pricing mode toggle
    const pricingRadios = document.querySelectorAll('input[name="pricingMode"]');
    const perKgSection = document.getElementById('perKgSection');
    const per8kgSection = document.getElementById('per8kgSection');
    pricingRadios.forEach(r => r.addEventListener('change', function(){
        if (this.value === 'per_kg') {
            perKgSection.style.display = '';
            per8kgSection.style.display = 'none';
        } else {
            perKgSection.style.display = 'none';
            per8kgSection.style.display = '';
        }
    }));

    // Auto-fill price per 8kg when rate type changes
    const rateType = document.getElementById('rateType');
    if (rateType) {
        rateType.addEventListener('change', function(){
            if (this.value === 'student') {
                document.getElementById('pricePer8kg').value = document.getElementById('pricePer8kgStudent').value || '140';
            } else {
                // if normal, keep pricePer8kg as-is or set default
                document.getElementById('pricePer8kg').value = document.getElementById('pricePer8kg').value || '180';
            }
        });
    }

    // Form submission
    document.getElementById('orderForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Get form data
        const formData = new FormData(this);
        
        // Get selected services
        const selectedServices = [];
        document.querySelectorAll('input[name="services"]:checked').forEach(checkbox => {
            selectedServices.push(checkbox.value);
        });
        
        // Validate services selection
        if (selectedServices.length === 0) {
            alert('Please select at least one service.');
            return;
        }
        
        // Add services to form data
        selectedServices.forEach(service => {
            formData.append('services[]', service);
        });
        
        // Add shop name if available (from URL parameter or default)
        const urlParams = new URLSearchParams(window.location.search);
        const shopName = urlParams.get('shop') || 'General Laundry Shop';
        formData.append('shop_name', shopName);
        
        // Add pricing details depending on pricing mode
        const pricingMode = document.querySelector('input[name="pricingMode"]:checked').value;
        formData.append('pricing_mode', pricingMode);
        if (pricingMode === 'per_kg') {
            formData.append('weight_kg', document.getElementById('weightKg').value);
            formData.append('price_per_kg', document.getElementById('pricePerKg').value);
        } else {
            formData.append('num_packs', document.getElementById('numPacks').value);
            formData.append('rate_type', document.getElementById('rateType').value);
            formData.append('price_per_8kg', document.getElementById('pricePer8kg').value);
            formData.append('price_per_8kg_student', document.getElementById('pricePer8kgStudent').value);
        }
        
        // Show loading state
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
        
        // Submit to server
        fetch('submit_order.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message || 'Order submitted successfully! We will contact you soon to confirm your order.');
                // Redirect to orders page
                window.location.href = 'customer_orders.php';
            } else {
                alert(data.error || 'Failed to submit order. Please try again.');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        });
    });
</script>
</body>
</html>

