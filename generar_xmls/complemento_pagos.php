<?php
 
satxmlsv40(false,"","","");
 
// {{{  satxmlsv40
function satxmlsv40($arr, $edidata=false, $dir="",$nodo="",$addenda="") {
global $xml, $cadena_original, $sello, $texto, $ret;
error_reporting(E_ALL & ~(E_WARNING | E_NOTICE));
satxmlsv40_genera_xml($arr,$edidata,$dir,$nodo,$addenda);
satxmlsv40_genera_cadena_original();
satxmlsv40_sella($arr);
$ret = satxmlsv40_termina($arr,$dir);
return $ret;
}
 
// {{{  satxmlsv40_genera_xml
function satxmlsv40_genera_xml($arr, $edidata, $dir,$nodo,$addenda) {
global $xml, $ret;
$xml = new DOMdocument("1.0","UTF-8");
satxmlsv40_generales($arr, $edidata, $dir,$nodo,$addenda);
satxmlsv40_relacionados($arr, $edidata, $dir,$nodo,$addenda);
satxmlsv40_emisor($arr, $edidata, $dir,$nodo,$addenda);
satxmlsv40_receptor($arr, $edidata, $dir,$nodo,$addenda);
satxmlsv40_conceptos($arr, $edidata, $dir,$nodo,$addenda);
satxmlsv40_Recepcion_Pagos20($arr, $edidata, $dir,$nodo,$addenda);
 
}
// }}}
 
// {{{  Datos generales del Comprobante
function satxmlsv40_generales($arr, $edidata, $dir,$nodo,$addenda) {
global $root, $xml;
$root = $xml->createElement("cfdi:Comprobante");
$root = $xml->appendChild($root);
 
satxmlsv40_cargaAtt($root, array("xmlns:cfdi"=>"http://www.sat.gob.mx/cfd/4",
                          "xmlns:xsi"=>"http://www.w3.org/2001/XMLSchema-instance",
                          "xsi:schemaLocation"=>"http://www.sat.gob.mx/cfd/4 http://www.sat.gob.mx/sitio_internet/cfd/4/cfdv40.xsd http://www.sat.gob.mx/Pagos20 http://www.sat.gob.mx/sitio_internet/cfd/Pagos/Pagos20.xsd",
                          "xmlns:pago20" => "http://www.sat.gob.mx/Pagos20",
                         )
                     );
 
satxmlsv40_cargaAtt($root, array("Version"=>"4.0",
                      "Serie"=>"P-COA",
                      "Folio"=>"159",
                      //"fecha"=>satxmlsv40_xml_fech(date),
                      "Fecha"=>date("Y-m-d"). "T" .date("H:i:s"),
                      "Sello"=>"@",                    
                      "NoCertificado"=>no_Certificado(),
                      "Certificado"=>"@",
                      "SubTotal"=>"0",
                      "Moneda"=>"XXX",
                      "Total"=>"0",
                      "TipoDeComprobante"=>"P",
                      "Exportacion"=> "01",
                      "LugarExpedicion"=>"26017",
                   )
                );
}
 
 
// {{{ Datos de documentos relacionados
function satxmlsv40_relacionados($arr, $edidata, $dir,$nodo,$addenda) {
    global $root, $xml;
 
      $cfdis = false;
 
      if ($cfdis == true){
        $cfdis = $xml->createElement("cfdi:CfdiRelacionados");
        $cfdis = $root->appendChild($cfdis);
        satxmlsv40_cargaAtt($cfdis, array("TipoRelacion"=>"01"));
        $cfdi = $xml->createElement("cfdi:CfdiRelacionado");
        $cfdi = $cfdis->appendChild($cfdi);
        satxmlsv40_cargaAtt($cfdi, array("UUID"=>"A39DA66B-52CA-49E3-879B-5C05185B0EF7"));
      }    
 
}
// }}}
 
 
// Datos del Emisor
function satxmlsv40_emisor($arr, $edidata, $dir,$nodo,$addenda) {
  global $root, $xml;
  $emisor = $xml->createElement("cfdi:Emisor");
  $emisor = $root->appendChild($emisor);
  satxmlsv40_cargaAtt($emisor, array("Rfc"=>"EKU9003173C9",
                                     "Nombre"=>"ESCUELA KEMPER URGATE",
                                     "RegimenFiscal"=>"601",
                                    )
                                );
}
 
