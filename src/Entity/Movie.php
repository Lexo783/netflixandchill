<?php

namespace App\Entity;

use App\Repository\MovieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
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
     * @Groups("result:movie")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("result:movie")
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Groups("result:movie")
     */
    private $plot;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("result:movie")
     */
    private $rated;

    /**
     * @ORM\Column(type="date")
     * @Groups("result:movie")
     */
    private $released;

    /**
     * @ORM\ManyToMany(targetEntity=Genre::class, inversedBy="movies")
     */
    private $genres;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("result:movie")
     */
    private $picture;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("result:movie")
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $movie;

    /**
     * @ORM\OneToMany(targetEntity=Favorite::class, mappedBy="movie")
     */
    private $favorites;

    /**
     * @ORM\OneToMany(targetEntity=Rate::class, mappedBy="movie")
     */
    private $rates;

    public function __construct()
    {
        $this->genres = new ArrayCollection();
        $this->favorites = new ArrayCollection();
        $this->rates = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getPlot(): ?string
    {
        return $this->plot;
    }

    public function setPlot(string $plot): self
    {
        $this->plot = $plot;

        return $this;
    }

    public function getRated(): ?string
    {
        return $this->rated;
    }

    public function setRated(string $rated): self
    {
        $this->rated = $rated;

        return $this;
    }

    public function getReleased(): ?\DateTimeInterface
    {
        return $this->released;
    }

    public function setReleased(\DateTimeInterface $released): self
    {
        $this->released = $released;

        return $this;
    }

    /**
     * @return Collection|Genre[]
     */
    public function getGenres(): Collection
    {
        return $this->genres;
    }

    public function addGenre(Genre $genre): self
    {
        if (!$this->genres->contains($genre)) {
            $this->genres[] = $genre;
        }

        return $this;
    }

    public function removeGenre(Genre $genre): self
    {
        $this->genres->removeElement($genre);

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getMovie(): ?string
    {
        return $this->movie;
    }

    public function setMovie(string $movie): self
    {
        $this->movie = $movie;
        return $this;
    }

    /**
     * @return Collection|Rate[]
     */
    public function getRates(): Collection
    {
        return $this->rates;
    }

    public function addRate(Rate $rate): self
    {
        if (!$this->rates->contains($rate)) {
            $this->rates[] = $rate;
            $rate->setMovie($this);
        }
    }
    /**
     * @return Collection|Favorite[]
     */
    public function getFavorites(): Collection
    {
        return $this->favorites;
    }

    public function addFavorite(Favorite $favorite): self
    {
        if (!$this->favorites->contains($favorite)) {
            $this->favorites[] = $favorite;
            $favorite->setMovie($this);
        }

        return $this;
    }

    public function removeRate(Rate $rate): self
    {
        if ($this->rates->removeElement($rate)) {
            // set the owning side to null (unless already changed)
            if ($rate->getMovie() === $this) {
                $rate->setMovie(null);
            }
        }
    }
    public function removeFavorite(Favorite $favorite): self
    {
        if ($this->favorites->removeElement($favorite)) {
            // set the owning side to null (unless already changed)
            if ($favorite->getMovie() === $this) {
                $favorite->setMovie(null);
            }
        }
        return $this;
    }
}
