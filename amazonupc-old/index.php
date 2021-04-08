<?php
session_start();
?>
<meta charset="UTF-8">
<link rel="stylesheet" href="input.css">
<style>
    body{
        background: #444dbf; /* Old browsers */
        background: -moz-radial-gradient(center, ellipse cover,  #fcfcfc 0%, #444dbf 100%); /* FF3.6+ */
        background: -webkit-gradient(radial, center center, 0px, center center, 100%, color-stop(0%,#fcfcfc), color-stop(100%,#444dbf)); /* Chrome,Safari4+ */
        background: -webkit-radial-gradient(center, ellipse cover,  #fcfcfc 0%,#444dbf 100%); /* Chrome10+,Safari5.1+ */
        background: -o-radial-gradient(center, ellipse cover,  #fcfcfc 0%,#444dbf 100%); /* Opera 12+ */
        background: -ms-radial-gradient(center, ellipse cover,  #fcfcfc 0%,#444dbf 100%); /* IE10+ */
        background: radial-gradient(center, ellipse cover,  #fcfcfc 0%,#444dbf 100%); /* W3C */
        filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#fcfcfc', endColorstr='#444dbf',GradientType=0 ); /* IE6-9 fallback on horizontal gradient */
        filter:  progid:DXImageTransform.Microsoft.gradient(GradientType=0,startColorstr='#FFFFFF', endColorstr='#444dbf'); /* IE6 & IE7 */
        -ms-filter: progid:DXImageTransform.Microsoft.gradient(GradientType=0,startColorstr='#FFFFFF', endColorstr='#444dbf'); /* IE8 */
    }
    table{
        font-size: 12px;
        opacity: 0.9;
    }
    table th{
        font-size: 16px;
    }
</style>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.js"></script>
<script src="http://malsup.github.com/jquery.form.js"></script>
<script>
//    $(document).ready(function() {
//        $('#myForm').ajaxForm(function() {
//
//        });
//    });
</script>
<div id="login_form" style="display:block; background:#999;color: #fff;" class="homecatview">
    <form action="run.php" id="myForm" method="post" class="login" enctype="multipart/form-data">
        <p>
            <label for="login">Upc File:</label>
            <input type="file" name="upcfile" required="required" id="login">
        </p>
        <p>
            <label for="login">MarketPlace</label>
            <select id="ext" name="ext"  required="required">
                <option value="">Choose An Amazon Marketplace</option>
                <option value="com" selected>USA</option>
                <option value="de">DE</option>
                <option value="co.uk">UK</option>
                <option value="fr">FRA</option>
                <option value="es">ESP</option>
            </select>
        </p>
        <p>
            <label for="login">Fixed%</label>
            <input type="text" name="fixed" required="required" value="0.85" placeholder="Fixed % eg (0.85)" id="login">
        </p>
        <p>
            <label for="login">Email:</label>
            <input type="text" name="email" required="required" value="" placeholder="email@domain.com" id="login">
        </p>
        <p>
            <input type="submit" name="submit" value="Run Crawler" class="button">
        </p>
    </form>
    <?php
    if(isset($_SESSION['ERROR'])) {
        echo "<h1>" . $_SESSION['ERROR'] . "</h1>";
        unset($_SESSION['ERROR']);
    }
    ?>
</div>
<br>
<div  style="display:block; background:#ccc;color: #fff; width: 90%" class="homecatview">
<?php
if(isset($_SESSION['RAND'])) {
    $file = dirname(__FILE__) . "/results-" . $_SESSION['RAND'] . ".db";
    $rows = getFromDatabase($file);
    if (count($rows) > 0) {
        ?>
        <table border="1">
            <tr>
                <th>ID
                </td>
                <th>Title</th>
                <th>Price</th>
                <th>Reviews</th>
                <th>Sellers</th>
                <th>Asin</th>
                <th>BSR</th>
                <th>Category</th>
                <th>Stock</th>
                <th>FBA/FBM</th>
                <th>ProductCategory</th>
                <th>Weight</th>
                <th>UPC</th>
                <th>Modified</th>
            </tr>
<pre>
        <?php
        foreach ($rows as $array) {
            echo "<tr><td rowspan='2'>" . stripslashes($array['id']) . "</td><td rowspan='2'><a href=\"" . stripslashes($array['url']) . "\">" . stripslashes($array['title']) . "</a></td><td>" . stripslashes($array['price']) . "</td><td>" . stripslashes($array['reviews']) . "</td><td>" . stripslashes($array['sellers']) . "</td><td>" . stripslashes($array['asin']) . "</td><td>" . stripslashes($array['bsr']) . "</td><td>" . stripslashes($array['category']) . "</td><td>" . stripslashes($array['stock']) . "</td><td>" . stripslashes($array['fba_fbm']) . "</td><td>" . stripslashes($array['productCategory']) . "</td><td>" . stripslashes($array['weight']) . "</td><td>" . stripslashes($array['upc']) . "</td><td>" . stripslashes($array['modified']) . "</td></tr>
            <tr><td colspan='2'><b>Cost:</b>" . stripslashes($array['cost']) . "</td>
            <td colspan='2'><b>Gross Profit:</b>" . stripslashes($array['grossProfit']) . "</td>
            <td colspan='6'><b>Margin:</b>" . stripslashes($array['margin']) . "</td></tr>";
        }
        ?>
        </table>

    <?php
    }
}
?>
</div>
<?php
if(isset($_SESSION['RAND']))
    removeAllTempFiles();
function removeAllTempFiles(){
    unlink(dirname(__FILE__) . "/amazon-".$_SESSION['RAND'].".csv");
    unlink(dirname(__FILE__) . "/results-".$_SESSION['RAND'].".db");
    unset($_SESSION['RAND']);
}
function getFromDatabase($file){
    if(file_exists($file)) {
        $db = new SQLite3($file);
        $results = $db->query('SELECT * FROM products');
        $array = array();
        while ($row = $results->fetchArray()) {
            $array[] = $row;
        }
        return $array;
    }
}
?>