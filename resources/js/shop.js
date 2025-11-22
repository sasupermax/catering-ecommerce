// ============================================
// SHOP.JS - Scripts para la tienda
// ============================================

// Función para formatear números con separadores de miles
function formatCurrency(amount) {
    const num = parseFloat(amount) || 0;
    return num.toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

// Esperar a que el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    initializeShop();
});

function initializeShop() {
    // Inicializar todas las funcionalidades
    initializeHeroCarousel();
    initializeCategoryModals();
    initializeLazyLoadProducts();
    initializeCart();
    initializeCheckout();
    initializePayment();
    initializeProduct();
    initializeDeleteForms();
    initializeErrorPages();
}

// ============================================
// HERO CAROUSEL
// ============================================

function initializeHeroCarousel() {
    const slidesContainer = document.querySelector('[data-carousel-slides]');
    if (!slidesContainer) return;

    const dots = document.querySelectorAll('.carousel-dot');
    const carouselWrapper = document.querySelector('.carousel-wrapper');
    const totalBanners = dots.length;
    
    if (totalBanners <= 1) return;

    // Obtener duración de transición desde el atributo data
    const container = document.querySelector('.hero-carousel-container');
    const transitionDuration = parseInt(container?.dataset.transitionDuration || 5000);

    let currentIndex = 0;
    let autoPlayInterval;
    let isPaused = false;

    function updateCarousel() {
        // Mover slides
        slidesContainer.style.transform = `translateX(-${currentIndex * 100}%)`;
        
        // Actualizar dots
        dots.forEach((dot, index) => {
            if (index === currentIndex) {
                dot.classList.remove('bg-white/50', 'w-3', 'h-3');
                dot.classList.add('bg-white', 'w-8', 'h-3');
            } else {
                dot.classList.remove('bg-white', 'w-8', 'h-3');
                dot.classList.add('bg-white/50', 'w-3', 'h-3');
            }
        });
    }

    function nextSlide() {
        currentIndex = (currentIndex + 1) % totalBanners;
        updateCarousel();
        resetAutoPlay();
    }

    function prevSlide() {
        currentIndex = (currentIndex - 1 + totalBanners) % totalBanners;
        updateCarousel();
        resetAutoPlay();
    }

    function goToSlide(index) {
        currentIndex = index;
        updateCarousel();
        resetAutoPlay();
    }

    function startAutoPlay() {
        stopAutoPlay();
        autoPlayInterval = setInterval(() => {
            if (!isPaused) {
                nextSlide();
            }
        }, transitionDuration);
    }

    function stopAutoPlay() {
        if (autoPlayInterval) {
            clearInterval(autoPlayInterval);
            autoPlayInterval = null;
        }
    }

    function resetAutoPlay() {
        stopAutoPlay();
        startAutoPlay();
    }

    // Event listeners para dots
    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            goToSlide(index);
        });
    });

    // Pause on hover
    if (carouselWrapper) {
        carouselWrapper.addEventListener('mouseenter', () => {
            isPaused = true;
        });

        carouselWrapper.addEventListener('mouseleave', () => {
            isPaused = false;
        });
    }

    // Touch/Swipe support for mobile
    let touchStartX = 0;
    let touchEndX = 0;

    if (carouselWrapper) {
        carouselWrapper.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
        });

        carouselWrapper.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        });
    }

    function handleSwipe() {
        if (touchEndX < touchStartX - 50) {
            nextSlide();
        }
        if (touchEndX > touchStartX + 50) {
            prevSlide();
        }
    }

    // Iniciar autoplay
    startAutoPlay();
}

// ============================================
// MODALES DE CATEGORÍA (Buscar, Filtrar, Ordenar)
// ============================================

