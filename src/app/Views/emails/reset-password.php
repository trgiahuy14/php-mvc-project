<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <div style="font-family: Arial, sans-serif; background:#f6f9fc; padding:24px;">
        <div style="max-width:600px; margin:auto; background:#ffffff; border-radius:10px;
                     padding:28px; box-shadow:0 2px 8px rgba(0,0,0,0.05); border:1px solid #e5e7eb;">

            <h2 style="text-align:center; color:#2563eb; margin-bottom:20px;">Yêu cầu đặt lại mật khẩu</h2>
            <p style="color:#374151;">Xin chào <b><?= $fullname ?></b>,</p>
            <p style="color:#374151;">Bạn vừa gửi yêu cầu đặt lại mật khẩu cho tài khoản trên hệ thống <b><?= APP_NAME ?></b>.</p>
            <p style="color:#374151;">Để đặt lại mật khẩu, vui lòng nhấn vào nút bên dưới:</p>

            <div style="text-align:center; margin:30px 0;">
                <a href="<?= $resetLink ?>" style="background-color:#2563eb; color:#fff; text-decoration:none;
                padding:12px 24px; border-radius:8px; font-weight:bold; display:inline-block;">Đặt lại mật khẩu</a>
            </div>

            <p style="color:#374151;">Nếu bạn không yêu cầu thay đổi mật khẩu, hãy bỏ qua email này.
                Liên kết sẽ tự động hết hạn sau một khoảng thời gian ngắn để đảm bảo an toàn.</p>

            <br>
            <p>Trân trọng,</p>
            <p><b>Đội ngũ <?= APP_NAME ?></b></p>

        </div>

        <div style="text-align:center; color:#6b7280; font-size:12px; margin-top:18px;">
            <p style="margin:0;">Email này được gửi tự động, vui lòng không trả lời.</p>
        </div>
    </div>
</body>

</html>