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
satxmlsv40_InformacionGlobal($arr, $edidata, $dir,$nodo,$addenda);
satxmlsv40_relacionados($arr, $edidata, $dir,$nodo,$addenda);
satxmlsv40_emisor($arr, $edidata, $dir,$nodo,$addenda);
satxmlsv40_receptor($arr, $edidata, $dir,$nodo,$addenda);
satxmlsv40_conceptos($arr, $edidata, $dir,$nodo,$addenda);
satxmlsv40_impuestos($arr, $edidata, $dir,$nodo,$addenda);
satxmlsv40_carta_porte($arr, $edidata, $dir, $nodo);
 
}
// }}}
 
// {{{  Datos generales del Comprobante
function satxmlsv40_generales($arr, $edidata, $dir,$nodo,$addenda) {
global $root, $xml;
$root = $xml->createElement("cfdi:Comprobante");
$root = $xml->appendChild($root);
 
satxmlsv40_cargaAtt($root, array("xmlns:cfdi"=>"http://www.sat.gob.mx/cfd/4",
                          "xmlns:xsi"=>"http://www.w3.org/2001/XMLSchema-instance",
                          "xsi:schemaLocation"=>"http://www.sat.gob.mx/cfd/4 http://www.sat.gob.mx/sitio_internet/cfd/4/cfdv40.xsd http://www.sat.gob.mx/CartaPorte20 http://www.sat.gob.mx/sitio_internet/cfd/CartaPorte/CartaPorte20.xsd",
                          "xmlns:cartaporte20" => "http://www.sat.gob.mx/CartaPorte20",
 
                         )
                     );
 
satxmlsv40_cargaAtt($root, array("Version"=>"4.0",
                      "Serie"=>"A",
                      "Folio"=>"1",
                      "Fecha"=>date("Y-m-d"). "T" .date("H:i:s"),
                      "Sello"=>"@",
                      "FormaPago"=>"02",
                      "NoCertificado"=>no_Certificado(),
                      "Certificado"=>"@",
                      "SubTotal"=>"6474.81",
                      "Moneda"=>"MXN",
                      "TipoCambio"=>"1",
                      "Total"=>"7510.77",
                      "TipoDeComprobante"=>"I", 
                      "Exportacion"=> "01",
                      "MetodoPago"=> "PUE",
                      "LugarExpedicion"=>"58000",
                   )
                );
}
 
// Datos de InformacionGlobal
function satxmlsv40_InformacionGlobal($arr, $edidata, $dir,$nodo,$addenda) {
    global $root, $xml;
        $iglobal = false; // indicamos si el nodo existira dentro del XML (true= existe, false = se omite)
        if ($iglobal == true)
        {
            $iglobal = $xml->createElement("cfdi:InformacionGlobal");
            $iglobal = $root->appendChild($iglobal);
            satxmlsv40_cargaAtt($iglobal, array("Periodicidad"=>"01",
                                          "Meses"=>"01",
                                          "Año"=>"2022",
                                        )
                                    );
        }
}
 
