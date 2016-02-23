<?php
session_start();

// ���������� ��������� ����������, ���� �������� ����� �� ������
$tmp_dir = '';

if (!isset($_SESSION['upload_tmp_dir']))
{
    $tmp_dir_ok = false;
    
    $tmp_dir = ini_get('upload_tmp_dir');

    $tmp_dir_ok = true;

    // ���� ��������� upload_tmp_dir �� ������ � php.ini, ��
    if (!is_dir($tmp_dir) || $tmp_dir=='')
    {
        // ������������ ����� ���������� ��������� ���������� � �������
        $tmp_dir = dirname(tempnam('127631782631827', 'foo'));
    
        if (!is_dir($tmp_dir))
        {
            echo "<script>alert('�� ������� ���������� ��������� ���������� �������.');</script>";
            $tmp_dir_ok = false;
        }
    }

    // ��������� �������� ��������� ���������� � ������ 
    if ($tmp_dir_ok)
    {
        $_SESSION['upload_tmp_dir'] = $tmp_dir;
    }
}
?>
<html>
<head>
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
 
<script language="JavaScript">
<!--
var start_date = null;
var start_time = null;                  // �����, ��������� � �������������

var time_limit = 5;                    // ����-����� � �������� ��� ������ �������

var r = false;                          // XMLHttpRequest-object
var secs_elapsed = 0;                   // ����� ��������� � ������� ��������

var uploaded = false;                   // ���� ��������

function uploadFile()
{
    if (document.forms[0].upload_file.value!='')
    {
        document.getElementById('progressMess').innerHTML = '��������� ������ �������...';
            
        document.forms[0].btnupload.disabled = true;

        start_date = new Date();
        start_time = start_date.getTime();
            
        uploaded = false;
            
        document.getElementById('progressProcents').style.width = '0%';
            
        setTimeout("document.forms[0].submit()", 10);
        request();
    }
    else
    {
        alert('�� ������ ����!');
        return false;
    }
}

/**
 * ��������� ����������� ������ � �������
 */
function request()
{
    var n = new Date();
    
    var current_time = n.getTime();
    
    secs_elapsed = Math.round((current_time-start_time)/1000);
    iSecs = secs_elapsed%60;
    
    document.getElementById('progressTime').innerHTML = '00:'+ (iSecs>9 ? iSecs : '0'+iSecs);
    
    if (secs_elapsed>=time_limit)
    {
        document.getElementById('progressMess').innerHTML = '����� ����������� ������� �������...';
        document.forms[0].btnupload.disabled = false;
        
        return false;
    }
    
    r = false;
    
    try 
    {
        r = new XMLHttpRequest();
    }
    catch(trymicrosoft)
    {
        try
        {
            r = new ActiveXObject("Msxml2.XMLHTTP");
        }
        catch (othermicrosoft)
        {
            try
            {
                r = new ActiveXObject("Microsoft.XMLHTTP");
            }
            catch(failed)
            {
                r = false;
            }
        }
    }

    if (!r)
    {
        alert("Error initializing XMLHttpRequest! Your browser does not support XMLHttpRequest object.");
        window.close();
        return false;
    }
        
    r.open('GET', 'whileuploading.php', true);
    r.onreadystatechange = progressUpdate;
    r.send(null);
}

/**
 * ��������� ��������� ������� � ��������� ���������� ��������.
 */
function progressUpdate()
{
    if (r.readyState==4)
    {
        if (r.status==200)
        {
            /*
                ��������� ������������ � �������
            
                current_human_filesize|current_filesize|total_filesize
            */
            
            var response = r.responseText;
            alert(response);

            var t = response.split('|');
            
            if (t[0]!='undefined')
            {
                //uploaded = true;
            }
            
            document.getElementById('progressBytes').innerHTML = response;

            /*if (t[1]>0 && t[2]>0)
            {
                runProgressBar(Math.round(t[1]/t[2]*100));
            }*/
            
            if (uploaded && t[0]=='undefined')      // �������� ��� ���������
            {
                document.getElementById('progressMess').innerHTML = '�������� ���������...';
                document.forms[0].btnupload.disabled = false;
                
                return;
            }
            
            setTimeout('request()', 10);
        }
        else if (r.status == 404)
        {
            alert("Error: request URL does not exist");
        }
        else
        {
            alert("Error: status code is " + r.status);
        }
    }
}
//-->
</script>
</head>
<body leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" marginwidth="1" marginheight="1">
<iframe name="upload_frame" src="upload.php" width="1" height="1" style="border: 0;"></iframe>

<form method="post" action="upload.php" enctype="multipart/form-data" target="upload_frame">
<table border="0" cellpadding="2" cellspacing="1" width="60%" style="margin: 10px; border: 1px solid black;">
<tr>
    <td colspan="4"><input type="file" name="upload_file" class="input"></td>
</tr>
<tr>
    <td width="12%"><b>���������</b>:</td>
    <td width="16%" nowrap><span id="progressBytes" style="color: blue; font-size: 13px">undefined</span></td>
    <td width="10%" nowrap><span id="progressProcents">0</span> %</td>
    <td width="62%"><span id="progressMess"> </span></td>
</tr>
<tr>
    <td><b>�����</b>:</td>
    <td colspan="3"><span id="progressTime">00:00</span> ���.</td>
</tr>
<tr>
    <td colspan="4"><input type="button" name="btnupload" value="���������" onClick="uploadFile();"></td>
</tr>
</table>
</form>
</body>
</html>