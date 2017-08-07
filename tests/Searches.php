<?php
use Kepler12\Bad\Search;

class OddsOnlySearch extends Search {
    public static $goal = OddsOnly::class;
    public static $rules = [
        RemoveFives::class,
        AboveTen::class
      ];
}

class AlwaysFail extends Search {
    public static $goal = NeverTrue::class;
    public static $rules = [
        A::class,
        B::class,
        C::class,
        D::class
      ];
}

class TrueFirst extends Search {
    public static $goal = AllTrue::class;
    public static $rules = [
        ReturnTrue::class,
        ReturnFalse::class,
        ReturnFalse::class,
        ReturnFalse::class
      ];
}
