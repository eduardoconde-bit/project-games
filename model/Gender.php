<?php
class Gender {
    private ?int $cod = null;
    private string $gender;

    public function __construct(string $gender, ?int $cod = null)
    {
        $this->setCod($cod);
        $this->setGender($gender);
    }

	/**
	 * Get the value of cod
	 *
	 * @return  int|null
	 */
	public function getCod():?int
	{
		return $this->cod;
	}

	/**
	 * Set the value of cod
	 *
	 * @param   int  $cod  
	 *
	 * @return  void
	 */
	public function setCod(?int  $cod = null):void
	{
		$this->cod = $cod;
	}

	/**
	 * Get the value of genero
	 *
	 * @return  mixed
	 */
	public function getGender():string
	{
		return $this->gender;
	}

	/**
	 * Set the value of genero
	 *
	 * @param   string  $genero  
	 *
	 * @return  self
	 */
	public function setGender(string $gender)
	{
        $isGeneroValid = strlen($gender) <= 25;
        
        if(!$isGeneroValid) {
            throw new Exception("Invalid length in genero");    
        }
		$this->gender = $gender;
	}

	/**
	 * 
	 */
	public function __serialize(): array
	{
		return [
			"cod" => $this->cod,
			"gender" => $this->gender,
		];
	}

	/**
	 * 
	 */
	public function __unserialize(array $data): void
	{
		$this->setCod($data["cod"]);
		$this->setGender($data["gender"]);
	}
}