// {{{ Datos de documentos relacionados
function satxmlsv40_relacionados($arr, $edidata, $dir,$nodo,$addenda) {
    global $root, $xml;
 
    $cfdis = false; // indicamos si el nodo existira dentro del XML (true= existe, false = se omite)
    if ($cfdis== true){
 
 
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
satxmlsv40_cargaAtt($receptor, array("Rfc"=>"AABF800614HI0",
                                     "Nombre"=>"FELIX MANUEL ANDRADE BALLADO",
                                     "UsoCFDI"=>"S01",
                                     "RegimenFiscalReceptor"=>"616",
                                     "DomicilioFiscalReceptor"=>"86400",
                      )
                  );
}
 
// 
// Detalle de los conceptos/productos de la factura
function satxmlsv40_conceptos($arr, $edidata, $dir,$nodo,$addenda) {
global $root, $xml;
$conceptos = $xml->createElement("cfdi:Conceptos");
$conceptos = $root->appendChild($conceptos);
for ($i=1; $i<=sizeof((array)[1]); $i++) { //descomentar linea de codigo para la version php 8.0

    $concepto = $xml->createElement("cfdi:Concepto");
    $concepto = $conceptos->appendChild($concepto);
    satxmlsv40_cargaAtt($concepto, array(
                              "ClaveProdServ" => "78101501",
                              //"NoIdentificacion"=>"NO123"
                              "Cantidad" => "1.00",
                              "ClaveUnidad" => "CE",
                              "NoIdentificacion" => "00001",
                              "Unidad" => "CE",
                              "Descripcion" => "ARRENDAMIENTO DE JUAREZ PTE 108-A",
                              "ValorUnitario" => "6474.81",
                              "Importe"=>"6474.81",
                              //"Descuento" => "22500.00",
                              "ObjetoImp" => "02"
        )
    );
    $impuestos = true; // indicamos si el nodo existirá dentro del XML (true= existe, false = se omite)
    if ($impuestos == true) 
    {
        $impuestos = $xml->createElement("cfdi:Impuestos");
        $impuestos = $concepto->appendChild($impuestos);
 
        $traslados = true;
        if ($traslados = true) 
        {
            $traslados = $xml->createElement("cfdi:Traslados");
            $traslados = $impuestos->appendChild($traslados);
            $traslado = $xml->createElement("cfdi:Traslado");
            $traslado = $traslados->appendChild($traslado);
            satxmlsv40_cargaAtt(
                $traslado,
                array(
                    "Base" => "6474.81",
                    "Impuesto" => "002",
                    "TipoFactor" => "Tasa",
                    "TasaOCuota" => "0.160000",
                    "Importe" => "1035.96",
                )
            );
        }
 
        $retenciones = false; // indicamos si el nodo existira dentro del XML (true= existe, false = se omite)
        if ($retenciones == true) 
        {
            $retenciones = $xml->CreateElement("cfdi:Retenciones");
            $retenciones = $impuestos->appendChild($retenciones);
            $retencion = $xml->CreateElement("cfdi:Retencion");
            $retencion = $retenciones->appendChild($retencion);
            satxmlsv40_cargaAtt(
                $retencion,
                array(
                    "Base" => "1000.00",
                    "Importe" => "40.00",
                    "Impuesto" => "002",
                    "TasaOCuota" => "0.040000",
                    "TipoFactor" => "Tasa",
 
                )
            );
        }
    }
  }
}
// 
// Impuesto (IVA)
function satxmlsv40_impuestos($arr, $edidata, $dir,$nodo,$addenda) {
global $root, $xml;
    $impuestos2 = true; // indicamos si el nodo existira dentro del XML (true= existe, false = se omite)
    if ($impuestos2 == true) 
    {
        $impuestos2 = $xml->CreateElement("cfdi:Impuestos");
        $impuestos2 = $root->appendChild($impuestos2);
        $impuestos2->SetAttribute("TotalImpuestosTrasladados","1035.96");
        //$impuestos2->SetAttribute("TotalImpuestosRetenidos","3599.99");
 
        $retenciones2 = false; // indicamos si el nodo existira dentro del XML (true= existe, false = se omite)
        if ($retenciones2 == true)
        {
 
            $retenciones2 = $xml->CreateElement("cfdi:Retenciones");
            $retenciones2 = $impuestos2->appendChild($retenciones2);
            for ($c = 1; $c <= sizeof((array)[1]); $c++) { //descomentar linea de codigo para la version php 8.0
           
                $retencion2 = $xml->CreateElement("cfdi:Retencion");
                $retencion2 = $retenciones2->appendChild($retencion2);
                satxmlsv40_cargaAtt($retencion2, array("Importe" => "40.00",
                                                       "Impuesto" => "002",
                ));
            }
        }
        $traslados2 = true; // indicamos si el nodo existira dentro del XML (true= existe, false = se omite)
 
        if ($traslados2 == true) 
        {
            $traslados2 = $xml->CreateElement("cfdi:Traslados");
            $traslados2 = $impuestos2->appendChild($traslados2);
 
            for ($c = 1; $c <= sizeof((array)[1]); $c++) { //descomentar linea de codigo para la version php 8.0 
           
                $traslado2 = $xml->CreateElement("cfdi:Traslado");
                $traslado2 = $traslados2->appendChild($traslado2);
                satxmlsv40_cargaAtt($traslado2, array("Base" => "6474.81",
                                                      "Importe" => "1035.96",
                                                      "Impuesto" => "002",
                                                      "TasaOCuota" => "0.160000",
                                                      "TipoFactor" => "Tasa",
                                                    )
                                                );
            }
        }
 
    }
}
 
function satxmlsv40_carta_porte($arr, $edidata, $dir, $nodo)
{
    global $root, $xml;
    $complemento_cp = $xml->createElement("cfdi:Complemento");
    $complemento_cp = $root->appendChild($complemento_cp);
    $cartap_cartaPorte = $xml->createElement("cartaporte20:CartaPorte");
    $cartap_cartaPorte = $complemento_cp->appendChild($cartap_cartaPorte);
    satxmlsv40_cargaAtt($cartap_cartaPorte, array(
        "Version" => "2.0",
        "TranspInternac" => utf8_decode("No"),
        "EntradaSalidaMerc" => False,
        "PaisOrigenDestino" => false,
        "ViaEntradaSalida" => False,
        "TotalDistRec" => "75.00",
 
    ));
 
    // CREAMOS EL NODO UBICACIONES => UBICACION
 
    $ub_ubicaciones = $xml->createElement("cartaporte20:Ubicaciones");
    $ub_ubicaciones = $cartap_cartaPorte->appendChild($ub_ubicaciones);
    for ($c = 1; $c <= sizeof((array)[1]); $c++) { //descomentar linea de codigo para la version php 8.0
    
        $ubicacion = $xml->createElement("cartaporte20:Ubicacion");
        $ubicacion = $ub_ubicaciones->appendChild($ubicacion);
        satxmlsv40_cargaAtt(
            $ubicacion,
            array(
                "TipoUbicacion" => "Origen",
                "IDUbicacion" => False,
                "RFCRemitenteDestinatario" => "EKU9003173C9",
                "NombreRemitenteDestinatario" => false,
                "NumRegIdTrib" => false,
                "ResidenciaFiscal" => false,
                "NumEstacion" => false,
                "NombreEstacion" => false,
                "NavegacionTrafico" => false,
                "FechaHoraSalidaLlegada" => "2021-11-09T10:04:00",
                "TipoEstacion" => false,
                "DistanciaRecorrida" => false
 
            )
 
        );
        $cp_domicilio = $xml->createElement("cartaporte20:Domicilio");
        $cp_domicilio = $ubicacion->appendChild($cp_domicilio);
        satxmlsv40_cargaAtt($cp_domicilio, array(
            "Calle" => "Calle",
            "NumeroExterior" => "63",
            "NumeroInterior" => false,
            "Colonia" => "0162",
            "Localidad" => "20",
            "Referencia" => false,
            "Municipio" => "106",
            "Estado" => "MEX",
            "Pais" => "MEX",
            "CodigoPostal" => "50009",
 
 
        ));
 
        $ubicacion = $xml->createElement("cartaporte20:Ubicacion");
        $ubicacion = $ub_ubicaciones->appendChild($ubicacion);
        satxmlsv40_cargaAtt(
            $ubicacion,
            array(
                "TipoUbicacion" => "Destino",
                "IDUbicacion" => False,
                "RFCRemitenteDestinatario" => "IVD920810GU2",
                "NombreRemitenteDestinatario" => false,
                "NumRegIdTrib" => false,
                "ResidenciaFiscal" => false,
                "NumEstacion" => false,
                "NombreEstacion" => false,
                "NavegacionTrafico" => false,
                "FechaHoraSalidaLlegada" => "2021-11-10T14:00:00",
                "TipoEstacion" => false,
                "DistanciaRecorrida" => "75.00"
 
            )
 
        );
 
        $cp_domicilio = $xml->createElement("cartaporte20:Domicilio");
        $cp_domicilio = $ubicacion->appendChild($cp_domicilio);
        satxmlsv40_cargaAtt($cp_domicilio, array(
            "Calle" => "Calle",
            "NumeroExterior" => "63",
            "NumeroInterior" => false,
            "Colonia" => "0162",
            "Localidad" => "20",
            "Referencia" => false,
            "Municipio" => "106",
            "Estado" => "MEX",
            "Pais" => "MEX",
            "CodigoPostal" => "50009",
 
        ));
    }
 
    //Generamos los nodos de Mercancias
 
    $mercancias = $xml->createElement("cartaporte20:Mercancias");
    $mercancias = $cartap_cartaPorte->appendChild($mercancias);
    satxmlsv40_cargaAtt(
        $mercancias,
        array(
            "PesoBrutoTotal" => "35.00",
            "UnidadPeso" => "KGM",
            "PesoNetoTotal" => false,
            "NumTotalMercancias" => "1",
            "CargoPorTasacion" => false
 
        )
 
    );
    for ($c = 1; $c <= sizeof((array)[1]); $c++) { //descomentar linea de codigo para la version 8.0 
    
        $mercancia = $xml->CreateElement("cartaporte20:Mercancia");
        $mercancia = $mercancias->appendChild($mercancia);
        satxmlsv40_cargaAtt(
            $mercancia,
            array(
 
                "BienesTransp" => "50202513",
                "ClaveSTCC" => false,
                "Descripcion" => "mercancia",
                "Cantidad" => "15.00",
                "ClaveUnidad" => "KGM",
                "Unidad" => false,
                "Dimenciones" => false,
                "MaterialPeligroso" => utf8_decode(false),
                "CveMaterialPeligroso" => false,
                "Embalaje" => false,
                "DescripEmbalaje" => false,
                "PesoEnKg" => "35.00",
                "ValorMercancia" => "1740.00",
                "Moneda" => "MXN",
                "FraccionArancelaria" => false,
                "UUIDComercioExt" => false,
 
            )
 
        );
    }
 
    // NODOS PEDIMENTOS DENTRO DE MERCANCIA
 
 
    $pedimentos = false; // indicamos si el nodo existira dentro del XML (true= existe, null = se omite)
 
    if ($pedimentos == null) {
    } else {
        for ($c = 1; $c <= sizeof((array)[1]); $c++) { //descomentar linea de codigo para la version php 8.0 
        
            $pedimentos = $xml->CreateElement("cartaporte20:Pedimentos");
            $pedimentos = $mercancia->appendChild($pedimentos);
            satxmlsv40_cargaAtt($pedimentos, array(
                "Pedimento" => "21  01  3173  1000001",
            ));
        }
    }
 
 
 
 
    //NODO GUIASiDENTEIFICACION DENTRO DE MERCANCIA
 
    $Guiasdentificacion = null; // indicamos si el nodo existira dentro del XML (true= existe, null = se omite)
    if ($Guiasdentificacion == null) {
    } else {
        for ($c = 1; $c <= sizeof((array)[1]); $c++) { //descomentar linea de codigo para la version php 8.0
    
            $Guiasdentificacion = $xml->createElement("cartaporte20:GuiasIdentificacion");
            $Guiasdentificacion = $mercancia->appendChild($Guiasdentificacion);
            satxmlsv40_cargaAtt($Guiasdentificacion, array(
                "NumeroGuiaIdentificacion" => "4343545665776",
                "DescripGuiaIdentificacion" => "guia",
                "PesoGuiaIdentificacion" => false,
 
 
            ));
        }
    }
 
 
    // NODO CANTIDADTRANSPORTA DENTRO DE MERCANCIA
    $cantidasTransporta = null; // indicamos si el nodo existira dentro del XML (true= existe, null = se omite)
    if ($cantidasTransporta == null) {
    } else {
        for ($c = 1; $c <= sizeof((array)[1]); $c++) { //descomentar linea de codigo para la verion php 8.0
        
            $cantidasTransporta = $xml->createElement("carataporte20:CantidadTransporta");
            $cantidasTransporta = $mercancia->appendChild($cantidasTransporta);
            satxmlsv40_cargaAtt($cantidasTransporta, array(
                "Cantidad" => "4",
                "IDOrigen" => false,
                "IDDestino" => false,
                "CvesTransporte" => false,
 
            ));
        }
    }
 
    //NODO DETALLEMERCANCIA DENTRO DE MERCANCIA
    $detalleMercancia = null; // indicamos si el nodo existira dentro del XML (true= existe, null = se omite)
    if ($detalleMercancia == null) {
    } else {
        $detalleMercancia = $xml->createElement("cartaporte20:DetalleMercancia");
        $detalleMercancia = $mercancia->appendChild($detalleMercancia);
        satxmlsv40_cargaAtt($detalleMercancia, array(
            "UnidadPesoMerc" => false,
            "PesoBruto" => false,
            "PesoNeto" => false,
            "PesoTara" => false,
            "NumPiezas" => false,
 
        ));
    }
 
 
    // CREAMOS NODO DE AUTOTRANSPORTE
    $autoTransporte = true; // indicamos si el nodo existira dentro del XML (true= existe, null = se omite)
    if ($autoTransporte == null) {
    } else {
        $autoTransporte = $xml->CreateElement("cartaporte20:Autotransporte");
        $autoTransporte = $mercancias->appendChild($autoTransporte);
        satxmlsv40_cargaAtt($autoTransporte, array(
            "PermSCT" => "TPAF03",
            "NumPermisoSCT" => "PER012345",
        ));
 
        $identtificacionVehicular = $xml->CreateElement("cartaporte20:IdentificacionVehicular");
        $identtificacionVehicular = $autoTransporte->appendChild($identtificacionVehicular);
        satxmlsv40_cargaAtt($identtificacionVehicular, array(
            "ConfigVehicular" => "VL",
            "PlacaVM" => "ABC3215",
            "AnioModeloVM" => "2020",
 
        ));
 
 
        $seguros = $xml->CreateElement("cartaporte20:Seguros");
        $seguros = $autoTransporte->appendChild($seguros);
        satxmlsv40_cargaAtt($seguros, array(
            "AseguraRespCivil" => "AAA-33B",
            "PolizaRespCivil" => "ACD-H23",
            "AseguraMedAmbiente" => false,
            "PolizaMedAmbiente" => false,
            "AseguraCarga" => false,
            "PolizaCarga" => false,
            "PrimaSeguro" => false,
 
 
        ));
        $remolques = true;
        if ($remolques == null) {
        } else {
            $remolques = $xml->CreateElement("cartaporte20:Remolques");
            $remolques = $autoTransporte->appendChild($remolques);
            for ($c = 1; $c <= sizeof((array)[1]); $c++) { //descomentar linea de codigo para la version php 8.0
    
                $remolque = $xml->CreateElement("cartaporte20:Remolque");
                $remolque = $remolques->appendChild($remolque);
                satxmlsv40_cargaAtt($remolque, array(
                    "SubTipoRem" => "CTR005",
                    "Placa" => "YYY1234",
 
                ));
            }
        }
    }
 
 
    //NODO DE FIGURA TRANSPORTE
 
    $FiguraTransporte = true;
    if ($FiguraTransporte == null) {
    } else {
        $FiguraTransporte = $xml->CreateElement("cartaporte20:FiguraTransporte");
        $FiguraTransporte = $cartap_cartaPorte->appendChild($FiguraTransporte);
    }
 
 
    $tiposFigura = true;
    if ($tiposFigura == null) {
    } else {
        for ($c = 1; $c <= sizeof((array)[1]); $c++) { //descomentar linea de codigo para la verison php 8.0
       
            $tiposFigura = $xml->CreateElement("cartaporte20:TiposFigura");
            $tiposFigura = $FiguraTransporte->appendChild($tiposFigura);
            satxmlsv40_cargaAtt($tiposFigura, array(
 
                "TipoFigura" => "01",
                "RFCFigura" => "XIQB891116QE4",
                "NumLicencia" => "123456",
                "NombreFigura" => false,
                "NumRegIdTribFigura" => false,
                "ResidenciaFiscalFigura" => false,
 
            ));
            $partesTransporte = null;
            if ($partesTransporte == null) {
            } else {
                $partesTransporte = $xml->CreateElement("cartaporte20:PartesTransporte");
                $partesTransporte = $tiposFigura->appendChild($partesTransporte);
                satxmlsv40_cargaAtt($partesTransporte, array(
                    "ParteTransporte" => false,
 
                ));
            }
            $domicilio_tipos = null;
            if ($domicilio_tipos == null) {
            } else {
                $domicilio_tipos = $xml->CreateElement("cartaporte20:Domicilio");
                $domicilio_tipos = $tiposFigura->appendChild($domicilio_tipos);
                satxmlsv40_cargaAtt($domicilio_tipos, array(
                    "Calle" => "Calle",
                    "Colonia" => "0162",
                    "Localidad" => "20",
                    "Municipio" => "106",
                    "NumeroExterior" => "63",
                    "Estado" => "MEX",
                    "Pais" => "MEX",
                    "CodigoPostal" => "50009",
 
                ));
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
//openssl_free_key($pkeyid); //comentar linea de codigo para la version php 8.0
 
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
file_put_contents("/home/ccalix/Documentos/soporte_tecnico/actividades_02_06_oct/generar_xmls/CFDI40_CartaPorte.xml",$todo);
 
    $xml->formatOutput = true;
    $file=$dir.$nufa.".xml";
    $xml->save($file);
 
return($todo);
}
// {{{ Funcion que carga los atributos a la etiqueta XML
function satxmlsv40_cargaAtt(&$nodo, $attr) {
    $quitar = array('sello' => 1, 'noCertificado' => 1, 'certificado' => 1);
    foreach ($attr as $key => $val) {
        if  ( $key != "Pedimento") {
           $val = preg_replace('/\s\s+/', ' ', $val);
        }
        $val = trim($val);
        if (strlen($val) > 0) {
            $val = utf8_encode(str_replace("|", "/", $val));
            $nodo->setAttribute($key, $val);
        }
    }}
 
 
?>