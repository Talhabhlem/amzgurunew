<?php
/*
  Coded by: Sandip Debnath
  Email: sandip5004@gmail.com
  Site: http://www.amazon.com
*/
error_reporting(E_ALL);
session_start();
$rand=date("dmYhis");
$_SESSION['RAND']=$rand;
header('Content-Type: text/html; charset=utf-8');
define ("BROWSED_LINKS_TO_FILE", dirname(__FILE__) . "/browsed.txt");
//define ("FIXED_AMT", dirname(__FILE__) . "/fixed.txt");
define ("UPCFILE", dirname(__FILE__) . "/upc-".$rand.".txt");
define ("CSV", dirname(__FILE__) . "/amazon-".$rand.".csv");
define ("DBPATH", dirname(__FILE__) . "/results-".$rand.".db");
define ("PROXYLIST", dirname(__FILE__) . "/proxy.txt");
//define ("EMAIL", dirname(__FILE__) . '/' . "email.txt");
define ("UALIST", dirname(__FILE__) . '/' . "useragent.txt");
define ("COOKIES", dirname(__FILE__) . '/' . "cookies.txt");
require_once ("smtp/email.php");

echo "<pre>";

ob_implicit_flush(true);
set_time_limit(0);
ini_set('memory_limit', '1900M');
$flag = true;

if(isset($_POST['submit'])) {
    $email=$_POST['email'];
    $ext=$_POST['ext'];
    $fixed=$_POST['fixed'];
    if ($fixed!=''){
        $_SESSION['ERROR']='Invalid Percentage';
        header("Location : ".$_SERVER['HTTP_REFERER']);
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $_SESSION['ERROR']='Invalid Email';
        header("Location : ".$_SERVER['HTTP_REFERER']);
    }
    //file_put_contents(EMAIL,$email);
    if(move_uploaded_file($_FILES['upcfile']['tmp_name'], UPCFILE)) {
        $upcs=file_get_contents(UPCFILE);
        unlink(UPCFILE);
        $obj = new amazon($upcs,$ext, $fixed);
    }
    else{
        $_SESSION['ERROR']='Problem Uploading UPC file';
        header("Location : ".$_SERVER['HTTP_REFERER']);
    }
}
else
    header("Location : ".$_SERVER['HTTP_REFERER']);
