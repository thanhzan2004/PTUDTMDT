<?php
// Kết nối đến cơ sở dữ liệu
include('header.html');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact Us - Lemonade Cosmetics</title>
  <style>
    /* Contact Form - Smaller and Centered */
    #contact-form {
      max-width: 600px;
      margin: 0 auto; /* Center-align the form */
      padding: 20px;
      background-color: #fff;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    input, textarea {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border: 1px solid #ddd;
      border-radius: 4px;
      box-sizing: border-box;
    }

    .form-button {
      background-color: #d3d027;
      color: rgb(255, 255, 255);
      padding: 12px 20px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      width: 100%;
    }

    .form-button:hover {
      background-color: #45a049;
    }

    /* Footer Section */
    footer {
      text-align: center;
      background-color: #333;
      color: #fff;
      padding: 15px;
      margin-top: 40px;
    }
    /* Mobile Responsiveness */
    @media (max-width: 768px) {

      #contact-form {
        width: 100%;
        padding: 15px;
      }
    }
  </style>
</head>
<body>
  <!-- Contact Us Section -->
  <section id="contact-us" class="contact-us">
    <div class="container">
      <h2>Liên Hệ</h2>
      <p>Lemonade mong muốn lắng nghe ý kiến của bạn. Vui lòng gửi mọi yêu cầu, thắc mắc qua Zalo, Messenger, Instagram ở thanh liên hệ bên phải màn hình. Lemonade rất hân hạnh được hỗ trợ bạn. Mọi yêu cầu của bạn sẽ được xử lý và phản hồi trong thời gian sớm nhất.</p>
     <img src="https://theme.hstatic.net/1000303351/1001070461/14/page-about-new-image-2.png?v=1843" alt="Contact Us Image">
    </div>
  </section>
</body>
</html>

<?php
include('footer.html');
?>