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
    
    /** Configura por um arquivo no servidor
     * 
     * @param string $configFile Filename(Completo) do arquivo
     * @throws \Exception
     */
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
	/** Configura um parametro
     * 
     * @param string $name
     * @param string $value
     */
    static function set($name, $value){
        self::$cfg[$name] = $value;
    }
	/** Adiciona(no final) ao parametro nCdServico um servico
     * 
     * @param string $nCdServico
     */
    static function nCdServicoAppend($nCdServico){
        $value = self::get('nCdServico',true);
        if(is_null($value) || empty($value)){
            $value = $nCdServico;
        } else {
            $value .= ",".$nCdServico;
        }
        self::set('nCdServico',$value);
    }
    
	/** Pega valor de parametro
     * 
     * @param string $name Nome do Parametro
     * @param boolean $silence Set true para nao mostrar mensagem quando não encontrado
     * @param string $default Valor padrao se nao encontrar parametro
     */
    static function get($name,$silence = false,$default = null){
        if( isset(self::$cfg[$name]) )
            return self::$cfg[$name];
        
       if(!$silence)
           throw new \Exception("Parametro '{$name}' não configurado");
       
        return $default;
    }
}