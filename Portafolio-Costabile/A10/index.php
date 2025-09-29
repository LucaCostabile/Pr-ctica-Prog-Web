<?php
declare(strict_types=1);

$products = require __DIR__ . '/products.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Tienda | Carrito de Compras</title>
	<link rel="stylesheet" href="assets/styles.css">
</head>

<body>
	<header class="header">
		<div class="header__content">
			<h1 class="header__title">Tienda</h1>
			<div class="cart-summary" id="cart-summary" aria-live="polite">
				<span class="cart-summary__icon" aria-hidden="true">🛒</span>
				<span class="cart-summary__label">Carrito</span>
				<span class="cart-summary__items">0 items</span>
				<span class="cart-summary__total">$0.00</span>
			</div>
		</div>
	</header>

	<main class="layout">
		<section class="catalog" aria-labelledby="catalog-title">
			<div class="catalog__header">
				<h2 id="catalog-title">Productos disponibles</h2>
				<button class="button button--ghost" id="clear-cart">Vaciar carrito</button>
			</div>
			<div class="product-grid">
				<?php foreach ($products as $product): ?>
					<article class="product" data-product-id="<?= htmlspecialchars($product['id'], ENT_QUOTES) ?>">
						<div class="product__body">
							<h3 class="product__title"><?= htmlspecialchars($product['nombre']) ?></h3>
							<p class="product__description"><?= htmlspecialchars($product['descripcion']) ?></p>
						</div>
						<div class="product__footer">
							<span class="product__price">$<?= number_format($product['precio'], 2) ?></span>
							<button class="button button--primary js-add-to-cart" data-product-id="<?= htmlspecialchars($product['id'], ENT_QUOTES) ?>">
								Agregar al carrito
							</button>
						</div>
					</article>
				<?php endforeach; ?>
			</div>
		</section>

		<aside class="cart" aria-labelledby="cart-title">
			<h2 id="cart-title">Contenido del carrito</h2>
			<p class="cart__empty" id="cart-empty">Tu carrito está vacío. ¡Agrega productos!</p>
			<ul class="cart__items" id="cart-items"></ul>
			<div class="cart__total" id="cart-total">Total: $0.00</div>
		</aside>
	</main>

	<script>
		window.PRODUCTS_CATALOG = <?= json_encode(array_values($products), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) ?>;
		window.CART_API_URL = 'api/cart.php';
	</script>
	<script src="assets/app.js" defer></script>
</body>

</html>
