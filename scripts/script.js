// Toggle nav on hamburger click
document.addEventListener("DOMContentLoaded", function () {
    const hamburgerBtn = document.getElementById('hamburgerBtn');
    const nav = document.getElementById('mainNav');

    if (hamburgerBtn && nav) {
        hamburgerBtn.addEventListener('click', () => {
            nav.classList.toggle('show');
        });

        // Close nav if clicking outside
        document.addEventListener('click', function (event) {
            if (!nav.contains(event.target) && !hamburgerBtn.contains(event.target)) {
                nav.classList.remove('show');
            }
        });
    }
});

// Toggle password on click on register and login page
document.addEventListener("DOMContentLoaded", function () {
    function setupPasswordToggle(toggleId, inputId) {
        const toggleIcon = document.getElementById(toggleId);
        const inputField = document.getElementById(inputId);

        if (toggleIcon && inputField) {
            toggleIcon.addEventListener("click", function () {
                const isPassword = inputField.type === "password";
                inputField.type = isPassword ? "text" : "password";
                this.classList.toggle("bi-eye-slash-fill", isPassword);
                this.classList.toggle("bi-eye-fill", !isPassword);
            });
        }
    }

    // Login
    setupPasswordToggle("login_togglePassword", "login_password");

    // Register
    setupPasswordToggle("register_togglePassword", "register_password");
    setupPasswordToggle("register_toggleConfirmPassword", "register_confirm_password");

    // Modals (class-based)
    document.querySelectorAll(".toggle-password").forEach(function (toggleIcon) {
        toggleIcon.addEventListener("click", function () {
            const passwordInput = this.parentElement.querySelector(".password-input");
            if (passwordInput) {
                const isPassword = passwordInput.type === "password";
                passwordInput.type = isPassword ? "text" : "password";
                this.classList.toggle("bi-eye-fill", !isPassword);
                this.classList.toggle("bi-eye-slash-fill", isPassword);
            }
        });
    });
});

// Toggle password on click manage users page
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".toggle-password").forEach(function (toggleIcon) {
        toggleIcon.addEventListener("click", function () {
            const passwordInput = this.parentElement.querySelector(".password-input");
            if (passwordInput) {
                const isPassword = passwordInput.type === "password";
                passwordInput.type = isPassword ? "text" : "password";

                // Toggle eye / eye-slash icons
                this.classList.toggle("bi-eye-fill", !isPassword);
                this.classList.toggle("bi-eye-slash-fill", isPassword);
            }
        });
    });
});

// Toggle button left-right
// Get elements
const authLeft = document.querySelector('.auth-left');
const authRight = document.querySelector('.auth-right');
const showRightBtn = document.querySelector('.show-right');
const showLeftBtn = document.querySelector('.show-left');

// Set initial state (show left by default)
function setInitialView() {
  if (window.innerWidth < 768) {
    authLeft.classList.add('active');
    authRight.classList.remove('active');
  } else {
    // On wider screens, show both
    authLeft.classList.add('active');
    authRight.classList.add('active');
  }
}

// Event Listeners
showRightBtn?.addEventListener('click', () => {
  authLeft.classList.remove('active');
  authRight.classList.add('active');
});

showLeftBtn?.addEventListener('click', () => {
  authRight.classList.remove('active');
  authLeft.classList.add('active');
});

// Responsive toggle
window.addEventListener('resize', setInitialView);
window.addEventListener('DOMContentLoaded', setInitialView);

