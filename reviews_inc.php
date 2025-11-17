<?php
// Expects $shop_name to be defined before include
if (!isset($shop_name)) {
    echo '<!-- reviews_inc.php: $shop_name not set -->';
    return;
}
require_once 'db.php';

// Fetch average rating and count
$avgStmt = $conn->prepare("SELECT AVG(rating) as avg_rating, COUNT(*) as cnt FROM reviews WHERE shop_name = ?");
$avgStmt->bind_param('s', $shop_name);
$avgStmt->execute();
$avgRes = $avgStmt->get_result();
$avgRow = $avgRes->fetch_assoc();
$avg = $avgRow['avg_rating'] ? number_format((float)$avgRow['avg_rating'],1) : '0.0';
$cnt = (int)$avgRow['cnt'];
$avgStmt->close();

// Fetch recent reviews (limit 10)
$listStmt = $conn->prepare("SELECT user_name, rating, comment, created_at FROM reviews WHERE shop_name = ? ORDER BY created_at DESC LIMIT 10");
$listStmt->bind_param('s', $shop_name);
$listStmt->execute();
$listRes = $listStmt->get_result();

?>
<style>
    .shop-reviews {
        margin-top: 60px;
        padding: 40px 0;
        border-top: 2px solid rgba(37, 99, 235, 0.1);
    }
    
    .shop-reviews h3 {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 30px;
        color: var(--primary, #2563eb);
        background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .reviews-summary {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 40px;
        padding: 25px;
        background: rgba(37, 99, 235, 0.05);
        border-radius: 12px;
        border: 1px solid rgba(37, 99, 235, 0.1);
    }
    
    .avg-rating, .reviews-count {
        font-size: 1rem;
        color: var(--medium, #334155);
    }
    
    .avg-rating strong, .reviews-count strong {
        font-size: 1.8rem;
        color: var(--primary, #2563eb);
        display: block;
        margin-top: 8px;
    }
    
    .review-form {
        background: linear-gradient(135deg, rgba(37, 99, 235, 0.05) 0%, rgba(59, 130, 246, 0.03) 100%);
        border: 2px solid rgba(37, 99, 235, 0.2);
        border-radius: 16px;
        padding: 30px;
        margin-bottom: 40px;
        backdrop-filter: blur(10px);
    }
    
    .review-form label {
        display: block;
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 15px;
        color: var(--dark, #0f172a);
    }
    
    .star-picker {
        display: flex;
        gap: 12px;
        margin-bottom: 20px;
    }
    
    .star-picker .star {
        font-size: 2.5rem;
        cursor: pointer;
        color: #cbd5e1;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .star-picker .star:hover {
        color: #fbbf24;
        transform: scale(1.2) rotate(-15deg);
    }
    
    #review-comment {
        width: 100%;
        padding: 15px;
        border: 2px solid rgba(37, 99, 235, 0.2);
        border-radius: 10px;
        font-family: inherit;
        font-size: 1rem;
        resize: vertical;
        min-height: 100px;
        transition: border-color 0.3s;
        margin-bottom: 15px;
    }
    
    #review-comment:focus {
        outline: none;
        border-color: var(--primary, #2563eb);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }
    
    #submitReviewBtn {
        background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
        color: #fff;
        border: none;
        padding: 12px 30px;
        border-radius: 25px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);
    }
    
    #submitReviewBtn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(37, 99, 235, 0.4);
    }
    
    #submitReviewBtn:active {
        transform: translateY(0);
    }
    
    .review-login-note {
        background: rgba(37, 99, 235, 0.05);
        border: 2px dashed rgba(37, 99, 235, 0.3);
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        margin-bottom: 40px;
        color: var(--medium, #334155);
    }
    
    .review-login-note a {
        color: var(--primary, #2563eb);
        text-decoration: none;
        font-weight: 600;
        transition: color 0.3s;
    }
    
    .review-login-note a:hover {
        color: var(--secondary, #1e40af);
    }
    
    #reviewsList {
        display: grid;
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .review {
        background: #fff;
        border: 1px solid rgba(37, 99, 235, 0.1);
        border-radius: 12px;
        padding: 20px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }
    
    .review:hover {
        border-color: rgba(37, 99, 235, 0.3);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.1);
        transform: translateY(-2px);
    }
    
    .review b {
        display: block;
        font-size: 1.1rem;
        color: var(--dark, #0f172a);
        margin-bottom: 8px;
    }
    
    .review span {
        display: inline-block;
        color: #fbbf24;
        font-weight: 700;
        margin-bottom: 10px;
    }
    
    .review p {
        color: var(--text-light, #475569);
        line-height: 1.6;
        margin-bottom: 12px;
    }
    
    .review-time {
        color: #94a3b8;
        font-size: 0.85rem;
    }
    
    @media (max-width: 768px) {
        .shop-reviews h3 {
            font-size: 1.4rem;
        }
        
        .reviews-summary {
            grid-template-columns: 1fr;
            gap: 15px;
            padding: 20px;
        }
        
        .review-form {
            padding: 20px;
        }
        
        .star-picker .star {
            font-size: 2rem;
            gap: 8px;
        }
        
        #submitReviewBtn {
            width: 100%;
        }
    }
</style>

<section class="shop-reviews">
    <h3><i class="fas fa-star"></i> Customer Reviews</h3>
    <div class="reviews-summary">
        <div class="avg-rating">
            <span>Average Rating</span>
            <strong><?php echo $avg; ?> ★</strong>
        </div>
        <div class="reviews-count">
            <span>Total Reviews</span>
            <strong><?php echo $cnt; ?></strong>
        </div>
    </div>

    <?php if (isset($_SESSION['user_email'])): ?>
    <div class="review-form">
        <label for="review-comment"><i class="fas fa-pen-fancy"></i> Share Your Experience</label>
        <div class="star-picker" data-rating="5">
            <span class="star" data-value="1">☆</span>
            <span class="star" data-value="2">☆</span>
            <span class="star" data-value="3">☆</span>
            <span class="star" data-value="4">☆</span>
            <span class="star" data-value="5">☆</span>
        </div>
        <textarea id="review-comment" placeholder="Tell us about your experience with our service..." maxlength="500"></textarea>
        <button id="submitReviewBtn"><i class="fas fa-paper-plane"></i> Submit Review</button>
    </div>
    <?php else: ?>
    <div class="review-login-note">
        Please <a href="login.php"><i class="fas fa-sign-in-alt"></i> log in</a> to leave a review and share your experience!
    </div>
    <?php endif; ?>

    <div id="reviewsList">
        <?php while ($r = $listRes->fetch_assoc()): ?>
            <div class="review">
                <b><i class="fas fa-user-circle"></i> <?php echo htmlspecialchars($r['user_name']); ?></b>
                <span><?php for($i = 0; $i < (int)$r['rating']; $i++) echo '★'; ?></span>
                <p><?php echo nl2br(htmlspecialchars($r['comment'])); ?></p>
                <small class="review-time"><i class="fas fa-calendar"></i> <?php echo htmlspecialchars($r['created_at']); ?></small>
            </div>
        <?php endwhile; ?>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function(){
    const shop = <?php echo json_encode($shop_name); ?>;
    const stars = document.querySelectorAll('.star-picker .star');
    let selected = 5;
    if (stars.length) {
        stars.forEach(s => {
            s.addEventListener('click', function(){
                selected = parseInt(this.getAttribute('data-value'));
                stars.forEach(st => st.textContent = parseInt(st.getAttribute('data-value')) <= selected ? '★' : '☆');
            });
            s.addEventListener('mouseenter', function(){
                const hoverValue = parseInt(this.getAttribute('data-value'));
                stars.forEach(st => st.style.color = parseInt(st.getAttribute('data-value')) <= hoverValue ? '#fbbf24' : '#cbd5e1');
            });
        });
        
        document.querySelector('.star-picker').addEventListener('mouseleave', function(){
            stars.forEach(st => st.style.color = parseInt(st.getAttribute('data-value')) <= selected ? '#fbbf24' : '#cbd5e1');
        });
    }

    const submitBtn = document.getElementById('submitReviewBtn');
    if (submitBtn) {
        submitBtn.addEventListener('click', function(){
            const comment = document.getElementById('review-comment').value.trim();
            if (!comment) {
                alert('Please write a review');
                return;
            }
            if (selected < 1 || selected > 5) {
                alert('Please select a rating');
                return;
            }
            const formData = new FormData();
            formData.append('shop', shop);
            formData.append('rating', selected);
            formData.append('comment', comment);

            // Show loading state
            const origText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';

            fetch('submit_review.php', { method: 'POST', body: formData })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    alert('✓ Thank you! Your review has been submitted.');
                    location.reload();
                } else {
                    alert(data.message || 'Could not submit review');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = origText;
                }
            }).catch(err => {
                alert('Network error. Please try again.');
                submitBtn.disabled = false;
                submitBtn.innerHTML = origText;
            });
        });
    }
});
</script>

<?php
$listStmt->close();
?>
