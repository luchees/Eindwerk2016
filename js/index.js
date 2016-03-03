//init variables
var firewalls = [];
var selectedFirewall;
var all=[];
var tcp=[];
var udp=[];
var icmp=[];
var rawip=[];
var protocolnames=[];

$(document).ready(function () {
	
	//get firewalls from config.xml through ASYNC AJAX
    
$.ajax({
        type: "GET",
        dataType: "xml",
        url: "config.xml",
        success: function (xml) {
            $(xml).find('firewalls').each(function () {

                //var interfaces= [];
                var name = $(this).find('name').text();
                var ip4 = $(this).find('ip4').text();
                var ip6 = $(this).find('ip6').text();

                // get all interfaces of firewall
                var interfaces = [];
                var interface;
                $(this).find('interfaces').each(function () {
                    // iterate
                    var intname = $(this).find('intname').text();
                    var intip4 = $(this).find('intip4').text();
                    var subnetmaskip4 = $(this).find('subnetmaskip4').text();
                    var intip6 = $(this).find('intip6').text();
                    var subnetmaskip6 = $(this).find('subnetmaskip6').text();
                    var extra = $(this).find('extra').text();

                    var subnetsip4 = [];

                    var findSubnetsip4 = $(this).find('subnetsip4');
                    $(findSubnetsip4).find('subnet').each(function () {
                        var ip = $(this).find('ip').text();
                        var subnetmaskip4 = $(this).find('subnetmaskip4').text();

                        subnet = {
                            ip: ip,
                            subnetmaskip4: subnetmaskip4
                        };
                        subnetsip4.push(subnet);
                    });

                    interface = {
                        intname: intname,
                        intip4: intip4,
                        subnetmaskip4: subnetmaskip4,
                        intip6: intip6,
                        subnetmaskip6: subnetmaskip6,
                        extra: extra,
                        subnetsip4: subnetsip4
                    };
                    interfaces.push(interface);
                });
                var firewall = {name: name, ip4: ip4, ip6: ip6, interfaces: interfaces};
                callback(firewall);
            });
        },
        async: false
    });
	// start the creating of the firewall radios
    start()
	//get macros from macros.xml and save to an object 
$.ajax({
        type: "GET",
        dataType: "xml",
        url: "macros.xml",
        success: function (xml) {
            $(xml).find('tcp').each(function () {
                protocolnames.push($(this).find('name').text());
            tcp.push({name:$(this).find('name').text(),sport:$(this).find('sport').text(),dport:$(this).find('dport').text() })
            });
            $(xml).find('udp').each(function () {
                udp.push({name:$(this).find('name').text(),sport:$(this).find('sport').text(),dport:$(this).find('dport').text() })
                protocolnames.push($(this).find('name').text());
            });
            $(xml).find('icmp').each(function () {
                icmp.push({name:$(this).find('name').text(),icmpcode:$(this).find('icmpcode').text(),icmptype:$(this).find('icmptype').text() ,icmpid:$(this).find('icmpid').text()})
                protocolnames.push($(this).find('name').text());
            });
            $(xml).find('rawip').each(function () {
                rawip.push({name:$(this).find('name').text(),ipprot:$(this).find('ipprot').text() })
                protocolnames.push($(this).find('name').text());
            });
        },
        async: false
    });
    createMacros();
});
// workaround to push firewall objects 
function callback(firewall) {
    firewalls.push(firewall);

}

//workaround to create firewallradios
function start() {
    for (var fw in firewalls) {
        createCheckboxFirewall(firewalls[fw].name);
    }
}

//get firewalls by name
function getFirewallByName(firewallname) {
    for (var fw in firewalls) {
        if (firewalls[fw].name == firewallname) {
            return firewalls[fw];
        }
    }
}

//change dynamic form when changing selected protocol
function changeProtocol(protocol) {
    $('#dynamicForm').empty();
    if (protocol == "") return; // please select - possibly you want something else here
    if (protocol == "tcp" || protocol == "udp") createTcpUdpForm();
    if (protocol == "rawip") createRawIpForm();
    if (protocol == "icmp") createIcmpForm();
}

function createIcmpForm() {
    createInputfield("#dynamicForm", "#picmpcode", "icmpcode", "Icmp code", "Enter the ICMP type here (0-255)");
    createInputfield("#dynamicForm", "#picmptype", "icmptype", "Icmp type", "Enter the ICMP code here (0-255)");
    createInputfield("#dynamicForm", "#picmpid", "icmpid", "Icmp ID", "Enter the ICMP identifier here (0-65535 / optional)");
}

