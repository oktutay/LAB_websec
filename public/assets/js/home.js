function escapeHtml(unsafe) {
  if (typeof unsafe !== "string") return "";
  return unsafe
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;")
    .replace(/'/g, "&#039;");
}
document.addEventListener("DOMContentLoaded", () => {
  const container = document.getElementById("productList");
  const searchInput = document.getElementById("searchInput");
  let allProducts = [];

  // ----------------------------
  // Hàm render sản phẩm
  // ----------------------------
  function render(products) {
    if (!Array.isArray(products) || products.length === 0) {
      container.innerHTML = "<p class='text-danger'>Không có sản phẩm nào.</p>";
      return;
    }

    const cards = products.map(p => `
      <div class="col-md-4 mb-3">
        <div class="card h-10 d-flex flex-column">
          <div class="card-body flex-grow-1">
            <input type="checkbox" class="select-product" data-id="${p.id}" />
            <h5 class="card-title">${escapeHtml(p.name)}</h5>
            <p class="card-text">${escapeHtml(p.description)}</p>
            <p><strong>Giá:</strong> ${Number(p.price).toLocaleString('vi-VN')} ₫</p>
            <p><small>Người đăng: ${escapeHtml(p.creatorName || 'N/A')}</small></p>
          </div>
          <div class="card-footer d-flex justify-content-between">
            <button class="btn btn-sm btn-warning btn-edit"
              data-id="${p.id}"
              data-name="${escapeHtml(p.name)}"
              data-price="${p.price}"
              data-description="${escapeHtml(p.description)}">
               Sửa
            </button>
            <button class="btn btn-sm btn-danger btn-delete"
              data-id="${p.id}">
               Xóa
            </button>
          </div>
        </div>
      </div>
    `);

    container.innerHTML = `<div class="row">${cards.join('')}</div>`;
  }

  // ----------------------------
  // Hàm fetch danh sách sản phẩm
  // ----------------------------
  function fetchProducts() {
    fetch("/Task1_VNPT_oktutay/backend/api/product/list.php")
      .then(res => res.json())
      .then(data => {
        allProducts = data.data || [];
        render(allProducts);
      })
      .catch(err => {
        console.error("Lỗi tải sản phẩm:", err);
        container.innerHTML = "<p class='text-danger'>Lỗi khi tải danh sách sản phẩm.</p>";
      });
  }

  // ----------------------------
  // Tìm kiếm sản phẩm
  // ----------------------------
  searchInput.addEventListener("input", () => {
    const q = searchInput.value.trim().toLowerCase();
    const filtered = allProducts.filter(p =>
      p.name.toLowerCase().includes(q) ||
      (p.creatorName && p.creatorName.toLowerCase().includes(q))
    );
    render(filtered);
  });

  // ----------------------------
  // Toggle form thêm sản phẩm
  // ----------------------------
  document.getElementById("toggleCreateForm").addEventListener("click", () => {
    const form = document.getElementById("createProductForm");
    form.style.display = form.style.display === "none" ? "block" : "none";
  });

  // ----------------------------
  // Submit form tạo sản phẩm
  // ----------------------------
  document.getElementById("createProductForm").addEventListener("submit", function (e) {
    e.preventDefault();

    const name = document.getElementById("name").value.trim();
    const price = parseFloat(document.getElementById("price").value);
    const description = document.getElementById("description").value.trim();

    fetch("/Task1_VNPT_oktutay/backend/api/product/create.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ name, price, description })
    })
      .then(res => res.json())
      .then(data => {
        alert(data.message);
        if (data.success) {
          this.reset();
          this.style.display = "none";
          fetchProducts(); // Gọi lại fetch sau khi thêm
        }
      })
      .catch(err => console.error("Lỗi tạo sản phẩm:", err));
  });

  // ----------------------------
  // Sửa sản phẩm
  // ----------------------------
  document.addEventListener("click", (e) => {
    if (e.target.classList.contains("btn-edit")) {
      const btn = e.target;
      document.getElementById("editId").value = btn.dataset.id;
      document.getElementById("editName").value = btn.dataset.name;
      document.getElementById("editPrice").value = btn.dataset.price;
      document.getElementById("editDescription").value = btn.dataset.description;

      document.getElementById("editProductForm").style.display = "block";
      window.scrollTo({ top: 0, behavior: "smooth" });
    }

    if (e.target.classList.contains("btn-delete")) {
      const id = e.target.dataset.id;
      if (!confirm("Bạn có chắc chắn muốn xóa sản phẩm này không?")) return;

      fetch("/Task1_VNPT_oktutay/backend/api/product/delete.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id })
      })
        .then(res => res.json())
        .then(data => {
          alert(data.message);
          if (data.success) fetchProducts();
        })
        .catch(err => console.error("Lỗi xóa sản phẩm:", err));
    }
  });

  // ----------------------------
  // Cancel form sửa
  // ----------------------------
  document.getElementById("cancelEdit").addEventListener("click", () => {
    document.getElementById("editProductForm").style.display = "none";
  });

  // ----------------------------
  // Mua sản phẩm
  // ----------------------------
  document.getElementById("buySelectedBtn").addEventListener("click", () => {
    const selected = [...document.querySelectorAll(".select-product:checked")]
      .map(cb => cb.dataset.id);

    if (selected.length === 0) {
      alert("Vui lòng chọn ít nhất một sản phẩm.");
      return;
    }

    fetch("/Task1_VNPT_oktutay/backend/api/bill/buy.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ productIds: selected })
    })
      .then(res => res.json())
      .then(data => {
        alert(data.message);
        if (data.success) {
          fetchProducts(); // reload sau khi mua
        }
      })
      .catch(err => console.error("Lỗi mua hàng:", err));
  });
  // ----------------------------
  // Load sản phẩm ban đầu
  // ----------------------------
  fetchProducts();
});
