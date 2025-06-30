document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(".btn-delete").forEach(btn => {
    btn.addEventListener("click", async () => {
      if (!confirm("Bạn có chắc chắn muốn xóa mục này?")) return;
      const id = btn.dataset.id;
      const type = btn.dataset.type; // 'bill' hoặc 'user'

      const res = await fetch(`/backend/api/${type}/delete.php`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id })
      });
      const result = await res.json();

      if (res.ok) {
        alert("Đã xóa thành công!");
        location.reload();
      } else {
        alert(result.message || "Xóa thất bại");
      }
    });
  });
});