function initializeCategoryModals() {
    // Función helper para abrir modal
    function openModal(modalName) {
        const modal = document.querySelector(`[data-modal="${modalName}"]`);
        if (modal) {
            modal.classList.remove('hidden');
        }
    }

    // Función helper para cerrar modal
    function closeModal(modalName) {
        const modal = document.querySelector(`[data-modal="${modalName}"]`);
        if (modal) {
            modal.classList.add('hidden');
        }
    }

    // Search Modal
    const openSearchBtn = document.querySelector('[data-action="open-search"]');
    const closeSearchBtn = document.querySelector('[data-action="close-search"]');
    const clearSearchBtn = document.querySelector('[data-action="clear-search"]');
    const searchModal = document.querySelector('[data-modal="search"]');

    if (openSearchBtn) {
        openSearchBtn.addEventListener('click', () => openModal('search'));
    }

    if (closeSearchBtn) {
        closeSearchBtn.addEventListener('click', () => closeModal('search'));
    }

    if (clearSearchBtn && clearSearchBtn.closest('form')) {
        clearSearchBtn.addEventListener('click', () => {
            const form = clearSearchBtn.closest('form');
            const searchInput = form.querySelector('input[name="search"]');
            if (searchInput) {
                searchInput.value = '';
            }
        });
    }

    if (searchModal) {
        searchModal.addEventListener('click', (e) => {
            if (e.target === searchModal) {
                closeModal('search');
            }
        });
    }

    // Filter Modal
    const openFilterBtn = document.querySelector('[data-action="open-filter"]');
    const closeFilterBtn = document.querySelector('[data-action="close-filter"]');
    const clearFilterBtn = document.querySelector('[data-action="clear-filter"]');
    const filterModal = document.querySelector('[data-modal="filter"]');

    if (openFilterBtn) {
        openFilterBtn.addEventListener('click', () => openModal('filter'));
    }

    if (closeFilterBtn) {
        closeFilterBtn.addEventListener('click', () => closeModal('filter'));
    }

    if (clearFilterBtn) {
        const categorySlug = clearFilterBtn.dataset.categorySlug || window.location.pathname.split('/').pop();
        clearFilterBtn.addEventListener('click', () => {
            window.location.href = `/categoria/${categorySlug}`;
        });
    }

    if (filterModal) {
        filterModal.addEventListener('click', (e) => {
            if (e.target === filterModal) {
                closeModal('filter');
            }
        });
    }

    // Sort Modal
    const openSortBtn = document.querySelector('[data-action="open-sort"]');
    const closeSortBtn = document.querySelector('[data-action="close-sort"]');
    const sortModal = document.querySelector('[data-modal="sort"]');

    if (openSortBtn) {
        openSortBtn.addEventListener('click', () => openModal('sort'));
    }

    if (closeSortBtn) {
        closeSortBtn.addEventListener('click', () => closeModal('sort'));
    }

    if (sortModal) {
        sortModal.addEventListener('click', (e) => {
            if (e.target === sortModal) {
                closeModal('sort');
            }
        });
    }
}

// ============================================
// LAZY LOAD DE PRODUCTOS (Cargar Más)
// ============================================

function initializeLazyLoadProducts() {
    const loadMoreBtn = document.querySelector('[data-action="load-more"]');
    if (!loadMoreBtn) return;

    const productsGrid = document.querySelector('[data-products-grid]');
    const productsCount = document.querySelector('[data-products-count]');
    const productsTotal = document.querySelector('[data-products-total]');
    const loadMoreText = document.querySelector('[data-load-more-text]');
    const loadMoreSpinner = document.querySelector('[data-load-more-spinner]');

    loadMoreBtn.addEventListener('click', async function() {
        const categorySlug = this.dataset.categorySlug;
        const nextPage = parseInt(this.dataset.nextPage);
        
        // Deshabilitar botón y mostrar spinner
        loadMoreBtn.disabled = true;
        loadMoreText.textContent = 'Cargando...';
        loadMoreSpinner.classList.remove('hidden');

        try {
            // Construir URL con parámetros actuales
            const url = new URL(window.location.href);
            url.searchParams.set('page', nextPage);

            const response = await fetch(url.toString(), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) throw new Error('Error al cargar productos');

            const data = await response.json();

            // Agregar productos al grid
            data.products.forEach(product => {
                const productCard = createProductCard(product);
                productsGrid.insertAdjacentHTML('beforeend', productCard);
            });

            // Actualizar contador
            const currentCount = parseInt(productsCount.textContent);
            productsCount.textContent = currentCount + data.products.length;

            // Actualizar página
            loadMoreBtn.dataset.currentPage = data.current_page;
            loadMoreBtn.dataset.nextPage = data.next_page;

            // Si no hay más productos, ocultar botón
            if (!data.has_more) {
                loadMoreBtn.style.display = 'none';
            }

        } catch (error) {
            console.error('Error:', error);
            alert('Error al cargar más productos. Por favor, intenta de nuevo.');
        } finally {
            // Restaurar botón
            loadMoreBtn.disabled = false;
            loadMoreText.textContent = 'Cargar más productos';
            loadMoreSpinner.classList.add('hidden');
        }
    });
}

