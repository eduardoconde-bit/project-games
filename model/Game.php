<?php
class Game
{

    private ?int $cod;
    private string $name;
    public string $description;
    private ?float $rating;
    private ?string $cover;
    private int|string $gender;
    private int|string $producer;
    public bool $is_active;

    public function __construct(string $name, string $description, string $gender, string $producer, $rating = null, string $cover = null, ?int $cod = null, bool $isActive = true)
    {
        $this->setName($name);
        $this->description = $description;
        $this->setRating($rating);
        $this->setCover($cover);
        $this->setGender($gender);
        $this->setProducer($producer);
        $this->cod = $cod;
        $this->is_active = $isActive;
    }

    /**
     * Get the value of name
     *
     * @return  string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @param   mixed  $name  
     *
     * @return  void
     */
    public function setName(string $name): void
    {
        $isValidName = strlen($name) <= 45;

        if (!$isValidName) {
            throw new Exception("Invalid Lentgh name");
        }

        $this->name = $name;
    }

    /**
     * Get the value of rating
     *
     * @return  float|null
     */
    public function getRating(): float|null
    {
        return $this->rating;
    }

    /**
     * Set the value of rating (Between 0 and 5)
     *
     * @param   float|null $rating  
     *
     * @return  void
     */
    public function setRating(?float $rating = null): void
    {
        $rating = (float) $rating;
        $isValidRating = ($rating >= 0 and $rating <= 10.00);

        if (!$isValidRating) {
            throw new Exception("Invalid value Rating");
        }

        $this->rating = $rating;
    }

    /**
     * Get the value of cover [Name to Cover]
     *
     * @return  string
     */
    public function getCover(): string
    {
        return $this->cover;
    }

    /**
     * Set the value of cover
     *
     * @param   string|null  $cover  
     *
     * @return  self
     */
    public function setCover(?string $cover = null): void
    {
        $cover = (string) $cover;
        $isValidCover = strlen($cover) <= 50;

        if (!$isValidCover) {
            throw new Exception("Invalid Length cover");
        }
        $this->cover = $cover;
    }

    /**
     * Get the value of gender
     *
     * @return  mixed
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set the value of gender
     *
     * @param   string  $gender  
     *
     * @return  void
     */
    public function setGender(int|string $gender): void
    {
        $this->gender = $gender;
    }

    /**
     * Get the value of producer
     *
     * @return  string
     */
    public function getProducer()
    {
        return $this->producer;
    }

    /**
     * Set the value of producer
     *
     * @param   mixed  $producer  
     *
     * @return  void
     */
    public function setProducer(int|string $producer): void
    {
        $this->producer = $producer;
    }

    /**
     * Get the value of cod
     *
     * @return int
     */
    public function getCod():int
    {
        return $this->cod;
    }

    /**
     * Set the value of cod
     *
     * @param int $cod  
     *
     * @return void
     */
    public function setCod(int $cod):void
    {
        $this->cod = $cod;
    }

    public function __serialize(): array
    {
        return [
            "cod" => $this->cod,
            "name" => $this->name,
            "description" => $this->description,
            "rating" => $this->rating,
            "cover" => $this->cover,
            "gender" => $this->gender,
            "producer" => $this->producer,
        ];
    }

    public function __unserialize(array $data): void
    {
        $this->setCod($data["cod"]);
        $this->setName($data["name"]);
        $this->description = $data["description"];
        $this->setRating($data["rating"]);
        $this->setCover($data["cover"]);
        $this->setGender($data["gender"]);
        $this->setProducer($data["producer"]);
    }
}
