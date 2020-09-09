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
        echo $GLOBALS["nowp"][0] . "," . $GLOBALS["nowp"][1];
        if ($GLOBALS["nowp"][2] == "\033[0;33m.\033[0m") {
            echo " dirt\n";
        } else if ($GLOBALS["nowp"][2] == "\033[1;32mW\033[0m") {
            echo " glass\n";
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

    function set_player($x,$y,$field) {
        $GLOBALS["nowp"] = array($x, $y, $field[$x][$y]);
        $field[$x][$y] = "\033[1;37m@\033[0m";
        return $field;
    }

    function pstate_disp($nowlife,$maxlife) {
        print "\n\n\n";
        $x = (int)($maxlife / 10);
        for ($i = 1; $i <= 10; $i++) {
            if ($nowlife > ($x * $i) - $x) {
                print "\033[0;32m■\033[0m";
            } else {
                print "\033[0;31m■\033[0m";
            }
        }
        print "\n" . $nowlife . "/" . $maxlife;
        print "\n\n";
    }

    function player_move($way, $field) {
        if ($way == "w" && array_key_exists($GLOBALS["nowp"][0] - 1, $field)) {
            $field[$GLOBALS["nowp"][0]][$GLOBALS["nowp"][1]] = $GLOBALS["nowp"][2];
            $GLOBALS["nowp"][0] = $GLOBALS["nowp"][0] - 1;
            $GLOBALS["nowp"][2] = $field[$GLOBALS["nowp"][0]][$GLOBALS["nowp"][1]];
            $field[$GLOBALS["nowp"][0]][$GLOBALS["nowp"][1]] = "\033[1;37m@\033[0m";
        } else if ($way == "d" && array_key_exists($GLOBALS["nowp"][1] + 1, $field[$GLOBALS["nowp"][0]])) {
            $field[$GLOBALS["nowp"][0]][$GLOBALS["nowp"][1]] = $GLOBALS["nowp"][2];
            $GLOBALS["nowp"][1] = $GLOBALS["nowp"][1] + 1;
            $GLOBALS["nowp"][2] = $field[$GLOBALS["nowp"][0]][$GLOBALS["nowp"][1]];
            $field[$GLOBALS["nowp"][0]][$GLOBALS["nowp"][1]] = "\033[1;37m@\033[0m";
        } else if ($way == "s" && array_key_exists($GLOBALS["nowp"][0] + 1, $field)) {
            $field[$GLOBALS["nowp"][0]][$GLOBALS["nowp"][1]] = $GLOBALS["nowp"][2];
            $GLOBALS["nowp"][0] = $GLOBALS["nowp"][0] + 1;
            $GLOBALS["nowp"][2] = $field[$GLOBALS["nowp"][0]][$GLOBALS["nowp"][1]];
            $field[$GLOBALS["nowp"][0]][$GLOBALS["nowp"][1]] = "\033[1;37m@\033[0m";
        } else if ($way == "a" && array_key_exists($GLOBALS["nowp"][1] - 1, $field[$GLOBALS["nowp"][0]])) {
            $field[$GLOBALS["nowp"][0]][$GLOBALS["nowp"][1]] = $GLOBALS["nowp"][2];
            $GLOBALS["nowp"][1] = $GLOBALS["nowp"][1] - 1;
            $GLOBALS["nowp"][2] = $field[$GLOBALS["nowp"][0]][$GLOBALS["nowp"][1]];
            $field[$GLOBALS["nowp"][0]][$GLOBALS["nowp"][1]] = "\033[1;37m@\033[0m";
        }
        return $field;
    }

    function glassgrow($field) {
        function glasspointset($field) {
            for ($i = 0; $i < count($field); $i++) {
                for ($n = 0; $n < count($field[$i]); $n++) {
                    $prob = rand(1,100);
                    if ($prob == 100) {
                        $growed[] = array($i, $n);
                    }
                }
            }  
            return $growed;
        }
        
        function recursionglass($field, $glow_p) {
            $field[$glow_p[0]][$glow_p[1]] = "\033[1;32mW\033[0m";

            $prob = rand(1,4);
            if (array_key_exists($glow_p[0] - 1, $field) && $prob == 4) {
                $glow_p[0]--;
                $field = recursionglass($field, $glow_p);
                $glow_p[0]++;
            } 
            
            $prob = rand(1,4);
            if (array_key_exists($glow_p[1] + 1, $field[$glow_p[0]]) && $prob == 4) {
                $glow_p[1]++;
                $field = recursionglass($field, $glow_p);
                $glow_p[1]--;
            }

            $prob = rand(1,4);
            if (array_key_exists($glow_p[0] + 1, $field) && $prob == 4) {
                $glow_p[0]++;
                $field = recursionglass($field, $glow_p);
                $glow_p[0]--;
            }

            $prob = rand(1,4);
            if (array_key_exists($glow_p[1] - 1, $field[$glow_p[0]]) && $prob == 4) {
                $glow_p[1]--;
                $field = recursionglass($field, $glow_p);
                $glow_p[1]++;
            }

            return $field;
        }

        $grown = glasspointset($field);
        for ($i = 0; $i < count($grown); $i++) {
            $field = recursionglass($field, $grown[$i]);
        }
        return $field;
    }

    cls();

    $field = setup_ground(20,50);
    $field_g = glassgrow($field);
    $field_p = set_player(10, 30, $field_g);
    display($field_p);

    $lifestate = array(50, 50);
    pstate_disp($lifestate[0], $lifestate[1]);
    $i = true;

    while ($i == true) {
        $ctrl = trim(fgets(STDIN));
        if ($ctrl == "w" || $ctrl == "d" || $ctrl == "s" || $ctrl == "a") {
            $field_p = player_move($ctrl, $field_p);
            display($field_p);
            pstate_disp($lifestate[0], $lifestate[1]);
        } else if ($ctrl == "e") {
            echo "終了します。\n";
        break;
        }
    }


?>