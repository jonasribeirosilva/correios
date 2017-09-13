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

class ECT {
    const VERSION = "1.0.0";
    /** Execulta o metodo CalcPrecoPrazo do webservice
     * 
     * @return array|boolean Retorna um array por servico OU se Erro retorna False
     */
    public function CalcPrecoPrazo(){
        $msgSend = new \DOMDocument('1.0', 'utf-8');
        $CalcPrecoPrazo = $msgSend->createElement('CalcPrecoPrazo');
        $CalcPrecoPrazo->setAttributeNS( "http://www.w3.org/2000/xmlns/",'xmlns','http://tempuri.org/');
        $CalcPrecoPrazo->appendChild( $msgSend->createElement('nCdEmpresa', Config::get('nCdEmpresa',true,'')));
        $CalcPrecoPrazo->appendChild( $msgSend->createElement('sDsSenha', Config::get('sDsSenha',true,'')));
        $CalcPrecoPrazo->appendChild( $msgSend->createElement('nCdServico', Config::get('nCdServico')));
        $CalcPrecoPrazo->appendChild( $msgSend->createElement('sCepOrigem', Config::get('sCepOrigem')));
        $CalcPrecoPrazo->appendChild( $msgSend->createElement('sCepDestino', Config::get('sCepDestino')));
        $CalcPrecoPrazo->appendChild( $msgSend->createElement('nVlPeso', Config::get('nVlPeso')));
        $CalcPrecoPrazo->appendChild( $msgSend->createElement('nCdFormato', Config::get('nCdFormato')));
        $CalcPrecoPrazo->appendChild( $msgSend->createElement('nVlComprimento', Config::get('nVlComprimento')));
        $CalcPrecoPrazo->appendChild( $msgSend->createElement('nVlAltura', Config::get('nVlAltura')));
        $CalcPrecoPrazo->appendChild( $msgSend->createElement('nVlLargura', Config::get('nVlLargura')));
        $CalcPrecoPrazo->appendChild( $msgSend->createElement('nVlDiametro', Config::get('nVlDiametro')));
        $CalcPrecoPrazo->appendChild( $msgSend->createElement('sCdMaoPropria', Config::get('sCdMaoPropria')));
        $CalcPrecoPrazo->appendChild( $msgSend->createElement('nVlValorDeclarado', Config::get('nVlValorDeclarado')));
        $CalcPrecoPrazo->appendChild( $msgSend->createElement('sCdAvisoRecebimento', Config::get('sCdAvisoRecebimento')));
        //-------------------------------------------------------------------
        $Http = new HttpCurl();
        $Http->setUrl('http://ws.correios.com.br/calculador/CalcPrecoPrazo.asmx');
        $Http->setSOAPAction('http://tempuri.org/CalcPrecoPrazo');
        $Http->envelopar( $CalcPrecoPrazo );
        $response = $Http->send();
        
        $xResponse = new \SimpleXMLElement($response);
        $xResponse->registerXPathNamespace("soap", "http://www.w3.org/2003/05/soap-envelope");
        $body = $xResponse->xpath("//soap:Body");
        
        $servicos = [];
        $Servicos = $body[0]
            ->CalcPrecoPrazoResponse[0]
            ->CalcPrecoPrazoResult[0]
            ->Servicos[0];
        
        foreach ($Servicos->cServico as $i=>$dataServico){
            $servicos[] = [
                'codigo' => $dataServico->Codigo->__toString(),
                'valor' => (float)str_replace(",", ".", $dataServico->Valor->__toString()),
                'prazoEntrega' => (int)$dataServico->PrazoEntrega->__toString(),
                'valorMaoPropria' => (float)str_replace(",", ".", $dataServico->ValorMaoPropria->__toString()),
                'valorAvisoRecebimento' => (float)str_replace(",", ".",$dataServico->ValorAvisoRecebimento->__toString()),
                    'valorValorDeclarado' => (float)str_replace(",", ".",$dataServico->ValorValorDeclarado->__toString()),
                'entregaDomiciliar' => $dataServico->EntregaDomiciliar->__toString()=='S',
                'entregaSabado' => $dataServico->EntregaSabado->__toString()=='S',
                'erro' => $dataServico->Erro->__toString()==0? false : $dataServico->Erro->__toString(),
                'msgErro' => $dataServico->MsgErro->__toString(),
                'valorSemAdicionais' => (float)str_replace(",", ".", $dataServico->ValorSemAdicionais->__toString()),
                'obsFim' => $dataServico->obsFim->__toString()
            ];
        }
        
        
        
        return $servicos;
    }
}