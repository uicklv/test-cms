<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Teem</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            width: 100% !important;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            margin: 0;
            padding: 0;
            line-height: 100%;
            color: #000000;
            font-size: 14px;
        }

        [style*="Open Sans"] {
            font-family: 'Open Sans', arial, sans-serif !important;
        }

        img {
            outline: none;
            text-decoration: none;
            border: none;
            -ms-interpolation-mode: bicubic;
            max-width: 100% !important;
            margin: 0;
            padding: 0;
            display: block;
        }

        table td {
            border-collapse: collapse;
        }

        table {
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        .header-td {
            padding-top: 40px;
            padding-bottom: 25px;
        }

        .title {
            font-weight: 600;
            font-size: 20px;
        }

        .label {
            padding-top: 10px;
            padding-bottom: 10px;
            width: 35%;
            font-weight: 600;
        }


        @media (max-width: 620px) {

            .table-600 {
                width: 400px !important;
            }

            .table-460 {
                width: 360px !important;
            }
        }

        @media (max-width: 420px) {

            .table-600 {
                width: 300px !important;
            }

            .table-460 {
                width: 100% !important;
            }

            .main-tb {
                width: 90% !important;
            }
        }
    </style>
</head>

<body style="margin: 0; padding: 0;">
    <div style="font-size:0px;font-color:#ffffff;opacity:0;visibility:hidden;width:0;height:0;display:none;"></div>

    <!-- Email body -->
    <?php echo Mail::getBody(); ?>
    <!-- /Email body -->

    <?php /*<img src="<?= SITE_URL ?>es.png?token=<?= Mail::getToken() ?>" style="opacity: 0; width: 1px; height: 1px;"> */ ?>
</body>
</html>