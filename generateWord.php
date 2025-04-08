<?php

function generateWord($difficulty) {

    $easyLevel = array(
        "album" => "A collection of recorded music or photos",
        "health" => "General condition of the body or mind",
        "board" => "A long, flat piece of timber or other material",
        "cross" => "A symbol consisting of two intersecting lines",
        "deal" => "Distribute cards in an orderly rotation",
        "event" => "An occurrence, especially one of some importance",
        "frame" => "A rigid structure that surrounds something such as a picture",
        "globe" => "A spherical model of Earth or the celestial sphere",
        "logic" => "Reasoning conducted according to strict principles",
        "radio" => "Transmission and reception of electromagnetic waves"
    );
    $mediumLevel = array(
        "powerful" => "Having great power or force",
        "buzzsaw" => "A circular saw that remains stationary",
        "jukebox" => "A machine that automatically plays selected music",
        "regular" => "Conforming to common standards",
        "grizzly" => "A large fierce bear found in North America"
    );
    $hardLevel = array(
        "elephant" => "A large mammal with a trunk and large ears",
        "chemistry" => "The science of substances and their interactions",
        "epitome" => "A perfect example of a particular quality or type",
        "physics" => "The science of matter and energy and their interactions",
        "geography" => "The study of physical features of the Earth"
    );
    

    if ($difficulty == "easy") {
        $words = $easyLevel;
    } elseif ($difficulty == "medium") {
        $words = $mediumLevel;
    } elseif ($difficulty == "hard") {
        $words = $hardLevel;
    } else {
        $words = $easyLevel; 
    }

    $randomKey = array_rand($words);
    $word = $randomKey;
    $hint = $words[$randomKey];
    
    return ['word' => $word, 'hint' => $hint];
}


?>