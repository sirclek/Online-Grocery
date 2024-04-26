const CART_KEY = 'CART_KEY';
const CART_LAST_ACCESSED_KEY = 'CART_LAST_ACCESSED_KEY';


// UI Design
function ProductCard(product, stock_info) {
    return `
        <div class="product">
            <img src="images/${product['image_id']}" alt="${product['product_name']}">
            <h3>${product['product_name']} (${product['unit_quantity']})</h3>
            <p>Price: $${product['unit_price']}</p>
            <p>${stock_info}</p>
            <button onclick="addToCart('${product['product_id']}')">Add to Cart</button>
        </div>
    `
}

function CartCard(product, quantity) {
    return `
        <div class="product">
            <h3>${product['product_name']} (${product['unit_quantity']})</h3>
            <p>${quantity}</p>
            <button onclick="removeItemFromCart('${product['product_id']}')">Remove item</button>
        </div>
    `
}

window.onload = async function () {
    const cartLastAccessed = new Date(localStorage.getItem(CART_LAST_ACCESSED_KEY));

    const now = new Date();

    if (cartLastAccessed) {
        const diffInHour = Math.abs(now - cartLastAccessed) / (1000 * 60 * 60);
        // const diffInMinute = Math.abs(now - cartLastAccessed) / (1000 * 60);

        if (diffInHour >= 24) {
            localStorage.removeItem(CART_LAST_ACCESSED_KEY);
            localStorage.removeItem(CART_KEY);
        }

        // if (diffInMinute >= 1) {
        //     localStorage.removeItem(CART_LAST_ACCESSED_KEY);
        //     localStorage.removeItem(CART_KEY);
        // }
    }
    updateCart();

    //await showProducts('all');
}


async function showProducts(category) {
    const url = `product.php?category=${category}`;
    try {
        const response = await fetch(url);

        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        const products = await response.json();
        
        document.getElementById("product-grid").innerHTML = ""
        var stock_info = "";
        products.forEach((product) => {
            if (product['in_stock'] > 0) {
                stock_info = "In stock";
            }
            else {
                stock_info = "Not in stock";
            }
            document.getElementById("product-grid").innerHTML += ProductCard(product, stock_info);
        })

    } catch (error) {
        console.error('Error fetching data:', error);
    }

}


async function showProductsSearch() {
    const search = document.querySelector('.searchbar').value;
    const url = `product.php?search=${search}`;

    try {
        const response = await fetch(url);

        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        const products = await response.json();
        console.log(products);

        document.getElementById("product-grid").innerHTML = ""

        products.forEach((product) => {
            if (product['in_stock'] > 0) {
                stock_info = "In stock";
            }
            else {
                stock_info = "Not in stock";
            }
            document.getElementById("product-grid").innerHTML += ProductCard(product, stock_info);
        })

    } catch (error) {
        console.error('Error fetching data:', error);
    }
}


function addToCart(productId) {
    const cart = localStorage.getItem(CART_KEY);
    const cartLastAccessed = localStorage.getItem(CART_LAST_ACCESSED_KEY);

    if (!cart) {
        const data = {
            [productId]: 1
        }

        localStorage.setItem(CART_KEY, JSON.stringify(data));
    } else {
        const data = JSON.parse(cart)
        if (productId in data) {
            data[productId] = Number(data[productId]) + 1;
        } else {
            data[productId] = 1;
        }

        localStorage.setItem(CART_KEY, JSON.stringify(data));
    }

    console.log(cartLastAccessed)
    localStorage.setItem(CART_LAST_ACCESSED_KEY, new Date());
    updateCart();
}

function removeItemFromCart(productId) {
    const cart = localStorage.getItem(CART_KEY);
    const data = JSON.parse(cart)
    if (data[productId] >= 1) {
        data[productId] = Number(data[productId]) - 1;
    }
    if (data[productId] == 0) {
        delete data[productId];
    }
    localStorage.setItem(CART_KEY, JSON.stringify(data));

    updateCart();
}

function buyButtonClick() {
    const cartString = localStorage.getItem(CART_KEY);
    if (cartString == "{}" || cartString == "") {
        alert("You don't have any products in your cart.");
    }
    else {
        window.location.replace('./checkout.php');
    }
}

async function updateCart() {
    const cartString = localStorage.getItem(CART_KEY);
    const cart = JSON.parse(cartString);
    var totalPrice = 0;
    document.getElementById("cart-content").innerHTML = "";
    document.getElementById("total-price").innerHTML = "$0";
    var buyButton = document.getElementById('buy-button');
    buyButton.style.display = 'none';


    for (const productId in cart) {
        buyButton.style.display = 'block';

        const quantity = cart[productId];
        const url = `product.php?productId=${productId}`;

        try {
            const response = await fetch(url);

            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }

            const product = await response.json();

            totalPrice += product.unit_price * quantity;
            totalPrice = Math.round(totalPrice * 100) / 100;
            document.getElementById("cart-content").innerHTML += CartCard(product, quantity);
            document.getElementById("total-price").innerHTML = "$" + totalPrice;
        } catch (error) {
            console.error('Error fetching data:', error);
        }
    }

}

async function updatePrice() {
    const cartString = localStorage.getItem(CART_KEY);
    const cart = JSON.parse(cartString);
    var totalPrice = 0;
    document.getElementById("total-price").innerHTML = "0";

    for (const productId in cart) {
        console.log(productId);
        const quantity = cart[productId];
        const url = `product.php?productId=${productId}`;

        try {
            const response = await fetch(url);

            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }

            const product = await response.json();

            totalPrice += product.unit_price * quantity;
            totalPrice = Math.round(totalPrice * 100) / 100;
            document.getElementById("total-price").innerHTML = "$" + totalPrice;
        } catch (error) {
            console.error('Error fetching data:', error);
        }
    }
}

async function clearCart() {
    const cartString = localStorage.getItem(CART_KEY);
    const cart = JSON.parse(cartString);
    for (const productId in cart) {
        delete cart[productId];
    }
    localStorage.setItem(CART_KEY, JSON.stringify(cart));
    updateCart();
}

//cart
let cartIcon = document.querySelector('#carticon');
let cart = document.querySelector('.cart');
let closeCart = document.querySelector('#close-cart');
let buyButton = document.querySelector('#buy-button');
let buy = document.querySelector('.buy-button');


cartIcon.addEventListener('click', () => {
    cart.classList.add('active');
    updateCart();
});

closeCart.addEventListener('click', () => {
    cart.classList.remove('active');
});

function ready() {
}
