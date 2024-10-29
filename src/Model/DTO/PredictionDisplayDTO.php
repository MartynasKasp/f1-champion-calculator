<?php

namespace App\Model\DTO;

class PredictionDisplayDTO
{
    /** @var array<int, PredictionDisplayItemDTO[]> */
    public array $items = [];
    /** @var array<string, string> */
    public array $drivers = [];
    public string $grandPrix;
    public string $leaderName;
}
