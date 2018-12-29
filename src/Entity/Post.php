<?php

    namespace App\Entity;

    use Doctrine\Common\Collections\ArrayCollection;
    use Doctrine\Common\Collections\Collection;
    use Doctrine\ORM\Mapping as ORM;

    /**
     * @ORM\Entity(repositoryClass="App\Repository\PostRepository")
     * @ORM\EntityListeners({"App\Listeners\PostListener"})
     */
    class Post
    {
        /**
         * @ORM\Id()
         * @ORM\GeneratedValue()
         * @ORM\Column(type="integer")
         */
        private $id;

        /**
         * @ORM\Column(type="string", length=255)
         */
        private $title;

        /**
         * @ORM\Column(type="string", length=255)
         */
        private $content;

        /**
         * @ORM\OneToMany(targetEntity="App\Entity\Attachment", mappedBy="post")
         */
        private $attachments;

        public function __construct()
        {
            $this->attachments = new ArrayCollection();
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

        public function getContent(): ?string
        {
            return $this->content;
        }

        public function setContent(string $content): self
        {
            $this->content = $content;

            return $this;
        }

        public function getCreatedAt(): ?\DateTimeInterface
        {
            return $this->created_at;
        }

        public function setCreatedAt(\DateTimeInterface $created_at): self
        {
            $this->created_at = $created_at;

            return $this;
        }

        public function getUpdatedAt(): ?\DateTimeInterface
        {
            return $this->updated_at;
        }

        public function setUpdatedAt(\DateTimeInterface $updated_at): self
        {
            $this->updated_at = $updated_at;

            return $this;
        }

        /**
         * @return Collection|Attachment[]
         */
        public function getAttachments(): Collection
        {
            return $this->attachments;
        }

        public function addAttachment(Attachment $attachment): self
        {
            if (!$this->attachments->contains($attachment)) {
                $this->attachments[] = $attachment;
                $attachment->setPost($this);
            }

            return $this;
        }

        public function removeAttachment(Attachment $attachment): self
        {
            if ($this->attachments->contains($attachment)) {
                $this->attachments->removeElement($attachment);
                // set the owning side to null (unless already changed)
                if ($attachment->getPost() === $this) {
                    $attachment->setPost(null);
                }
            }

            return $this;
        }
    }
