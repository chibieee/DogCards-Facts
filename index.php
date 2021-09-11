<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head lang="en-US">
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Dog Card Project</title>
        <link rel="stylesheet" href="/css/style.css" />
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js"></script>
        <script src="https://unpkg.com/imagesloaded@4/imagesloaded.pkgd.min.js"></script>
        <script src="/js/script.js"></script>
    </head>

    <body>

        <h1>Dog Card Project</h1>
        <?php

        $num = $_GET["number"];
        $cURLDogBreed = curl_init();
        $cURLDogFact = curl_init();

        if($num) {
            $dogBreedAPI = 'https://dog.ceo/api/breeds/image/random/'. $num .'';
            $dogFactAPI = 'https://dog-facts-api.herokuapp.com/api/v1/resources/dogs?number='. $num .'';
        } else {
            $dogBreedAPI = 'https://dog.ceo/api/breeds/image/random';
            $dogFactAPI = 'https://dog-facts-api.herokuapp.com/api/v1/resources/dogs?number=1';
            
        }

        curl_setopt($cURLDogBreed, CURLOPT_URL, $dogBreedAPI);
        curl_setopt($cURLDogBreed, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($cURLDogFact, CURLOPT_URL, $dogFactAPI);
        curl_setopt($cURLDogFact, CURLOPT_RETURNTRANSFER, true);

        $mh = curl_multi_init();
        curl_multi_add_handle($mh,$cURLDogBreed);
        curl_multi_add_handle($mh,$cURLDogFact);


        do {
            $status = curl_multi_exec($mh, $active);
            if ($active) {
                curl_multi_select($mh);
            }
        } while ($active && $status == CURLM_OK);

        $response_1 = curl_multi_getcontent($cURLDogBreed);
        $response_2 = curl_multi_getcontent($cURLDogFact);
        
        curl_multi_remove_handle($mh, $cURLDogBreed);
        curl_multi_remove_handle($mh, $cURLDogFact);
        curl_multi_close($mh);

        $dogBreedsArray = json_decode($response_1);
        $dogFactArray = json_decode($response_2);

        ?>

        
        <div class="dog-grid">
            <?php 
                if($num) {
                    for($i = 0; $i < $num; $i++) {
                        echo '<div class="dog-card">';
                            echo '<div class="dog-card-front"><img src="'. $dogBreedsArray->message[$i] .'"/></div>';
                            echo '<div class="dog-card-back"><p class="dog-fact">'.$dogFactArray[$i]->fact.'</p></div>';
                        echo '</div>';
                    }
                } else {
                    echo '<div class="dog-card">';
                        echo '<div class="dog-card-front"><img src="'. $dogBreedsArray->message .'"/></div>';
                        echo '<div class="dog-card-back"><p class="dog-fact">'.$dogFactArray[0]->fact.'</p></div>';
                    echo '</div>';
                }
                
            ?>
        </div>
    </body>
</html>