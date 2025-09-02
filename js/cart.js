document.addEventListener('DOMContentLoaded', function () {
			let cart = {};
			const cartModalEl = document.getElementById('cartModal');
			const cartModal = new bootstrap.Modal(cartModalEl);

			// ==== Сохранение корзины ====
			function saveCart() {
				localStorage.setItem('cart', JSON.stringify(cart));
			}

			// ==== Загрузка корзины ====
			function loadCart() {
				const savedCart = localStorage.getItem('cart');
				if (savedCart) {
					cart = JSON.parse(savedCart);
				}
				updateCartTable();
				updateButtons();
			}

			// ==== Обновление таблицы корзины ====
			function updateCartTable() {
				const tbody = document.querySelector('#cartTable tbody');
				tbody.innerHTML = '';
				let total = 0;

				for (const id in cart) {
					const item = cart[id];
					const itemTotal = item.price * item.quantity;
					total += itemTotal;

					const tr = document.createElement('tr');
					tr.innerHTML = `
						<td><img src="${item.image}" alt="${item.name}" width="50"></td>
						<td>${item.name}</td>
						<td>${item.price} грн</td>
						<td><input type="number" min="1" value="${item.quantity}" class="form-control form-control-sm cart-qty" data-id="${id}"></td>
						<td class="item-total">${itemTotal.toFixed(2)} грн</td>
						<td><button class="btn btn-danger btn-sm btn-remove" data-id="${id}">Удалить</button></td>
					`;
					tbody.appendChild(tr);
				}

				document.getElementById('cartTotal').textContent = total.toFixed(2) + ' грн';
			}

			// ==== Обновление кнопок "Купити / В корзині" ====
			function updateButtons() {
				document.querySelectorAll('.btn-buy').forEach(button => {
					const id = button.dataset.id;
					if (cart[id]) {
						button.textContent = 'В корзині';
						button.classList.remove('btn-primary');
						button.classList.add('btn-success');
					} else {
						button.textContent = 'Купити';
						button.classList.remove('btn-success');
						button.classList.add('btn-primary');
					}
				});
			}

			// ==== Добавление товара ====
			document.addEventListener('click', function(e) {
				if (e.target.classList.contains('btn-buy')) {
					const button = e.target;
					const id = button.dataset.id;
					const name = button.dataset.name;
					const price = parseFloat(button.dataset.price);
					const image = button.dataset.image;

					if (cart[id]) {
						cart[id].quantity += 1;
					} else {
						cart[id] = {id, name, price, image, quantity: 1};
					}

					updateCartTable();
					updateButtons();
					saveCart();
					cartModal.show();
				}
			});

			// ==== Изменение количества ====
			document.getElementById('cartTable').addEventListener('input', function (e) {
				if (e.target.classList.contains('cart-qty')) {
					const id = e.target.dataset.id;
					let qty = parseInt(e.target.value);
					if (qty < 1) qty = 1;
					cart[id].quantity = qty;

					updateCartTable();
					saveCart();
				}
			});

			// ==== Удаление товара ====
			document.getElementById('cartTable').addEventListener('click', function (e) {
				if (e.target.classList.contains('btn-remove')) {
					const id = e.target.dataset.id;
					delete cart[id];

					updateCartTable();
					updateButtons();
					saveCart();
				}
			});

			// ==== Подхватываем кнопки после AJAX подгрузки товаров ====
			// (когда get_products.php возвращает товары)
			$(document).ajaxSuccess(function () {
				updateButtons();
			});

			// ==== Первичная загрузка ====
			loadCart();
		});