<?php
    function cls() {
        print("\033[2J\033[;H");
    }

    function display($field) {
        for ($i = 0; $i < count($field); $i++) {
            for ($n = 0; $n < count($field[$i]); $n++) {
                echo $field[$i][$n];
            }
            echo "\n";
        } 
    }

    function setup_ground($x,$y) {
        $field = [];
        for ($i = 0; $i <= $x; $i++) {
            for ($n = 0; $n <= $y; $n++) {
                $field[$i][$n] = "\033[0;33m.\033[0m";
            }
        }
        return $field;
    }

    function glassgrow($field) {
        for ($i = 0; $i < count($field); $i++) {
            for ($n = 0; $n < count($field[$i]); $n++) {
                $prob = rand(1,100);
                if ($prob == 100) {
                    $field[$i][$n] = "\033[1;32mW\033[0m";
                    $roops = 1;
                    do {
                        $split = rand(1,2);
                        if (array_key_exists($i - $roops, $field) && $split == 2) {
                            $field[$i - $roops][$n] = "\033[0;32mW\033[0m";
                        }
                        $roops += 1;
                    } while ($split == 2);
                    $roops = 1;
                    do {
                        $split = rand(1,2);
                        if (array_key_exists($n + $roops, $field) && $split == 2) {
                            $field[$i][$n + $roops] = "\033[0;32mW\033[0m";
                        }
                        $roops += 1;
                    } while ($split == 2);
                    $roops = 1;
                    do {
                        $split = rand(1,2);
                        if (array_key_exists($i + $roops, $field) && $split == 2) {
                            $field[$i + $roops][$n] = "\033[0;32mW\033[0m";
                        }
                        $roops += 1;
                    } while ($split == 2);
                    $roops = 1;
                    do {
                        $split = rand(1,2);
                        if (array_key_exists($n - $roops, $field) && $split == 2) {
                            $field[$i][$n - $roops] = "\033[0;32mW\033[0m";
                        }
                        $roops += 1;
                    } while ($split == 2);
                    
                }
            }
        } 
        return $field;
    }

    cls();

    $field = setup_ground(20,50);
    $field_g = glassgrow($field);
    display($field_g);

?>