// User management script:
// Edit + Delete modal population
document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".edit-btn").forEach(btn => {
        btn.addEventListener("click", () => {
            document.getElementById("editUserId").value = btn.dataset.id;
            document.getElementById("editUsername").value = btn.dataset.username;
            document.getElementById("editEmail").value = btn.dataset.email;
            document.getElementById("editRole").value = btn.dataset.role;

            const pic = btn.dataset.pic;
            const preview = document.getElementById("editPicPreview");
            preview.src = pic ? "uploads/" + pic : "uploads/default_img.png";
        });
    });

    document.querySelectorAll(".delete-btn").forEach(btn => {
        btn.addEventListener("click", () => {
            document.getElementById("deleteUserId").value = btn.dataset.id;
            document.getElementById("deleteUsername").textContent = btn.dataset.username;
        });
    });

    // Full image preview modal
    const thumbnails = document.querySelectorAll(".profile-thumbnail");
    const fullImage = document.getElementById("fullImage");
    const modal = new bootstrap.Modal(document.getElementById("imageModal"));

    thumbnails.forEach(img => {
        img.addEventListener("click", () => {
            fullImage.src = img.src;
            modal.show();
        });
    });

    // Search toggle and filtering (users page)
    const searchToggle = document.getElementById("searchToggle");
    const searchBarContainer = document.getElementById("searchBarContainer");
    const userSearch = document.getElementById("userSearch");
    const searchClear = document.getElementById("searchClear");
    const tableRows = document.querySelectorAll("table tbody tr");
    const noUsersMessage = document.getElementById("noUsersMessage");

    // Live filter function
    function filterUsers(term) {
        term = term.toLowerCase();
        let visibleCount = 0;

        tableRows.forEach(row => {
            const username = row.children[0]?.textContent.toLowerCase() || "";
            const email = row.children[1]?.textContent.toLowerCase() || "";
            const role = row.children[2]?.textContent.toLowerCase() || "";

            const match = username.includes(term) || email.includes(term) || role.includes(term);
            row.style.display = match ? "" : "none";

            if (match) visibleCount++;
        });

        noUsersMessage.style.display = visibleCount === 0 ? "block" : "none";
    }

    // Show search bar, hide toggle button
    if (searchToggle && userSearch) {
        
        searchToggle.addEventListener("click", () => {
            searchToggle.style.display = "none";
            searchBarContainer.style.display = "flex";
            userSearch.focus();
        });

        
        // Clicking the search icon inside input clears and hides search bar
        searchClear.addEventListener("click", () => {
            userSearch.value = "";
            filterUsers("");
            searchBarContainer.style.display = "none";
            searchToggle.style.display = "inline-block";
        });

        // Filter users as you type
        userSearch.addEventListener("input", (e) => {
            filterUsers(e.target.value);
        });

        // Hide search bar on clicking outside
        document.addEventListener("click", (e) => {
            if (!searchBarContainer.contains(e.target) && !searchToggle.contains(e.target)) {
                if (searchBarContainer.style.display === "flex") {
                    userSearch.value = "";
                    filterUsers("");
                    searchBarContainer.style.display = "none";
                    searchToggle.style.display = "inline-block";
                }
            }
        });
    }
});

