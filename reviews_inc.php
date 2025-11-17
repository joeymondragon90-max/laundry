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
<section class="shop-reviews">
    <h3>Customer Reviews</h3>
    <div class="reviews-summary">
        <div class="avg-rating">Average: <strong><?php echo $avg; ?></strong></div>
        <div class="reviews-count"><?php echo $cnt; ?> review<?php echo $cnt !== 1 ? 's' : ''; ?></div>
    </div>

    <?php if (isset($_SESSION['user_email'])): ?>
    <div class="review-form">
        <label for="review-comment">Your review</label>
        <div class="star-picker" data-rating="5">
            <span class="star" data-value="1">☆</span>
            <span class="star" data-value="2">☆</span>
            <span class="star" data-value="3">☆</span>
            <span class="star" data-value="4">☆</span>
            <span class="star" data-value="5">☆</span>
        </div>
        <textarea id="review-comment" rows="3" placeholder="Write a short review..."></textarea>
        <button id="submitReviewBtn" class="btn">Submit Review</button>
    </div>
    <?php else: ?>
    <div class="review-login-note">Please <a href="login.php">log in</a> to leave a review.</div>
    <?php endif; ?>

    <div id="reviewsList">
        <?php while ($r = $listRes->fetch_assoc()): ?>
            <div class="review">
                <b><?php echo htmlspecialchars($r['user_name']); ?></b>
                <span>★ <?php echo (int)$r['rating']; ?></span>
                <p><?php echo nl2br(htmlspecialchars($r['comment'])); ?></p>
                <small class="review-time"><?php echo htmlspecialchars($r['created_at']); ?></small>
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
        });
    }

    const submitBtn = document.getElementById('submitReviewBtn');
    if (submitBtn) {
        submitBtn.addEventListener('click', function(){
            const comment = document.getElementById('review-comment').value.trim();
            if (selected < 1 || selected > 5) {
                alert('Please select a rating');
                return;
            }
            const formData = new FormData();
            formData.append('shop', shop);
            formData.append('rating', selected);
            formData.append('comment', comment);

            fetch('submit_review.php', { method: 'POST', body: formData })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    // simple approach: reload to show updated list and averages
                    location.reload();
                } else {
                    alert(data.message || 'Could not submit review');
                }
            }).catch(err => {
                alert('Network error');
            });
        });
    }
});
</script>

<?php
$listStmt->close();
?>
