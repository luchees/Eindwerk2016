<html>
<head>
    <link rel="stylesheet" href="css/stylesheet.css">
    <link rel="stylesheet" href="css/resultpage.css">
    <script src="js/jquery-1.12.0.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <script src="//code.jquery.com/jquery-1.10.2.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <meta http-equiv="Content-Type" content="text/html; charset=US-ASCII">
    <?php include 'functionsxml.php' ?>
    <script>
        $(function () {
            var dialog, form,

                sourcename = $("#sourcename"),
                destname = $("#destname"),
                sourcereversename = $("#sourcereversename"),
                destreversename = $("#destreversename"),
                hostnameregex = /^(NETV4|NETV6)_.*$/,
                allFields = $([]).add(sourcename).add(destname),
                tips = $(".validateTips");

            function updateTips(t) {
                tips
                    .text(t)
                    .addClass("ui-state-highlight");
                setTimeout(function () {
                    tips.removeClass("ui-state-highlight", 1500);
                }, 500);
            }


            function checkRegexp(o, regexp, n) {
                if (!( regexp.test(o.val()) )) {
                    o.addClass("ui-state-error");
                    updateTips(n);
                    return false;
                } else {
                    return true;
                }
            }

            function addNames() {
                var valid = true;
                allFields.removeClass("ui-state-error");


                valid = valid && checkRegexp(sourcename, hostnameregex, "source name must begin with NETV4_ or NETV6_");
                valid = valid && checkRegexp(destname, hostnameregex, "destination name must begin with NETV4_ or NETV6_");

                if (valid) {
                    $("#sourcereversename").val(sourcename.val());
                    $("#destreversename").val(destname.val());


                    dialog.dialog("close");
                }
		else {
		
		
		}
                return valid;
            }

            dialog = $("#dialog-form").dialog({
                autoOpen: false,
                height: 400,
                width: 500,
                modal: true,
                buttons: {
                    "Confirm": function(){
		 	addNames()
                },
		    Cancel: function () {
                        dialog.dialog("close");
                    }
                },
                close: function () {
                    form[0].reset();
                    allFields.removeClass("ui-state-error");
                }
            });

            form = dialog.find("form").on("submit", function (event) {
                event.preventDefault();
                addNames();
            });

            $("#fill_hosts").button().on("click", function () {
                sourcename.val(sourcereversename.val());
                destname.val(destreversename.val());
                dialog.dialog("open");
            });
        });

    </script>
    <title>Webinterface firewall - Result</title>
</head>

<body>

