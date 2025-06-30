// Toggle form
// Khi người dùng bấm vào nút "Đăng ký"
document.getElementById("signUpButton").onclick = () => {
  // An form dang nhap
  document.getElementById("signIn").style.display = "none";
  // Hien form dang ky
  document.getElementById("signup").style.display = "block";
};
// Khi nguoi dung bam dang nhap
document.getElementById("signInButton").onclick = () => {
  // An form dang ky
  document.getElementById("signup").style.display = "none";
  // Hien form dang nhap
  document.getElementById("signIn").style.display = "block";
};

// Đăng nhập
document.getElementById("loginForm").addEventListener("submit", async (e) => {
  e.preventDefault();
  const form = e.target;
  const email = form.email.value.trim();
  const password = form.password.value.trim();

  const res = await fetch("../backend/api/auth/login.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ email, password })
  });

  const data = await res.json();
  if (res.ok) {
    window.location.href = data.user.role === "admin"
      ? "../public/dashboard/admin.php"
      : "../public/dashboard/user.php";
  } else {
    alert(data.message || "Sai thông tin đăng nhập");
  }
});

// Đăng ký
document.getElementById("registerForm").addEventListener("submit", async (e) => {
  e.preventDefault(); //Tránh reload lại trang
  const form = e.target;

  const body = { //body là 1 object java chứa thông tin như dưới
    fName: form.fName.value.trim(),
    lName: form.lName.value.trim(),
    email: form.email.value.trim(),
    password: form.password.value.trim(),
    role: "user"
  }; //lấy dữ liệu từ form và gán sẵn role là user

  const res = await fetch("../backend/api/user/create.php", {
    method: "POST", //post đến create.php
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(body) // chuyển dữ liệu thành json
  });

  const data = await res.json();
  if (res.ok) {
    alert("Đăng ký thành công! Vui lòng đăng nhập.");
    document.getElementById("signIn").style.display = "block";
    document.getElementById("signup").style.display = "none";
  } else {
    alert(data.message || "Đăng ký thất bại");
  }
});

//JavaScript thực hiện từng dòng một, theo trình tự thời gian.
//-> Tức là gọi cái nào trước thì cái đó hoạt động trước, còn cái nào lâu thời gian thì nó thực hiện luôn thằng sau, rồi đến khi nào thằng trước trả về thì hiện ra ở sau
//-> Sai tuần tự
//async (e) => { ... }: dùng hàm bất đồng bộ (async) để sử dụng await sau này (dừng đợi dữ liệu về).
//Vậy tại sao nên dùng await? -> bởi vì khi thực hiện thì trong hàm có rất nhiều câu lệnh khác nhau
//-> Và tất nhiên có hàm thực hiện luôn và có hàm phải tốn nhiều thao tác (kết nối, gửi thông tin,...)
//Vậy nên, dùng await là để chờ các câu lệnh nhiều thao tác đó thực hiện xong, để đúng theo trình tự chứ không bị leo nhau
// Tại sao không gọi await thuần mà phải gán const res = await... ?
//Bởi vì nhiều thao tác (dữ liệu, kết nối, gửi thông tin,...) nên gán vào biến sẽ lợi hơn để sử dụng từng cái trong các thao tác