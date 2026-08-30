<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;">
    <title>@yield('title')</title>
    <style type="text/css">
        div, p, a, li, td { -webkit-text-size-adjust:none; }
        .ReadMsgBody{width: 100%; background-color: #f3f3f3;}
        .ExternalClass{width: 100%; background-color: #f3f3f3;}
        body{width: 100%; height: 100%; background-color: #f3f3f3; margin:0; padding:0; -webkit-font-smoothing: antialiased;font-family: arial}
        html{width: 100%;}
        a {color: #002a80; text-decoration: none}
        a:hover{color: #001f3f}
        .hover:hover {opacity:0.90;filter:alpha(opacity=90);}
    </style>
</head>
<body>

<table width="95%" border="0" cellpadding="0" cellspacing="0" align="center">
    <tr>
        <td>
            <table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" style="box-shadow: 0px 4px 5px #EBEBEB; margin-top: 50px; margin-bottom: 100px;">
                <tr>
                    <td width="100%">

                        <table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
                            <tr>
                                <td width="100%" bgcolor="#ffffff" style="border-bottom: 1px solid #F3F3F3;">



                                    <table width="100%" border="0" cellpadding="30" cellspacing="0" align="center">
                                        <tr>
                                            <td width="100%" bgcolor="#FDFDFD">

                                                {!! $content !!}

                                            </td>
                                        </tr>
                                    </table>



                                </td>
                            </tr>
                        </table>


                        <table width="100%" border="0" height="100px" cellpadding="10" cellspacing="0" align="center" bgcolor="#33a0d5" style="color:#fff; background:#33a0d5">
                            <tr>
                                <td width="100%" bgcolor="#33a0d5">
                                    <h1 style="font-size:38px;color:#ffffff;text-transform:uppercase;text-align:center;margin:0;line-height: 1;">
                                        {{$heading}} </h1>
                                    <p style="color: #fff; text-align:center; display: block;font-size: 14px;font-weight: normal;letter-spacing: 1.5px;text-transform: none;">{{$subheading}}</p>

                                </td>
                            </tr>
                        </table>

                        <div style="text-align: center">
                            <p style="font-size: 14px; color: #7a7a7a; text-align: center; line-height: 24px;">&copy; Copyrights {{date('Y')}}  | <a href="{{ URL('/') }}">{{$heading}}</a></p>
                        </div>

                    </td>
                </tr>
            </table>

        </td>
    </tr>
</table>


</body>
</html>