<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{$.env.APP_NAME}</title>
    <style>
        body {
            background: #f7f7f7;
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            font-family: Arial, serif;
            font-size: 14px;
            color: #000;
        }
        table { border-spacing: 0; width: 100%; }
        table td { margin: 0; }
        body > table { width: 600px; margin: auto; }
        a { color: #369; outline: none; text-decoration: none; }
        pre { padding: 10px; background: #f5f5f5; border-radius: 5px; white-space: pre-line; }
        .content {
            height: 100px;
            vertical-align: top;
            background: #ffffff;
            border: 1px solid #e1ddcb;
            border-radius: 5px;
            padding: 30px;
        }
        .main-logo { padding: 20px 0; text-align: center; }
        .footer td { padding: 35px 0; }
        .footer .left { width: 150px; padding-left: 30px; }
        .footer .center a { vertical-align: middle; width: 30px; height: 30px; display: inline-block; }
        .footer .center img { width: 30px; height: 30px; }
        .footer .right { text-align: right; text-transform: uppercase; }
        .footer .right a { color: #999; font-weight: bold; padding-right: 30px; }

        {block 'style'}{/block}
    </style>
</head>
<body>
<table class="main">
    <thead>
    <tr>
        <td class="main-logo">
            <a href="{$.env.SITE_URL}" target="_blank">
                <img src="{$.env.SITE_URL}email/logo.png" srcset="{$.env.SITE_URL}email/logo@2x.png 2x" alt="{$.env.APP_NAME}" width="328" height="43" />
            </a>
        </td>
    </tr>
    </thead>
    <tbody>
    <tr>
        {block 'content-wrapper'}
            <td class="content">
                {block 'content'}{/block}
            </td>
        {/block}
    </tr>
    <tr>
        <td>
            <table class="footer">
                <tr>
                    <td class="left"></td>
                    <td class="center"></td>
                    <td class="right"><a href="{$.env.SITE_URL}" target="_blank">{$.env.APP_NAME}</a></td>
                </tr>
            </table>
        </td>
    </tr>
    </tbody>
</table>
</body>
</html>