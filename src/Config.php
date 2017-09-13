<?php
/**
 * MIT License
 *
 * Copyright (c) 2017 Jonas Silva
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 * @author    Jonas R. Silva
 * @copyright 2017 Jonas R. Silva
 * @license   MIT License
 * */
namespace Correios;

class Config {
    const SERVICO_SEDEX = 40010;//SEDEX Varejo
    const SERVICO_SEDEX_A_COBRAR = 40045;//SEDEX a Cobrar Varejo
    const SERVICO_SEDEX_10 = 40215;//SEDEX 10 Varejo
    const SERVICO_SEDEX_HOJE = 40290;//SEDEX Hoje Varejo
    const SERVICO_PAC = 41106;//PAC Varejo
    
    const FORMATO_CAIXA = 1;
    const FORMATO_PACOTE = 1;
    const FORMATO_ROLO = 2;
    const FORMATO_PRISMA = 2;
    const FORMATO_ENVELOPE = 3;
    
    static $cfg = [];
    static function setDefaults($configFile){
        if( is_file($configFile) ){
            $config = file_get_contents($configFile);
            $config = str_replace("\r\n", "\n", $config);
            
            $params = explode("\n", $config);
            foreach ($params as $param){
                if(strpos($param, "=")!==false){
                    list($name,$value) = explode("=", $param);
                    self::set($name, $value);
                }
            }
        } else {
            throw new \Exception("Arquivo de configurações do 'Correios' não encontrado.");
        }
    }
    static function set($name, $value){
        self::$cfg[$name] = $value;
    }
    static function nCdServicoAppend($nCdServico){
        $value = self::get('nCdServico',true);
        if(is_null($value) || empty($value)){
            $value = $nCdServico;
        } else {
            $value .= ",".$nCdServico;
        }
        self::set('nCdServico',$value);
    }
    
    static function get($name,$silence = false,$default = null){
        if( isset(self::$cfg[$name]) )
            return self::$cfg[$name];
        
       if(!$silence)
           throw new \Exception("Parametro '{$name}' não configurado");
       
        return $default;
    }
    /*nCdEmpresa String Seu código administrativo junto à ECT. O código está
disponível no corpo do contrato firmado com os
Correios.
Não, mas o parâmetro
tem que ser passado
mesmo vazio.
sDsSenha String Senha para acesso ao serviço, associada ao seu
código administrativo. A senha inicial corresponde aos
8 primeiros dígitos do CNPJ informado no contrato. A
qualquer momento, é possível alterar a senha no
endereço
http://www.corporativo.correios.com.br/encomendas/s
ervicosonline/recuperaSenha.
Não, mas o parâmetro
tem que ser passado
mesmo vazio.
.
nCdServico String Código do serviço:
Código Serviço
40010 SEDEX Varejo
40045 SEDEX a Cobrar Varejo
40215 SEDEX 10 Varejo
40290 SEDEX Hoje Varejo
41106 PAC Varejo
Para outros serviços, consulte o código no seu
contrato.
Sim.
Pode ser mais de um
numa consulta
separados por vírgula.
sCepOrigem String CEP de Origem sem hífen.Exemplo: 05311900 Sim
sCepDestino String CEP de Destino sem hífen Sim
nVlPeso String Peso da encomenda, incluindo sua embalagem. O
peso deve ser informado em quilogramas. Se o
formato for Envelope, o valor máximo permitido será 1
kg.
Sim
nCdFormato Int Formato da encomenda (incluindo embalagem).
Valores possíveis: 1, 2 ou 3
1 – Formato caixa/pacote
2 – Formato rolo/prisma
3 - Envelope
Sim
nVlComprimento Decimal Comprimento da encomenda (incluindo embalagem),
em centímetros.
Sim.*/
}