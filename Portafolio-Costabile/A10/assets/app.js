(function () {
	const apiUrl = window.CART_API_URL || 'api/cart.php';

	const summaryElement = document.getElementById('cart-summary');
	const summaryItemsElement = summaryElement?.querySelector('.cart-summary__items');
	const summaryTotalElement = summaryElement?.querySelector('.cart-summary__total');
	const cartItemsElement = document.getElementById('cart-items');
	const emptyStateElement = document.getElementById('cart-empty');
	const totalElement = document.getElementById('cart-total');
	const clearButton = document.getElementById('clear-cart');

	const formatter = new Intl.NumberFormat('es-AR', {
		style: 'currency',
		currency: 'USD',
		minimumFractionDigits: 2,
	});

	async function requestCart(action, payload = {}) {
		if (action === 'list') {
			const response = await fetch(apiUrl, { credentials: 'same-origin' });
			return response.json();
		}

		const response = await fetch(apiUrl, {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
			},
			credentials: 'same-origin',
			body: JSON.stringify({ action, ...payload }),
		});

		return response.json();
	}

	function renderCart(cart) {
		if (!cart) {
			return;
		}

		const { items, totalItems, totalPrice } = cart;

		if (summaryItemsElement) {
			summaryItemsElement.textContent = totalItems === 1 ? '1 ítem' : `${totalItems} ítems`;
		}

		if (summaryTotalElement) {
			summaryTotalElement.textContent = formatter.format(totalPrice);
		}

		if (!items || items.length === 0) {
			if (emptyStateElement) {
				emptyStateElement.hidden = false;
			}

			if (cartItemsElement) {
				cartItemsElement.innerHTML = '';
			}

			if (totalElement) {
				totalElement.textContent = 'Total: $0.00';
			}

			return;
		}

		if (emptyStateElement) {
			emptyStateElement.hidden = true;
		}

		if (cartItemsElement) {
			cartItemsElement.innerHTML = '';

			items.forEach((item) => {
				const li = document.createElement('li');
				li.className = 'cart-item';
				li.dataset.productId = item.id;

				li.innerHTML = `
					<div class="cart-item__info">
						<span class="cart-item__title">${item.nombre}</span>
						<span class="cart-item__price">${formatter.format(item.precio)}</span>
					</div>
					<div class="cart-item__controls">
						<button class="button button--square" data-action="decrement" aria-label="Restar una unidad">-</button>
						<span class="cart-item__quantity" aria-live="polite">${item.cantidad}</span>
						<button class="button button--square" data-action="add" aria-label="Sumar una unidad">+</button>
						<button class="button button--ghost" data-action="remove">Eliminar</button>
						<span class="cart-item__subtotal">${formatter.format(item.subtotal)}</span>
					</div>
				`;

				cartItemsElement.appendChild(li);
			});
		}

		if (totalElement) {
			totalElement.textContent = `Total: ${formatter.format(totalPrice)}`;
		}
	}

	async function refreshCart() {
		try {
			const cart = await requestCart('list');
			renderCart(cart);
		} catch (error) {
			console.error('No se pudo obtener el carrito', error);
		}
	}

	async function handleAction(action, productId = null) {
		try {
			const cart = await requestCart(action, productId ? { productId } : {});
			if (cart.error) {
				throw new Error(cart.error);
			}
			renderCart(cart);
		} catch (error) {
			console.error(error);
			alert(error.message || 'Ocurrió un error al actualizar el carrito.');
		}
	}

	function setupListeners() {
		document.querySelectorAll('.js-add-to-cart').forEach((button) => {
			button.addEventListener('click', () => {
				const productId = button.dataset.productId;
				if (productId) {
					handleAction('add', productId);
				}
			});
		});

		if (clearButton) {
			clearButton.addEventListener('click', () => handleAction('clear'));
		}

		if (cartItemsElement) {
			cartItemsElement.addEventListener('click', (event) => {
				const target = event.target;
				if (!(target instanceof HTMLElement)) {
					return;
				}

				const action = target.dataset.action;
				if (!action) {
					return;
				}

				const itemElement = target.closest('.cart-item');
				const productId = itemElement?.dataset.productId;

				if (!productId) {
					return;
				}

				if (action === 'add') {
					handleAction('add', productId);
				} else if (action === 'decrement') {
					handleAction('decrement', productId);
				} else if (action === 'remove') {
					handleAction('remove', productId);
				}
			});
		}
	}

	setupListeners();
	refreshCart();
})();
