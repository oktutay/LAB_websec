// public/assets/js/dashboard.js
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
  const user = window.CURRENT_USER;
  // Admin thì show bảng user, user thường thì show bảng hóa đơn
  const containerId = user.role === "admin" ? "userList" : "invoiceList";
  const container = document.getElementById(containerId);

  // Xác định URL API để fetch
  const listUrl =
    user.role === "admin"
      ? "/Task1_VNPT_oktutay/backend/api/user/list.php"
      : `/Task1_VNPT_oktutay/backend/api/bill/list.php?userId=${user.id}`;

  // Lấy danh sách
  fetch(listUrl)
    .then(res => {
      const ct = res.headers.get("Content-Type") || "";
      if (!res.ok) throw new Error(`HTTP ${res.status}`);
      if (!ct.includes("application/json")) throw new Error("Invalid JSON");
      return res.json();
    })
    .then(data => {
      if (!Array.isArray(data) || data.length === 0) {
        container.innerHTML = "<p class='text-danger'>Không có dữ liệu.</p>";
        return;
      }

      // Tạo table
      const table = document.createElement("table");
      table.className = "table table-bordered";

      // HEAD
      let thead = "<thead><tr>";
      if (user.role === "admin") {
        thead +=
          "<th>ID</th><th>Họ tên</th><th>Email</th><th>Vai trò</th><th>Trạng thái</th><th>Hành động</th>";
      } else {
        thead += "<th>ID</th><th>Ngày</th><th>Tổng tiền</th><th>Hành động</th>";
      }
      thead += "</tr></thead>";

      // BODY
      const rows = data.map(item => {
        if (user.role === "admin") {
          // Nút duyệt / từ chối
          let approveSection = "";
          if (item.status === "pending") {
            //định nghĩa approveSection
            approveSection = `
              <button class="btn btn-success btn-approve" data-id="${item.id}">
                Duyệt
              </button>
              <button class="btn btn-danger btn-reject" data-id="${item.id}">
                Từ chối
              </button>
            `;
          } else {
            approveSection ="";
          }

          return `
            <tr>
              <td>${item.id}</td>
              <td>${escapeHtml(item.firstName)} ${escapeHtml(item.lastName)}</td>
              <td>${escapeHtml(item.email)}</td>
              <td>${escapeHtml(item.role)}</td>
              <td>${escapeHtml(item.status)}</td>
              <td>
                <button
                  class="btn btn-warning btn-edit"
                  data-id="${item.id}"
                  data-first="${item.firstName}"
                  data-last="${item.lastName}"
                  data-email="${item.email}"
                  data-role="${item.role}"
                >Sửa</button>
                <button
                  class="btn btn-danger btn-delete"
                  data-id="${item.id}"
                  data-type="user"
                >Xóa</button>
                ${approveSection}
              </td>
            </tr>
          `;
        } else {
          // Với user thường, hiện hóa đơn kèm sản phẩm đã mua
const productList = item.items?.map(p => `
  <li class="mb-1">
    <strong>${escapeHtml(p.name)}</strong><br>
    Số lượng: ${p.quantity} x ${Number(p.unit_price).toLocaleString("vi-VN")} ₫ = 
    <strong>${Number(p.line_total).toLocaleString("vi-VN")} ₫</strong><br>
    <small>${escapeHtml(p.description || "")}</small>
  </li>
`).join("") || "<li>Không có sản phẩm nào</li>";

return `
  <tr>
    <td>${item.id}</td>
    <td>${item.date}</td>
    <td>
      ${Number(item.total).toLocaleString("vi-VN")} ₫<br>
      <ul class="mt-2">${productList}</ul>
    </td>
    <td>
      <button class="btn btn-sm btn-success btn-export-excel" data-id="${item.id}">
        Xuất hóa đơn
      </button>
    </td>
  </tr>
`;

        }
      });

      table.innerHTML = thead + "<tbody>" + rows.join("") + "</tbody>";
      container.appendChild(table);
    })
    .catch(err => {
      console.error("Lỗi tải dữ liệu:", err);
      container.innerHTML = "<p class='text-danger'>Đã xảy ra lỗi.</p>";
    });

  // Nếu là admin, lấy tiếp thống kê hóa đơn
  if (user.role === "admin") {
    fetch("/Task1_VNPT_oktutay/backend/api/bill/stats.php")
      .then(res => res.json())
      .then(stat => {
        document.getElementById("totalBills").textContent =
          stat.totalBills || 0;
        document.getElementById("totalRevenue").textContent =
          (stat.totalRevenue || 0).toLocaleString("vi-VN") + " ₫";
      })
      .catch(err => console.error("Lỗi lấy thống kê:", err));
  }
});

