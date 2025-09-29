<?php

declare(strict_types=1);

session_start();

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
	http_response_code(204);
	exit;
}

$products = require __DIR__ . '/../products.php';

if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
	$_SESSION['cart'] = [];
}

function respond(array $payload, int $status = 200): void
{
	http_response_code($status);
	echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
	exit;
}

function getCartState(array $products): array
{
	$items = [];
	$totalItems = 0;
	$totalPrice = 0.0;

	foreach ($_SESSION['cart'] as $productId => $quantity) {
		if (!isset($products[$productId])) {
			continue;
		}

		$product = $products[$productId];
		$subtotal = $product['precio'] * $quantity;
		$items[] = [
			'id' => $product['id'],
			'nombre' => $product['nombre'],
			'precio' => $product['precio'],
			'cantidad' => $quantity,
			'subtotal' => $subtotal,
		];

		$totalItems += $quantity;
		$totalPrice += $subtotal;
	}

	return [
		'items' => $items,
		'totalItems' => $totalItems,
		'totalPrice' => $totalPrice,
	];
}

function readInput(): array
{
	$raw = file_get_contents('php://input');
	if ($raw !== false && $raw !== '') {
		$decoded = json_decode($raw, true);
		if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
			return $decoded;
		}
	}

	return $_POST ?? [];
}

switch ($_SERVER['REQUEST_METHOD']) {
	case 'GET':
		respond(getCartState($products));

	case 'POST':
		$data = readInput();
		$action = $data['action'] ?? '';
		$productId = $data['productId'] ?? null;

		if ($action === '') {
			respond(['error' => 'Acción no especificada'], 400);
		}

		switch ($action) {
			case 'add':
				if (!isset($products[$productId])) {
					respond(['error' => 'Producto no encontrado'], 404);
				}

				$quantity = isset($data['quantity']) ? (int) $data['quantity'] : 1;
				$quantity = max(1, $quantity);
				$_SESSION['cart'][$productId] = ($_SESSION['cart'][$productId] ?? 0) + $quantity;
				respond(getCartState($products));

			case 'decrement':
				if (!isset($_SESSION['cart'][$productId])) {
					respond(['error' => 'El producto no está en el carrito'], 404);
				}

				$_SESSION['cart'][$productId]--;

				if ($_SESSION['cart'][$productId] <= 0) {
					unset($_SESSION['cart'][$productId]);
				}

				respond(getCartState($products));

			case 'remove':
				unset($_SESSION['cart'][$productId]);
				respond(getCartState($products));

			case 'clear':
				$_SESSION['cart'] = [];
				respond(getCartState($products));

			default:
				respond(['error' => 'Acción no soportada'], 400);
		}

	default:
		respond(['error' => 'Método no permitido'], 405);
}
