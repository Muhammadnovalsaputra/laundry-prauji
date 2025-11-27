let currentCategory = "all";
let products = [];
let cart = [];

function selectCustomers() {
  const select = document.getElementById("customer_id");
  const phone = select.options[select.selectedIndex].getAttribute("data-phone");
  document.getElementById("phone").value = phone || "";
}

function openModal(service) {
  document.getElementById("modal_id").value = service.id;
  document.getElementById("modal_name").value = service.name;
  document.getElementById("modal_price").value = service.price;
  document.getElementById("modal_qty").value = 1;
  new bootstrap.Modal("#exampleModal").show();
}

function addToCart() {
  const id = document.getElementById("modal_id").value;
  const name = document.getElementById("modal_name").value;
  const price = parseInt(document.getElementById("modal_price").value);
  const qty = parseInt(document.getElementById("modal_qty").value);

  const existing = cart.find((item) => item.id == id);
  if (existing) {
    existing.qty += qty;
  } else {
    cart.push({
      id,
      name,
      price,
      qty,
    });
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

                <strong>${item.name}</strong>
                <small>${item.price.toLocaleString("id-ID")}</small>
                </div>
                <div class="d-flex align-items-center m-5 gap-2">
                    <button class="btn btn-outline-secondary me-2" onclick="changeQty(${
                      item.id
                    }, -1)">-</button>
                    <span>${item.qty}</span>
                    <button class="btn btn-outline-secondary ms-3" onclick="changeQty(${
                      item.id
                    }, 1)">+</button>
                    <button class="btn btn-sm btn-danger ms-3" onclick="removeItem(${
                      item.id
                    })">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>`;

    cartContainer.appendChild(div);
  });
  updateTotal();
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
  const subTotal = cart.reduce((sum, item) => sum + item.price * item.qty, 0);
  const tax = subTotal * 0.1;
  const total = subTotal + tax;

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
}

// clearCart
document.getElementById("clearCart").addEventListener("click", function () {
  cart = [];
  renderCart();
});

// ngelampar ke php subtotalnya
async function processPayment() {
  if (cart.length === 0) {
    alert("cart Masih Kosong");
    return;
  }
  const modal = new bootstrap.Modal(document.getElementById("exampleModal"));
  modal.show();
}
async function handlePayment() {
  const paymentMethod = document.getElementById("payment_method").value;
  const order_code = document.querySelector(".orderNumber").textContent.trim();
  const subtotal = document.querySelector("#subtotal_value").value.trim();
  const tax = document.querySelector("#tax_value").value.trim();
  const grandTotal = document.querySelector("#total_value").value.trim();
  const customer_id = document.getElementById("customer_id").value;
  const end_date = document.getElementById("end_date").value;

  console.log(paymentMethod);
  if (paymentMethod == "cash") {
    try {
      const res = await fetch("/order", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
            .content,
        },
        body: JSON.stringify({
          cart,
          order_code,
          subtotal,
          tax,
          grandTotal,
          customer_id,
          end_date,
        }),
      });
      const data = await res.json();
      if (data.status == "success") {
        alert("ur transaction is success");
        window.location.href = "order";
      } else {
        alert("ur transaction is failed", data.message);
      }
    } catch (error) {
      alert("ur transaction is failed");
      console.log("error", error);
    }
  } else if (paymentMethod == "cashless") {
    try {
      const res = await fetch("/cashless", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
            .content,
        },
        body: JSON.stringify({
          cart,
          order_code,
          subtotal,
          tax,
          grandTotal,
        }),
      });
      const data = await res.json();
      if (data.status == "success") {
        window.snap.pay(data.snapToken);
      } else {
        alert("ur transaction is failed", data.message);
      }
    } catch (error) {
      alert("ur transaction is failed");
      console.log("error", error);
    }
  }
}