<div class="container">
    <header>
        <h1>Webinterface firewall</h1>
    </header>
    <main></main>
    <div id="marginleftright">


        <?php
        $firewall = $ifcname = $source = $destination = $sport = $protocol = $dport = $sport = $protocolid = $icmptype = $icmpcode = $icmpid = "";
        $valid = true;
        $link = "";

        if (!empty($_POST["firewall"])) {
            if ($_POST["firewall"] = "on") {

            }
            $firewall = $_POST["firewall"];
            $link .= "firewall=" . $firewall;

        } else {
            $valid = false;
            $link .= "firewall=Missing";
        }
        if (!empty($_POST["ifcname"])) {
            $ifcname = $_POST["ifcname"];
            $link .= "&ifcname=" . $ifcname;

        } else {
            $valid = false;
            $link .= "&ifcname=Missing";
        }
        if (!empty($_POST["source"])) {
            $source = $_POST["source"];

            $link .= "&source=" . $source;
        } else {
            $valid = false;
            $link .= "&source=Missing";
        }
        if (!empty($_POST["source-desc"])) {
            $sourcedesc = $_POST["source-desc"];
        }

        if (!empty($_POST["destination"])) {
            $destination = $_POST["destination"];
            $link .= "&destination=" . $destination;
        } else {
            $valid = false;
            $link .= "&destination=Missing";
        }
        if (!empty($_POST["destination-desc"])) {
            $destdesc = $_POST["destination-desc"];
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
            $valid = false;
            $link .= "&protocol=Missing";
        }

        if ($valid) {
            $result = "packet-tracer input " . $ifcname . " " . $protocol . " ";

            $originalcommand = "Interface name: " . $ifcname . "<br>Protocol used: " . $protocol . "<br>Source: " . $source . "<br>Destination: " . $destination;

            $result .= $source . " ";
            if ($protocol == "tcp" || $protocol == "udp") {
                $result .= $sport;
                $originalcommand .= "<br>Source port: " . $sport . "<br>Destination port: " . $dport;
            }
            if ($protocol == "rawip") {
                $result .= $protocolid;
                $originalcommand .= "<br>Protocol ID: " . $protocolid;
            }
            if ($protocol == "icmp") {

                $result .= $icmptype . " " . $icmpcode . " " . $icmpid;
                $originalcommand .= "<br>ICMP type: " . $icmptype . "<br>ICMP code: " . $icmpcode . "<br>ICMP id " . $icmpid;
//        echo $result;
            }

            $result .= " " . $destination . " " . $dport . " ";

            $pi = "193.191.187.41";

            $command = escapeshellcmd("./netmiko_ssh_asa.py " . $pi . " " . $result . "xml");
            //echo $command;
/*
            $output = shell_exec($command);
            //       echo $command;

            //echo $output;
            //echo nl2br($output); // echo, zet de \n om naar <br>

            //$substring = explode ("<result>",$output);

            //echo $substring[0];
//	echo $output;

            $xml = simplexml_load_string($output) or die("Error: Cannot create object Phases");
            $phases = array();
            $result = array();
            $denied = false;
            foreach ($xml->children() as $children) {
                if (!empty($children->id)) {
                    if ($children->result == "ALLOW") {
                        $color = "green";
                    } else {
                        $denied = true;
                        $color = "red";
                    }
                    $childRes = "<div style='color:" . $color . "'>" . $children->result . "</div>"; // set color if denied
                    array_push($phases, array('phase' => $children->id, 'type' => $children->type, 'result' => $childRes, 'config' => $children->config, 'extra' => $children->extra));

                } else {
                    array_push($result, array('input interface' => $children->inputinterface, 'input status' => $children->inputstatus, 'input line status' => $children->inputlinestatus, 'output status' => $children->outputstatus, 'output line status' => $children->outputlinestatus, 'action' => $children->action));
                }
            }
*/
//if the packet is denied do a reverse lookup
            if (1) {
                //reversedns for source if ip and denied


            }
        } else {
            header("Location: index.php?" . $link);
            exit();
        }

        $html = "";
        ob_start();
        echo build_table($result);
        echo "<BR>";
        echo build_table($phases);

        $html = ob_get_contents();
        ?>
        <div id="dialog-form" title="Fill hostnames">
            <p class="validateTips">All form fields are required.</p>

            <form>
                <fieldset>
                    <?php
                    $suggestedsource = "";
                    $suggesteddest = "";
                    $regex = "/^(NETV4|NETV6)_.*$/";
                    $command = escapeshellcmd("./reversedns.py " . $source);
                    $sourcedns = shell_exec($command);
                    preg_match($regex, $sourcedns, $outputsource);
                    if ($outputsource) {
                    } else {
                        $suggestedsource = "NETV*_" . $sourcedesc;
                        $sourcedns = "";

                    }
                    $command = escapeshellcmd("./reversedns.py " . $destination);
                    $destdns = shell_exec($command);
                    preg_match($regex, $destdns, $outputdest);

                    if ($outputdest) {


                    } else {

                        $suggesteddest = "NETV*_" . $destdesc;
                        $destdns = "";
                    }

                    ?>
                    <label for="sourcename">source name</label><br>
                    <p>suggested: <?php echo $suggestedsource ?></p>
                    <input type="text" name="sourcename" id="sourcename" value=""
                           class="text ui-widget-content ui-corner-all"><br>
                    <label for="destname">destination name</label>
                    <p>suggested: <?php echo $suggesteddest ?></p>
                    <input type="text" name="destname" id="destname" value=""
                           class="text ui-widget-content ui-corner-all">


                    <!-- Allow form submission with keyboard without duplicating the dialog button -->
                    <input type="submit" tabindex="-1" style="position:absolute; top:-1000px">
                </fieldset>
            </form>
        </div>
        <button id="fill_hosts">add host</button>


        <form action="sendmail.php" method="post" id="formID" class="form-style-7">


            <div id="hosts-contain" class="ui-widget">
                <h1>entered hosts</h1>
                <table id="hosts" class="ui-widget ui-widget-content">
                    <ul>
                        <li>
                            <p> is this the name of the source?</p>
                            <input name="sourcereversename" id='sourcereversename' value='<?php echo $sourcedns ?>'
                                   readonly size='50' type='text'>


                            <p> is this the name of the destination?</p>
                            <input name="destreversename" id='destreversename' value='<?php echo $destdns ?>' readonly
                                   size='50' type='text'>

                        </li>
                    </ul>

                    </tbody>
                </table>
            </div>


            <input type="textarea" hidden name="htmlvar" id="htmlvar" value="<?php echo htmlentities($html) ?>">
            <ul>
                <li>
                    <label for="contact">Contact support about this issue</label>
                    <textarea name="messageforsupport" id="messageforsupport"
                              onkeyup="adjust_textarea(this)"></textarea>
                    <span>The information displayed on this page will be included in sent mail</span>
                </li>
                <li>
                    <label for="emailsender">Your email address</label>
                    <input type="text" id="emailsender" name="emailsender">
                    <span>Enter your personal email address here</span>
                </li>

                <input type="text" name="originalcommand" value="<?php echo $originalcommand ?>" style="display: none;">
                <input type="text" name="contentscommand" value="<?php echo $output ?>" style="display: none;">
            </ul>
            <input type="submit" name="submit" id="sendmail" value="Send mail">
        </form>
    </div>
</div>
</body>
</html>

