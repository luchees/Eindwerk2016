<!DOCTYPE html>
<html>
<head>

    <link rel="stylesheet" href="css/stylesheet.css">
    <meta http-equiv="Content-Type" content="text/html; charset=US-ASCII">
    <script src="js/jquery-1.12.0.js"></script>
    <script src="js/createAdvancedProtocols.js"></script>

    <title>AddMacro Protocol</title>
</head>
<body>
<?php
/**
 * Created by PhpStorm.
 * User: lucas
 * Date: 27/02/2016
 * Time: 16:00
 */

$namemacro= $sport = $protocol = $dport = $protocolid = $icmptype = $icmpcode = $icmpid = "";
$link="";
$valid=true;
/*
if(isset($_POST['delMacroSubmit'])) {
    $file = "macros.xml";
    $xml = simplexml_load_file("macros.xml");


    $macros=$_POST['macro'];
    foreach( $macros as $check) {
        for($i = 0, $length = count($xml->macros->book); $i < $length; $i++){
            if($data->resource->book[$i]->ID == $id){
                unset($data->resource->book[$i]);
                break;
            }
        }
        file_put_contents($filename, $data->saveXML());
    }
        foreach($xml->children() as $protvalue) {
            if ($protvalue->name==$check){
                unset($data->row[$i]);
                $xml->parentNode->removeChild($protvalue);
            }
        }
    }
}
else {


}

*/
if(isset($_POST['addMacroSubmit'])) {
    if (!empty($_POST['namemacro'])) {
        $namemacro = $_POST['namemacro'];
    } else {
        $valid = false;
    }

    if (!empty($_POST["protocol"])) {
        $protocol = $_POST["protocol"];
        $link .= "&protocol=" . $protocol;
        if ($protocol == "tcp" || $protocol == "udp") {

            if (!empty($_POST["sport"])) {
                $sport = $_POST["sport"];
                $link .= "&sport=" . $sport;
            } else {
                $valid = false;
                $link .= "&sport=Missing";
            }
            if (!empty($_POST["dport"])) {
                $dport = $_POST["dport"];
                $link .= "&dport=" . $dport;
            } else {
                $valid = false;
                $link .= "&dport=Missing";
            }
        }
        if ($protocol == "rawip") {
            if (!empty($_POST["protocolid"])) {

                $protocolid = $_POST["protocolid"];
                $link .= "&protocolid=" . $protocolid;
            } else {
                $valid = false;
                $link .= "&protocolid=Missing";
            }
        }
        if ($protocol == "icmp") {
            if (!empty($_POST["icmptype"])) {

                $icmptype = $_POST["icmptype"];
                $link .= "&icmptype=" . $icmptype;
            } else {
                $valid = false;
                $link .= "&icmptype=Missing";
            }
            if (!empty($_POST["icmpcode"])) {

                $icmpcode = $_POST["icmpcode"];
                $link .= "&icmpcode=" . $icmpcode;
            } else {
                $valid = false;
                $link .= "&icmpcode=Missing";
            }
            if (!empty($_POST["icmpid"])) {

                $icmpid = $_POST["icmpid"];
                $link .= "&icmpid=" . $icmpid;
            } else {
                $valid = false;
                $link .= "&icmpid=Missing";
            }
        }
    } else {
        echo "missingsomething";
        $valid = false;
        $link .= "&protocol=Missing";
    }

    if ($valid) {
        $file = "macros.xml";
        $xml = simplexml_load_file("macros.xml");
        $unique=true;
        // CREATE
        $child="";
        foreach($xml->children() as $protvalue) {
            if ($protvalue->name==$namemacro){
                $unique=false;
            }
        }
        if ($unique){
            if ($protocol == "tcp") {
                $child = $xml->addChild("tcp");
                $child->addChild("name", $namemacro);
                $child->addChild("sport", $sport);
                $child->addChild("dport", $dport);

            } elseif ($protocol == "udp") {
                $child = $xml->addChild("udp");
                $child->addChild("name", $namemacro);
                $child->addChild("sport", $sport);
                $child->addChild("dport", $dport);
            } elseif ($protocol == "icmp") {
                $child = $xml->addChild("icmp");
                $child->addChild("name", $namemacro);
                $child->addChild("icmpcode", $icmpcode);
                $child->addChild("icmptype", $icmptype);
                $child->addChild("icmpid", $icmpid);
            } elseif ($protocol =="rawip") {
                $child = $xml->addChild("rawip");
                $child->addChild("name", $namemacro);
                $child->addChild("ipprot", $protocolid);
            } else {
                echo "problem in Protocol selecting";
            }

            if (!empty($child)) {
                $xml->asXML($file);
                echo "ADDED";

// Create a child in the first topic node
// Add the text attribute

                //  You can either display the new XML code with echo or store it in a file.

            }
        }
        else {
            echo "not added because of duplicate, choose other name";
        }


// Display the new XML code

//http://stackoverflow.com/questions/7098093/how-to-append-to-a-xml-file-with-php-preferably-with-simplexml
    }
}
?>

<div class="container">
    <header><h1>Webinterface firewall</h1>
    </header>

    <form name="addMacroForm" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="form-style-7" >
        <ul>
            <li>
                <label for="namemacro">Name of the macro</label>
                <input type="text" id="namemacro" name="namemacro" value="<?php echo $namemacro ?>">

        <span class="error">
           <?php //echo $nameMacro ;?>
        </span>
                <span>Choose a name</span>
            </li>

            <div id="advancedProtocols"></div>
            <div id="dynamicForm"></div>
        </ul>
        <input type="submit" name="addMacroSubmit" value="Add macro" id="addmacro">
    </form>
    <form name="delMacroForm" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="form-style-7" >
        <div id="protocolMacros"></div>

        <input type="submit" name="delMacroSubmit" value="Delete macro" id="delmacro">
    </form>
</div>
</body>
</html>
