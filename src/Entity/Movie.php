<?php

namespace App\Entity;

use App\Repository\MovieRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use DateTime;


/**
 * @ORM\Entity(repositoryClass=MovieRepository::class)
 */
class Movie
{   
    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $movieid;

    /**
     * @var string
     * @ORM\Column(name="movietitle", type="string", length=256, nullable=false)
     */
    private $movietitle;

    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * @var string
     * @ORM\Column(name="director", type="string", length=256, nullable=false)
     */
    private $director;

     /**
     * @var string
     * @ORM\Column(name="langage", type="string", length=256, nullable=false)
     */
    private $langage;

    /**
     * @var string
     * @ORM\Column(name="genre", type="string", length=256, nullable=false)
     */
    private $genre;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $rottem_tomatoes;

     /**
     * @var DateTime
     * @ORM\Column(name="release_date", type="datetime")
     * @Assert\DateTime()
     */
    private $release_date;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $duration;

    /**
     * @var string
     * @ORM\Column(name="distribution_type", type="string", length=256, nullable=false)
     */
    private $distribution_type;

    /**
     * @var string
     * @ORM\Column(name="production_company", type="string", length=256, nullable=false)
     */
    private $production_company;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMovieid(): ?int
    {
        return $this->movieid;
    }

    public function setMovieid(int $movieid): self
    {
        $this->movieid = $movieid;

        return $this;
    }

    public function getMovietitle(): ?string
    {
        return $this->movietitle;
    }

    public function setMovietitle(string $movietitle): self
    {
        $this->movietitle = $movietitle;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDirector(): ?string
    {
        return $this->director;
    }

    public function setDirector(string $director): self
    {
        $this->director = $director;

        return $this;
    }

    public function getLangage(): ?string
    {
        return $this->langage;
    }

    public function setLangage(string $langage): self
    {
        $this->langage = $langage;

        return $this;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(string $genre): self
    {
        $this->genre = $genre;

        return $this;
    }

    public function getRottemTomatoes(): ?int
    {
        return $this->rottem_tomatoes;
    }

    public function setRottemTomatoes(int $rottem_tomatoes): self
    {
        $this->rottem_tomatoes = $rottem_tomatoes;

        return $this;
    }

    public function getReleaseDate(): ?\DateTimeInterface
    {
        return $this->release_date;
    }

    public function setReleaseDate(\DateTimeInterface $release_date): self
    {
        $this->release_date = $release_date;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getDistributionType(): ?string
    {
        return $this->distribution_type;
    }

    public function setDistributionType(string $distribution_type): self
    {
        $this->distribution_type = $distribution_type;

        return $this;
    }

    public function getProductionCompany(): ?string
    {
        return $this->production_company;
    }

    public function setProductionCompany(string $production_company): self
    {
        $this->production_company = $production_company;

        return $this;
    }
}
