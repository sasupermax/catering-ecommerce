import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Registrar componentes Alpine ANTES de iniciar
Alpine.data('bulkSelection', () => ({
    selected: [],
    allSelected: false,
    productIds: [],
    
    init() {
        this.productIds = this.getAllProductIds();
    },
    
    getAllProductIds() {
        const checkboxes = document.querySelectorAll('tbody input[type="checkbox"][data-product-id]');
        return Array.from(checkboxes)
            .map(cb => parseInt(cb.dataset.productId))
            .filter(id => !isNaN(id));
    },
    
    toggle(id) {
        const index = this.selected.indexOf(id);
        if (index === -1) {
            this.selected.push(id);
        } else {
            this.selected.splice(index, 1);
        }
        this.updateAllSelected();
        this.notifySelectionChange();
    },
    
    toggleAll(checked) {
        if (checked) {
            if (this.productIds.length === 0) {
                this.productIds = this.getAllProductIds();
            }
            this.selected = [...this.productIds];
        } else {
            this.selected = [];
        }
        this.allSelected = checked;
        this.notifySelectionChange();
    },
    
    updateAllSelected() {
        if (this.productIds.length === 0) {
            this.productIds = this.getAllProductIds();
        }
        this.allSelected = this.selected.length === this.productIds.length && this.productIds.length > 0;
    },
    
    notifySelectionChange() {
        window.dispatchEvent(new CustomEvent('selection-changed', {
            detail: { count: this.selected.length }
        }));
    }
}));

Alpine.start();

// Inicializar funcionalidades del admin
document.addEventListener('DOMContentLoaded', function() {
    initializeAdmin();
});

function initializeAdmin() {
    // Logout links
    document.querySelectorAll('[data-action="logout"]').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            this.closest('form').submit();
        });
    });
    
    // Formularios de eliminación con confirmación
    document.querySelectorAll('[data-action="delete-confirm"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            const message = this.dataset.confirmMessage || '¿Estás seguro de que deseas eliminar este elemento?';
            if (!confirm(message)) {
                e.preventDefault();
            }
        });
    });
    
    // Select de ofertas que cambia el label de descuento
    const discountTypeSelect = document.getElementById('discount_type');
    if (discountTypeSelect) {
        discountTypeSelect.addEventListener('change', updateDiscountLabel);
        updateDiscountLabel(); // Ejecutar al cargar
    }
    
    // Preview de imagen en banners
    const imageInputs = document.querySelectorAll('[data-action="preview-image"]');
    imageInputs.forEach(input => {
        input.addEventListener('change', function(e) {
            previewImage(e);
        });
    });
    
    // Select de items por página con auto-submit
    document.querySelectorAll('[data-action="auto-submit"]').forEach(select => {
        select.addEventListener('change', function() {
            this.form.submit();
        });
    });

}

// Función global para bulk actions (llamada desde Alpine)
window.bulkAction = function(action) {
    const component = Alpine.$data(document.querySelector('[x-data*="bulkSelection"]'));
    
    if (!component) {
        console.error('No se encontró el componente bulkSelection');
        return;
    }
    
    const selectedIds = component.selected;
    
    if (selectedIds.length === 0) {
        alert('Por favor, selecciona al menos un producto');
        return;
    }
    
    if (action === 'delete') {
        if (!confirm(`¿Estás seguro de eliminar ${selectedIds.length} producto(s)?`)) {
            return;
        }
    }
    
    // Obtener la URL de bulk-action desde el meta tag
    const bulkActionUrl = document.querySelector('meta[name="bulk-action-url"]')?.content;
    if (!bulkActionUrl) {
        console.error('No se encontró la URL de bulk-action');
        return;
    }
    
    // Crear formulario y enviar
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = bulkActionUrl;
    
    // CSRF Token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    if (csrfToken) {
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrfToken;
        form.appendChild(csrfInput);
    }
    
    // Action
    const actionInput = document.createElement('input');
    actionInput.type = 'hidden';
    actionInput.name = 'action';
    actionInput.value = action;
    form.appendChild(actionInput);
    
    // IDs
    selectedIds.forEach(id => {
        const idInput = document.createElement('input');
        idInput.type = 'hidden';
        idInput.name = 'ids[]';
        idInput.value = id;
        form.appendChild(idInput);
    });
    
    document.body.appendChild(form);
    form.submit();
};

function updateDiscountLabel() {
    const discountType = document.getElementById('discount_type');
    const discountLabel = document.getElementById('discount_label');
    
    if (!discountType || !discountLabel) return;
    
    if (discountType.value === 'percentage') {
        discountLabel.textContent = 'Descuento (%)';
    } else {
        discountLabel.textContent = 'Descuento ($)';
    }
}

function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function() {
        const preview = document.getElementById('image_preview');
        if (preview) {
            preview.src = reader.result;
            preview.classList.remove('hidden');
        }
    };
    if (event.target.files[0]) {
        reader.readAsDataURL(event.target.files[0]);
    }
}