// CSV / XLS / XML Download
function downloadUsersAsCSV() {
    const rows = document.querySelectorAll("table tr");
    let csv = "";

    rows.forEach(row => {
        const cols = row.querySelectorAll("th, td");
        let rowData = [];
        cols.forEach(col => {
            let text = col.innerText.replace(/"/g, '""');
            if (text.includes(",") || text.includes('"')) {
                text = `"${text}"`;
            }
            rowData.push(text);
        });
        csv += rowData.join(",") + "\n";
    });

    const blob = new Blob([csv], { type: "text/csv;charset=utf-8;" });
    const link = document.createElement("a");
    link.href = URL.createObjectURL(blob);
    link.download = "users.csv";
    link.click();
}

function downloadUsersAsXLS() {
    const table = document.querySelector("table");
    const wb = XLSX.utils.table_to_book(table, { sheet: "Users" });
    XLSX.writeFile(wb, "users.xlsx");
}

function downloadUsersAsXML() {
    const rows = document.querySelectorAll("table tbody tr");
    let xml = '<?xml version="1.0" encoding="UTF-8"?>\n<users>\n';

    rows.forEach(row => {
        const cols = row.querySelectorAll("td");
        if (cols.length >= 4) {
            xml += `  <user>\n`;
            xml += `    <username>${cols[0].innerText}</username>\n`;
            xml += `    <email>${cols[1].innerText}</email>\n`;
            xml += `    <role>${cols[2].innerText}</role>\n`;
            xml += `    <profilePic>${cols[3].querySelector("img")?.src || ''}</profilePic>\n`;
            xml += `  </user>\n`;
        }
    });

    xml += '</users>';

    const blob = new Blob([xml], { type: "application/xml" });
    const link = document.createElement("a");
    link.href = URL.createObjectURL(blob);
    link.download = "users.xml";
    link.click();
}
// Product management script:
document.addEventListener("DOMContentLoaded", function () {
    // Edit button logic
    document.querySelectorAll(".edit-btn").forEach(btn => {
    btn.addEventListener("click", () => {
        document.getElementById("editProductId").value = btn.dataset.id;
        document.getElementById("editName").value = btn.dataset.name;
        document.getElementById("editPrice").value = btn.dataset.price;
        document.getElementById("editQuantity").value = btn.dataset.quantity;
        document.getElementById("editDescription").value = btn.dataset.description;
        document.getElementById("editImagePreview").src = btn.dataset.image || "images/default.png";
        });
    });

    // Delete button logic
    document.querySelectorAll(".delete-btn").forEach(btn => {
        btn.addEventListener("click", () => {
          document.getElementById("deleteProductId").value = btn.dataset.id;
          document.getElementById("deleteProductName").textContent = btn.dataset.name;
        });
    });

    // Image preview
    const images = document.querySelectorAll(".product-thumbnail");
    const fullImage = document.getElementById("fullImage");
    const modal = new bootstrap.Modal(document.getElementById("imageModal"));

    images.forEach(img => {
    img.addEventListener("click", () => {
      fullImage.src = img.src;
      modal.show();
    });
    });

    // Search toggle and filtering (products page)
    const searchToggle = document.getElementById("searchToggle");
    const searchBarContainer = document.getElementById("searchBarContainer");
    const productSearch = document.getElementById("productSearch");
    const searchClear = document.getElementById("searchClear");
    const tableRows = document.querySelectorAll("table tbody tr");
    const noProductsMessage = document.getElementById("noProductsMessage");

    searchToggle.addEventListener("click", () => {
        searchToggle.style.display = "none";
        searchBarContainer.style.display = "flex";
        productSearch.focus();
    });

    searchClear.addEventListener("click", () => {
        productSearch.value = "";
        filterProducts("");
        searchBarContainer.style.display = "none";
        searchToggle.style.display = "inline-block";
    });

    function filterProducts(term) {
        term = term.toLowerCase();
        let visibleCount = 0;

        tableRows.forEach(row => {
          const name = row.children[0]?.textContent.toLowerCase() || "";
          const price = row.children[1]?.textContent.toLowerCase() || "";
          const desc = row.children[3]?.textContent.toLowerCase() || "";

          const match = name.includes(term) || price.includes(term) || desc.includes(term);
          row.style.display = match ? "" : "none";

          if (match) visibleCount++;
        });
        noProductsMessage.style.display = visibleCount === 0 ? "block" : "none";
    }

    productSearch.addEventListener("input", (e) => {
        filterProducts(e.target.value);
    });

    document.addEventListener("click", (e) => {
        if (!searchBarContainer.contains(e.target) && !searchToggle.contains(e.target)) {
            if (searchBarContainer.style.display === "flex") {
            productSearch.value = "";
            filterProducts("");
            searchBarContainer.style.display = "none";
            searchToggle.style.display = "inline-block";
            }
        }
        });
    });

// Download: CSV
    function downloadProductsAsCSV() {
        const rows = document.querySelectorAll("table tr");
        let csv = "";

        rows.forEach(row => {
            const cols = row.querySelectorAll("th, td");
            let rowData = [];
            cols.forEach(col => {
                let text = col.innerText.replace(/"/g, '""');
                if (text.includes(",") || text.includes('"')) {
                    text = `"${text}"`;
                }
                rowData.push(text);
            });
        csv += rowData.join(",") + "\n";
        });

        const blob = new Blob([csv], { type: "text/csv;charset=utf-8;" });
        const link = document.createElement("a");
        link.href = URL.createObjectURL(blob);
        link.download = "products.csv";
        link.click();
    }

// Download: XLS
function downloadProductsAsXLS() {
    const table = document.querySelector("table");
    const wb = XLSX.utils.table_to_book(table, { sheet: "Products" });
    XLSX.writeFile(wb, "products.xlsx");
}

// Download: XML
function downloadProductsAsXML() {
    const rows = document.querySelectorAll("table tbody tr");
    let xml = '<?xml version="1.0" encoding="UTF-8"?>\n<products>\n';

    rows.forEach(row => {
        const cols = row.querySelectorAll("td");
        if (cols.length >= 5) {
            xml += `  <product>\n`;
            xml += `    <name>${cols[0].innerText}</name>\n`;
            xml += `    <price>${cols[1].innerText.replace('$', '')}</price>\n`;
            xml += `    <quantity>${cols[2].innerText}</quantity>\n`;
            xml += `    <description>${cols[3].innerText}</description>\n`;
            xml += `  </product>\n`;
        }
    });

    xml += '</products>';

    const blob = new Blob([xml], { type: "application/xml" });
    const link = document.createElement("a");
    link.href = URL.createObjectURL(blob);
    link.download = "products.xml";
    link.click();
}

// Order management script:
function downloadAsCSV() {
    const table = document.querySelector("table");
    const rows = table.querySelectorAll("tr");
    let csvContent = "";

    rows.forEach(row => {
        const cols = row.querySelectorAll("th, td");
        const rowData = [];
        cols.forEach(col => {
            let text = col.innerText.replace(/"/g, '""');
            if (text.includes(',') || text.includes('"')) {
                text = `"${text}"`;
            }
            rowData.push(text);
        });
        csvContent += rowData.join(",") + "\n";
    });

    const blob = new Blob([csvContent], { type: "text/csv;charset=utf-8;" });
    const link = document.createElement("a");
    link.href = URL.createObjectURL(blob);
    link.download = "customer_orders.csv";
    link.click();
}

function downloadAsXLS() {
    const table = document.querySelector("table");
    const wb = XLSX.utils.table_to_book(table, { sheet: "Orders" });
    XLSX.writeFile(wb, "customer_orders.xlsx");
}

function downloadAsXML() {
    const rows = document.querySelectorAll("table tbody tr");
    let xml = '<?xml version="1.0" encoding="UTF-8"?>\n<orders>\n';

    rows.forEach(row => {
        const cols = row.querySelectorAll("td");
        if (cols.length >= 8) {
            xml += `  <order>\n`;
            xml += `    <customer_name>${cols[1].innerText}</customer_name>\n`;
            xml += `    <customer_email>${cols[2].innerText}</customer_email>\n`;
            xml += `    <customer_address>${cols[3].innerText}</customer_address>\n`;
            xml += `    <total_price>${cols[4].innerText.replace('$', '')}</total_price>\n`;
            xml += `    <status>${cols[5].innerText}</status>\n`;
            xml += `    <created_by>${cols[6].innerText}</created_by>\n`;
            xml += `    <created_at>${cols[7].innerText}</created_at>\n`;
            xml += `  </order>\n`;
        }
    });

    xml += '</orders>';

    const blob = new Blob([xml], { type: "application/xml" });
    const link = document.createElement("a");
    link.href = URL.createObjectURL(blob);
    link.download = "customer_orders.xml";
    link.click();
}

// Search functionality
document.addEventListener("DOMContentLoaded", () => {
    const searchToggle = document.getElementById("searchToggle");
    const searchBarContainer = document.getElementById("searchBarContainer");
    const orderSearch = document.getElementById("orderSearch");
    const searchClear = document.getElementById("searchClear");
    const tableRows = document.querySelectorAll("table tbody tr");
    const noOrdersMessage = document.getElementById("noOrdersMessage");

    searchToggle.addEventListener("click", () => {
        searchToggle.style.display = "none";
        searchBarContainer.style.display = "flex";
        orderSearch.focus();
    });

    searchClear.addEventListener("click", () => {
        orderSearch.value = "";
        filterOrders("");
        searchBarContainer.style.display = "none";
        searchToggle.style.display = "inline-block";
    });

    function filterOrders(term) {
        term = term.toLowerCase();
        let visibleCount = 0;

        tableRows.forEach(row => {
            const content = row.innerText.toLowerCase();
            const match = content.includes(term);
            row.style.display = match ? "" : "none";
            if (match) visibleCount++;
        });

        noOrdersMessage.style.display = visibleCount === 0 ? "block" : "none";
    }

    orderSearch.addEventListener("input", (e) => {
        filterOrders(e.target.value);
    });

    document.addEventListener("click", (e) => {
        if (!searchBarContainer.contains(e.target) && !searchToggle.contains(e.target)) {
            if (searchBarContainer.style.display === "flex") {
                orderSearch.value = "";
                filterOrders("");
                searchBarContainer.style.display = "none";
                searchToggle.style.display = "inline-block";
            }
        }
    });
});

// Support Management script
function downloadAsCSV() {
    const table = document.querySelector("table");
    const rows = table.querySelectorAll("tr");
    let csvContent = "";

    rows.forEach(row => {
        const cols = row.querySelectorAll("th, td");
        const rowData = [];
        cols.forEach(col => {
            let text = col.innerText.replace(/"/g, '""');
            if (text.includes(',') || text.includes('"')) {
                text = `"${text}"`;
            }
            rowData.push(text);
        });
        csvContent += rowData.join(",") + "\n";
    });

    const blob = new Blob([csvContent], { type: "text/csv;charset=utf-8;" });
    const link = document.createElement("a");
    link.href = URL.createObjectURL(blob);
    link.download = "customer_messages.csv";
    link.click();
}

function downloadAsXLS() {
    const table = document.querySelector("table");
    const wb = XLSX.utils.table_to_book(table, { sheet: "Customer Messages" });
    XLSX.writeFile(wb, "customer_messages.xlsx");
}

function downloadAsXML() {
    const rows = document.querySelectorAll("table tbody tr");
    let xml = '<?xml version="1.0" encoding="UTF-8"?>\n<supportMessages>\n';

    rows.forEach(row => {
        const cols = row.querySelectorAll("td");
        if (cols.length >= 5) {
            xml += `  <customer_message>\n`;
            xml += `    <full_name>${cols[1].innerText}</full_name>\n`;
            xml += `    <phone>${cols[2].innerText}</phone>\n`;
            xml += `    <address>${cols[3].innerText}</address>\n`;
            xml += `    <submitted_at>${cols[4].innerText}</submitted_at>\n`;
            xml += `  </customer_message>\n`;
        }
    });

    xml += '</supportMessages>';

    const blob = new Blob([xml], { type: "application/xml" });
    const link = document.createElement("a");
    link.href = URL.createObjectURL(blob);
    link.download = "customer_messages.xml";
    link.click();
}

document.addEventListener("DOMContentLoaded", () => {
    const searchToggle = document.getElementById("searchToggle");
    const searchBarContainer = document.getElementById("searchBarContainer");
    const supportSearch = document.getElementById("supportSearch");
    const searchClear = document.getElementById("searchClear");
    const tableRows = document.querySelectorAll("table tbody tr");
    const noSupportMessage = document.getElementById("noSupportMessage");

    searchToggle.addEventListener("click", () => {
        searchToggle.style.display = "none";
        searchBarContainer.style.display = "flex";
        supportSearch.focus();
    });

    searchClear.addEventListener("click", () => {
        supportSearch.value = "";
        filterSupport("");
        searchBarContainer.style.display = "none";
        searchToggle.style.display = "inline-block";
    });

    function filterSupport(term) {
        term = term.toLowerCase();
        let visibleCount = 0;

        tableRows.forEach(row => {
            const rowText = row.innerText.toLowerCase();
            const match = rowText.includes(term);
            row.style.display = match ? "" : "none";
            if (match) visibleCount++;
        });

        noSupportMessage.style.display = visibleCount === 0 ? "block" : "none";
    }

    supportSearch.addEventListener("input", (e) => {
        filterSupport(e.target.value);
    });

    document.addEventListener("click", (e) => {
        if (!searchBarContainer.contains(e.target) && !searchToggle.contains(e.target)) {
            if (searchBarContainer.style.display === "flex") {
                supportSearch.value = "";
                filterSupport("");
                searchBarContainer.style.display = "none";
                searchToggle.style.display = "inline-block";
            }
        }
    });
});

// my_orders page functionality:

function downloadAsCSV() {
    const table = document.querySelector("table");
    const rows = table.querySelectorAll("tr");
    let csvContent = "";

    rows.forEach(row => {
        const cols = row.querySelectorAll("th, td");
        const rowData = [];
        cols.forEach(col => {
            let text = col.innerText.replace(/"/g, '""');
            if (text.includes(',') || text.includes('"')) {
                text = `"${text}"`;
            }
            rowData.push(text);
        });
        csvContent += rowData.join(",") + "\n";
    });

    const blob = new Blob([csvContent], { type: "text/csv;charset=utf-8;" });
    const link = document.createElement("a");
    link.href = URL.createObjectURL(blob);
    link.download = "my_orders.csv";
    link.click();
}

function downloadAsXLS() {
    const table = document.querySelector("table");
    const wb = XLSX.utils.table_to_book(table, { sheet: "Orders" });
    XLSX.writeFile(wb, "my_orders.xlsx");
}

function downloadAsXML() {
    const rows = document.querySelectorAll("table tbody tr");
    let xml = '<?xml version="1.0" encoding="UTF-8"?>\n<orders>\n';

    rows.forEach(row => {
        const cols = row.querySelectorAll("td");
        if (cols.length >= 8) {
            xml += `  <order>\n`;
            xml += `    <customer_name>${cols[1].innerText}</customer_name>\n`;
            xml += `    <customer_email>${cols[2].innerText}</customer_email>\n`;
            xml += `    <customer_address>${cols[3].innerText}</customer_address>\n`;
            xml += `    <total_price>${cols[4].innerText.replace('$', '')}</total_price>\n`;
            xml += `    <status>${cols[5].innerText}</status>\n`;
            xml += `    <created_by>${cols[6].innerText}</created_by>\n`;
            xml += `    <created_at>${cols[7].innerText}</created_at>\n`;
            xml += `  </order>\n`;
        }
    });

    xml += '</orders>';

    const blob = new Blob([xml], { type: "application/xml" });
    const link = document.createElement("a");
    link.href = URL.createObjectURL(blob);
    link.download = "my_orders.xml";
    link.click();
}

// Searchbar functionality
document.addEventListener("DOMContentLoaded", () => {
    const searchToggle = document.getElementById("searchToggle");
    const searchBarContainer = document.getElementById("searchBarContainer");
    const orderSearch = document.getElementById("orderSearch");
    const searchClear = document.getElementById("searchClear");
    const tableRows = document.querySelectorAll("table tbody tr");
    const noOrdersMessage = document.getElementById("noOrdersMessage");

    searchToggle.addEventListener("click", () => {
        searchToggle.style.display = "none";
        searchBarContainer.style.display = "flex";
        orderSearch.focus();
    });

    searchClear.addEventListener("click", () => {
        orderSearch.value = "";
        filterOrders("");
        searchBarContainer.style.display = "none";
        searchToggle.style.display = "inline-block";
    });

    function filterOrders(term) {
        term = term.toLowerCase();
        let visibleCount = 0;

        tableRows.forEach(row => {
            const customer = row.children[1]?.textContent.toLowerCase() || "";
            const email = row.children[2]?.textContent.toLowerCase() || "";
            const status = row.children[5]?.textContent.toLowerCase() || "";

            const match = customer.includes(term) || email.includes(term) || status.includes(term);
            row.style.display = match ? "" : "none";

            if (match) visibleCount++;
        });

        noOrdersMessage.style.display = visibleCount === 0 ? "block" : "none";
    }

    orderSearch.addEventListener("input", (e) => {
        filterOrders(e.target.value);
    });

    document.addEventListener("click", (e) => {
        if (!searchBarContainer.contains(e.target) && !searchToggle.contains(e.target)) {
            if (searchBarContainer.style.display === "flex") {
                orderSearch.value = "";
                filterOrders("");
                searchBarContainer.style.display = "none";
                searchToggle.style.display = "inline-block";
            }
        }
    });
});

// products,product_details page functionality:

document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById("customImageModal");
    const modalImg = document.getElementById("modalImage");
    const closeBtn = document.getElementById("modalClose");

    // Show modal on image click
    document.querySelectorAll(".product-thumbnail").forEach(img => {
        img.addEventListener("click", () => {
            modalImg.src = img.src;
            modal.classList.add("show");
            modal.style.display = "block";
        });
    });

    // Close modal on close button click
    closeBtn.addEventListener("click", () => {
        modal.classList.remove("show");
        setTimeout(() => modal.style.display = "none", 300);
    });

    // Close modal on outside click
    window.addEventListener("click", (e) => {
        if (e.target === modal) {
            modal.classList.remove("show");
            setTimeout(() => modal.style.display = "none", 300);
        }
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const searchIcon = document.getElementById("toggleSearch");
    const searchBar = document.getElementById("searchBarContainer");

    searchIcon.addEventListener("click", function () {
        searchBar.classList.toggle("active");
        const input = searchBar.querySelector("input");

        // Hide the icon when bar is active
        searchIcon.style.display = searchBar.classList.contains("active") ? "none" : "inline";

        if (searchBar.classList.contains("active")) {
            input.focus();
        } else {
            input.value = '';
        }
    });

    // Live product filter + "no results" handling
    const productInput = document.getElementById("productSearch");
    const noResultsMessage = document.getElementById("noResultsMessage");

    productInput.addEventListener("input", function () {
        const searchTerm = this.value.toLowerCase();
        let anyVisible = false;

        document.querySelectorAll('.product-card').forEach(card => {
            const name = card.querySelector('h3').textContent.toLowerCase();
            const isMatch = name.includes(searchTerm);
            card.style.display = isMatch ? 'block' : 'none';
            if (isMatch) anyVisible = true;
        });

        noResultsMessage.style.display = anyVisible ? 'none' : 'block';
    });
});

// Clear password fields when modals are closed
document.addEventListener("DOMContentLoaded", function () {
    // Add modal IDs here if different
    const modals = ["addModal", "editModal"]; 

    modals.forEach(function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.addEventListener("hidden.bs.modal", function () {
                modal.querySelectorAll(".password-input").forEach(function(input) {
                    input.value = ""; // Clear value
                    input.type = "password"; // Reset type to password
                });

                modal.querySelectorAll(".toggle-password").forEach(function(icon) {
                    icon.classList.remove("bi-eye-slash-fill");
                    icon.classList.add("bi-eye-fill");
                });
            });
        }
    });
});