// Datos del Receptor
 
function satxmlsv40_receptor($arr, $edidata, $dir,$nodo,$addenda) {
global $root, $xml;
$receptor = $xml->createElement("cfdi:Receptor");
$receptor = $root->appendChild($receptor);
satxmlsv40_cargaAtt($receptor, array("Rfc"=>"MASO451221PM4",
                                     "Nombre"=>"MARIA OLIVIA MARTINEZ SAGAZ",
                                     "UsoCFDI"=>"CP01",
                                     "RegimenFiscalReceptor"=>"610",
                                     "DomicilioFiscalReceptor"=>"80290",
                      )
                  );
}
 
// 
// Detalle de los conceptos/productos de la factura
function satxmlsv40_conceptos($arr, $edidata, $dir,$nodo,$addenda) 
{
  global $root, $xml;
  $conceptos = $xml->createElement("cfdi:Conceptos");
  $conceptos = $root->appendChild($conceptos);
  $concepto = $xml->createElement("cfdi:Concepto");
  $concepto = $conceptos->appendChild($concepto);
  satxmlsv40_cargaAtt($concepto, array(
                              "ClaveProdServ"=>"84111506",                              
                              "Cantidad"=>"1",
                              "ClaveUnidad"=>"ACT",
                              //"NoIdentificacion"=>"00001",
                              "Descripcion"=>"Pago",
                              "ValorUnitario"=>"0",
                              "Importe"=>"0",
                              "ObjetoImp"=>"01",
                            ));
}
 
 
// Complemento recepcion de pagos
 
