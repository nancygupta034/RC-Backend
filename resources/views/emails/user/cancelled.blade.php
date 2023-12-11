<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Responsive Center</title>
    <style type="text/css">
    /* CLIENT-SPECIFIC STYLES */

    body,
    table,
    td,
    a {
        -webkit-text-size-adjust: 100%;
        -ms-text-size-adjust: 100%;
    }

    table,
    td {
        mso-table-lspace: 0pt;
        mso-table-rspace: 0pt;
        font-size:14px;
    }

    img {
        -ms-interpolation-mode: bicubic;
    }

    /* RESET STYLES */

    img {
        border: 0;
        line-height: 100%;
        outline: none;
        text-decoration: none;
    }

    table {
        border-collapse: collapse !important;
        font-size:14px;
    }

    body {
        height: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        width: 100% !important;
        font-size:14px;
    }

    .a6S {
        display: none !important;
        opacity: 0.01 !important;
    }

    /* If the above doesn't work, add a .g-img class to any image in question. */
    img.g-img+div {
        display: none !important;
    }

    /* iOS BLUE LINKS */

    a[x-apple-data-detectors] {
        color: inherit !important;
        text-decoration: none !important;
        font-size: inherit !important;
        font-family: inherit !important;
        font-weight: inherit !important;
        line-height: inherit !important;
    }

    body {
        margin-left: 0px;
        margin-top: 0px;
        margin-right: 0px;
        margin-bottom: 0px;
        background: #eee;
    }

    /* iPhone 4, 4S, 5, 5S, 5C, and 5SE */
    @media only screen and (min-device-width: 320px) and (max-device-width: 575px) {
        .email-container {
            min-width: 320px !important;
            width: 100% !important;
        }
        .br{
            display: none !important;
        }

        td[width="40"] {
            width: 15px !important;
        }

        td[width="40"] img {
            display: none !important;
        }

        td[width="520"] {
            width: auto !important;
        }
        img[width="520"],
        img[width="780"] {
            width: 100% !important;
            height: auto !important;
        }
        .d-btn{
            width: 240px !important;
            max-width: 240px !important;
        }

    }
</style>
</head>

<body>
    <table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#eeeeee">
        <tr>
            <td width="100%" valign="top" align="center">
                <table class="email-container" width="600" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
                    <tbody>
                        <tr>
                            <td>
                                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                                    <tbody>
                                        <tr>
                                            <td align="left" height="10px" valign="top" style="background-color:#3A89C9; height:10px;"></td>
                                        </tr>
                                        <tr>
                                            <td align="center" style="padding-top:15px; padding-bottom:15px; background-color:#E4F3FF;">
                                                <img src="https:/manage.rc.designthemes.com/assets/images/logo-email.png" alt="">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td align="center" valign="top" style="line-height:50%">
                                <img src="https:/manage.rc.designthemes.com/assets/images/spacer-40x50.png" width="40" height="40" alt="" style="display:block; text-align:center" />
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                                    <tbody>
                                        <tr>
                                            <td width="40" align="left" valign="top" style="line-height:50%"><img src="https:/manage.rc.designthemes.com/assets/images/spacer-40x50.png" width="40" height="70" alt="" style="display:block; text-align:center" /></td>
                                            <td width="520" align="left" valign="top">
                                                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                                                    <tbody>

                                                        <tr>
                                                            <td align="left" valign="top" style="font-size:22px;line-height: 30px; font-family:Arial; color:#000; font-weight:bold;">
                                                                Dear {{$name}},
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td align="left" valign="top" style="font-size:14px;line-height: 22px; font-family:Arial; color:#000; padding-top:15px;"> Your appointment (Ref No. <span style="color:#3A89C9">#{{$reference_no}}</span>) scheduled with <span style="color:#3A89C9">{{$clinicianName}}</span> on <span style="color:#3A89C9">{{$date}}</span> at <span style="color:#3A89C9">{{$time}}</span> has been cancelled.
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td align="left" valign="top" style="font-size:14px;line-height: 22px; font-family:Arial; color:#000; padding-top:15px;">
                                                                <b>Team</b><br>
                                                                Responsive Center
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                             <td height="20" style="font-size:40px; line-height:20px;">&nbsp;</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td width="40" align="right" valign="top" style="line-height:50%"><img src="https:/manage.rc.designthemes.com/assets/images/spacer-40x50.png" width="40" height="70" alt="" style="display:block; text-align:center" /></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td align="left" height="10px" valign="top" style="background-color:#3A89C9; height:10px;"></td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
