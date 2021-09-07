<?php

namespace App\Entity;

use App\Repository\MarcadorRepository;
use App\Validator as AppAssert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass=MarcadorRepository::class)
 */
class Marcador
{
    /**
     * @ORM\Id  
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Url
     * @AppAssert\UrlAccesible
     */
    private $url;

    /**
     * @ORM\ManyToOne(targetEntity=Categoria::class)
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank
     */
    private $categoria;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creado;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $favorito;
// es necesario que en el many to many metamos la persistencia en cascada para que se pueda crear de otra clase una etiqueta si o si , si no no nos dejara y saltara un error
    /**
     * @ORM\ManyToMany(targetEntity=Etiqueta::class , cascade={"persist"})
     */
    private $etiqueta;

    public function __construct()
    {
        $this->etiqueta = new ArrayCollection();
    }

     //con este campo indicamos que realce esta funcion antes de guardarse , es lo mismo que lo indiquemos en el constructor
    /**
     * @ORM\PrePersist
     */
    public function setValorDefecto(){
        $this->creado = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getCategoria(): ?Categoria
    {
        return $this->categoria;
    }

    public function setCategoria(?Categoria $categoria): self
    {
        $this->categoria = $categoria;

        return $this;
    }

    public function getCreado(): ?\DateTimeInterface
    {
        return $this->creado;
    }

    public function setCreado(\DateTimeInterface $creado): self
    {
        $this->creado = $creado;

        return $this;
    }

    public function getFavorito(): ?bool
    {
        return $this->favorito;
    }

    public function setFavorito(?bool $favorito): self
    {
        $this->favorito = $favorito;

        return $this;
    }

    /**
     * @return Collection|Etiqueta[]
     */
    public function getEtiqueta(): Collection
    {
        return $this->etiqueta;
    }

    public function addEtiquetum(Etiqueta $etiquetum): self
    {
        if (!$this->etiqueta->contains($etiquetum)) {
            $this->etiqueta[] = $etiquetum;
        }

        return $this;
    }

    public function removeEtiquetum(Etiqueta $etiquetum): self
    {
        $this->etiqueta->removeElement($etiquetum);

        return $this;
    }
}
