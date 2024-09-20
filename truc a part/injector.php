<?php

//le pdo.php c'est juste
// <?php

// try{
//     $pdo = new PDO('mysql:host=localhost;dbname=ecommerce', 'UTILISATEUR', 'CODE');
// }catch(Exception $e){
//     echo $e->getMessage();
// }

include 'pdo.php';

$thP = file_get_contents('https://www.lego.com/en-us/themes');
$sec = explode("</section>", explode("<section", $thP)[1])[0];
$articles = explode('<article', $thP);
array_shift($articles);

foreach ($articles as &$art) {
    $art = explode('</article>', $art)[0];
    $art = explode('"><div class="', $art)[0];
    if (str_contains($art, 'href="/en-us/themes/')) {
        $art = explode('href="/en-us/themes/', $art)[1];
    } else {
        $art = '';
    }
}
$articles = array_filter($articles);

$themes = [];
foreach ($articles as &$art) {
    usleep(50000);
    $q = $pdo->query("SELECT * FROM theme WHERE theme LIKE '$art'");
    $r = $q->fetchAll();
    if (count($r) < 1) {
        $q = $pdo->query("INSERT INTO theme (theme) VALUES ('$art')");
        $themes[$art] = $pdo->lastInsertId();
        echo "\e[91mTHEME \e[32mAdded:\e[39m " . $art . "\n";
    } else {
        echo "\e[91mTHEME \e[33mAlready here:\e[39m " . $art . "\n";
        $themes[$art] = $r[0]['id'];
    }
}


foreach ($themes as $theme => $idTheme) {
    echo "\n$theme \n";
    $themePage = file_get_contents("https://www.lego.com/en-us/themes/" . $theme);


    $themePage = explode('<ul>', explode('id="product-listing-grid"', $themePage)[1])[0];
    preg_match_all('/<article class="ProductLeaf_wrapper__H0TCb "(.*?)<\/article>/', $themePage, $r);
    $r = $r[0];

    foreach ($r as $pro) {
        preg_match_all('/" href="(.*?)"><ul id="carousel-container-/', $pro, $re);
        $proPage = file_get_contents("https://www.lego.com/" . $re[1][0]);

        preg_match_all('/"name":"(.*?)","slug":"/', $proPage, $res);
        if(!isset($res[1][0])){
            continue;
        }
        $res = explode('"', $res[1][0]);
        $name = array_pop($res);


        preg_match_all('/.attributes":(.*?),"ProductImage:/', $proPage, $json);
        preg_match_all('/.attributes":(.*?)tresbienmonpouletsucreauscure/', $json[1][0] . 'tresbienmonpouletsucreauscure', $json);
        $json = json_decode($json[1][0], true);


        $nb_piece = $json['pieceCount'];
        $petite_desc = $json['headlineText'];
        $age = str_replace('+', '', $json['ageRange']);
        $age = str_replace('½', '', $age);
        if (str_contains($age, '-')) {
            $age = explode('-', $age)[0];
        }else if(strlen($age) < 1){
            $age = 8;
            //c'est pas ouf mais bon osef non ?
        }

        $description = preg_replace('/\\\u003c/', '<', $json['bulletText']);
        $description = preg_replace('/\\\u003e/', '>', $description);
        $description = strip_tags($description);

        $description = str_replace("'", "’", $description);
        $petite_desc = str_replace("'", "’", $petite_desc);
        $name = str_replace("'", "’", $name);

        $description = str_replace('"', "’", $description);
        $petite_desc = str_replace('"', "’", $petite_desc);
        $name = str_replace('"', "’", $name);
        
        $description = str_replace("\u0026", "&", $description);
        $petite_desc = str_replace("\u0026", "&", $petite_desc);
        $name = str_replace("\u0026", "&", $name);

        preg_match_all('/,"formattedValue":(.*?)},"\$ProductVariant/', $proPage, $res);
        $res = explode('"', $res[1][0]);
        $prix = array_pop($res);

        preg_match_all('/"typename":"ProductSafetyWarning"}},(.*?)":{"text":"Share a photo of/', $proPage, $json);
        if (!isset($json[1][0])) {
            preg_match_all('/"safetyWarning":null},(.*?)":{"text":"Share a photo of/', $proPage, $json);
        }
        if (!isset($json[1][0])) {
            continue;
        }
        $arr = explode(',', $json[1][0]);
        array_pop($arr);
        $json = implode(',', $arr);
        $arr = explode(',"$', $json);
        array_pop($arr);
        $json = $arr[0];
        $arr = explode('{', $json);
        array_shift($arr);
        array_unshift($arr, '');
        $json = '{"osef":' . implode('{', $arr) . '}';

        $jason = json_decode($json, true);
        if(!$jason){continue;}
        $jason = array_values($jason);
        $imgs = '';
        foreach ($jason as $v) {
            if (isset($v['baseImgUrl'])) {
                if (strlen($imgs) > 1500) {
                    break;
                }
                $imgs .= $v['baseImgUrl'] . ",";
            }
        }
        $imgs = substr_replace($imgs, '', -1);

        preg_match('/(\d+(?:\.\d+)?)\s*in\.?\s*\((\d+(?:\.\d+)?)\s*cm\)\s*high,?\s*(\d+(?:\.\d+)?)\s*in\.?\s*\((\d+(?:\.\d+)?)\s*cm\)\s*wide,?\s*and\s*(\d+(?:\.\d+)?)\s*in\.?\s*\((\d+(?:\.\d+)?)\s*cm\)\s*deep/', $description, $matches);
        if ($matches) {
            $height = $matches[2];
            $width = $matches[4];
            $depth = $matches[6];

            $dimension = "$height cm x $width cm x $depth cm";
        } else {
            $dimension = "";
        }


        $q = $pdo->query("SELECT * FROM produit WHERE nom LIKE '$name'");
        $r = $q->fetchAll();
        if (count($r) < 1) {

            $q = $pdo->prepare("INSERT INTO produit (id_theme, nom, description, nb_piece, age, dimension,prix,petite_desc,img, popularite, stock) VALUES (:idTheme, :name, :description, :nb_piece, :age, :dimension, :prix, :petite_desc, :imgs, '0', '50')");
            $q->bindValue(':idTheme', $idTheme);
            $q->bindValue(':name', $name);
            $q->bindValue(':description', $description);
            $q->bindValue(':nb_piece', $nb_piece);
            $q->bindValue(':age', $age);
            $q->bindValue(':dimension', $dimension);
            $q->bindValue(':prix', $prix);
            $q->bindValue(':petite_desc', $petite_desc);
            $q->bindValue(':imgs', $imgs);
            $q->execute();
            echo "\e[95mPRODUIT \e[32mAdded:\e[39m " . $name . "\n";
        } else {
            echo "\e[95mPRODUIT \e[33mAlready here:\e[39m " . $name . "\n";
        }
    }
}
