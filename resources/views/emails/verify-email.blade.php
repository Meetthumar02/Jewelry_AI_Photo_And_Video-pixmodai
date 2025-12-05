<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Account</title>
</head>

<body style="margin:0; padding:0; background:#f4f6f8; font-family: Arial, Helvetica, sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td align="center" style="padding:40px 0;">

                <table width="600" cellpadding="0" cellspacing="0" border="0"
                    style="background:#ffffff; border-radius:8px; overflow:hidden; max-width:600px;">

                    <!-- Header -->
                    <tr>
                        <td
                            style="background:#0d6efd; color:#ffffff; padding:20px 30px; text-align:center; font-size:22px; font-weight:bold;">
                            JW AI
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding:30px; color:#333333; font-size:15px; line-height:1.7;">

                            <p style="margin:0 0 15px 0;">Hello <strong>{{ $user->name }}</strong>,</p>

                            <p style="margin:0 0 15px 0;">
                                Welcome to <strong>JW AI</strong>!
                                We're excited to have you on board.
                            </p>

                            <p style="margin:0 0 15px 0;">
                                Please verify your email address to activate your account:
                            </p>

                            <p
                                style="margin:0 0 15px 0; word-break:break-all; font-size:13px; background:#f8f9fa; padding:10px; border-radius:4px;">
                                <a href="{{ $verifyLink }}" target="_blank"
                                    style="color:#0d6efd; text-decoration:underline;">
                                    {{ $verifyLink }}
                                </a>
                            </p>

                            <p style="margin:0 0 15px 0; font-size:14px; color:#666;">
                                If you did not create this account, you can safely ignore this email.
                            </p>

                            <p style="margin:30px 0 0 0;">
                                Regards,<br>
                                <strong>JW AI Team</strong>
                            </p>

                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background:#f1f1f1; padding:15px; text-align:center; font-size:12px; color:#777;">
                            © {{ date('Y') }} JW AI. All rights reserved.
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>
</body>

</html>
