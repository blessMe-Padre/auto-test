<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Каталог</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?
    // session_start();
    // if (isset($_SESSION['selectedValue'])) {
    //     $selectedValue = $_SESSION['selectedValue'];
    // }
    
    function aj_get($sql)
    {
        error_reporting(0);

        ##----- CONFIG ---------
        $code = 'APTnghDfD64KJ';       ## REQUIRED
        $server = '78.46.90.228'; ## optional :: $server='144.76.203.145'; 
        $go = 'api';              ## optional :: $go='gzip'; // gzip work faster
    
        ## SET IP,URL
        $ip = $_SERVER['HTTP_CF_CONNECTING_IP'] == '' ? $_SERVER['REMOTE_ADDR'] : $_SERVER['HTTP_CF_CONNECTING_IP'];
        $url = 'http://' . $server . '/' . $go . '/?ip=' . $ip . '&json&code=' . $code . '&sql=' . urlencode(preg_replace("/%25/", "%", $sql));

        ## DEBUG
        // echo "<hr><a style='font-size:12px' href='$url'>".$url."</a><hr>";
    
        ## API REQUEST
        $s = file_get_contents($url);
        //echo $s;
    
        ## GZIP DECODE
        if ($go == 'gzip') {
            $s = $server == '144.76.203.145' ? gzinflate(substr($s, 10, -8)) :
                gzuncompress(preg_replace("/^\\x1f\\x8b\\x08\\x00\\x00\\x00\\x00\\x00/", "", $s));
        }

        $arr = json_decode($s, true);  //die(var_export($arr));
        // echo gettype($arr);
        // print_r($arr);
        return $arr;
    }
    ?>

    <form class="pt-10 pb-10 grid grid-cols-1 md:grid-cols-4 gap-4 uppercase" action="/" method="get">
        <div class="mb-4">
            <?php
            $arr = aj_get("select marka_name from main group by marka_id order by marka_name ASC limit 5");
            $json = json_encode($arr);

            print_r($json);

            echo '<label class="block text-white text-sm font-medium mb-2" for="marka_name">Выберите марку</label>';
            echo '<select name="marka_name" class="block appearance-none w-full border  hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="markaAuc">';

            foreach ($arr as $v) {
                echo '<option value="' . $v['MARKA_NAME'] . '">' . $v['MARKA_NAME'] . "</option>";
            }

            echo '</select>';
            ?>
        </div>

        <div class="mb-4">
            <?php
            $arr_model = aj_get("select model_name from main where marka_name='" . $selectedValue . "' group by model_name order by model_name");
            echo $arr_model;
            echo '<label class="block text-white text-sm font-medium mb-2" for="model_name">Выберите модель</label>';
            echo '<select name="model_name" class="block appearance-none w-full border  hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="modelAuc">';
            echo '<option value="">Любая</option>';

            // foreach ($arr_model as $v) {
            //     echo '<option value="' . $v['MODEL_NAME'] . '">' . $v['MODEL_NAME'] . "</option>";
            // }
            
            echo '</select>';
            ?>
        </div>

        <?php
        echo "<p>" . htmlspecialchars($selectedValue) . "</p>";
        ?>

    </form>

    <p id="responseP"></p>

    <script src="main.js"></script>
</body>

</html>