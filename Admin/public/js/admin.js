// Global Data Store
let services = [];
let parts = [];
let users = [];

const API_URL = 'index.php?request=api&action=';

// --- INITIALIZATION ---
document.addEventListener('DOMContentLoaded', () => {
    loadData();
});

function loadData() {
    fetch('index.php?request=api&action=get_all')
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                showToast(data.error, 'danger');
                return;
            }
            services = data.services;
            parts = data.parts;
            users = data.users;

            // Stats
            document.getElementById('stat-services').innerText = data.stats.services;
            document.getElementById('stat-users').innerText = data.stats.users;
            document.getElementById('stat-stock').innerText = data.stats.low_stock;

            renderAll();
        })
        .catch(err => console.error('Error loading data:', err));
}

// --- NAVIGATION & RESPONSIVENESS ---
function toggleMobileMenu() {
    const navLinks = document.getElementById('navLinks');
    navLinks.classList.toggle('open');
}

function showSection(sectionId, navElement) {
    document.querySelectorAll('.section-view').forEach(sec => sec.classList.remove('active'));
    document.getElementById(sectionId).classList.add('active');

    document.getElementById('navLinks').classList.remove('open');

    // Update active class on nav
    if (navElement) {
        document.querySelectorAll('.nav-link').forEach(item => item.classList.remove('active'));
        navElement.classList.add('active');
    }
}

// --- RENDERING LOGIC ---
function renderAll() {
    renderServices();
    renderParts();
    renderUsers();
}

function renderServices() {
    const tbody = document.getElementById('services-table-body');
    tbody.innerHTML = '';
    services.forEach(s => {
        tbody.innerHTML += `
            <tr>
                <td>#${s.id}</td>
                <td>${s.name}</td>
                <td>৳${parseFloat(s.price).toFixed(2)}</td>
                <td>
                    <button class="btn btn-sm btn-primary" onclick="openModal('service', ${s.id})"><i class="fas fa-edit"></i></button>
                    <button class="btn btn-sm btn-danger" onclick="deleteItem('service', ${s.id})"><i class="fas fa-trash"></i></button>
                </td>
            </tr>
        `;
    });
}

function renderParts() {
    const tbody = document.getElementById('parts-table-body');
    tbody.innerHTML = '';
    parts.forEach(p => {
        let stock = parseInt(p.stock);
        let stockClass = stock < 5 ? 'stock-low' : 'stock-ok';
        let stockText = stock < 5 ? 'Low Stock' : 'In Stock';

        // Handle missing image
        let img = p.image_url || 'https://via.placeholder.com/50';

        tbody.innerHTML += `
            <tr>
                <td><img src="${img}" class="part-img" alt="Part" onerror="this.src='https://via.placeholder.com/50'"></td>
                <td>${p.name}</td>
                <td>৳${parseFloat(p.price).toFixed(2)}</td>
                <td><span class="stock-badge ${stockClass}">${stock} (${stockText})</span></td>
                <td>
                    <button class="btn btn-sm btn-primary" onclick="openModal('part', ${p.id})"><i class="fas fa-edit"></i></button>
                    <button class="btn btn-sm btn-danger" onclick="deleteItem('part', ${p.id})"><i class="fas fa-trash"></i></button>
                </td>
            </tr>
        `;
    });
}

function renderUsers() {
    const tbody = document.getElementById('users-table-body');
    tbody.innerHTML = '';
    users.forEach(u => {
        tbody.innerHTML += `
            <tr>
                <td>#${u.id}</td>
                <td>${u.name}</td>
                <td>${u.email}</td>
                <td><span style="font-weight:500; color:var(--primary)">${u.role}</span></td>
                <td>
                    ${u.role.toLowerCase() !== 'admin' ?
                `<button class="btn btn-sm btn-danger" onclick="deleteItem('user', ${u.id})"><i class="fas fa-trash"></i></button>` :
                '<span class="text-muted" style="font-size:0.8rem;">Protected</span>'}
                </td>
            </tr>
        `;
    });
}

