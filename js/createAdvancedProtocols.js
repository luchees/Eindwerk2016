/**
 * Created by lucas on 28/02/2016.
 */
var tcp=[];
var udp=[];
var icmp=[];
var rawip=[];
var protocolnames=[];
function loadxml(){

}
$(document).ready(function () {
    createAdvancedProtocols();
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
    console.log(protocolnames);
    createMacros();
});
function createMacros(){
    $('#protocolMacros').empty();
    $("#protocolMacros").append('<li id="liProtocolMacros"></li>');
    $("#liProtocolMacros").append('<label for="ProtocolMacro">protocol macros</label>');

    for (var x in protocolnames) {
        $("#liProtocolMacros").append('<input type="checkbox" name="macro[]" value="'+protocolnames[x]+'">'+protocolnames[x]);
    }
    $("#liProtocolMacros").append('<span>Select a protocol macro here</span>');


}
    function createAdvancedProtocols(){
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

function createInputfield(mainID, childID, labelFor, labelText, spanText) {
    $(mainID).append('<li id=' + childID.replace('#', '') + '></li>');
    $(childID).append("<label for=" + labelFor + ">" + labelText + "</label>")
    $('<input>').attr({type: 'text', id: labelFor, name: labelFor}).appendTo(childID);
    $(childID).append("<span>" + spanText + "</span>");
}

function changeProtocol(protocol) {
    $('#dynamicForm').empty();
    if (protocol == "") return; // please select - possibly you want something else here
    if (protocol == "tcp" || protocol == "udp") createTcpUdpForm();
    if (protocol == "rawip") createRawIpForm();
    if (protocol == "icmp") createIcmpForm();
}

