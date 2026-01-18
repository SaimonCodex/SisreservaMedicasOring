<?php
$target = '0c0bc37267a758a7755b1b4fc634ba53';
$words = ['hola', 'Hola', 'HOLA', 'perro', 'Perro', 'azul', 'Azul', 'casa', 'Casa', 'mamá', 'Mamá', 'mama', 'Mama', '1234', '123456', 'mascota', 'Mascota'];

foreach ($words as $word) {
    $double = md5(md5($word));
    $single = md5($word);
    
    echo "Word: '$word' | Double: $double | Single: $single\n";
    
    if ($double === $target) echo "MATCH DOUBLE FOUND for '$word'!\n";
    if ($single === $target) echo "MATCH SINGLE FOUND for '$word'!\n";
}