// Función para crear HTML de tarjeta de producto
function createProductCard(product) {
    const imageHtml = product.image 
        ? `<img src="${product.image}" alt="${product.name}" class="w-full h-32 sm:h-48 p-4 object-contain bg-white" loading="lazy" style="view-transition-name: product-${product.slug};">`
        : `<div class="w-full h-32 sm:h-48 bg-gradient-to-br from-gray-100 to-red-400 flex items-center justify-center">
            <span class="text-4xl sm:text-6xl">🍴</span>
           </div>`;

    return `
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition product-card" data-product-id="${product.id}">
            ${imageHtml}
            <div class="p-3 sm:p-4">
                <h3 class="text-sm sm:text-lg font-semibold text-gray-700 mt-1 line-clamp-2">${product.name}</h3>
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mt-3 sm:mt-4 gap-2">
                    <div>
                        <span class="text-lg sm:text-2xl swissbold text-gray-800">$${parseFloat(product.price).toFixed(2)}</span>
                        <span class="text-xs text-gray-500 block sm:inline">${product.price_suffix}</span>
                    </div>
                </div>
                <a href="${product.url}" class="mt-3 block w-full bg-[#ffd90f] swissregular text-gray-800 px-3 sm:px-4 py-2 rounded-lg hover:bg-[#e2bf00] active:[#c9a700] transition text-xs sm:text-sm text-center">
                    Ver más
                </a>
            </div>
        </div>
    `;
}

// ============================================
// CARRITO DE COMPRAS
// ============================================

function initializeCart() {
    // Event listeners para botones del carrito
    document.querySelectorAll('[data-action="decrease-qty"]').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.productId;
            const step = parseFloat(this.dataset.step || 1);
            updateQuantity(productId, -step);
        });
    });

    document.querySelectorAll('[data-action="increase-qty"]').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.productId;
            const step = parseFloat(this.dataset.step || 1);
            updateQuantity(productId, step);
        });
    });

    document.querySelectorAll('[data-action="remove-item"]').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.productId;
            removeItem(productId);
        });
    });

    document.querySelectorAll('[data-action="update-qty-input"]').forEach(input => {
        input.addEventListener('change', function() {
            const productId = this.dataset.productId;
            updateQuantityInput(productId);
        });
        
        input.addEventListener('input', function() {
            if(this.value < 0) this.value = this.min;
        });
    });

    // Botón reload carrito
    const reloadButton = document.querySelector('[data-action="reload-cart"]');
    if (reloadButton) {
        reloadButton.addEventListener('click', function() {
            location.reload();
        });
    }

    // Prevenir submit si no alcanza el mínimo
    const checkoutBtn = document.querySelector('[data-action="checkout"]');
    if (checkoutBtn && checkoutBtn.dataset.disabled === 'true') {
        checkoutBtn.addEventListener('click', function(e) {
            e.preventDefault();
        });
    }
}

function updateQuantity(productId, change) {
    const input = document.getElementById(`qty-${productId}`);
    if (!input) return;
    
    const currentQty = parseFloat(input.value);
    const step = parseFloat(input.step);
    const min = parseFloat(input.min);
    const newQty = currentQty + change;

    if (newQty >= min) {
        const displayValue = (step === 0.5) ? newQty.toFixed(1) : Math.round(newQty);
        input.value = displayValue;
        saveQuantity(productId, newQty);
    }
}

