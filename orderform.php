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
            --primary: #53629E;
            --secondary: #473472;
            --accent: #87BAC3;
            --light: #D6F4ED;
            --dark: #473472;
            --mid-dark: #53629E;
            --medium: #87BAC3;
            --warning: #f59e0b;
            --danger: #ef4444;
            --card-bg: #ffffff;
            --card-accent: #87BAC3;
            --card-hover: #D6F4ED;
            --text-light: #53629E;
            --gradient-1: linear-gradient(135deg, #473472 0%, #53629E 100%);
            --gradient-2: linear-gradient(135deg, #53629E 0%, #87BAC3 100%);
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
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
            background: var(--gradient-1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
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
            box-shadow: var(--shadow-lg);
            border: 1px solid rgba(83, 98, 158, 0.1);
        }

        .pricing-notice {
            background: #D6F4ED;
            border-left: 4px solid #87BAC3;
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 30px;
            color: #473472;
        }

        .pricing-notice h4 {
            margin: 0 0 8px 0;
            color: #53629E;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 1.1rem;
        }

        .pricing-notice p {
            margin: 4px 0;
            font-size: 0.95rem;
            line-height: 1.5;
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
        <!-- Pricing Notice -->
        <div class="pricing-notice">
            <h4><i class="fas fa-info-circle"></i> How Pricing Works</h4>
            <p><strong>Step 1:</strong> Estimate your laundry items below to get an estimated cost</p>
            <p><strong>Step 2:</strong> Your laundry will be weighed at the shop upon pickup/delivery</p>
            <p><strong>Step 3:</strong> You pay the final amount based on actual weight</p>
            <p style="margin-top: 12px; color: #87BAC3; font-weight: 600;"><i class="fas fa-check-circle"></i> The amount shown below is an estimate only</p>
        </div>

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
                    <input type="checkbox" name="services" value="wash_fold">
                    <i class="fas fa-tshirt"></i> Wash & Fold
                </label>
                <label class="service-option">
                    <input type="checkbox" name="services" value="dry_clean">
                    <i class="fas fa-shirt"></i> Dry Cleaning
                </label>
                <label class="service-option">
                    <input type="checkbox" name="services" value="ironing">
                    <i class="fas fa-shirt"></i> Ironing/Pressing
                </label>
                <label class="service-option">
                    <input type="checkbox" name="services" value="starch">
                    <i class="fas fa-spray-can"></i> Starch Service
                </label>
                <label class="service-option">
                    <input type="checkbox" name="services" value="delicate">
                    <i class="fas fa-feather"></i> Delicate Wear
                </label>
                <label class="service-option">
                    <input type="checkbox" name="services" value="heavy">
                    <i class="fas fa-layer-group"></i> Heavy Items (Bed Sheets)
                </label>
            </div>
        </div>

        <!-- Order Details (per-item / pack) -->
        <div class="form-section">
            <h3><i class="fas fa-list"></i> Order Details</h3>
            <div class="form-row">
                <div class="form-group">
                    <label>Pricing Mode *</label>
                    <div style="display:flex;gap:12px;align-items:center;">
                        <label><input type="radio" name="pricingMode" value="per_item" checked> Per Item</label>
                        <label><input type="radio" name="pricingMode" value="per_8kg"> Per 8kg Pack (approx 40-50 pieces)</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="urgency">Urgency Level</label>
                    <select id="urgency" name="urgency">
                        <option value="normal">Normal (2-3 days) - ₱0</option>
                        <option value="urgent">Urgent (1 day) - ₱50 extra</option>
                        <option value="express">Express (Same day) - ₱100 extra</option>
                    </select>
                </div>
            </div>

            <div id="perItemSection">
                <div class="form-row">
                    <div class="form-group">
                        <label for="itemCount">Number of Items/Pieces *</label>
                        <input type="number" id="itemCount" name="itemCount" min="1" step="1" value="10" placeholder="e.g., 10 shirts, 5 pants, etc.">
                        <small style="color: var(--medium); display: block; margin-top: 4px;">Count each piece: shirts, pants, underwear, socks, towels, etc.</small>
                    </div>
                    <div class="form-group">
                        <label for="pricePerItem">Price per item (₱) *</label>
                        <input type="number" id="pricePerItem" name="pricePerItem" min="0" step="0.01" value="8" readonly>
                        <small style="color: var(--medium); display: block; margin-top: 4px;">Standard rate per piece</small>
                    </div>
                </div>
            </div>

            <div id="per8kgSection" style="display:none;">
                <div class="form-row">
                    <div class="form-group">
                        <label for="numPacks">Number of 8kg Packs *</label>
                        <input type="number" id="numPacks" name="numPacks" min="1" step="1" value="1">
                        <small style="color: var(--medium); display: block; margin-top: 4px;">1 pack = approximately 40-50 pieces of clothing</small>
                    </div>
                    <div class="form-group">
                        <label for="rateType">Rate Type</label>
                        <select id="rateType" name="rateType">
                            <option value="normal">Regular</option>
                            <option value="student">Student (15% off)</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="pricePer8kg">Price per 8kg pack (₱)</label>
                        <input type="number" id="pricePer8kg" name="pricePer8kg" min="0" step="0.01" value="200">
                    </div>
                    <div class="form-group">
                        <label for="pricePer8kgStudent">Student price per 8kg (₱)</label>
                        <input type="number" id="pricePer8kgStudent" name="pricePer8kgStudent" min="0" step="0.01" value="170">
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

        <!-- Delivery Options -->
        <div class="form-section">
            <h3><i class="fas fa-truck"></i> Delivery Options</h3>
            <div style="display:flex;gap:12px;align-items:center;flex-wrap:wrap;">
                <label><input type="radio" name="delivery_option" value="pickup" checked> Pickup Only (FREE)</label>
                <label><input type="radio" name="delivery_option" value="delivery"> Delivery (₱50) - Auto FREE if order ≥ ₱300</label>
            </div>
            <small style="color: var(--medium); display: block; margin-top: 8px;"><i class="fas fa-info-circle"></i> Free delivery automatically applied if total order amount reaches ₱300</small>
        </div>

        <!-- Student Status -->
        <div class="form-section">
            <h3><i class="fas fa-graduation-cap"></i> Student Status</h3>
            <div class="form-row">
                <div class="form-group">
                    <label for="studentStatus">Are you a student? *</label>
                    <select id="studentStatus" name="studentStatus" required>
                        <option value="no">No, I'm not a student</option>
                        <option value="yes">Yes, I'm a student (20% discount)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="studentId">Student ID (if applicable)</label>
                    <input type="text" id="studentId" name="studentId" placeholder="e.g., 2024-12345">
                </div>
            </div>
        </div>

        <!-- Voucher/Promo Code -->
        <div class="form-section">
            <h3><i class="fas fa-tag"></i> Promo & Voucher</h3>
            <div class="form-group">
                <label for="voucherCode">Voucher Code (Optional)</label>
                <div style="display:flex;gap:10px;align-items:flex-end;">
                    <div style="flex:1;">
                        <input type="text" id="voucherCode" name="voucherCode" placeholder="Enter voucher code" style="margin-bottom:0;">
                    </div>
                    <button type="button" id="applyVoucherBtn" style="padding:12px 20px;background:var(--accent);color:#fff;border:none;border-radius:8px;cursor:pointer;transition:background 0.3s;">Apply</button>
                </div>
                <small id="voucherMessage" style="color:#666;margin-top:8px;display:block;"></small>
            </div>
        </div>

        <!-- Payment Method -->
        <div class="form-section">
            <h3><i class="fas fa-credit-card"></i> Payment Method</h3>
            <div class="form-row">
                <div class="form-group">
                    <label for="paymentMethod">Select Payment Method *</label>
                    <select id="paymentMethod" name="paymentMethod" required>
                        <option value="">-- Select Payment Method --</option>
                        <option value="cash">Cash on Pickup/Delivery</option>
                        <option value="gcash">GCash</option>
                        <option value="paypal">PayPal</option>
                        <option value="bank">Bank Transfer</option>
                        <option value="credit_card">Credit/Debit Card</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="referenceNumber">Reference Number (if applicable)</label>
                    <input type="text" id="referenceNumber" name="referenceNumber" placeholder="Transaction ID or Reference Number">
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="form-section" style="background:#D6F4ED;border-radius:12px;padding:20px;border:2px solid #87BAC3;">
            <h3><i class="fas fa-receipt"></i> Estimated Order Cost</h3>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:15px;font-size:0.95rem;color:#473472;">
                <div><strong>Base Amount:</strong> ₱<span id="baseAmount">0.00</span></div>
                <div><strong>Student Discount:</strong> -₱<span id="studentDiscount">0.00</span></div>
                <div><strong>Urgency Fee:</strong> +₱<span id="urgencyFee">0.00</span></div>
                <div><strong>Delivery Fee:</strong> +₱<span id="deliveryFee">0.00</span></div>
                <div><strong>Voucher Discount:</strong> -₱<span id="voucherDiscount">0.00</span></div>
                <div style="border-top:2px solid #87BAC3;padding-top:10px;grid-column:1/-1;"><strong style="font-size:1.1rem;color:#53629E;">ESTIMATED Total: ₱<span id="totalAmount">0.00</span></strong></div>
            </div>
            <p style="margin-top:12px;font-size:0.9rem;color:#53629E;"><i class="fas fa-check-circle"></i> Final amount will be calculated based on actual laundry weight at the shop</p>
        </div>

        <button type="submit" class="submit-btn">
            <i class="fas fa-paper-plane"></i> Submit Order
        </button>
    </form>
</div>

<script>
    // Set minimum date to today
    document.getElementById('pickupDate').min = new Date().toISOString().split('T')[0];

    // Voucher codes database (would come from server in real app)
    const validVouchers = {
        'SUMMER20': { discount: 20, type: 'fixed' },
        'PROMO15': { discount: 15, type: 'fixed' },
        'SAVE10': { discount: 10, type: 'fixed' }
    };

    let appliedVoucher = null;

    // Calculate and update totals
    function calculateTotals() {
        let baseAmount = 0;

        // Calculate base amount based on pricing mode
        const pricingMode = document.querySelector('input[name="pricingMode"]:checked').value;
        
        if (pricingMode === 'per_item') {
            const itemCount = parseFloat(document.getElementById('itemCount').value) || 0;
            const pricePerItem = parseFloat(document.getElementById('pricePerItem').value) || 0;
            baseAmount = itemCount * pricePerItem;
        } else {
            const numPacks = parseFloat(document.getElementById('numPacks').value) || 0;
            const pricePerPack = parseFloat(document.getElementById('pricePer8kg').value) || 0;
            baseAmount = numPacks * pricePerPack;
        }

        // Add urgency fee
        const urgency = document.querySelector('select[name="urgency"]').value;
        let urgencyFee = 0;
        if (urgency === 'urgent') urgencyFee = 50;
        if (urgency === 'express') urgencyFee = 100;

        // Check delivery option
        const deliveryOption = document.querySelector('input[name="delivery_option"]:checked').value;
        let deliveryFee = 0;
        if (deliveryOption === 'delivery') {
            // Only charge ₱50 if total is less than ₱300
            deliveryFee = (baseAmount + urgencyFee) >= 300 ? 0 : 50;
        }

        // Calculate student discount (20% off base amount only)
        const isStudent = document.getElementById('studentStatus').value === 'yes';
        const studentDiscount = isStudent ? (baseAmount * 0.20) : 0;
        
        // Calculate voucher discount
        let voucherDiscount = 0;
        if (appliedVoucher) {
            const discountedAmount = baseAmount - studentDiscount;
            voucherDiscount = appliedVoucher.discount;
        }

        // Calculate total
        const totalAmount = baseAmount - studentDiscount - voucherDiscount + urgencyFee + deliveryFee;

        // Update display
        document.getElementById('baseAmount').textContent = baseAmount.toFixed(2);
        document.getElementById('studentDiscount').textContent = studentDiscount.toFixed(2);
        document.getElementById('urgencyFee').textContent = urgencyFee.toFixed(2);
        document.getElementById('deliveryFee').textContent = deliveryFee.toFixed(2);
        document.getElementById('voucherDiscount').textContent = voucherDiscount.toFixed(2);
        document.getElementById('totalAmount').textContent = Math.max(0, totalAmount).toFixed(2);
    }

    // Apply voucher code
    document.getElementById('applyVoucherBtn').addEventListener('click', function() {
        const voucherCode = document.getElementById('voucherCode').value.trim().toUpperCase();
        const messageEl = document.getElementById('voucherMessage');

        if (!voucherCode) {
            messageEl.textContent = 'Please enter a voucher code';
            messageEl.style.color = '#ef4444';
            appliedVoucher = null;
            calculateTotals();
            return;
        }

        if (validVouchers[voucherCode]) {
            const voucher = validVouchers[voucherCode];
            const baseAmount = parseFloat(document.getElementById('baseAmount').textContent) || 0;
            const studentDiscount = parseFloat(document.getElementById('studentDiscount').textContent) || 0;
            const discountedAmount = baseAmount - studentDiscount;

            if (voucher.type === 'fixed') {
                appliedVoucher = { code: voucherCode, discount: voucher.discount, type: 'fixed' };
            }

            messageEl.textContent = `✓ Voucher "${voucherCode}" applied successfully! Discount: ₱${appliedVoucher.discount.toFixed(2)}`;
            messageEl.style.color = '#22c55e';
            calculateTotals();
        } else {
            messageEl.textContent = 'Invalid voucher code';
            messageEl.style.color = '#ef4444';
            appliedVoucher = null;
            calculateTotals();
        }
    });

    // Event listeners for total calculation
    document.getElementById('studentStatus').addEventListener('change', calculateTotals);
    document.getElementById('itemCount').addEventListener('change', calculateTotals);
    document.getElementById('pricePerItem').addEventListener('change', calculateTotals);
    document.getElementById('numPacks').addEventListener('change', calculateTotals);
    document.getElementById('pricePer8kg').addEventListener('change', calculateTotals);
    document.querySelector('select[name="urgency"]').addEventListener('change', calculateTotals);
    document.querySelectorAll('input[name="delivery_option"]').forEach(r => r.addEventListener('change', calculateTotals));

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
    const perItemSection = document.getElementById('perItemSection');
    const per8kgSection = document.getElementById('per8kgSection');
    pricingRadios.forEach(r => r.addEventListener('change', function(){
        if (this.value === 'per_item') {
            perItemSection.style.display = '';
            per8kgSection.style.display = 'none';
        } else {
            perItemSection.style.display = 'none';
            per8kgSection.style.display = '';
        }
        calculateTotals();
    }));

    // Auto-fill price per 8kg when rate type changes
    const rateType = document.getElementById('rateType');
    if (rateType) {
        rateType.addEventListener('change', function(){
            if (this.value === 'student') {
                document.getElementById('pricePer8kg').value = document.getElementById('pricePer8kgStudent').value || '140';
            } else {
                document.getElementById('pricePer8kg').value = '180';
            }
            calculateTotals();
        });
    }

    // Form submission
    document.getElementById('orderForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validate payment method
        if (!document.getElementById('paymentMethod').value) {
            alert('Please select a payment method');
            return;
        }

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

        // Add student, voucher and payment info
        formData.append('student_status', document.getElementById('studentStatus').value);
        formData.append('student_id', document.getElementById('studentId').value);
        formData.append('voucher_code', appliedVoucher ? appliedVoucher.code : '');
        formData.append('payment_method', document.getElementById('paymentMethod').value);
        formData.append('reference_number', document.getElementById('referenceNumber').value);
        formData.append('total_amount', document.getElementById('totalAmount').textContent);
        
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

    // Initial calculation
    calculateTotals();
</script>
</body>
</html>

