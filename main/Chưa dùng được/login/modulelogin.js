// Lắng nghe sự kiện mở popup đăng nhập/đăng ký
const popup = document.getElementById('popup');
const openPopup = document.getElementById('open-popup');
const loginTab = document.getElementById('login-tab');
const forgotTab = document.getElementById('forgot-tab');
const registerTab = document.getElementById('register-tab');

const loginForm = document.getElementById('login-form');
const forgotForm = document.getElementById('forgot-form');
const registerForm = document.getElementById('register-form');

// Mở popup khi nhấn nút "Tài khoản"
openPopup.addEventListener('click', () => {
  popup.classList.remove('hidden');
});

// Đóng popup
popup.addEventListener('click', (e) => {
  if (e.target === popup) {
    popup.classList.add('hidden');
  }
});

// Chuyển giữa các tab
loginTab.addEventListener('click', () => {
  loginTab.classList.add('active');
  forgotTab.classList.remove('active');
  registerTab.classList.remove('active');
  loginForm.classList.remove('hidden');
  forgotForm.classList.add('hidden');
  registerForm.classList.add('hidden');
});

forgotTab.addEventListener('click', () => {
  forgotTab.classList.add('active');
  loginTab.classList.remove('active');
  registerTab.classList.remove('active');
  forgotForm.classList.remove('hidden');
  loginForm.classList.add('hidden');
  registerForm.classList.add('hidden');
});

registerTab.addEventListener('click', () => {
  registerTab.classList.add('active');
  loginTab.classList.remove('active');
  forgotTab.classList.remove('active');
  registerForm.classList.remove('hidden');
  loginForm.classList.add('hidden');
  forgotForm.classList.add('hidden');
});

// Xử lý đăng nhập
document.getElementById('login-btn').addEventListener('click', (event) => {
  event.preventDefault();

  const email = document.getElementById('login-email').value;
  const password = document.getElementById('login-password').value;

  if (email && password) {
    sendRequest('login', email, password);
  } else {
    alert('Vui lòng điền đầy đủ thông tin đăng nhập.');
  }
});

// Xử lý quên mật khẩu
document.getElementById('reset-btn').addEventListener('click', (event) => {
  event.preventDefault();

  const forgotEmail = document.getElementById('forgot-email').value;

  if (forgotEmail) {
    sendRequest('forgot_password', forgotEmail);
  } else {
    alert('Vui lòng nhập email để reset mật khẩu.');
  }
});

// Xử lý đăng ký
document.getElementById('register-btn').addEventListener('click', (event) => {
  event.preventDefault();

  const registerEmail = document.getElementById('register-email').value;
  const registerPassword = document.getElementById('register-password').value;
  const firstName = document.getElementById('first-name').value;
  const lastName = document.getElementById('last-name').value;
  const phone = document.getElementById('phone').value;
  const gender = document.querySelector('input[name="gender"]:checked').value;
  const address = document.getElementById('address').value;

  if (registerEmail && registerPassword && firstName && lastName && phone && address) {
    sendRequest('register', registerEmail, registerPassword, firstName, lastName, phone, gender, address);
  } else {
    alert('Vui lòng điền đầy đủ thông tin.');
  }
});

// Hàm gửi yêu cầu đến server (ví dụ với AJAX)
function sendRequest(action, ...data) {
  console.log(`Action: ${action}`);
  console.log('Dữ liệu:', data);

  const formData = new FormData();
  formData.append('action', action);

  // Thêm dữ liệu vào FormData tùy theo hành động
  if (action === 'login' || action === 'forgot_password') {
    formData.append('email', data[0]);
    if (action === 'login') formData.append('password', data[1]);
  } else if (action === 'register') {
    const [email, password, firstName, lastName, phone, gender, address] = data;
    formData.append('email', email);
    formData.append('password', password);
    formData.append('first_name', firstName);
    formData.append('last_name', lastName);
    formData.append('phone', phone);
    formData.append('gender', gender);
    formData.append('address', address);
  }

  // Gửi yêu cầu AJAX đến PHP
  fetch('modulelogin.php', {
    method: 'POST',
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    alert(data.message);
    if (data.status === 'success' && data.redirect) {
      window.location.href = data.redirect;
    }
  })
  .catch(error => console.error('Error:', error));
}