function createRawIpForm() {
    createInputfield("#dynamicForm", "#pprotocolid", "protocolid", "Ip protocol/Next header", "Enter the Protocol ID/Next header here (0-255)");

}

function createTcpUdpForm() {
    createInputfield("#dynamicForm", "#psport", "sport", "Source port", "Enter the source port here (0-65535)");
    createInputfield("#dynamicForm", "#pdport", "dport", "Destination port", "Enter the destination port here (0-65535)");
}

// function to create an inputfield 
function createInputfield(mainID, childID, labelFor, labelText, spanText) {
    $(mainID).append('<li id=' + childID.replace('#', '') + '></li>');
    $(childID).append("<label for=" + labelFor + ">" + labelText + "</label>")
    $('<input>').attr({type: 'text', id: labelFor, name: labelFor}).appendTo(childID);
    $(childID).append("<span>" + spanText + "</span>");
}

// create radios 
function createCheckboxFirewall(firewallname) {

    var checkboxes = document.getElementById("generateFirewalls");
    var newNode = document.createElement('div');
    newNode.setAttribute("id", firewallname);
    var checkNode = document.createElement('input');
    checkNode.setAttribute("type", "radio");
    checkNode.setAttribute("name", "firewall");
    checkNode.setAttribute("id", firewallname);
    checkNode.setAttribute("onclick", "createInterfaces(\"" + firewallname + "\")");
    newNode.appendChild(checkNode);
    newNode.appendChild(document.createTextNode(firewallname));
    checkboxes.appendChild(newNode);

}

//create interfaces when firewall selected
function createInterfaces(firewallname) {
    selectedFirewall = getFirewallByName(firewallname);
    var firewall = getFirewallByName(firewallname);
    $('#generateInterfaces').empty();
    $("#generateInterfaces").append('<li id="liInterfaces"></li>');
    $("#liInterfaces").append('<label for="interface">Interface located on firewall ' + firewallname + '</label>');
    $("#liInterfaces").append('<select id="ifcname" name="ifcname" onchange="selectInterface(this.value)">');
    $("#ifcname").append('<option disabled selected id="selectAInterface"> -- Select a interface --</option>');
    $("#ifcname").append('<option value="guessInt" id="guessInt"> --- Guess interface based on source address ---</option>');
    for (i = 0; i < firewall.interfaces.length; i++) {
        var interface = firewall.interfaces[i].intname;
        $("#ifcname").append('<option value="' + interface + '" id="' + interface + '">' + interface + '</option>');
    }
    $("#liInterfaces").append('</select>');
    $("#liInterfaces").append('<span>Select your interface</span>');

}

function createAdvancedProtocols() {
    $('#advancedProtocols').empty();
    $("#advancedProtocols").append('<li id="liAdvancedProtocols"></li>');
    $("#liAdvancedProtocols").append('<label for="AdvancedProtocol">Advanced protocol configurations</label>');
    $("#liAdvancedProtocols").append('<select id="advancedProtocol" name="protocol" onchange="changeProtocol(this.value)">');
    $("#advancedProtocol").append('<option disabled selected> -- Select a protocol --</option>');
    $("#advancedProtocol").append('<option value="tcp">TCP</option>');
    $("#advancedProtocol").append('<option value="udp">UDP</option>');
    $("#advancedProtocol").append('<option value="icmp">ICMP</option>');
    $("#advancedProtocol").append('<option value="rawip">RAWIP</option>');
    $("#liAdvancedProtocols").append('</select>');
    $("#liAdvancedProtocols").append('<span>Select your chosen protocol here</span>');
}

//create macros out of protocolnames
function createMacros(){
    $('#protocolMacros').empty();
    $("#protocolMacros").append('<li id="liProtocolMacros"></li>');
    $("#liProtocolMacros").append('<label for="ProtocolMacro">protocol macros</label>');

    for (var x in protocolnames) {
        $("#liProtocolMacros").append('<input type="radio" name="macro" value="'+protocolnames[x]+'" onclick="enterProtocolMacro(this.value)">'+protocolnames[x]);
    }
    $("#liProtocolMacros").append('<input type="radio" name="macro" value="advancedprotocol" onclick="createAdvancedProtocols()"> advanced protocol');

    $("#liProtocolMacros").append('<span>Select a protocol macro here</span>');


}