// Xử lý nút in
document.addEventListener("click", e => {
  if (e.target.classList.contains("btn-export-excel")) {
    const invoiceId = e.target.dataset.id;

    fetch(`/Task1_VNPT_oktutay/backend/api/bill/get-detail.php?billId=${invoiceId}`)
      .then(res => res.json())
      .then(data => {
        if (!data.success) {
          alert("Không thể lấy thông tin hóa đơn.");
          return;
        }

        const bill = data.bill;
        const items = data.items || [];

        const rows = [];

        // Dòng tiêu đề
        rows.push([`HÓA ĐƠN MUA HÀNG #${bill.id}`]);
        rows.push([`Ngày: ${bill.date}`]);
        rows.push([`Người mua: ${bill.firstName} ${bill.lastName}`]);
        rows.push([]);
        
        // Header bảng
        rows.push(["Tên sản phẩm", "Mô tả", "Người bán", "Số lượng", "Đơn giá", "Thành tiền"]);

        // Dữ liệu sản phẩm
        items.forEach(p => {
          rows.push([
            p.name,
            p.description || "",
            `${p.sellerFirst} ${p.sellerLast}`,
            p.quantity,
            Number(p.unit_price).toLocaleString("vi-VN"),
            Number(p.line_total).toLocaleString("vi-VN")
          ]);
        });

        // Tổng cộng
        rows.push([]);
        rows.push(["", "", "", "", "Tổng tiền:", Number(bill.total).toLocaleString("vi-VN") + " ₫"]);

        // Xuất file
        const ws = XLSX.utils.aoa_to_sheet(rows);
        const wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, "Hóa đơn");

        const filename = `hoadon_${bill.id}_${bill.date}.xlsx`;
        XLSX.writeFile(wb, filename);
      })
      .catch(async err => {
  let text = await err?.response?.text?.();  // phòng khi response có JSON lỗi
  console.error("Chi tiết lỗi:", err, text);
  alert("Lỗi khi xuất hóa đơn.");
});
  }
});
// Đăng ký 1 listener duy nhất cho toàn document để xử lý các nút
document.addEventListener("click", e => {
  const btn = e.target;

  // Sửa user
  if (btn.classList.contains("btn-edit")) {
    const id = btn.dataset.id;
    const first = btn.dataset.first;
    const last = btn.dataset.last;
    const email = btn.dataset.email;
    const role = btn.dataset.role;

    const input = prompt(
      "Nhập theo định dạng: Họ|Tên|Email|Role",
      `${first}|${last}|${email}|${role}`
    );
    if (!input) return;

    const [firstName, lastName, emailNew, roleNew] = input.split("|");
    fetch("/Task1_VNPT_oktutay/backend/api/user/update.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        id,
        firstName,
        lastName,
        email: emailNew,
        role: roleNew
      })
    })
      .then(res => res.json())
      .then(data => {
        alert(data.message);
        location.reload();
      })
      .catch(err => console.error("Lỗi cập nhật user:", err));
  }

  // Xóa user hoặc xóa data khác nếu có
  if (btn.classList.contains("btn-delete")) {
    const id = btn.dataset.id;
    const type = btn.dataset.type; // 'user' hoặc 'bill'...
    if (!confirm("Bạn có chắc muốn xóa?")) return;

    fetch(`/../Task1_VNPT_oktutay/backend/api/${type}/delete.php`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ id })
    })
      .then(res => res.json())
      .then(data => {
        alert(data.message);
        location.reload();
      })
      .catch(err => console.error("Lỗi xóa:", err));
  }

  // Duyệt / Từ chối user
  if (
    btn.classList.contains("btn-approve") ||
    btn.classList.contains("btn-reject")
  ) {
    const id = btn.dataset.id;
    const action = btn.classList.contains("btn-approve")
      ? "approve"
      : "reject";

    fetch(`/Task1_VNPT_oktutay/backend/api/user/${action}.php`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ id })
    })
      .then(res => res.json())
      .then(data => {
        alert(data.message);
        location.reload();
      })
      .catch(err => console.error("Lỗi duyệt/từ chối:", err));
  }
});