function saveQuantity(productId, quantity) {
    fetch('/carrito/actualizar', {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ product_id: productId, quantity: quantity })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (data.reload) {
                location.reload();
                return;
            }
            
            // Actualizar subtotales
            const desktopSubtotal = document.getElementById(`subtotal-${productId}`);
            if (desktopSubtotal) {
                desktopSubtotal.textContent = `$${formatCurrency(data.subtotal)}`;
            }
            
            const mobileSubtotal = document.getElementById(`subtotal-mobile-${productId}`);
            if (mobileSubtotal) {
                mobileSubtotal.textContent = `$${formatCurrency(data.subtotal)}`;
            }
            
            // Actualizar subtotal del resumen
            const cartSubtotal = document.getElementById('cart-subtotal');
            if (cartSubtotal && data.subtotalOriginal !== undefined) {
                cartSubtotal.textContent = `$${formatCurrency(data.subtotalOriginal)}`;
            }
            
            // Actualizar totales
            const cartTotal = document.getElementById('cart-total');
            if (cartTotal) cartTotal.textContent = `$${formatCurrency(data.total)}`;
            
            const cartTotalFinal = document.getElementById('cart-total-final');
            if (cartTotalFinal) cartTotalFinal.textContent = `$${formatCurrency(data.total)}`;
            
            // Actualizar estado del botón y advertencia de monto mínimo
            updateCheckoutButton(data.total, data.minOrderAmount);
            
            updateCartCount();
        } else {
            alert(data.message || 'Error al actualizar cantidad');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al actualizar el carrito');
    });
}

function updateCheckoutButton(total, minOrderAmount) {
    const checkoutBtn = document.getElementById('checkout-btn');
    const minimumWarning = document.getElementById('minimum-warning');
    const amountNeeded = document.getElementById('amount-needed');
    
    if (!checkoutBtn || !minimumWarning) return;
    
    const meetsMinimum = total >= minOrderAmount;
    
    if (meetsMinimum) {
        // Habilitar botón
        checkoutBtn.href = '/checkout';
        checkoutBtn.classList.remove('bg-gray-300', 'text-gray-500', 'cursor-not-allowed');
        checkoutBtn.classList.add('bg-[#ffd90f]', 'hover:bg-[#fad300]');
        checkoutBtn.removeAttribute('data-disabled');
        if (minimumWarning) minimumWarning.classList.add('hidden');
    } else {
        // Deshabilitar botón
        checkoutBtn.removeAttribute('href');
        checkoutBtn.classList.remove('bg-[#ffd90f]', 'hover:bg-[#fad300]');
        checkoutBtn.classList.add('bg-gray-300', 'text-gray-500', 'cursor-not-allowed');
        checkoutBtn.setAttribute('data-disabled', 'true');
        if (minimumWarning) minimumWarning.classList.remove('hidden');
        
        // Actualizar monto faltante solo si hay una diferencia real
        if (amountNeeded) {
            const needed = minOrderAmount - total;
            if (needed > 0) {
                amountNeeded.textContent = `$${formatCurrency(needed)}`;
            }
        }
    }
}

function updateQuantityInput(productId) {
    const input = document.getElementById(`qty-${productId}`);
    if (!input) return;
    
    let newQty = parseFloat(input.value);
    const minQty = parseFloat(input.min);
    const maxQty = parseFloat(input.max) || 10000;
    
    // Validar rango
    if (isNaN(newQty) || newQty < minQty) {
        newQty = minQty;
    }
    if (newQty > maxQty) {
        newQty = maxQty;
    }
    
    // Redondear según el step
    if (parseFloat(input.step) === 0.5) {
        newQty = Math.round(newQty * 2) / 2;
        input.value = newQty.toFixed(1);
    } else {
        newQty = Math.round(Math.abs(newQty));
        input.value = newQty;
    }
    
    saveQuantity(productId, newQty);
}

