<?php //EXEMPLO

require 'src\ECT.php';
require 'src\Config.php';
require 'src\HttpCurl.php';

use Correios\ECT;
use Correios\Config;

$ECT = new ECT();
Config::setDefaults( 'exemplo.config');
//Tipos de servico
Config::nCdServicoAppend(Config::SERVICO_PAC);
Config::nCdServicoAppend(Config::SERVICO_SEDEX);

Config::set('sCepOrigem', '30140070');
Config::set('sCepDestino', '30140040');
Config::set('nVlPeso', '0.5');/*Peso da encomenda, incluindo sua embalagem. O
peso deve ser informado em quilogramas. Se o
formato for Envelope, o valor máximo permitido será 1
kg.*/
Config::set('nCdFormato', Config::FORMATO_ENVELOPE);
Config::set('nVlComprimento', 33);//(incluindo embalagem), em centímetros
Config::set('nVlAltura', 2);//(incluindo embalagem), em centímetros
Config::set('nVlLargura', 22);//(incluindo embalagem), em centímetros


$servicos = $ECT->CalcPrecoPrazo();

header("Content-type:text/plain; Charset:utf-8");
echo json_encode($servicos, JSON_PRETTY_PRINT);