// --- CURD MODAL LOGIC ---
function openModal(type, id = null) {
    const modal = document.getElementById('modal-overlay');
    const formFields = document.getElementById('form-fields');
    const title = document.getElementById('modal-title');
    const editIdInput = document.getElementById('edit-id');
    const typeInput = document.getElementById('item-type');

    typeInput.value = type;
    editIdInput.value = id || '';
    formFields.innerHTML = '';

    let data = null;
    if (id) {
        if (type === 'service') data = services.find(x => x.id == id);
        if (type === 'part') data = parts.find(x => x.id == id);
        if (type === 'user') data = users.find(x => x.id == id);
        title.innerText = `Edit ${type.charAt(0).toUpperCase() + type.slice(1)}`;
    } else {
        title.innerText = `Add ${type.charAt(0).toUpperCase() + type.slice(1)}`;
    }

    if (type === 'service') {
        formFields.innerHTML = `
            <div class="form-group">
                <label>Service Name</label>
                <input type="text" name="name" class="form-control" required value="${data ? data.name : ''}">
            </div>
            <div class="form-group">
                <label>Price (৳)</label>
                <input type="number" step="0.01" name="price" class="form-control" required value="${data ? data.price : ''}">
            </div>
        `;
    } else if (type === 'part') {
        formFields.innerHTML = `
            <div class="form-group">
                <label>Part Name</label>
                <input type="text" name="name" class="form-control" required value="${data ? data.name : ''}">
            </div>
            <div class="form-group">
                <label>Price ($)</label>
                <input type="number" step="0.01" name="price" class="form-control" required value="${data ? data.price : ''}">
            </div>
            <div class="form-group">
                <label>Stock Count</label>
                <input type="number" name="stock" class="form-control" required value="${data ? (data.stock || 0) : ''}">
            </div>
            <div class="form-group">
                <label>Image URL (Optional)</label>
                <input type="text" name="img" class="form-control" placeholder="https://..." value="${data ? (data.image_url || '') : ''}">
            </div>
        `;
    } else if (type === 'user') {
        formFields.innerHTML = `
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="name" class="form-control" required value="${data ? data.name : ''}">
            </div>
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" class="form-control" required value="${data ? data.email : ''}">
            </div>
            <div class="form-group">
                <label>Role</label>
                <select name="role" class="form-control">
                    <option value="Customer" ${data && data.role === 'Customer' ? 'selected' : ''}>Customer</option>
                    <option value="CarOwner" ${data && data.role === 'CarOwner' ? 'selected' : ''}>CarOwner</option>
                    <option value="Admin" ${data && data.role === 'Admin' ? 'selected' : ''}>Admin</option>
                </select>
            </div>
        `;
    }

    modal.classList.add('open');
}

function closeModal() {
    document.getElementById('modal-overlay').classList.remove('open');
}

function handleFormSubmit(e) {
    e.preventDefault();
    const formData = new FormData(e.target);
    const type = document.getElementById('item-type').value;
    const id = document.getElementById('edit-id').value; // if present, it's update

    // Append ID manually to formData if needed, but fetch sends form data
    if (id) formData.append('id', id);

    let action = '';
    if (type === 'service') action = 'save_service';
    if (type === 'part') action = 'save_part';
    if (type === 'user') action = 'save_user';

    fetch(`index.php?request=api&action=${action}`, {
        method: 'POST',
        body: formData
    })
        .then(r => r.json())
        .then(resp => {
            if (resp.success) {
                showToast(resp.message || 'Saved successfully');
                closeModal();
                loadData(); // Re-fetch to update table
            } else {
                showToast(resp.error || 'Operation failed', 'danger');
            }
        })
        .catch(err => {
            console.error(err);
            showToast('Server error', 'danger');
        });
}

function deleteItem(type, id) {
    if (!confirm('Are you sure you want to delete this?')) return;

    let action = '';
    if (type === 'service') action = 'delete_service';
    if (type === 'part') action = 'delete_part';
    if (type === 'user') action = 'delete_user';

    const formData = new FormData();
    formData.append('id', id);

    fetch(`index.php?request=api&action=${action}`, {
        method: 'POST',
        body: formData
    })
        .then(r => r.json())
        .then(resp => {
            if (resp.success) {
                showToast('Item deleted.');
                loadData();
            } else {
                showToast(resp.error || 'Delete failed', 'danger');
            }
        })
        .catch(err => {
            console.error(err);
            showToast('Server error', 'danger');
        });
}

// --- UTILS ---
function showToast(message, color = 'success') {
    const container = document.getElementById('toast-container');
    const toast = document.createElement('div');
    toast.className = 'toast';
    toast.style.borderLeftColor = color === 'success' ? 'var(--success)' : 'var(--danger)';

    const icon = color === 'success' ? 'fa-check-circle' : 'fa-trash-alt';
    const iconColor = color === 'success' ? 'var(--success)' : 'var(--danger)';

    toast.innerHTML = `
        <i class="fas ${icon}" style="color: ${iconColor}; font-size: 1.2rem;"></i>
        <span>${message}</span>
    `;
    container.appendChild(toast);
    setTimeout(() => { toast.remove(); }, 3000);
}

function filterTable(tbodyId, text) {
    const filter = text.toLowerCase();
    const trs = document.getElementById(tbodyId).getElementsByTagName('tr');
    for (let i = 0; i < trs.length; i++) {
        let txtValue = trs[i].textContent || trs[i].innerText;
        if (txtValue.toLowerCase().indexOf(filter) > -1) {
            trs[i].style.display = "";
        } else {
            trs[i].style.display = "none";
        }
    }
}
