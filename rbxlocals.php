<html><body>
<?php
 error_reporting(0);
 
 $username = "jbcvupload";
 $password = "uploadfiles123";
 
 $Source = file_get_contents("php://input");
 $Uncompressed = gzinflate(substr($Source, 10, -8));
 if ($Uncompressed != "") {
  $Source = $Uncompressed;
 }
  
 $PostData = '<roblox xmlns:xmime="http://www.w3.org/2005/05/xmlmime" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="http://www.roblox.com/roblox.xsd" version="4">
 <External>null</External>
 <External>nil</External>
 <Item class="ModuleScript" referent="RBX4D11BCF7D08A4AC1A86EF6D9DA136530">
  <Properties>
   <Content name="LinkedSource"><null></null></Content>
   <string name="Name">MainModule</string>
   <ProtectedString name="Source"><![CDATA[return script.NewLocal]]></ProtectedString>
  </Properties>
  <Item class="LocalScript" referent="RBX0B551DA5583A403E83C5E77416B66DDB">
   <Properties>
    <bool name="Disabled">false</bool>
    <Content name="LinkedSource"><null></null></Content>
    <string name="Name">NewLocal</string>
    <ProtectedString name="Source"><![CDATA[' . $Source . ']]></ProtectedString>
   </Properties>
  </Item>
 </Item>
</roblox>';

 function curl($url, $post=false, $cookie=false){
  $ch = curl_init();
  curl_setopt ($ch, CURLOPT_URL, $url);
  curl_setopt ($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_REFERER, $url);
  //curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
  curl_setopt($ch, CURLOPT_AUTOREFERER, true);
  curl_setopt($ch, CURLOPT_TIMEOUT, 60);
  curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch,CURLOPT_HTTPHEADER,array('Host: www.roblox.com','Content-Type: application/xml','Connection: keep-alive','Accept: */*','X-requested-with: XMLHTMLRequest','Referer: http://www.roblox.com/places/229775498/update','Access-Control-Allow-Credentials: true','Access-Control-Allow-Origin: http://www.roblox.com'));
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  $agent = "Roblox/WinInet";
  curl_setopt($ch, CURLOPT_USERAGENT, $agent);
     if($cookie){
   curl_setopt($ch, CURLOPT_COOKIEFILE, md5( SHA1($username) . SHA1($password) ));
   curl_setopt($ch, CURLOPT_COOKIEJAR, md5( SHA1($username) . SHA1($password) ));
     }
     if($post){
   curl_setopt($ch,CURLOPT_HTTPHEADER,array('Content-Type: application/x-www-form-urlencoded'));
   curl_setopt($ch, CURLOPT_POST, true);
   curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
     }
     return  curl_exec($ch);
 }
 
 function CheckIfLoggedIn($Username){
  $UserInfo = curl("http://www.roblox.com/mobileapi/userinfo", false, $Username);
  
  $Data = json_decode($UserInfo);
  
  if($Data){
   return true;
  }else{
   return false;
  }
  
 }
  
 function Login($Username, $Password){
   file_put_contents(md5( SHA1($username) . SHA1($password) ),"");
  $Login = curl('https://www.roblox.com/newlogin', ('username=' . $Username . '&password=' . $Password), true);
  if (stristr($Login, 'Object moved')){
   return true;
  }else{
   return false;
  }
 }
 $response = curl("http://data.roblox.com/Data/Upload.ashx?assetid=0&type=Model&name=NewLocalScript&description=Blah&genreTypeId=1&ispublic=False&allowcomments=False",$PostData,true);
 if (stristr($response, 'Object moved')){
  Login($username,$password);
  $response = curl("http://data.roblox.com/Data/Upload.ashx?assetid=0&type=Model&name=NewLocalScript&description=Blah&genreTypeId=1&ispublic=False&allowcomments=False",$PostData,true);
 }
 $response = curl("http://www.roblox.com/ide/toolbox/items?category=MyModels&creatorId=86235999&keyword=&num=1&page=1&sort=Relevance",false,true);
 $data = json_decode($response,true);
 print($data["Results"][0]["Asset"]["Id"]);
?>
</body>
</html>
