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

            <h2 style="text-align:center; color:#2563eb !important; margin-bottom:20px;">Đổi mật khẩu thành công</h2>

            <p style="color:#374151 !important; margin:0 0 12px;">
                Xin chào <span style="color:#374151 !important;"><b><?= $fullname ?></b></span>,
            </p>

            <p style="color:#374151 !important; margin:0 0 12px;">
                Mật khẩu tài khoản của bạn trên hệ thống <span style="color:#374151 !important;"><b><?= APP_NAME ?></b></span> đã được thay đổi thành công.
            </p>

            <p style="color:#374151 !important; margin:0 0 24px;">
                Nếu bạn không thực hiện thay đổi này, vui lòng đặt lại mật khẩu ngay bằng cách chọn “Quên mật khẩu” tại trang đăng nhập để đảm bảo an toàn tài khoản.
            </p>

            <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" style="margin:30px auto;">
                <tr>
                    <td bgcolor="#2563eb" style="border-radius:8px;">
                        <a href="<?= BASE_URL ?>/login"
                            style="display:inline-block; padding:12px 24px; border-radius:8px; 
                          background:#2563eb !important; border:1px solid #2563eb !important; 
                          color:#ffffff !important; text-decoration:none !important; font-weight:700;">
                            <span style="color:#ffffff !important; text-decoration:none !important;">Đăng nhập ngay</span>
                        </a>
                    </td>
                </tr>
            </table>

            <p style="color:#374151 !important; margin:0 0 12px;">Cảm ơn bạn đã sử dụng hệ thống <span style="color:#374151 !important;"><b><?= APP_NAME ?></b></span>.</p>
            <br>
            <p style="color:#374151 !important; margin:0 0 4px;">Trân trọng,</p>
            <p style="color:#374151 !important; margin:0;"><b>Đội ngũ><?= APP_NAME ?></b></p>

        </div>

        <div style="text-align:center; color:#6b7280 !important; font-size:12px; margin-top:18px;">
            <p style="margin:0; color:#6b7280 !important;">Email này được gửi tự động, vui lòng không trả lời.</p>
        </div>
    </div>
</body>

</html>