// create inputfields out of macro selection
function enterProtocolMacro(macro){
  $('#dynamicForm').empty();
    for (tcps in tcp){
        console.log(tcps);
        if (tcp[tcps].name==macro){
            console.log(macro);
            $('#advancedProtocols').empty();
            $("#advancedProtocols").append('<input type="hidden" id="advancedProtocol" name="protocol" value="tcp">');
            $("#advancedProtocols").append('<input type="hidden" id="sport" name="sport" value="'+tcp[tcps].sport+'">');
            $("#advancedProtocols").append('<input type="hidden" id="dport" name="dport" value="'+tcp[tcps].dport+'">');
        }
    }
    for (udps in udp){
        if (udp[udps].name==macro){

            $('#advancedProtocols').empty();
            $("#advancedProtocols").append('<input type="hidden" id="advancedProtocol" name="protocol" value="udp">');
            $("#advancedProtocols").append('<input type="hidden" id="sport" name="sport" value="'+udp[udps].sport+'">');
            $("#advancedProtocols").append('<input type="hidden" id="dport" name="dport" value="'+udp[udps].dport+'">');
        }
    }
    for (icmps in icmp){
        if (icmp[icmps].name==macro){
            $('#advancedProtocols').empty();
            $("#advancedProtocols").append('<input type="hidden" id="advancedProtocol" name="protocol" value="icmp">');
            $("#advancedProtocols").append('<input type="hidden" id="icmpcode" name="icmpcode" value="'+icmp[icmps].icmpcode+'">');
            $("#advancedProtocols").append('<input type="hidden" id="icmptype" name="icmptype" value="'+icmp[icmps].icmptype+'">');
            $("#advancedProtocols").append('<input type="hidden" id="icmpid" name="icmpid" value="'+icmp[icmps].icmpid+'">');

        }
    }
    for (rawips in rawip){
        if (rawip[rawips].name==macro){
            $('#advancedProtocols').empty();
            $("#advancedProtocols").append('<input type="hidden" id="advancedProtocol" name="protocol" value="rawip">');
            $("#advancedProtocols").append('<input type="hidden" id="protocolid" name="protocolid" value="'+rawip[rawips].ipprot+'">');

        }
    }

}
//guess interface
function guessInterface() {
    var source = $("#source").val();
    if (source.match(/^(\d+)\.(\d+)\.(\d+)\.(\d+)$/)) {
        for (i = 0; i < selectedFirewall.interfaces.length; i++) {
            for (j = 0; j < selectedFirewall.interfaces[i].subnetsip4.length; j++) {
                var network = selectedFirewall.interfaces[i].subnetsip4[j].ip;
                var mask = selectedFirewall.interfaces[i].subnetsip4[j].subnetmaskip4;
                var intface = selectedFirewall.interfaces[i].intname;
                lookForMatchingInterface(source, mask, network, intface);
            }
        }
    }
    if (document.getElementById("guessInt").selected == true) {
        //alert("Sorry, we're not able to guess the interface by source adress.");
        document.getElementById("outside").selected = true;
    }
}


function selectInterface(value) {
    if (value == "guessInt") {
        guessInterface();
    }
}

// look for a matching interface 

function lookForMatchingInterface(sourceIP, mask, network, interface) {
    // source IP Address - Subnetmask - Network
    if ((IPnumber(sourceIP) & IPmask(mask)) == IPnumber(network)) {
        document.getElementById(interface).selected = true;
    } else {
        //console.log("not a matching interface: " + interface)
    }
}


function IPnumber(IPaddress) {
    var ip = IPaddress.match(/^(\d+)\.(\d+)\.(\d+)\.(\d+)$/);
    if (ip) {
        return (+ip[1] << 24) + (+ip[2] << 16) + (+ip[3] << 8) + (+ip[4]);
    }
    return null;
}

function IPmask(maskSize) {
    return -1 << (32 - maskSize)
}

// get xmlobjects from firewallobjects.xml and put in a object.
$(function () {
    names = [];
    objectnames = [];
    $(function () {
        $.ajax({
            type: "GET",
            dataType: "xml",
            url: "firewallobjects.xml",
            success: function (xml) {
                $(xml).find('objectnetwork').each(function () {
                    var name = $(this).find('name').text();
                    var ip4 = $(this).find('ip4').text();
                    var desc = $(this).find('description').text();
                    callbackObj([{label: name, value: name, desc: desc, ip4: ip4}]);
                });


            },
            async: false
        });
    });
    function callbackObj(objects) {
        //  objectnames.push(objects);
        names.push(objects[0]);
    }

// autocomplete Jquery 
$(".hostautocomplete").each(function(){
    $(this).autocomplete({
        source: names,

        select: function (event, ui) {
            $(this).val(ui.item.ip4);
		console.log(event.target.id);
            	$descr=event.target.id+"-desc";
		$("#"+$descr).val(ui.item.desc);
            $("#"+$descr+"-div").html(ui.item.desc);
		console.log("#"+$descr)
            return false;
        }
    }).autocomplete("instance")._renderItem = function (ul, item) {
        return $("<li>")
            .append("<a>" + item.label + " " + item.ip4 + "<br>" + item.desc + "</a>")
            .appendTo(ul);
    };


});
/*$(document).on('change','.hostautocomplete', function(event,ui) {
	$("#"+event.target.id+"-desc").val("");
}); */
});


