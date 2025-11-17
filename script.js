document.addEventListener('DOMContentLoaded', function() {
    // Shop card hover effect
    const shopCards = document.querySelectorAll('.shop-card');
    shopCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.boxShadow = '0 10px 25px rgba(0, 0, 0, 0.1)';
        });
        card.addEventListener('mouseleave', function() {
            this.style.boxShadow = '0 5px 15px rgba(0, 0, 0, 0.05)';
        });
    });

    // Map points (if any)
    const mapPoints = document.querySelectorAll('.map-point');
    mapPoints.forEach(point => {
        point.addEventListener('click', function() {
            const shopName = this.getAttribute('title');
            alert(`You selected ${shopName}. Details would be shown here.`);
        });
    });

    // Login modal logic
    let isLoggedIn = localStorage.getItem('isLoggedIn') === 'true';

    function showLoginModal() {
        document.getElementById('loginModal').classList.add('active');
        document.getElementById('loginBtn').style.display = '';
        document.getElementById('logoutBtn').style.display = 'none';
    }

    function showPageContent() {
        document.getElementById('loginModal').classList.remove('active');
        document.getElementById('pageContent').style.display = '';
        document.getElementById('loginBtn').style.display = 'none';
        document.getElementById('logoutBtn').style.display = '';
    }

    if (isLoggedIn) {
        showPageContent();
    } else {
        showLoginModal();
    }
    const tabLogin = document.getElementById('tabLogin');
    const tabSignup = document.getElementById('tabSignup');
    const formLogin = document.getElementById('formLogin');
    const formSignup = document.getElementById('formSignup');
    const goToSignup = document.getElementById('goToSignup');
    const goToLogin = document.getElementById('goToLogin');

    tabLogin.addEventListener('click', function() {
        tabLogin.classList.add('active');
        tabSignup.classList.remove('active');
        formLogin.classList.add('active');
        formSignup.classList.remove('active');
    });
    tabSignup.addEventListener('click', function() {
        tabSignup.classList.add('active');
        tabLogin.classList.remove('active');
        formSignup.classList.add('active');
        formLogin.classList.remove('active');
    });
    goToSignup.addEventListener('click', function(e) {
        e.preventDefault();
        tabSignup.click();
    });
    goToLogin.addEventListener('click', function(e) {
        e.preventDefault();
        tabLogin.click();
    });
    document.getElementById('closeModal').addEventListener('click', function(e) {
        e.preventDefault();
        if (!isLoggedIn) {
            showLoginModal();
        }
    });

    formLogin.addEventListener('submit', function(e) {
        e.preventDefault();
        isLoggedIn = true;
        localStorage.setItem('isLoggedIn', 'true');
        showPageContent();
    });

    formSignup.addEventListener('submit', function(e) {
        e.preventDefault();
        isLoggedIn = true;
        localStorage.setItem('isLoggedIn', 'true');
        showPageContent();
    });

    document.getElementById('loginBtn').addEventListener('click', function(e) {
        e.preventDefault();
        showLoginModal();
    });

    document.getElementById('logoutBtn').addEventListener('click', function(e) {
        e.preventDefault();
        isLoggedIn = false;
        localStorage.setItem('isLoggedIn', 'false');
        showLoginModal();
    });

    // Image modal (lightbox)
    const imgModal = document.getElementById('imgModal');
    const imgModalImg = document.getElementById('imgModalImg');
    const imgModalClose = document.getElementById('imgModalClose');

    document.querySelectorAll('.photo-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            imgModalImg.src = this.href;
            imgModal.style.display = "flex";
        });
    });
    imgModalClose.onclick = function() {
        imgModal.style.display = "none";
        imgModalImg.src = '';
    };
    imgModal.onclick = function(e) {
        // Close if user clicks outside the image
        if (e.target === imgModal) {
            imgModal.style.display = "none";
            imgModalImg.src = '';
        }
    };
});