function removeItem(productId) {
    if (!confirm('¿Eliminar este producto del carrito?')) return;
    
    fetch(`/carrito/remover/${productId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Error al eliminar producto');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al eliminar del carrito');
    });
}

function updateCartCount() {
    fetch('/carrito/count')
        .then(response => response.json())
        .then(data => {
            document.querySelectorAll('.cart-count').forEach(el => {
                el.textContent = data.count;
            });
        })
        .catch(error => console.error('Error updating cart count:', error));
}

// ============================================
// PÁGINA DE PRODUCTO
// ============================================

function initializeProduct() {
    // Botones de cantidad del producto
    const decreaseBtn = document.querySelector('[data-action="decrease-product-qty"]');
    const increaseBtn = document.querySelector('[data-action="increase-product-qty"]');
    const quantityInput = document.getElementById('quantity');
    
    if (decreaseBtn) {
        decreaseBtn.addEventListener('click', function() {
            const step = parseFloat(this.dataset.step || 1);
            const minValue = parseFloat(this.dataset.min || 1);
            const productType = this.dataset.productType || 'N';
            decreaseProductQuantity(step, minValue, productType);
        });
    }
    
    if (increaseBtn) {
        increaseBtn.addEventListener('click', function() {
            const step = parseFloat(this.dataset.step || 1);
            const maxValue = parseFloat(this.dataset.max || 10000);
            const productType = this.dataset.productType || 'N';
            increaseProductQuantity(step, maxValue, productType);
        });
    }
    
    if (quantityInput) {
        quantityInput.addEventListener('input', function() {
            const minValue = parseFloat(this.min || 1);
            const maxValue = parseFloat(this.max || 10000);
            const productType = this.dataset.productType || 'N';
            validateProductQuantity(this, minValue, maxValue, productType);
        });
    }
    
    // Formulario de agregar al carrito
    const addToCartForm = document.getElementById('add-to-cart-form');
    if (addToCartForm) {
        addToCartForm.addEventListener('submit', handleAddToCart);
    }
}

function decreaseProductQuantity(step, minValue, productType) {
    const input = document.getElementById('quantity');
    if (!input) return;
    
    let newValue = parseFloat(input.value) - step;
    
    if (newValue < minValue) {
        newValue = minValue;
    }
    
    input.value = productType === 'P' ? newValue.toFixed(1) : Math.round(newValue);
}

function increaseProductQuantity(step, maxValue, productType) {
    const input = document.getElementById('quantity');
    if (!input) return;
    
    let newValue = parseFloat(input.value) + step;
    
    if (newValue > maxValue) {
        newValue = maxValue;
    }
    
    input.value = productType === 'P' ? newValue.toFixed(1) : Math.round(newValue);
}

function validateProductQuantity(input, minValue, maxValue, productType) {
    let value = parseFloat(input.value);
    
    // Prevenir valores negativos, NaN o superiores al máximo
    if (isNaN(value) || value < minValue) {
        input.value = minValue;
        return;
    }
    if (value > maxValue) {
        input.value = maxValue;
        return;
    }
    
    // Formatear según el tipo de producto
    input.value = productType === 'P' ? value.toFixed(1) : Math.round(value);
}

function handleAddToCart(e) {
    e.preventDefault();
    
    const btn = document.getElementById('add-to-cart-btn');
    const btnText = document.getElementById('btn-text');
    const btnSpinner = document.getElementById('btn-spinner');
    const btnCheck = document.getElementById('btn-check');
    
    if (!btn) return;
    
    // Deshabilitar el botón y mostrar spinner
    btn.disabled = true;
    btn.classList.remove('active:scale-95', 'active:bg-[#e6c800]');
    btn.classList.add('cursor-not-allowed', 'opacity-75');
    if (btnText) btnText.classList.add('hidden');
    if (btnSpinner) btnSpinner.classList.remove('hidden');
    
    // Enviar el formulario vía fetch
    const formData = new FormData(e.target);
    
    fetch(e.target.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Ocultar spinner y mostrar check
            if (btnSpinner) btnSpinner.classList.add('hidden');
            if (btnCheck) btnCheck.classList.remove('hidden');
            
            // Cambiar color del botón a verde con animación suave
            btn.classList.remove('bg-[#ffd90f]', 'opacity-75');
            btn.classList.add('bg-green-600');
            if (btnText) {
                btnText.textContent = 'Agregado';
                btnText.classList.remove('hidden', 'text-gray-700');
                btnText.classList.add('text-white');
            }
            
            // Actualizar contador del carrito
            updateCartCount();
            
            // Restaurar el botón después de 1.5 segundos
            setTimeout(() => {
                btn.disabled = false;
                btn.classList.remove('bg-green-600', 'cursor-not-allowed');
                btn.classList.add('bg-[#ffd90f]', 'active:scale-95', 'active:bg-[#e6c800]');
                if (btnCheck) btnCheck.classList.add('hidden');
                if (btnText) {
                    btnText.classList.remove('text-white');
                    btnText.classList.add('text-gray-700');
                    btnText.textContent = '🛒 Agregar al Carrito';
                }
            }, 1500);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Restaurar botón en caso de error
        btn.disabled = false;
        btn.classList.remove('cursor-not-allowed', 'opacity-75');
        btn.classList.add('active:scale-95', 'active:bg-[#e6c800]');
        if (btnSpinner) btnSpinner.classList.add('hidden');
        if (btnText) btnText.classList.remove('hidden');
        alert('Hubo un error al agregar el producto. Por favor intenta de nuevo.');
    });
}

function decreaseQuantity() {
    const input = document.getElementById('quantity');
    const step = parseFloat(input.step) || 1;
    const min = parseFloat(input.min) || 0;
    const currentValue = parseFloat(input.value);
    const newValue = currentValue - step;
    
    if (newValue >= min) {
        input.value = newValue;
    }
}

function increaseQuantity() {
    const input = document.getElementById('quantity');
    const step = parseFloat(input.step) || 1;
    const max = parseFloat(input.max) || Infinity;
    const currentValue = parseFloat(input.value);
    const newValue = currentValue + step;
    
    if (newValue <= max) {
        input.value = newValue;
    }
}

// ============================================
// CHECKOUT
// ============================================

async function initializeCheckout() {
    const deliveryDateInput = document.getElementById('delivery_date');
    if (!deliveryDateInput) return;
    
    // Cargar configuración de fechas disponibles
    let deliverySettings = null;
    
    try {
        const response = await fetch('/api/delivery/available-dates');
        if (response.ok) {
            deliverySettings = await response.json();
            
            const fechaInfo = document.getElementById('fecha-info');
            
            deliveryDateInput.min = deliverySettings.minDate;
            deliveryDateInput.max = deliverySettings.maxDate;
            
            if (fechaInfo) {
                const minDateFormatted = new Date(deliverySettings.minDate).toLocaleDateString('es-AR');
                const maxDateFormatted = new Date(deliverySettings.maxDate).toLocaleDateString('es-AR');
                fechaInfo.textContent = `Selecciona una fecha entre ${minDateFormatted} y ${maxDateFormatted}`;
            }
            
            // Validar fecha seleccionada
            deliveryDateInput.addEventListener('change', (e) => {
                const fechaSeleccionada = e.target.value;
                const fechaError = document.getElementById('fecha-error');
                
                if (!fechaSeleccionada) {
                    return;
                }
                
                // Verificar si la fecha está en el rango
                if (fechaSeleccionada < deliverySettings.minDate || fechaSeleccionada > deliverySettings.maxDate) {
                    fechaError.textContent = 'La fecha seleccionada está fuera del rango permitido';
                    fechaError.classList.remove('hidden');
                    deliveryDateInput.value = '';
                    return;
                }
                
                // Verificar si la fecha ya alcanzó el límite de pedidos
                const pedidosEnFecha = deliverySettings.occupiedDates[fechaSeleccionada] || 0;
                if (pedidosEnFecha >= deliverySettings.maxOrdersPerDay) {
                    fechaError.textContent = `Esta fecha ya alcanzó el límite de ${deliverySettings.maxOrdersPerDay} pedidos. Por favor, selecciona otra fecha.`;
                    fechaError.classList.remove('hidden');
                    deliveryDateInput.value = '';
                    return;
                }
                
                // Fecha válida
                fechaError.classList.add('hidden');
            });
        }
    } catch (error) {
        console.error('Error al cargar configuración de entregas:', error);
    }
    
    // Si Flatpickr está disponible, usarlo en lugar del input nativo
    if (typeof flatpickr !== 'undefined' && deliverySettings) {
        const disabledDates = Object.keys(deliverySettings.occupiedDates)
            .filter(date => deliverySettings.occupiedDates[date] >= deliverySettings.maxOrdersPerDay);
        
        flatpickr(deliveryDateInput, {
            dateFormat: 'Y-m-d',
            minDate: deliverySettings.minDate,
            maxDate: deliverySettings.maxDate,
            disable: disabledDates,
            locale: 'es'
        });
    }
}

// ============================================
// PAGO
// ============================================

function initializePayment() {
    // Mercado Pago form
    const mpForm = document.getElementById('mercadopago-form');
    if (mpForm) {
        mpForm.addEventListener('submit', handleMercadoPagoSubmit);
    }
    
    // Botones de otros métodos de pago
    document.querySelectorAll('[data-action="select-payment"]').forEach(button => {
        button.addEventListener('click', function() {
            const method = this.dataset.paymentMethod;
            selectPaymentMethod(method);
        });
    });
}

async function handleMercadoPagoSubmit(e) {
    e.preventDefault();
    console.log('Formulario interceptado correctamente');
    
    const form = e.target;
    const button = form.querySelector('button');
    const btnText = document.getElementById('mp-btn-text');
    const loader = document.getElementById('mp-loader');
    const originalText = btnText.textContent;
    
    button.disabled = true;
    btnText.textContent = 'Procesando...';
    if (loader) loader.classList.remove('hidden');
    
    try {
        console.log('Enviando petición a:', form.action);
        
        const response = await fetch(form.action, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        console.log('Status de respuesta:', response.status);
        
        if (!response.ok) {
            const errorText = await response.text();
            console.error('Error en respuesta:', errorText);
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        console.log('Datos recibidos:', data);
        
        if (data.success && data.init_point) {
            btnText.textContent = 'Redirigiendo a Mercado Pago...';
            console.log('Redirigiendo a:', data.init_point);
            window.location.replace(data.init_point);
        } else {
            console.error('Error de Mercado Pago:', data);
            let errorMsg = data.error || 'No se pudo procesar el pago';
            if (data.details) {
                console.error('Detalles:', data.details);
            }
            alert('Error: ' + errorMsg);
            button.disabled = false;
            btnText.textContent = originalText;
            if (loader) loader.classList.add('hidden');
        }
    } catch (error) {
        console.error('Error al procesar:', error);
        alert('Error al procesar el pago. Por favor intenta nuevamente.\n\nDetalles: ' + error.message);
        button.disabled = false;
        btnText.textContent = originalText;
        if (loader) loader.classList.add('hidden');
    }
}

function selectPaymentMethod(method) {
    alert('Opción de pago en desarrollo: ' + method);
}

// ============================================
// FORMULARIOS DE ELIMINACIÓN
// ============================================

function initializeDeleteForms() {
    document.querySelectorAll('[data-action="delete-confirm"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            const message = this.dataset.confirmMessage || '¿Estás seguro de eliminar este elemento?';
            if (!confirm(message)) {
                e.preventDefault();
            }
        });
    });
}

// ============================================
// PÁGINAS DE ERROR
// ============================================

function initializeErrorPages() {
    // Botón de reload
    const reloadBtn = document.querySelector('[data-action="reload-page"]');
    if (reloadBtn) {
        reloadBtn.addEventListener('click', function() {
            window.location.reload();
        });
    }
    
    // Botón de volver atrás
    const backBtn = document.querySelector('[data-action="go-back"]');
    if (backBtn) {
        backBtn.addEventListener('click', function() {
            window.history.back();
        });
    }
    
    // Botón con countdown (429)
    const countdownBtn = document.querySelector('[data-action="reload-countdown"]');
    if (countdownBtn) {
        countdownBtn.addEventListener('click', function() {
            this.disabled = true;
            this.innerText = 'Recargando en 5s...';
            setTimeout(() => window.location.reload(), 5000);
        });
    }
}

// ============================================
// NAVEGACIÓN
// ============================================

// Manejar logout forms
document.querySelectorAll('[data-action="logout"]').forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('logout-form').submit();
    });
});

// ============================================
// HERO CAROUSEL (si existe)
// ============================================

if (typeof initCarousel === 'function') {
    // La función del carousel ya existe en otro archivo
    // Solo la llamamos si está disponible
    document.addEventListener('DOMContentLoaded', initCarousel);
}

// ============================================
// LOTTIE ANIMATIONS
// ============================================

function initLottieAnimations() {
    const lottieContainers = document.querySelectorAll('[data-lottie]');
    lottieContainers.forEach(container => {
        if (typeof lottie !== 'undefined') {
            const animationPath = container.dataset.lottie;
            lottie.loadAnimation({
                container: container,
                renderer: 'svg',
                loop: true,
                autoplay: true,
                path: animationPath
            });
        }
    });
}

// Inicializar animaciones Lottie si están disponibles
if (typeof lottie !== 'undefined') {
    document.addEventListener('DOMContentLoaded', initLottieAnimations);
}

// Exportar funciones globales para compatibilidad temporal
window.updateQuantity = updateQuantity;
window.updateQuantityInput = updateQuantityInput;
window.removeItem = removeItem;
window.decreaseQuantity = decreaseQuantity;
window.increaseQuantity = increaseQuantity;
window.selectPaymentMethod = selectPaymentMethod;
