<?php

namespace App\Entity;

use App\Repository\MovieRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MovieRepository::class)
 */
class Movie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $movieid;

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
}