function satxmlsv40_Recepcion_Pagos20(){
  global $root, $xml;
  $complemento_rp = $xml->createElement("cfdi:Complemento");
  $complemento_rp = $root->appendChild($complemento_rp);
  $recep_pagos20 = $xml->createElement("pago20:Pagos");
  $recep_pagos20 = $complemento_rp->appendChild($recep_pagos20);
  satxmlsv40_cargaAtt($recep_pagos20, array(
    "Version" => "2.0",
  ));
 
  //creamos el nodo TOTALES
  $totales = $xml->createElement("pago20:Totales");
  $totales = $recep_pagos20->appendChild($totales);
  satxmlsv40_cargaAtt($totales, array(
    "TotalRetencionesIVA"=>false,
    "TotalRetencionesISR"=>false,
    "TotalRetencionesIEPS"=>false,
    "TotalTrasladosBaseIVA16"=>"33000.00",
    "TotalTrasladosImpuestoIVA16"=>"5280.00",
    "TotalTrasladosBaseIVA8"=>false,
    "TotalTrasladosImpuestoIVA8"=>false,
    "TotalTrasladosBaseIVA0"=>false,
    "TotalTrasladosImpuestoIVA0"=>false,
    "TotalTrasladosBaseIVAExento"=>false,
    "MontoTotalPagos"=>"38280.00",
  ));
 
  //creamos el nodo Pago
 
    for ($p20=1; $p20<=sizeof((array)[1]); $p20++){ //descomentar linea de codigo para la version php 8.0
  //for ($p20=1; $p20<=sizeof(1); $p20++){ //comentar linea de codigo para la version php 8.0 
    $pago = $xml->createElement("pago20:Pago");
    $pago = $recep_pagos20->appendChild($pago);
    satxmlsv40_cargaAtt($pago, array(
      "FechaPago"=>"2021-11-23T00:00:00",
      "FormaDePagoP"=>"02",
      "MonedaP"=>"MXN",
      "TipoCambioP"=>"1",
      "Monto"=>"38280.00",
      "NumOperacion"=>false,
      "RfcEmisorCtaOrd"=>false,
      "NomBancoOrdExt"=>false,
      "CtaOrdenante"=>false,
      "RfcEmisorCtaBen"=>false,
      "CtaBeneficiario"=>false,
      "TipoCadPago"=>false,
      "CertPago"=>false,
      "CadPago"=>false,
      "SelloPago"=>false,
    ));
 
    //Nodo DoctoRelacionado
    for ($dr=1; $dr<=sizeof((array)[1]); $dr++){ //descomentar linea de codigo para la version php 8.0 
    // for ($dr=1; $dr<=sizeof(1); $dr++){ //comentar linea de codigo para la version php 8.0 
      $doc_relacionado = $xml->createElement("pago20:DoctoRelacionado");
      $doc_relacionado = $pago->appendChild($doc_relacionado);
      satxmlsv40_cargaAtt($doc_relacionado, array(
        "IdDocumento"=>"C3D58118-A8D0-4D88-8621-3EEB8C43D35C",
        "Serie"=>"COA",
        "Folio"=>"195",
        "MonedaDR"=>"MXN",
        "EquivalenciaDR"=>"1",
        "NumParcialidad"=>"1",
        "ImpSaldoAnt"=>"38280.00",
        "ImpPagado"=>"38280.00",
        "ImpSaldoInsoluto"=>"0.00",  
        "ObjetoImpDR"=>"02",
      ));
 
      //Nodo ImpuestosDR
      $impuestosDR = true; 
      if($impuestosDR == null){
      } else {        
          $impuestosDR = $xml->createElement("pago20:ImpuestosDR");
          $impuestosDR = $doc_relacionado->appendChild($impuestosDR);        
      }
 
      // Nodo retencionesDR
      $retencionesDR =  false;
      if ($retencionesDR == null){
      }else {        
          $retencionesDR = $xml->createElement("pago20:RetencionesDR");
          $retencionesDR = $impuestosDR->appendChild($retencionesDR);
 
          for($r=1; $r<= sizeof((array)[1]); $r++){ //descomentar linea de codigo para la version php 8.0
        //   for($r=1; $r<= sizeof(1); $r++){ //comentar linea de codigo para la version php 8.0 
          $retencionDR = $xml->createElement("pago20:RetencionDR");
          $retencionDR = $retencionesDR->appendChild($retencionDR);
          satxmlsv40_cargaAtt($retencionDR, array(
            "BaseDR"=>false,
            "ImpuestoDR"=>false,
            "TipoFactorDR"=>false,
            "TasaOCuotaDR"=>false,
            "ImporteDR"=>false,
          ));
        }
      }
 
      //Nodo TrasladosDR 
      $trasladosDR = true;
      if($trasladosDR == null){            
      }else {
        $trasladosDR = $xml->createElement("pago20:TrasladosDR");
        $trasladosDR = $impuestosDR->appendChild($trasladosDR);
        for($i=1; $i<= sizeof((array)[1]); $i++){ //descomentar linea de codigo para la version php 8.0
        // for($i=1; $i<= sizeof(1); $i++){ //comentar linea de codigo para la version php 8.0
          $trasladoDR = $xml->createElement("pago20:TrasladoDR");
          $trasladoDR = $trasladosDR->appendChild($trasladoDR);
          satxmlsv40_cargaAtt($trasladoDR, array(
            "BaseDR"=>"33000.00",
            "ImpuestoDR"=>"002",
            "TipoFactorDR"=>"Tasa",
            "TasaOCuotaDR"=>"0.160000",
            "ImporteDR"=>"5280.00",
         ));
        }
      } 
 
      $impuestosP = true;
      if($impuestosP == null){        
      }else{
        $impuestosP = $xml->createElement("pago20:ImpuestosP");
        $impuestosP = $pago->appendChild($impuestosP);
 
        $retencionesP = false;
        if($retencionesP == null){
          }else{
            $retencionesP = $xml->createElement("pago20:RetencionesP");
            $retencionesP = $impuestosP->appendChild($retencionesP);
 
            $retencionP = $xml->createElement("pago20:RetencionP");
            $retencionP = $retencionesP->appendChild($retencionP);
            satxmlsv40_cargaAtt($retencionP, array(
              "ImpuestoP"=>false,
              "ImporteP"=>false,
            ));
          } 
        $trasladosP = true;
        if($trasladosP == null){
          }else{
            $trasladosP = $xml->createElement("pago20:TrasladosP");
            $trasladosP = $impuestosP->appendChild($trasladosP);
 
            $trasladoP = $xml->createElement("pago20:TrasladoP");
            $trasladoP = $trasladosP->appendChild($trasladoP);
            satxmlsv40_cargaAtt($trasladoP, array(
              "BaseP"=>"33000.00",
              "ImpuestoP"=>"002",
              "TipoFactorP"=>"Tasa",
              "TasaOCuotaP"=>"0.160000",
              "ImporteP"=>"5280.00",
            ));
          } 
      }
 
    }
  }
}
 
 
function no_Certificado()
{
    $cer = "/home/ccalix/Documentos/soporte_tecnico/actividades_02_06_oct/kemper/CSD_Sucursal_1_EKU9003173C9_20230517_223850.cer"; //Ruta del archivo .cer
    $noCertificado = shell_exec("openssl x509 -inform DER -in " . $cer . " -noout -serial");
    $noCertificado = str_replace(' ', ' ', $noCertificado);
    $arr1 = str_split($noCertificado);
    $certificado = '';
    for ($i = 7; $i < count($arr1); $i++) {
        # code...
        if ($i % 2 == 0) {
            $certificado = ($certificado . ($arr1[$i]));
        }
    }
    return $certificado;
}
 
 
// genera_cadena_original
function satxmlsv40_genera_cadena_original() {
global $xml, $cadena_original;
$paso = new DOMDocument;
$paso->loadXML($xml->saveXML());
$xsl = new DOMDocument;
$file="/home/ccalix/Documentos/soporte_tecnico/actividades_02_06_oct/xslt/cadenaoriginal_4_0.xslt";  // Ruta al archivo
$xsl->load($file);
$proc = new XSLTProcessor;
$proc->importStyleSheet($xsl);
$cadena_original = $proc->transformToXML($paso);
$cadena_original = str_replace(array("\r", "\n"), '', $cadena_original);
#echo $cadena_original;
}
// 
 
