<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Sheet</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>

<body style="margin: 0; padding: 0;">
    <table border="1" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
        <tr>
            <td rowspan="2"></td>
            <td colspan="2" style="text-align: center">Cài app</td>
        </tr>
        <tr>
            <td style="text-align: center">Cài app có HĐ FTEL</td>
            <td style="text-align: center">Cài app vãng lai</td>
        </tr>
        @foreach ($data as $key => $value)
            <tr>
                <td>{{ $value['date_report'] }}</td>
                <td>{{ $value['countInstallWithContract'] }}</td>
                <td>{{ $value['countInstallWithOutContract'] }}</td>
            </tr>
        @endforeach
    </table>
</body>
</html>