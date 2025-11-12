<?php

// app/Contracts/HasIcon.php

namespace App\Contracts;

interface HasIcon
{
    public function setIconId(?int $id): void;

    public function getIconId(): ?int;
}
