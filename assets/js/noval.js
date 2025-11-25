// // const nama = 'muhammad noval';
// // let name = 'muhammad noval';
// // console.log(name);
// // alert(nama);

// // let angka1 = 10;
// // let angka2 = 20;
// // console.log(angka1 + angka2);
// // console.log(angka1 - angka2);
// // console.log(angka1 / angka2);
// // console.log(angka1 * angka2);
// // console.log(angka1 % angka2);
// // console.log(angka1 ** angka2);

// // // operator penugasan
// // let x = 10;
// // x += 5; //15
// // console.log(x);

// // // operator pembandingan
// // let a = 2;
// // let b = 1;

// // if (a === b) {
// //     console.log("ya");
// // } else {
// //     console.log("tidak");
// // }

// // console.log(a > b)
// // console.log(a < b)

// // operator logika && ,AND,OR,||,!
// // let umur = 20;
// // let punyaSim = true;

// // if (umur >= 17 && punyaSim) {
// //   console.log("boleh mengemudi");
// // } else {
// //   console.log("Tidak boleh mengemudi");
// // }

// // let buah = ["pisang", "salak", "semangka"];
// // console.log("buah dikeranjang:", buah);
// // console.log("saya mau buah", buah[1]);
// // buah[1] = "nanas";
// // console.log("buah baru:", buah);
// // buah.push("pepaya");
// // console.log("buah", buah);
// // buah.pop();

// document.getElementById("product-title").innerHTML = "Data Product";
// // let btn = document.getElementsByClassName("category-btn");
// // btn[0].style.color = "red";
// // console.log("ini button", btn);
// let buttons = document.querySelectorAll(".category-btn");
// console.log(buttons);

// // Jika menggunakan queryselector biasa tidak bisa karena tidak bernilai array foreach akan berjalan ketika bernilai array
// buttons.forEach((btn) => {
//   btn.style.color = "black";
//   console.log(btn);
// });

// let card = document.getElementById("card");
// let h3 = document.createElement("h3");
// let textH3 = document.createTextNode("Selamat Datang");
// h3.textContent = "Selamat Datang dengan TextContent";

// let p = document.createElement("p");
// p.innerText = "Duarr";
// p.textContent = "diarrrrrr";
// // nambahin elemet di dalam card
// card.appendChild(h3);
// card.appendChild(p);

let currentCategory = "all";

function filterCategory(category, event) {
  currentCategory = category;

  let buttons = document.querySelectorAll(".category-btn");
  buttons.forEach((btn) => {
    btn.classList.remove("active");
    btn.classList.remove("btn-primary");
    btn.classList.add("btn-outline-primary");
  });
  event.classList.add("active");
  event.classList.remove("btn-outline-primary");
  event.classList.add("btn-primary");
  console.log({
    currentCategory: currentCategory,
    category: category,
    event: event,
  });
  renderProducts();
}

function renderProducts(searchProduct = "") {
  const productGrid = document.getElementById("productGrid");
  productGrid.innerHTML = "";
  // console.log(products);

  // filter
  const filtered = products.filter((p) => {
    // shorthand / ternery
    const matchCategory =
      currentCategory === "all" || p.category_name === currentCategory;
    const matchSearch = p.product_name.toLowerCase().includes(searchProduct);
    return matchCategory && matchSearch;
  });

  // munculin data dari table products
  filtered.forEach((product) => {
    console.log(product);

    const col = document.createElement("div");
    col.className = "col-md-4 col-sm-6";
    col.innerHTML = `<div class="card product-card" onclick="addToCart(${product.id})">
    <div class="product-img">
    <img src="../${product.product_photo}" width="100%">
    </div>
    <div class="card-body">
    <span class="badge bg-secondary badge-category">${product.category_name}</span>
    <h6 class="card-title mt-2 mb-2">${product.product_name}</h6>
    <p class="card-text text-primary fw-bold">Rp. ${product.product_price}</p>
    </div>
    </div>`;
    productGrid.appendChild(col);
  });
}

