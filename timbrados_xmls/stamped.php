<?php
 
# Username and Password, assigned by FINKOK
$username = 'ccalix@finkok.com.mx';
$password = 'Legoland1953!';
 
# Read the xml file and encode it on base64
$invoice_path = "/home/ccalix/Documentos/soporte_tecnico/actividades_02_06_oct/timbrados_xmls/pagosrecep.xml";
$xml_file = fopen($invoice_path, "rb");
$xml_content = fread($xml_file, filesize($invoice_path));
fclose($xml_file);
 
# In newer PHP versions the SoapLib class automatically converts FILE parameters to base64, so the next line is not needed, otherwise uncomment it
#$xml_content = base64_encode($xml_content);

# Consuming the stamp service
$url = "https://demo-facturacion.finkok.com/servicios/soap/stamp.wsdl";
$client = new SoapClient($url);
 
$params = array(
  "xml" => $xml_content,
  "username" => $username,
  "password" => $password
);
$response = $client->__soapCall("stamp", array($params));
print_r($response);
 
####Guardar XMLtimbrado
$file = fopen("/home/ccalix/Documentos/soporte_tecnico/actividades_02_06_oct/timbrados_xmls/timbres_pagos/PagoStampResult.xml", "a");
fwrite($file, $response->stampResult->xml);
fclose($file)
####mostrar el código de error en caso de presentar alguna incidencia
#print $response->stampResult->Incidencias->Incidencia->CodigoError;
####mostrar el mensaje de incidencia en caso de presentar alguna
#print $response->stampResult->Incidencias->Incidencia->MensajeIncidencia;
?>