<?php

/*
 * OpenBoleto - Geração de boletos bancários em PHP
 *
 * LICENSE: The MIT License (MIT)
 *
 * Copyright (C) 2020
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this
 * software and associated documentation files (the "Software"), to deal in the Software
 * without restriction, including without limitation the rights to use, copy, modify,
 * merge, publish, distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to the following
 * conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies
 * or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED,
 * INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A
 * PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
 * HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
 * SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

namespace OpenBoleto\Banco;

use OpenBoleto\BoletoAbstract;
use OpenBoleto\Exception;

/**
 * Classe boleto Banco BV
 *
 * @package    OpenBoleto
 * @author     Rafael Laurindo <https://github.com/rafaellaurindo>
 * @license    MIT License
 * @version    1.0
 */
class Bv extends BoletoAbstract
{
    /**
     * Código do banco
     * @var string
     */
    protected $codigoBanco = '655';

    /**
     * Localização do logotipo do banco, referente ao diretório de imagens
     * @var string
     */
    protected $logoBanco = 'bv.png';

    /**
     * Linha de local de pagamento
     * @var string
     */
    protected $localPagamento = 'Pagável em qualquer banco até o vencimento';

    /**
     * Define as carteiras disponíveis para este banco
     * @var array
     */
    protected $carteiras = array('00');

    /**
     * Define o número do convênio (4, 6 ou 7 caracteres)
     * @var string
     */
    protected $convenio;

    /**
     * Array com as linhas de instruções
     * @var array
     */
    protected $instrucoes = 'Não receber após vencimento';

    /**
     * Espécie do documento, geralmente DM (Duplicata Mercantil)
     * @var string
     */
    protected $especieDoc = 'DM';

    /**
     * Modalidade de cobrança do cliente, geralmente Cobrança Simples ou Registrada
     * @var string
     */
    protected $carteira = '00';

    /**
     * @var array Nome espécie das moedas
     */
    protected static $especie = [
        parent::MOEDA_REAL => 'R$',
    ];

    /**
     * Define o número do convênio. Sempre use string pois a quantidade de caracteres é validada.
     *
     * @param string $convenio
     * @return BancoDoBrasil
     */
    public function setConvenio($convenio)
    {
        $this->convenio = $convenio;
        return $this;
    }

    /**
     * Retorna o número do convênio
     *
     * @return string
     */
    public function getConvenio()
    {
        return $this->convenio;
    }

    /**
     * Gera o Nosso Número.
     *
     * @throws Exception
     * @return string
     */
    protected function gerarNossoNumero(): string
    {
        $convenio = $this->getConvenio();
        $sequencial = $this->getSequencial();

        return self::zeroFill($convenio, 4) . '500' . self::zeroFill($sequencial, 10);
    }

    /**
     * Método para gerar o código da posição de 20 a 44
     *
     * @return string
     * @throws \OpenBoleto\Exception
     */
    public function getCampoLivre(): string
    {
        $nossoNumero = $this->gerarNossoNumero();

        return '000000' . $nossoNumero . self::zeroFill($this->getCarteira(), 2);
    }
}
