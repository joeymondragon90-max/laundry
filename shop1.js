document.addEventListener('DOMContentLoaded', function() {
    // Chat button (optional alert for now)
    document.getElementById('chatBtn').onclick = function() {
        alert("Chat feature coming soon!");
    };

    // Dynamically fill services in order modal from services table
    function populateServiceOptions() {
        const servicesTable = document.getElementById('servicesTable');
        const serviceSelect = document.getElementById('orderService');
        serviceSelect.innerHTML = '<option value="">Select Service</option>'; // Reset

        if (servicesTable) {
            const rows = servicesTable.querySelectorAll('tbody tr');
            rows.forEach(row => {
                const serviceName = row.children[0].textContent.trim();
                const description = row.children[1].textContent.trim();
                const price = row.children[2].textContent.trim();
                const option = document.createElement('option');
                option.value = serviceName;
                option.textContent = `${serviceName} (${description}, ${price})`;
                serviceSelect.appendChild(option);
            });
        }
    }

    // Modal open/close
    const orderBtn = document.getElementById('orderBtn');
    const orderModal = document.getElementById('orderModal');
    const closeOrderModal = document.getElementById('closeOrderModal');

    orderBtn.onclick = function() {
        populateServiceOptions();
        orderModal.classList.add('active');
    };

    closeOrderModal.onclick = function() {
        orderModal.classList.remove('active');
    };

    // Optional: close modal when clicking outside modal-content
    orderModal.addEventListener('click', function(e) {
        if (e.target === orderModal) {
            orderModal.classList.remove('active');
        }
    });

    // Handle order form submit
    document.getElementById('orderForm').onsubmit = function(e) {
        e.preventDefault();
        // Here you would implement backend logic
        alert("Order submitted!\n\nName: " + document.getElementById('orderName').value +
              "\nLocation: " + document.getElementById('orderLocation').value +
              "\nService: " + document.getElementById('orderService').value +
              "\nWeight: " + document.getElementById('orderKg').value);
        orderModal.classList.remove('active');
        this.reset();
    };
});

  // Image modal (lightbox)
  const imgModal = document.getElementById('imgModal');
  const imgModalImg = document.getElementById('imgModalImg');
  const imgModalClose = document.getElementById('imgModalClose');

  document.querySelectorAll('.photo-link').forEach(link => {
      link.addEventListener('click', function(e) {
          e.preventDefault();
          imgModalImg.src = this.href;
          imgModal.classList.add('active');
      });
  });
  imgModalClose.onclick = function() {
      imgModal.classList.remove('active');
      imgModalImg.src = '';
  };
  imgModal.onclick = function(e) {
      if (e.target === imgModal) {
          imgModal.classList.remove('active');
          imgModalImg.src = '';
      }
  };