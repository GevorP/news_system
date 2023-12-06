<?php

namespace App\Entity\User;

use Doctrine\ORM\Mapping as ORM;
use Gesdinet\JWTRefreshTokenBundle\Entity\RefreshTokenRepository;
use Gesdinet\JWTRefreshTokenBundle\Model\AbstractRefreshToken;

#[ORM\Entity(RefreshTokenRepository::class)]
#[ORM\Table(name: 'refresh_tokens', schema: 'public')]
class RefreshToken extends AbstractRefreshToken
{
    #[ORM\Id]
    #[ORM\Column(type:"integer")]
    #[ORM\GeneratedValue(strategy:"SEQUENCE")]
    #[ORM\SequenceGenerator(sequenceName:"public.refresh_tokens_id_seq")]
    protected $id;

    #[ORM\Column(name: "refresh_token", type: "string", length: 128, unique: true)]
    protected $refreshToken;

    #[ORM\Column(name: "username", type: "string", length: 255)]
    protected $username;

    #[ORM\Column(name: "valid", type: "datetime")]
    protected $valid;
}