// Calculo de sello
function satxmlsv40_sella($arr) {
global $root, $cadena_original, $sello;
$certificado = no_Certificado();
$file="/home/ccalix/Documentos/soporte_tecnico/actividades_02_06_oct/kemper/CSD_Sucursal_1_EKU9003173C9_20230517_223850.key.pem";      // Ruta al archivo
// Obtiene la llave privada del Certificado de Sello Digital (CSD),
//    Ojo , Nunca es la FIEL/FEA
$pkeyid = openssl_get_privatekey(file_get_contents($file));
openssl_sign($cadena_original, $crypttext, $pkeyid, OPENSSL_ALGO_SHA256);
openssl_free_key($pkeyid); // comentar linea de codigo para la version php 8.0 
 
$sello = base64_encode($crypttext);      // lo codifica en formato base64
$root->setAttribute("Sello",$sello);
 
$file="/home/ccalix/Documentos/soporte_tecnico/actividades_02_06_oct/kemper/CSD_Sucursal_1_EKU9003173C9_20230517_223850.cer.pem";      // Ruta al archivo de Llave publica
$datos = file($file);
$certificado = ""; $carga=false;
for ($i=0; $i<sizeof($datos); $i++) {
    if (strstr($datos[$i],"END CERTIFICATE")) $carga=false;
    if ($carga) $certificado .= trim($datos[$i]);
    if (strstr($datos[$i],"BEGIN CERTIFICATE")) $carga=true;
}
// El certificado como base64 lo agrega al XML para simplificar la validacion
$root->setAttribute("Certificado",$certificado);
}
 
 
// {{{ Termina, graba en edidata o genera archivo en el disco
function satxmlsv40_termina($arr,$dir) {
global $xml, $conn;
$xml->formatOutput = true;
$todo = $xml->saveXML();
$nufa = $arr['Serie'].$arr['Folio'];    // Junta el numero de factura   serie + folio
$paso = $todo;
file_put_contents("/home/ccalix/Documentos/soporte_tecnico/actividades_02_06_oct/generar_xmls/CFDI40_REP20.xml",$todo);
 
    $xml->formatOutput = true;
    $file=$dir.$nufa.".xml";
    $xml->save($file);
 
return($todo);
}
// {{{ Funcion que carga los atributos a la etiqueta XML
function satxmlsv40_cargaAtt(&$nodo, $attr) {
$quitar = array('Sello'=>1,'NoCertificado'=>1,'Certificado'=>1);
foreach ($attr as $key => $val) {
    $val = preg_replace('/\s\s+/', ' ', $val);   // Regla 5a y 5c
    $val = trim($val);                           // Regla 5b
    if (strlen($val)>0) {   // Regla 6
        $val = utf8_encode(str_replace("|","/",$val)); // Regla 1
        $nodo->setAttribute($key,$val);
    }
}
}
 
?>