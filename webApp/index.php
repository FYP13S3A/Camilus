<!doctype html>
<!--[if lt IE 7]> <html class="ie6 oldie"> <![endif]-->
<!--[if IE 7]>    <html class="ie7 oldie"> <![endif]-->
<!--[if IE 8]>    <html class="ie8 oldie"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="">
<!--<![endif]-->
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Untitled Document</title>
<link href="_css/boilerplate.css" rel="stylesheet" type="text/css">
<link href="_css/layout.css" rel="stylesheet" type="text/css">
<link href="_css/typography.css" rel="stylesheet" type="text/css" media="screen, projection">
<!-- 
To learn more about the conditional comments around the html tags at the top of the file:
paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/

Do the following if you're using your customized build of modernizr (http://www.modernizr.com/):
* insert the link to your js here
* remove the link below to the html5shiv
* add the "no-js" class to the html tags at the top
* you can also remove the link to respond.min.js if you included the MQ Polyfill in your modernizr build 
-->
<!--[if lt IE 9]>
<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<script src="_script/respond.min.js"></script>
</head>
<body>
<div class="gridContainer clearfix">
  <div class="mainContainer" id="mainContainer">
    <?php include("header.php"); ?>
    <div id="LoginBox">
      <div id="boxTitle">
        <h1>Camilus System Login</h1>
      </div>
      <div id="sysMsgContainer"></div>
      <div id="loginDesc">
        &nbsp;Use a valid username and password to gain access
        <table width="100%" border="0">
          <tr>
            <td align="center" valign="top" ><p><img src="_assests/padLock.jpg" alt="Lock" width="98" height="138"></td>
          </tr>
        </table>
      </div>
      <div id="sectionBox">
        <form name="form1" method="post" action="">
        <br><br>
        &nbsp;<label id="lblUsername">User Name:</label>
        <input name="" id="txtUsername" type="text" size="15"></input><br><br>
        &nbsp;<label id="lblPassword">Password:&nbsp;&nbsp;</label>
      <input name=""  id="txtPassword" type="password" size="15" /><br><br><br>
      &nbsp;<input name=""  id="btnSubmit" type="submit" value="Submit" /><br><br>
        </form>
      </div>
    </div>
     <?php include("footer.php"); ?>
     done
  </div>
</div>
</body>
</html>