$(document).ready(function () {
    $("#source").keypress(function (e) {

        if (e.keyCode == 8 || e.keyCode == 46) {
            $("#source-desc").val("");
            $("#source-desc-div").empty();
        }
    });
    $("#destination").keypress(function (e) {
        if (e.keyCode == 8 || e.keyCode == 46) {
            $("#destination-desc").val("");
            $("#destination-desc-div").empty();
        }
    });

    // CHROME FIX
    $("#source").keydown(function (e) {

        if (e.keyCode == 8 || e.keyCode == 46) {
            $("#source-desc").val("");
            $("#source-desc-div").empty();
        }
    });
    $("#destination").keydown(function (e) {
        if (e.keyCode == 8 || e.keyCode == 46) {
            $("#destination-desc").val("");
            $("#destination-desc-div").empty();
        }
    });
});

function testCommand() {

    var errorString = clientsideValidation();

    if (errorString != "") {

        swal({
            title: "Error!",
            text: errorString,
            type: "error",
            confirmButtonText: "Continue"
        });


    } else {
        // <input type="submit" name="submit" id="command" value="Test command">
        var loadnumber = Math.floor(Math.random() * 4) + 1;
        $("#formID").submit();
        swal({
            title: 'Starting up SSH connection!',
            text: 'This might take a few seconds...',
            imageUrl: 'images/loader' + loadnumber + '.gif',
            showConfirmButton: false,
            animation: "slide-from-top"
        });
    }
}

function clientsideValidation() {
    var errorString = "";

    var source = $("#source").val();
    var destination = $("#destination").val();
    var ifcname = $("#ifcname").val();

    if ($('input[name=firewall]:checked').length == 0) {
        errorString += "Firewall: you have to select a Firewall.\n";
    }

    errorString += validateIPaddress(source, "Source address: ");
    errorString += validateIPaddress(destination, "Destination address: ");

    if (ifcname == "" || ifcname == null) {
        errorString += "Interface: you have to select an interface.\n";
    }

    if ($('input[name=macro]:checked').length == 0) {
        errorString += "Please select a macro or advanced protocol configurations.\n";
    }


    if ($('input[name=macro]:checked').val() == "advancedprotocol") {
        if ($("#advancedProtocol").val() == "" || $("#advancedProtocol").val() == null) {
            errorString += "Advanced protocol configuration: you have to select a protocol.\n";
        }

        else if ($("#advancedProtocol").val() == "tcp" || $("#advancedProtocol").val() == "udp") {
            var protocol = $("#advancedProtocol").val();
            var sourceP = $("#sport").val();
            if (sourceP < 0 || sourceP > 65535 || !$.isNumeric(sourceP)) {
                errorString += protocol.toUpperCase() + " source port: must be a number between 0 and 65535.\n";
            }

            var destinationP = $("#dport").val();
            if (destinationP < 0 || destinationP > 65535 || !$.isNumeric(destinationP)) {
                errorString += protocol.toUpperCase() + " destination port: must be a number between 0 and 65535.\n";
            }
        }

        else if ($("#advancedProtocol").val() == "icmp") {
            var icmpcode = $("#icmpcode").val();
            var icmptype = $("#icmptype").val();
            var icmpid = $("#icmpid").val();
            if (icmpcode < 0 || icmpcode > 255 || !$.isNumeric(icmpcode)) {
                errorString += "Icmp code: must be a number between 0 and 255.\n";
            }
            if (icmptype < 0 || icmptype > 255 || !$.isNumeric(icmptype)) {
                errorString += "Icmp type: must be a number between 0 and 255.\n";
            }
            if (icmpid != "") { // ICMP ID is optional
                if (icmpid < 0 || icmpid > 65535 || !$.isNumeric(icmpid)) {
                    errorString += "Icmp id: optional number between 0 and 65535.\n";
                }
            }

        }

        else if ($("#advancedProtocol").val() == "rawip") {
            var protocolid = $("#protocolid").val();
            if (protocolid < 0 || protocolid > 255 || !$.isNumeric(protocolid)) {
                errorString += "Protocol ID: must be a number between 0 and 255.\n";
            }
        }

    }
    return errorString;
}


function validateIPaddress(ipaddress, addressString) {
    if (/^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/.test(ipaddress)) {
        return "";

    }
    return addressString + "this is an invalid IP address.\n";
}

