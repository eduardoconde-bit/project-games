<?php
class User
{
	//user_name = unique

	private string $userName;
	private string $fullName;
	private ?string $keyword;
	private string $userType;
	private bool $isAuthenticated = false;


	/*
	!IMPORTANTE: UM USUÁRIO PODE TER A AÇÃO DESLOGAR OU O SISTEMA PODE TER UM SERVIÇO PRA ISSO DENTRO DO AUTHENTICATION (MELHOR?)
	*/

	public function __construct(string $userName, string $fullName, ?string $keyword, ?string $userType, $isAuthenticated = false)
	{
		$this->setUserName($userName);
		$this->setFullName($fullName);
		$this->setKeyword($keyword);
		$this->setUserType($userType);
		$this->isAuthenticated = $isAuthenticated;
	}

	/**
	 * Get the value of userName
	 *
	 * @return  string
	 */
	public function getUserName(): string
	{
		return $this->userName;
	}

	/**
	 * Set the value of userName
	 * 
	 * Max 20 characters, if value > 20, throw Error
	 * 
	 * @param   string  $userName  
	 *
	 */

	//max 11 characters
	public function setUserName($userName): void
	{
		$isUserNameValid = strlen($userName) <= 20;

		if (!$isUserNameValid) {
			throw new Exception("character limit exceeded in user name");
		}
		$this->userName = $userName;
	}

	/**
	 * Get the value of name
	 *
	 * @return  string
	 */
	public function getFullName(): string
	{
		return $this->fullName;
	}

	/**
	 * Set the value of name
	 *
	 * @param  string  $name  
	 *
	 * @return  void
	 */
	public function setFullName(string $name)
	{
		$isNameValid = strlen($name) <= 50;

		if (!$isNameValid) {
			throw new Exception("Name Length Invalid!");
		}
		$this->fullName = $name;
	}

	/**
	 * Get the value of keyword
	 *
	 * @return  mixed
	 */
	public function getKeyword()
	{
		return $this->keyword;
	}

	/**
	 * Set the value of keyword
	 *
	 * @param   mixed  $keyword  
	 *
	 * @return  self
	 */

	//Verifica conformidades com hashs e relacionados.
	public function setKeyword($keyword): void
	{
		$isKeywordValid = strlen($keyword) <= 255;
		if (!$isKeywordValid) {
			throw new Exception("Keyword Length Invalid!");
		}
		$this->keyword = $keyword;
	}

	/**
	 * Get the value of userType
	 *
	 * @return  mixed
	 */
	public function getUserType(): string
	{
		return $this->userType;
	}

	/**
	 * Set the value of userType
	 *
	 * @param   mixed  $userType  
	 *
	 * @return  self
	 */
	public function setUserType(string $userType = null): void
	{
		$isUserTypeValid = $userType == "ADM" || $userType == "NRM";

		if (!$isUserTypeValid) {
			throw new Exception("User Type Invalid!");
		}
		$this->userType = $userType;
	}
}