// hapus item cart
function removeItem(id) {
  cart = cart.filter((p) => p.id != id);
  renderCart();
}
// mengatur qty item cart
function changeQty(id, x) {
  const item = cart.find((p) => p.id == id);
  if (!item) {
    return;
  }
  item.quantity += x;
  if (item.quantity <= 0) {
    alert("minimal harus 1 product");
    // cart = filter((p) => p.id != id);
  }
  renderCart();
}

function updateTotal() {
  const subTotal = cart.reduce(
    (sum, item) => sum + item.product_price * item.quantity,
    0
  );
  const tax = subTotal * 0.1;
  const total = tax + subTotal;

  document.getElementById(
    "Subtotal"
  ).textContent = `Rp. ${subTotal.toLocaleString()}`;
  document.getElementById("tax").textContent = `Rp. ${tax.toLocaleString()}`;
  document.getElementById(
    "total"
  ).textContent = `Rp. ${total.toLocaleString()}`;
  document.getElementById("subtotal_value").value = subTotal;
  document.getElementById("tax_value").value = tax;
  document.getElementById("total_value").value = total;

  // console.log(subTotal);
  // console.log(tax);
  // console.log(total);
}

// clearCart
document.getElementById("clearCart").addEventListener("click", function () {
  cart = [];
  renderCart();
});

// ngelampar ke php subtotalnya
async function processPayment() {
  if (cart.length === 0) {
    alert("The cart is still empty");
    return;
  }

  const order_code = document.querySelector(".orderNumber").textContent.trim();
  const subtotal = document.querySelector("#subtotal_value").value.trim();
  const tax = document.querySelector("#tax_value").value.trim();
  const grandTotal = document.querySelector("#total_value").value.trim();

  try {
    const res = await fetch("add-pos.php?payment", {
      method: "POST",
      headers: { "content-type": "application/json" },
      body: JSON.stringify({ cart, order_code, subtotal, tax, grandTotal }),
    });
    const data = await res.json();
    if (data.status == "success") {
      alert("Transaction success");
      window.location.href = "print.php";
    } else {
      alert("transaction failed: " + data.message);
    }
  } catch (error) {
    alert("upss transaction fail");
    console.log("error:" + error);
  }
}

let cart = [];
function addToCart(id) {
  const product = products.find((p) => p.id == id);

  if (!product) {
    return;
  }
  // mengecek apakah produknya sudah ada cart atau belum
  const existing = cart.find((item) => item.id == id);
  if (existing) {
    existing.quantity += 1;
  } else {
    cart.push({ ...product, quantity: 1 });
  }
  renderCart();
}

function renderCart() {
  const cartContainer = document.querySelector("#cartItems");
  cartContainer.innerHTML = "";

  if (cart.length === 0) {
    cartContainer.innerHTML = `
      <div class="cart-items" id="cartItems">
      <div class="text-center text-muted mt-5">
      <i class="bi bi-cart mb-3"></i>
      <p>Cart Empty</p>
      </div>
      </div>`;
    updateTotal();
    // return;
  }
  cart.forEach((item, index) => {
    const div = document.createElement("div");
    div.className =
      "cart-item d-flex justify-content-between align-items-center mb-2";
    div.innerHTML = `
        <div>
                    <strong>${item.product_name}</strong>
                    <small>${item.product_price}</small>
                </div>
                <div class="d-flex align-items-center m-5 gap-2">
                    <button class="btn btn-outline-secondary me-2" onclick="changeQty(${item.id}, -1)">-</button>
                    <span>${item.quantity}</span>
                    <button class="btn btn-outline-secondary ms-3" onclick="changeQty(${item.id}, 1)">+</button>
                    <button class="btn btn-sm btn-danger ms-3" onclick="removeItem(${item.id})">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>`;

    cartContainer.appendChild(div);
  });
  updateTotal();
}

// useEffect(() => {
// }, [])

// DomContentLoaded : akan meliad function pertama kali
renderProducts();

document
  .getElementById("searchProduct")
  .addEventListener("input", function (e) {
    const searchProduct = e.target.value.toLowerCase();
    renderProducts(searchProduct);
    // console.log(searchProduct);
    // alert("eyy");
  });