class database{
    function createDatabase(){
        @unlink(DBPATH);
        $db = new SQLite3(DBPATH);
        $db->exec('CREATE TABLE IF NOT EXISTS products  (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            url varchar (255),
            title varchar (255),
            price VARCHAR (255),
            reviews VARCHAR (255),
            sellers VARCHAR (255),
            asin VARCHAR (255),
            bsr VARCHAR (255),
            category VARCHAR (255),
            stock VARCHAR (255),
            fba_fbm VARCHAR (255),
            productCategory VARCHAR (255),
            weight VARCHAR (255),
            upc VARCHAR (255),
            grossProfit VARCHAR (255),
            margin VARCHAR (255),
            cost VARCHAR (255),
            modified CURRENT_TIMESTAMP
            )');
    }
    function saveinDB($array){
        $now=date("d-m-Y H:i:s");
        $db = new SQLite3(DBPATH);
        $array['title']=mb_convert_encoding($array['title'], 'UTF-8','UTF-16LE');
        $array['price']=mb_convert_encoding($array['price'], 'UTF-8','UTF-16LE');
        $array['reviews']=mb_convert_encoding($array['reviews'], 'UTF-8','UTF-16LE');
        $array['sellers']=mb_convert_encoding($array['sellers'], 'UTF-8','UTF-16LE');
        $array['asin']=mb_convert_encoding($array['asin'], 'UTF-8','UTF-16LE');
        $array['bsr']=mb_convert_encoding($array['bsr'], 'UTF-8','UTF-16LE');
        $array['category']=mb_convert_encoding($array['category'], 'UTF-8','UTF-16LE');
        $array['stock']=mb_convert_encoding($array['stock'], 'UTF-8','UTF-16LE');
        $array['productCategory']=mb_convert_encoding($array['productCategory'], 'UTF-8','UTF-16LE');
        $array['weight']=mb_convert_encoding($array['weight'], 'UTF-8','UTF-16LE');

        $id=$this->doesUPCexist($array['upc']);
        if(!$id) {
            $db->exec("INSERT INTO products (`url`,`title`,`price`,`reviews`,`sellers`,`asin`,`bsr`,`category`,`stock`,`fba_fbm`,`productCategory`,`weight`,`upc`,`grossProfit`,`margin`,`cost`,`modified`)
                    VALUES ('" . addslashes($array['url']) . "','" . addslashes($array['title']) . "','" . addslashes($array['price']) . "','" . addslashes($array['reviews']) . "','" . addslashes($array['sellers']) . "',
                    '" . addslashes($array['asin']) . "','" . addslashes($array['bsr']) . "','" . addslashes($array['category']) . "','" . addslashes($array['stock']) . "','" . addslashes($array['fba_fbm']) . "',
                    '" . addslashes($array['productCategory']) . "','" . addslashes($array['weight']) . "','" . addslashes($array['upc']) . "','" . addslashes($array['grossProfit']) . "','" . addslashes($array['margin']) . "','" . addslashes($array['cost']) . "','" . $now . "')");
        }
        else{
            $db->exec('UPDATE products SET `url`="' . addslashes($array['url']) . '", `title`="' . addslashes($array['title']) . '",`price`="' . addslashes($array['price']) . '",
            `reviews`="' . addslashes($array['reviews']) . '",`sellers`="' . addslashes($array['sellers']) . '",`asin`="' . addslashes($array['asin']) . '",`bsr`="' . addslashes($array['bsr']) . '",
            `category`="' . addslashes($array['category']) . '",`stock`="' . addslashes($array['stock']) . '",`fba_fbm`="' . addslashes($array['fba_fbm']) . '",
            `productCategory`="' . addslashes($array['productCategory']) . '",`weight`="' . addslashes($array['weight']) . '",`upc`="' . addslashes($array['upc']) . '",
            `grossProfit`="' . addslashes($array['grossProfit']) . '",`margin`="' . addslashes($array['margin']) . '",`cost`="' . addslashes($array['cost']) . '",
            `modified`="' . $now . '" WHERE `id`='.$id);
        }
    }
    function doesUPCexist($upc){
        $db = new SQLite3(DBPATH);
        $result=$db->querySingle('SELECT * FROM products WHERE `upc`=\''.$upc.'\'',true);
        if(@$result['id']!='')
            return $result['id'];
        else
            return false;
    }
}
class extractFunctions extends database{
    function weight($contents){
        $pattern = '/\s+Weight.*([0-9\.\,]+\s+[a-zA-z]+)\W/Usi';
        preg_match($pattern, $contents, $result);
        $weight = html_entity_decode(trim($result[1]), ENT_QUOTES, 'utf-8');
        return $weight=mb_convert_encoding($weight, 'UTF-16LE', 'UTF-8');
    }
    function productCategory($contents){
        $pattern = '/breadcrumbs\_container[\"\'].*<ul.*>(.*)<\/ul>/Usi';
        preg_match($pattern, $contents, $result);
        $cont=$result[1];

        $pattern = '/<li.*>(.*)<\/li>/Usi';
        preg_match_all($pattern, $cont, $result);

        $breadcrmb='';

        foreach($result[1] as $li)
            $breadcrmb.=trim(preg_replace('/\s\s+/Usi','',preg_replace('/<.*>/Usi','',$li)));

        if($breadcrmb!=''){
            $breadcrmb = html_entity_decode(trim($breadcrmb), ENT_QUOTES, 'utf-8');
            return $breadcrmb=mb_convert_encoding($breadcrmb, 'UTF-16LE', 'UTF-8');
        }

        $pattern = '/<select\s*class=[\'\"]nav\-search\-dropdown.*data\-nav\-selected=[\'\"](.*)[\'\"].*>(.*)<\/select/Usi';
        preg_match($pattern, $contents, $result);
        $loc = trim($result[1]);
        if(is_numeric($loc)) {
            $catopt = trim($result[2]);
            $pattern = '/<option.*>(.*)<\/option/Usi';
            preg_match_all($pattern, $catopt, $result);
            $cat=$result[1][$loc-1];
            $cat = html_entity_decode(trim($cat), ENT_QUOTES, 'utf-8');
            return $cat=mb_convert_encoding($cat, 'UTF-16LE', 'UTF-8');
        }
    }
    function fb_a_m($contents){
        if (stristr($contents, 'sold by Amazon') || stristr($contents, 'Fulfilled by Amazon'))
            $fb_a_m = 'FBA';
        else
            $fb_a_m = 'FBM';
        return $fb_a_m;
    }
    function stock($contents){
        $pattern = '/<span\s*id=[\"\']availability[\"\'].*>(.*)<\/span>/Usi';
        preg_match($pattern, $contents, $result);
        $stock = trim($result[1]);

        if ($stock == '') {
            if (stristr($contents, 'In Stock.') && !stristr($contents,'Currently unavailable.'))
                $stock = 'In Stock';
            else
                $stock = 'Out Of Stock';
        }
        $stock = html_entity_decode(trim($stock), ENT_QUOTES, 'utf-8');
        return $stock=mb_convert_encoding($stock,'UTF-16LE','UTF-8');
    }
    function category($contents){
        $pattern = '/Best\s*Sellers\s*Rank.*\#([0-9\s \,\.]+)\s*in\s*(.*)\(/Usi';
        preg_match($pattern, $contents, $result);
        $category = trim($result[2]);
        $category = html_entity_decode(trim($category), ENT_QUOTES, 'utf-8');
        return $category=mb_convert_encoding($category, 'UTF-16LE', 'UTF-8');
    }
    function bsr($contents){
        $pattern = '/Best\s*Sellers\s*Rank.*\#([0-9\s \,\.]+)\s*in\s*(.*)\(/Usi';
        preg_match($pattern, $contents, $result);
        $bsr = trim($result[1]);
        $bsr = html_entity_decode(trim($bsr), ENT_QUOTES, 'utf-8');
        return $bsr=mb_convert_encoding($bsr, 'UTF-16LE', 'UTF-8');
    }
    function asin($contents){
        $pattern = '/asin=(.*)[\&\;\"]/Usi';
        preg_match($pattern, $contents, $result);
        $asin = str_ireplace('"','',trim($result[1]));
        $asin = html_entity_decode(trim($asin), ENT_QUOTES, 'utf-8');
        return $asin=mb_convert_encoding($asin, 'UTF-16LE', 'UTF-8');
    }
    function sellers($contents){
        $pattern = '/Other\s*Sellers\s*on\s*Amazon.*Sold\s*by\:.*<a.*>(.*)<\/a>/Usi';
        preg_match($pattern, $contents, $result);
        $sellers = trim($result[1]);
        @$sellers = trim(preg_replace('/[^0-9]+/Usi', '', $sellers));
        $sellers = html_entity_decode(trim($sellers), ENT_QUOTES, 'utf-8');
        return $asin=mb_convert_encoding($sellers, 'UTF-16LE', 'UTF-8');
    }
    function reviews($contents){
        $pattern = '/id=[\"\']summaryStars[\"\'].*<a.*>(.*)<\/a>/Usi';
        preg_match($pattern, $contents, $result);
        $reviews = trim($result[1]);
        $reviews = trim(preg_replace('/<.*>/Usi', '', $reviews));
        $reviews = html_entity_decode(trim($reviews), ENT_QUOTES, 'utf-8');
        return $reviews=mb_convert_encoding($reviews, 'UTF-16LE', 'UTF-8');
    }
    function price($contents){
        $pattern = '/<div\s*id=[\"\']atfResults.*s\-price.*>(.*)</Usi';
        preg_match($pattern, $contents, $result);
        $price = trim($result[1]);
        if($price==''){
            $pattern = '/<div\s*id=[\"\']atfResults.*a\-color\-price.*>(.*)</Usi';
            preg_match($pattern, $contents, $result);
            $price = trim($result[1]);
        }
        $price = html_entity_decode(trim($price), ENT_QUOTES, 'utf-8');
        return $price=mb_convert_encoding($price, 'UTF-16LE', 'UTF-8');

    }
    function name($contents){
        $pattern = '/<div\s*id=[\"\']atfResults.*<h2.*>(.*)<\/h2>/Usi';
        preg_match($pattern, $contents, $result);
        if (trim($result[1]) == '') {
            $pattern = '/<span\s*id=[\"\']btAsinTitle[\"\'].*>(.*)<\/span>/Usi';
            preg_match($pattern, $contents, $result);
        }
        $name = preg_replace('/\s\s+/Usi', ' ', preg_replace('/<.*>/Usi', '', trim($result[1])));
        $name = html_entity_decode(trim($name), ENT_QUOTES, 'utf-8');
        return $name=mb_convert_encoding($name, 'UTF-16LE', 'UTF-8');
    }
    function produrl($contents){
        $pattern='/<div\s*id=[\"\']atfResults.*<a.*href=[\'\"](.*)[\'\"]/Usi';
        preg_match($pattern,$contents,$result);
        $produrl=trim($result[1]);
        $produrl=html_entity_decode(urldecode($produrl));
        return $produrl;
    }
}
class amazon extends extractFunctions
{
    function __construct($upcs,$ext, $fixed){
        database::createDatabase();
        $this->newcsv(CSV);
        $contents=$upcs;//file_get_contents(UPCFILE);
        //$contents=$upc;
        $upcarray=explode(PHP_EOL,$contents);
        foreach($upcarray as $upc){
            $arr=explode('-',$upc);
            $upc=$arr[0];
            @$cost=$arr[1];
            if(trim($upc)!='') {
                if($cost!='')
                    $this->browseAmazon(trim($upc),$ext,$fixed,$cost);
                else
                    $this->browseAmazon(trim($upc),$ext,$fixed);
            }
        }
    }
    function __destruct(){
        unlink(COOKIES);
        global $email;
        $_SESSION['ERROR']='An Email Has Been Sent to '.$email.' Which has the csv file amazon-'.$_SESSION['RAND'].'.csv as attachment';
        $emailid=$email;//file_get_contents(EMAIL);
        $email = new amazonEmail();
        // $email->sendEmailWithAttachment($emailid,'amazon-'.$_SESSION['RAND'].'.csv');
        if ($email->sendEmailWithAttachment($emailid,'amazon-'.$_SESSION['RAND'].'.csv')) {
            $_SESSION['csvToolSuccess'] = 1;
        } else {
            $_SESSION['csvToolSuccess'] = 0;
        }

//        echo("<pre>");
//        print_r($_SESSION);

//        exit;
        ?>
        <script>
            window.location.href='http://stats.ecommelite.com/upcTool?success=<?php echo $_SESSION['csvToolSuccess'] ?>';
        </script>
        <?php
    }
    function browseAmazon($upc,$ext='com',$fixed,$cost=0){
        echo $upc.'<br>';
        $url='http://www.amazon.'.$ext.'/s/ref=nb_sb_noss?&field-keywords='.$upc;
        $contents=curl_download($url,true);
        $produrl=$this->produrl($contents);
        if(trim($produrl)!='') {
            $name=$this->name($contents);
            $price=$this->price($contents);
            $contents = curl_download($produrl,true);
            $reviews=$this->reviews($contents);
            $sellers=$this->sellers($contents);
            $asin=$this->asin($contents);
            $bsr=$this->bsr($contents);
            $category=$this->category($contents);
            $stock=$this->stock($contents);
            $fb_a_m=$this->fb_a_m($contents);
            $productCategory=$this->productCategory($contents);
            $weight=$this->weight($contents);
            $tmpprice=$price;
            if(trim($price)=='')
                $stock="Out Of Stock";
            if($cost!=0) {
                $price=(float) $this->formatPriceToEng($price);
                $fixedprice = (float) $fixed;//file_get_contents(FIXED_AMT);
                $fixedprice = (float) $fixedprice;
                $grossProfit = (float) ($price * $fixedprice) - $cost;
                $margin=(float) $grossProfit/$price;
                $grossProfit=round($grossProfit, 2);
                $margin=round($margin, 2);
            }
            else
                $grossProfit=$margin=$cost;

            $price=$tmpprice;
            $array = array(
                "url"=>$produrl,
                "title" => $name,
                "price" => $price,
                "reviews" => $reviews,
                "sellers" => $sellers,
                "asin" => $asin,
                "bsr" => $bsr,
                "category" => $category,
                "stock" => $stock,
                "fba_fbm" => $fb_a_m,
                "productCategory"=>$productCategory,
                "weight"=>$weight,
                "upc"=>$upc,
                "grossProfit"=>$grossProfit,
                "margin"=>$margin,
                "cost"=>$cost
            );

            if($array['asin']!='') {
                $this->saveincsv(CSV, $array);
                database::saveinDB($array);
            }

            print_r($array) ;
            flush();
            ob_flush();
        }
    }
    function formatPriceToEng($price){
        $price=preg_replace('/[^0-9\.\,]+/Usi','',$price);
        $arr=str_split($price,1);
        $count=count($arr);
        $decsep=$arr[$count-3];
        if($decsep!=',' && $decsep!='.' ){
            $price=str_ireplace(',','',$price);
            return $price=str_ireplace('.','',$price);
        }
        elseif($decsep==','){
            $price=str_ireplace('.','',$price);
            return $price=str_ireplace(',','.',$price);
        }
        elseif($decsep=='.'){
            return $price=str_ireplace(',','',$price);
        }
    }
    function createdir(){
        $dir = DIR;
        if(!file_exists($dir) && !is_dir($dir)){
            mkdir($dir,0777);
        }
    }
    function isincsv($pid, $csvfile)
    {
        $file = fopen($csvfile, 'r');
        while (($line = fgetcsv($file)) !== FALSE) {
            foreach ($line as $val) {
                if (stristr($val, $pid))
                    return true;
            }
        }
        fclose($file);
        return false;
    }
    function saveincsv($csvfile, $array)
    {
        $fp = fopen($csvfile, 'a') or die("can't open csv file");;
        $list[] = $array;
        foreach ($list as $fields) {
            fputcsv($fp, $fields);
        }
        fclose($fp);
    }
    function newcsv($csvfile)
    {   if(file_exists($csvfile))
            unlink($csvfile);
        if(!file_exists($csvfile))
        {
            $list = array();
            $fp = fopen($csvfile, 'w+');
            $list[] = array('url','title','price','reviews','sellers','asin','bsr','category','stock','fba_fbm','productCategory','weight','UPC','Gross Profit','Margin','Cost');
            foreach ($list as $fields) {
                fputcsv($fp,$fields);
            }
            fclose($fp);
        }
    }
    function notbrowsed($name,$storecode)
    {
        if (!file_exists(BROWSED_LINKS_TO_FILE)) {
            $fh = fopen(BROWSED_LINKS_TO_FILE, 'w') or die("can't open file");
            fclose($fh);
        }
        $content = @file_get_contents(BROWSED_LINKS_TO_FILE);

        $arr = explode('http', $content);
        foreach($arr as $url) {
            if(stristr($url, $name) && stristr($url, $storecode))
                return false;
        }
        return true;
    }
    function saveurl($val,$file)
    {
        $File = $file;
        $file = file_get_contents($File);
        if (!stristr($file, $val)) {
            $fh = fopen($File, 'a') or die("can't open file");
            fwrite($fh, $val . PHP_EOL);
            fclose($fh);
        }
    }
}
function rrmdir($dir)
{
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                if (filetype($dir . "/" . $object) == "dir") {
                    if (filemtime($dir . "/" . $object) < time() - 60 * 60)
                        rrmdir($dir . "/" . $object);
                    else {
                        if (filemtime($dir . "/" . $object) < time() - 60 * 60)
                            unlink($dir . "/" . $object);
                    }
                }
                reset($objects);
                if (filemtime($dir) < time() - 60 * 60)
                    rmdir($dir);
            }
        }
    }
    unlink($dir);
}
function logresult($file, $data)
{
    file_put_contents($file, $data, FILE_APPEND | LOCK_EX);
}
function deleteoldfiles($dir)
{
    foreach (glob($dir . "*") as $file) {
//        if (filemtime($file) < time() - 60 * 60 * 1) {//86400 * 1
        if (filemtime($file) < time()) {//86400 * 1
            if (is_dir($file))
                deleteoldfiles($file);
            unlink($file);
        }
    }
}
function clean($input)
{
    $search = array(
        '@<script[^>]*?>.*?</script>@si',   // Strip out javascript
        '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
        '#<style[^>]*?>.*?</style>#siU',    // Strip style tags properly
        '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
    );
    $output = preg_replace($search, '', $input);
    return $output;
}
function getRandomItem($file,$seperator=PHP_EOL){
    $content = @file_get_contents($file);
    $arr = explode($seperator,$content);
    return $arr[array_rand($arr)];
}
function curl_download($Url, $proxy = false)
{
    $header=array(
    'Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
    'Accept-Encoding:gzip, deflate, sdch',
    'Accept-Language:en-US,en;q=0.8',
    'Cache-Control:max-age=0',
    'Connection:keep-alive',
    'Host:www.amazon.com',
    'Upgrade-Insecure-Requests:1',
    );
    $ch=curl_init();
    if (!function_exists('curl_init')) {
        die('Data Stream Curently Unavailable');
    }
    @$proxip = getRandomItem(PROXYLIST);
    @$arr = explode(':', $proxip);
    @$proxy_ip = $arr[0];
    @$proxy_port = $arr[1];
    curl_setopt($ch, CURLOPT_URL, $Url);
    curl_setopt($ch, CURLOPT_REFERER, "https://www.amazon.com");
    curl_setopt($ch, CURLOPT_USERAGENT, getRandomItem(UALIST));
    curl_setopt($ch, CURLOPT_COOKIEJAR, COOKIES);
    curl_setopt($ch, CURLOPT_COOKIEFILE, COOKIES);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 20);
    curl_setopt($ch, CURLOPT_ENCODING, '');
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    if ($proxy) {
        curl_setopt($ch, CURLOPT_PROXY, $proxy_ip);
        curl_setopt($ch, CURLOPT_PROXYPORT, $proxy_port);
        //curl_setopt($ch, CURLOPT_PROXYUSERPWD, PROXYLOGIN);
    }
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}
function mailclient($file,$email)
{
    $fileatt_type = "text/csv";
    $myfile = $file;

    $file_size = filesize($myfile);
    $handle = fopen($myfile, "r");
    $content = fread($handle, $file_size);
    fclose($handle);

    $content = chunk_split(base64_encode($content));

    $message = "<html>
<head>
  <title>Searched Upc</title>
</head>
<body><table><tr><td>MAKE</td></tr></table></body></html>";

    $uid = md5(uniqid(time()));

    $header = "From: Amazonbot <amazonbot@bot.com>\r\n";
    $header .= "Reply-To: ".$email."\r\n";
    $header .= "MIME-Version: 1.0\r\n";
    $header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n";
    $header .= "This is a multi-part message in MIME format.\r\n";
    $header .= "--".$uid."\r\n";
    $header .= "Content-type:text/html; charset=iso-8859-1\r\n";
    $header .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $header .= $message."\r\n\r\n";
    $header .= "--".$uid."\r\n";
    $header .= "Content-Type: text/csv; name=\"".$myfile."\"\r\n"; // use diff. tyoes here
    $header .= "Content-Transfer-Encoding: base64\r\n";
    $header .= "Content-Disposition: attachment; filename=\"".$myfile."\"\r\n\r\n";
    $header .= $content."\r\n\r\n";
    $header .= "--".$uid."--";

    mail($email, $subject='subject', $message, $header);
}
?>