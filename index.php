<!DOCTYPE html>
<html>
<head>

    <link rel="stylesheet" href="css/stylesheet.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="sweetalert-master/sweetalert.css">
    <meta http-equiv="Content-Type" content="text/html; charset=US-ASCII">


    <script src="js/jquery-1.12.0.js"></script>
    <!-- 1.11.4jquery-ui.js wordt gebruikt voor autocomplete !-->
    <script src="js/1.11.4jquery-ui.js"></script>
    <script src="sweetalert-master/sweetalert.min.js"></script>
    <script src="js/index.js"></script>


    <style>

    </style>
    <title>Webinterface firewall</title>
</head>
<body>

<?php
// define variables and initialize with empty values
$firewall = $source = $destination = $sport = $protocol = $dport = $protocolid = $icmptype = $icmpcode = $icmpid = "";


if (isset($_GET["firewall"])) {
    $firewall = $_GET["firewall"];
}


if (isset($_GET["source"])) {
    $source = $_GET["source"];
}

if (isset($_GET["destination"])) {
    $destination = $_GET["destination"];

}

if (isset($_GET["ifcname"])) {
    $ifcname = "Missing";

}
if (isset($_GET["protocol"])) {
    $protocol = $_GET["protocol"];
    if ($protocol == "tcp" || $protocol == "udp") {

        if (isset($_GET["sport"])) {
            $sport = $_GET["sport"];

        }
        if (isset($_GET["dport"])) {
            $dport = $_GET["dport"];

        }
    }

    if ($protocol == "rawip") {
        if (isset($_GET["protocolid"])) {
            $protocolid = $_GET["protocolid"];

        }

    }
    if ($protocol == "icmp") {
        if (isset($_GET["icmptype"])) {
            $icmptype = $_GET["icmptype"];

        }
        if (isset($_GET["icmpcode"])) {
            $icmpcode = $_GET["icmpcode"];

        }
        if (isset($_GET["icmpid"])) {
            $icmpid = $_GET["icmpid"];

        }
    }

}

// echo $firewall . "/" . $source . "/" . $destination . "/" . $sport . "/" . $protocol . "/" . $dport . "/" . $protocolid . "/" . $icmptype . "/" . $icmpcode . "/" . $icmpid

?>
<div class="container">
    <header><h1>Webinterface firewall</h1>
    </header>
    <form action="result.php" method="POST" id="formID" class="form-style-7">
        <ul>
            <li>
                <label for="firewall">Firewall</label>
                <div id="generateFirewalls">

                </div>
                <span class="error"><?php echo $firewall; ?></span>
                <span>Select your firewall</span>
            </li>

            <li>
                <label for="source">Source</label>
                <input type="text" class="hostautocomplete" id="source" name="source" value="<?php echo $source ?>">
	 <input type="hidden" readonly name="source-desc" id="source-desc">
                <div id="source-desc-div"></div>

                <span>Enter the source IP address here</span>
            </li>

            <li>
                <label for="destination">Destination</label> 
<input type="text"  class="hostautocomplete" name="destination" id="destination" value="<?php echo $destination ?>">

                <input type="hidden" readonly name="destination-desc" id="destination-desc">
                <div id="destination-desc-div"></div>

                <span>Enter the destination IP address here</span>
            </li>


            <div id="generateInterfaces">

            </div>

	<div id="protocolMacros">
		</div>

            <div id="advancedProtocols">

            </div>

            <div id="dynamicForm">
            </div>

            <input type="button" onclick="testCommand()" id="command" value="Test Command">
        </ul>
    </form>
</div>
</body>
</html>

<script>

</script>
