<?php
class Produtora {
    protected ?int $cod;
    protected ?string $produtora;
    protected ?string $pais;

    public function __construct(string $produtora, string $country, ?string $cod = null)
    {
        $this->setProdutora($produtora);
        $this->setCountry($country);
        $this->setCod($cod);
    }

	/**
	 * Get the value of cod
	 *
	 * @return  mixed
	 */
	public function getCod()
	{
		return $this->cod;
	}

	/**
	 * Set the value of cod
	 *
	 * @param   int|null  $cod  
	 *
	 * @return  void
	 */
	public function setCod(int $cod = null)
	{
		$this->cod = $cod;
	}

	/**
	 * Get the value of produtora
	 *
	 * @return  mixed
	 */
	public function getProdutora()
	{
		return $this->produtora;
	}

	/**
	 * Set the value of produtora
	 *
	 * @param   string  $produtora  
	 *
	 * @return  void
	 */
	public function setProdutora(string $produtora):void
	{
        $isProdutoraValid = strlen($produtora) <= 20;

        if (!$isProdutoraValid) {
            throw new Exception("Invalid Length produtora");
        }

		$this->produtora = $produtora;
	}

	/**
	 * Get the value of pais
	 *
	 * @return  mixed
	 */
	public function getPais()
	{
		return $this->pais;
	}

	/**
	 * Set the value of pais
	 *
	 * @param   string  $pais  
	 *
	 * @return  void
	 */
	public function setCountry(string $country):void
	{
        $isCountryValid = strlen($country) <= 20;

        if(!$isCountryValid) {
            throw new Exception("Invalid Length country");
        }

		$this->pais = $country;
	}
}