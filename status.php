<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Chip Status</title>
    <link rel="stylesheet" href="./assets/vendor/bootstrap/dist/css/bootstrap.min.css" crossorigin="anonymous">
    <script src="./assets/vendor/jquery/dist/jquery.min.js"  crossorigin="anonymous"></script>
    <script src="./assets/vendor/bootstrap/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <style type="text/css">
        h1 {
            text-align: center; 
            margin: 0 auto;
            padding-top: 20px;
        }
        #btn-navigator {
            text-align: center; 
            margin: 0 auto; 
            padding-top:40px; 
            padding-bottom: 30px;
        }
        button {
            margin-left: 20px;
        }
        .chain-data {
            padding: 10px 80px;
        }
        .visible {
            display: block;
        }
        .invisible {
            display: none;
        }
        td {
            font-size :14px;
        }
        h4{
            width:20%;      
            float:left;
        }
        
    </style>
  </head>
  <body>

  <?php
    function merge_spaces($string)
    {
        return preg_replace("/\s(?=\s)/","\\1",$string);
    }

    function getBoardLevel()
    {
		return trim(exec("head -n 1 /firmware_info | awk -F ' ' '{print $2}'"));
    }

    function handle_data($data)
    {
        array_pop($data);

        $real_str = [];
        foreach($data as $v)
        {
            $real_str[] = str_replace("\t", ",", $v);
        }

        $real_data = [];
        foreach($real_str as $v)
        {
            $real_data[] = explode(',', merge_spaces($v));
        }

        $render_data = [];
        foreach($real_data as $k=>$v)
        {
            $render_data[$k][] = explode(' ', merge_spaces($v[6]));
            $render_data[$k][] = explode(' ', merge_spaces($v[7]));
            $render_data[$k][] = explode(' ', merge_spaces($v[8]));
        }
        $actual_data = [];
        foreach($render_data as $k=>$v)
        {
            $actual_data[$k][] = $v[0][5]."|".$v[0][6]."|".$v[0][12]."|".$v[0][9];
            $actual_data[$k][] = $v[1][5]."|".$v[1][6]."|".$v[1][12]."|".$v[1][9];
            $actual_data[$k][] = $v[2][5]."|".$v[2][6]."|".$v[2][12]."|".$v[2][9];
        }
        return $actual_data;
    }

    $data1 = $data2 = $data3 = $data4 = [];
    if(file_exists("/tmp/status-0-map"))
    {
        $handle1 = fopen("/tmp/status-0-map", "r");
        $handle2 = fopen("/tmp/status-1-map", "r");
        $handle3 = fopen("/tmp/status-2-map", "r");
        $handle4 = fopen("/tmp/status-3-map", "r");
    } else {
        $handle1 = fopen("/tmp/status-1-map", "r");
        $handle2 = fopen("/tmp/status-2-map", "r");
        $handle3 = fopen("/tmp/status-3-map", "r");
    }

    if ($handle1) {
        while (($line = fgets($handle1)) !== false) {
            $data1[] = $line;
        }
        fclose($handle1);
    }
    if ($handle2) {
        
        while (($line = fgets($handle2)) !== false) {
            $data2[] = $line;
        }
        fclose($handle2);
    }
    if ($handle3) {
        while (($line = fgets($handle3)) !== false) {
            $data3[] = $line;
        }
        fclose($handle3);
    }
    if ($handle4) {
        while (($line = fgets($handle4)) !== false) {
            $data4[] = $line;
        }
        fclose($handle4);
    }
    $level = getBoardLevel();

    $last1 = $data1[count($data1)-2];
    $last2 = $data2[count($data2)-2];
    $last3 = $data3[count($data3)-2];
    $last4 = $data4[count($data4)-2];

    $actual_data1 = handle_data($data1);
    $actual_data2 = handle_data($data2);
    $actual_data3 = handle_data($data3);
    $actual_data4 = handle_data($data4);
    
    ?>
    <h1>芯片实时数据</h1>

    <div id="btn-navigator">
        <?php if ($level == 36): ?>
            <button class="btn btn-primary " data-ref="chain1" type="button">Chain #1</button>
            <button class="btn btn-default" data-ref="chain2" type="button">Chain #2</button>
            <button class="btn btn-default" data-ref="chain3" type="button">Chain #3</button>
            <button class="btn btn-default" data-ref="chain4" type="button">Chain #4</button>
        <?php else: ?>
            <button class="btn btn-primary" data-ref="chain1" type="button">Chain #1</button>
            <button class="btn btn-default" data-ref="chain2" type="button">Chain #2</button>
            <button class="btn btn-default" data-ref="chain3" type="button">Chain #3</button>
        <?php endif;?>
    </div>
   <?php
    if ($level ==  36) :
   ?>
   <div id="chain1" class="chain-data visible">
       <h4>板平均温度: <?php echo explode(' ', $last1)[20] ?>℃</h4>
       <h4>芯片平均温度： <?php echo explode (' ', $last1)[18] ?>℃</h4>
        <table class="table table-bordered table-hover table-responsive">
            <tr>
                <th class="active">芯片级数</th>
                <th class="active"> 电压|温度|yield值|硬件错误数</th>
                <th class="active"> 电压|温度|yield值|硬件错误数</th>
                <th class="active"> 电压|温度|yield值|硬件错误数</th>
                <th></th>
                <th class="active">芯片级数</th>
                <th class="active"> 电压|温度|yield值|硬件错误数</th>
                <th class="active"> 电压|温度|yield值|硬件错误数</th>
                <th class="active"> 电压|温度|yield值|硬件错误数</th>
            </tr>

            <tr>
                <td class="active">1(U1-U3)</td>
                <td class="active"><?php echo $actual_data1[0][2] ?></td>
                <td class="active"><?php echo $actual_data1[0][1] ?></td>
                <td class="active"><?php echo $actual_data1[0][0] ?></td>
                <td>&nbsp;</td>
                <td class="active">36(U106-U108)</td>
                <td class="active"><?php echo $actual_data1[35][2] ?></td>
                <td class="active"><?php echo $actual_data1[35][1] ?></td>
                <td class="active"><?php echo $actual_data1[35][0] ?></td>
            </tr>
            <tr>
                <td class="active">2(U4-U6)</td>
                <td class="active"><?php echo $actual_data1[1][0] ?></td>
                <td class="active"><?php echo $actual_data1[1][1] ?></td>
                <td class="active"><?php echo $actual_data1[1][2] ?></td>
                <td>&nbsp;</td>
                <td class="active">35(U103-U105)</td>
                <td class="active"><?php echo $actual_data1[34][0] ?></td>
                <td class="active"><?php echo $actual_data1[34][1] ?></td>
                <td class="active"><?php echo $actual_data1[34][2] ?></td>
            </tr>
            <tr>
                <td class="active">3(U7-U9)</td>
                <td class="active"><?php echo $actual_data1[2][2] ?></td>
                <td class="active"><?php echo $actual_data1[2][1] ?></td>
                <td class="active"><?php echo $actual_data1[2][0] ?></td>
                <td>&nbsp;</td>
                <td class="active">34(U100-U102)</td>
                <td class="active"><?php echo $actual_data1[33][2] ?></td>
                <td class="active"><?php echo $actual_data1[33][1] ?></td>
                <td class="active"><?php echo $actual_data1[33][0] ?></td>
            </tr>
            <tr>        
                <td class="active">4(U10-U12)</td>
                <td class="active"><?php echo $actual_data1[3][0] ?></td>
                <td class="active"><?php echo $actual_data1[3][1] ?></td>
                <td class="active"><?php echo $actual_data1[3][2] ?></td>
                <td>&nbsp;</td>
                <td class="active">33(U97-99)</td>
                <td class="active"><?php echo $actual_data1[32][0] ?></td>
                <td class="active"><?php echo $actual_data1[32][1] ?></td>
                <td class="active"><?php echo $actual_data1[32][2] ?></td>
            </tr>
            <tr>        
                <td class="active">5(U13-U15)</td>
                <td class="active"><?php echo $actual_data1[4][2] ?></td>
                <td class="active"><?php echo $actual_data1[4][1] ?></td>
                <td class="active"><?php echo $actual_data1[4][0] ?></td>
                <td>&nbsp;</td>
                <td class="active">32(U94-U96)</td>
                <td class="active"><?php echo $actual_data1[31][2] ?></td>
                <td class="active"><?php echo $actual_data1[31][1] ?></td>
                <td class="active"><?php echo $actual_data1[31][0] ?></td>
            </tr>
            <tr>        
                <td class="active">6(U16-U18)</td>
                <td class="active"><?php echo $actual_data1[5][0] ?></td>
                <td class="active"><?php echo $actual_data1[5][1] ?></td>
                <td class="active"><?php echo $actual_data1[5][2] ?></td>
                <td>&nbsp;</td>
                <td class="active">31(91-U93)</td>
                <td class="active"><?php echo $actual_data1[30][0] ?></td>
                <td class="active"><?php echo $actual_data1[30][1] ?></td>
                <td class="active"><?php echo $actual_data1[30][2] ?></td>
            </tr>
            <tr>        
                <td class="active">7(U19-U21)</td>
                <td class="active"><?php echo $actual_data1[6][2] ?></td>
                <td class="active"><?php echo $actual_data1[6][1] ?></td>
                <td class="active"><?php echo $actual_data1[6][0] ?></td>
                <td>&nbsp;</td>
                <td class="active">30(U88-U90)</td>
                <td class="active"><?php echo $actual_data1[29][2] ?></td>
                <td class="active"><?php echo $actual_data1[29][1] ?></td>
                <td class="active"><?php echo $actual_data1[29][0] ?></td>
            </tr>
            <tr>        
                <td class="active">8(U22-U24)</td>
                <td class="active"><?php echo $actual_data1[7][0] ?></td>
                <td class="active"><?php echo $actual_data1[7][1] ?></td>
                <td class="active"><?php echo $actual_data1[7][2] ?></td>
                <td>&nbsp;</td>
                <td class="active">29(U85-U87)</td>
                <td class="active"><?php echo $actual_data1[28][0] ?></td>
                <td class="active"><?php echo $actual_data1[28][1] ?></td>
                <td class="active"><?php echo $actual_data1[28][2] ?></td>
            </tr>
            <tr>        
                <td class="active">9(U25-U27)</td>
                <td class="active"><?php echo $actual_data1[8][2] ?></td>
                <td class="active"><?php echo $actual_data1[8][1] ?></td>
                <td class="active"><?php echo $actual_data1[8][0] ?></td>
                <td>&nbsp;</td>
                <td class="active">28(U82-U84)</td>
                <td class="active"><?php echo $actual_data1[27][2] ?></td>
                <td class="active"><?php echo $actual_data1[27][1] ?></td>
                <td class="active"><?php echo $actual_data1[27][0] ?></td>
            </tr>
            <tr>        
                <td class="active">10(U28-U30)</td>
                <td class="active"><?php echo $actual_data1[9][0] ?></td>
                <td class="active"><?php echo $actual_data1[9][1] ?></td>
                <td class="active"><?php echo $actual_data1[9][2] ?></td>
                <td>&nbsp;</td>
                <td class="active">27(U79-U81)</td>
                <td class="active"><?php echo $actual_data1[26][0] ?></td>
                <td class="active"><?php echo $actual_data1[26][1] ?></td>
                <td class="active"><?php echo $actual_data1[26][2] ?></td>
            </tr>
            <tr>        
                <td class="active">11(U31-U33)</td>
                <td class="active"><?php echo $actual_data1[10][2] ?></td>
                <td class="active"><?php echo $actual_data1[10][1] ?></td>
                <td class="active"><?php echo $actual_data1[10][0] ?></td>
                <td>&nbsp;</td>
                <td class="active">26(U76-U78)</td>
                <td class="active"><?php echo $actual_data1[25][2] ?></td>
                <td class="active"><?php echo $actual_data1[25][1] ?></td>
                <td class="active"><?php echo $actual_data1[25][0] ?></td>
            </tr>
            <tr>        
                <td class="active">12(U34-U36)</td>
                <td class="active"><?php echo $actual_data1[11][0] ?></td>
                <td class="active"><?php echo $actual_data1[11][1] ?></td>
                <td class="active"><?php echo $actual_data1[11][2] ?></td>
                <td>&nbsp;</td>
                <td class="active">25(U73-U75)</td>
                <td class="active"><?php echo $actual_data1[24][0] ?></td>
                <td class="active"><?php echo $actual_data1[24][1] ?></td>
                <td class="active"><?php echo $actual_data1[24][2] ?></td>
            </tr>
            <tr>        
                <td class="active">13(U37-U39)</td>
                <td class="active"><?php echo $actual_data1[12][2] ?></td>
                <td class="active"><?php echo $actual_data1[12][1] ?></td>
                <td class="active"><?php echo $actual_data1[12][0] ?></td>
                <td>&nbsp;</td>
                <td class="active">24(U70-U72)</td>
                <td class="active"><?php echo $actual_data1[23][2] ?></td>
                <td class="active"><?php echo $actual_data1[23][1] ?></td>
                <td class="active"><?php echo $actual_data1[23][0] ?></td>
            </tr>
            <tr>        
                <td class="active">14(U40-U42)</td>
                <td class="active"><?php echo $actual_data1[13][0] ?></td>
                <td class="active"><?php echo $actual_data1[13][1] ?></td>
                <td class="active"><?php echo $actual_data1[13][2] ?></td>
                <td>&nbsp;</td>
                <td class="active">23(U67-U69)</td>
                <td class="active"><?php echo $actual_data1[22][0] ?></td>
                <td class="active"><?php echo $actual_data1[22][1] ?></td>
                <td class="active"><?php echo $actual_data1[22][2] ?></td>
            </tr>
            <tr>        
                <td class="active">15(U43-U45)</td>
                <td class="active"><?php echo $actual_data1[14][2] ?></td>
                <td class="active"><?php echo $actual_data1[14][1] ?></td>
                <td class="active"><?php echo $actual_data1[14][0] ?></td>
                <td>&nbsp;</td>
                <td class="active">22(U64-U66)</td>
                <td class="active"><?php echo $actual_data1[21][2] ?></td>
                <td class="active"><?php echo $actual_data1[21][1] ?></td>
                <td class="active"><?php echo $actual_data1[21][0] ?></td>
            </tr>
            <tr>        
                <td class="active">16(U46-U48)</td>
                <td class="active"><?php echo $actual_data1[15][0] ?></td>
                <td class="active"><?php echo $actual_data1[15][1] ?></td>
                <td class="active"><?php echo $actual_data1[15][2] ?></td>
                <td>&nbsp;</td>
                <td class="active">21(U61-U63)</td>
                <td class="active"><?php echo $actual_data1[20][0] ?></td>
                <td class="active"><?php echo $actual_data1[20][1] ?></td>
                <td class="active"><?php echo $actual_data1[20][2] ?></td>
            </tr>
            <tr>        
                <td class="active">17(U49-U51)</td>
                <td class="active"><?php echo $actual_data1[16][2] ?></td>
                <td class="active"><?php echo $actual_data1[16][1] ?></td>
                <td class="active"><?php echo $actual_data1[16][0] ?></td>
                <td>&nbsp;</td>
                <td class="active">20(U58-U60)</td>
                <td class="active"><?php echo $actual_data1[19][2] ?></td>
                <td class="active"><?php echo $actual_data1[19][1] ?></td>
                <td class="active"><?php echo $actual_data1[19][0] ?></td>
            </tr>
            <tr>        
                <td class="active">18(U52-U54)</td>
                <td class="active"><?php echo $actual_data1[17][0] ?></td>
                <td class="active"><?php echo $actual_data1[17][1] ?></td>
                <td class="active"><?php echo $actual_data1[17][2] ?></td>
                <td>&nbsp;</td>
                <td class="active">19(U55-U57)</td>
                <td class="active"><?php echo $actual_data1[18][0] ?></td>
                <td class="active"><?php echo $actual_data1[18][1] ?></td>
                <td class="active"><?php echo $actual_data1[18][2] ?></td>
            </tr>
    </table>
    </div>

   <div id="chain2" class="chain-data invisible">
        <h4>板平均温度: <?php echo explode(' ', $last2)[20] ?>℃</h4>
        <h4>芯片平均温度： <?php echo explode (' ', $last2)[18] ?>℃</h4>
        <table class="table table-bordered table-hover table-responsive">
        <tr>
            <th class="active">芯片级数</th>
            <th class="active"> 电压|温度|yield值|硬件错误数</th>
            <th class="active"> 电压|温度|yield值|硬件错误数</th>
            <th class="active"> 电压|温度|yield值|硬件错误数</th>
            <th></th>
            <th class="active">芯片级数</th>
            <th class="active"> 电压|温度|yield值|硬件错误数</th>
            <th class="active"> 电压|温度|yield值|硬件错误数</th>
            <th class="active"> 电压|温度|yield值|硬件错误数</th>
        </tr>
        <tr>
            <td class="active">1(U1-U3)</td>
            <td class="active"><?php echo $actual_data2[0][0] ?></td>
            <td class="active"><?php echo $actual_data2[0][1] ?></td>
            <td class="active"><?php echo $actual_data2[0][2] ?></td>
            <td>&nbsp;</td>
            <td class="active">36(U106-U108)</td>
            <td class="active"><?php echo $actual_data2[35][0] ?></td>
            <td class="active"><?php echo $actual_data2[35][1] ?></td>
            <td class="active"><?php echo $actual_data2[35][2] ?></td>
        </tr>
        <tr>
            <td class="active">2(U4-U6)</td>
            <td class="active"><?php echo $actual_data2[1][0] ?></td>
            <td class="active"><?php echo $actual_data2[1][1] ?></td>
            <td class="active"><?php echo $actual_data2[1][2] ?></td>
            <td>&nbsp;</td>
            <td class="active">35(U103-U105)</td>
            <td class="active"><?php echo $actual_data2[34][0] ?></td>
            <td class="active"><?php echo $actual_data2[34][1] ?></td>
            <td class="active"><?php echo $actual_data2[34][2] ?></td>
        </tr>
        <tr>
            <td class="active">3(U7-U9)</td>
            <td class="active"><?php echo $actual_data2[2][0] ?></td>
            <td class="active"><?php echo $actual_data2[2][1] ?></td>
            <td class="active"><?php echo $actual_data2[2][2] ?></td>
            <td>&nbsp;</td>
            <td class="active">34(U100-U102)</td>
            <td class="active"><?php echo $actual_data2[33][0] ?></td>
            <td class="active"><?php echo $actual_data2[33][1] ?></td>
            <td class="active"><?php echo $actual_data2[33][2] ?></td>
        </tr>
        <tr>        
            <td class="active">4(U10-U12)</td>
            <td class="active"><?php echo $actual_data2[3][0] ?></td>
            <td class="active"><?php echo $actual_data2[3][1] ?></td>
            <td class="active"><?php echo $actual_data2[3][2] ?></td>
            <td>&nbsp;</td>
            <td class="active">33(U97-U99)</td>
            <td class="active"><?php echo $actual_data2[32][0] ?></td>
            <td class="active"><?php echo $actual_data2[32][1] ?></td>
            <td class="active"><?php echo $actual_data2[32][2] ?></td>
        </tr>
        <tr>        
            <td class="active">5(U13-U15)</td>
            <td class="active"><?php echo $actual_data2[4][0] ?></td>
            <td class="active"><?php echo $actual_data2[4][1] ?></td>
            <td class="active"><?php echo $actual_data2[4][2] ?></td>
            <td>&nbsp;</td>
            <td class="active">32(U94-U96)</td>
            <td class="active"><?php echo $actual_data2[31][0] ?></td>
            <td class="active"><?php echo $actual_data2[31][1] ?></td>
            <td class="active"><?php echo $actual_data2[31][2] ?></td>
        </tr>
        <tr>        
            <td class="active">6(U16-U18)</td>
            <td class="active"><?php echo $actual_data2[5][0] ?></td>
            <td class="active"><?php echo $actual_data2[5][1] ?></td>
            <td class="active"><?php echo $actual_data2[5][2] ?></td>
            <td>&nbsp;</td>
            <td class="active">31(U91-U93)</td>
            <td class="active"><?php echo $actual_data2[30][0] ?></td>
            <td class="active"><?php echo $actual_data2[30][1] ?></td>
            <td class="active"><?php echo $actual_data2[30][2] ?></td>
        </tr>
        <tr>        
            <td class="active">7(U19-U21)</td>
            <td class="active"><?php echo $actual_data2[6][0] ?></td>
            <td class="active"><?php echo $actual_data2[6][1] ?></td>
            <td class="active"><?php echo $actual_data2[6][2] ?></td>
            <td>&nbsp;</td>
            <td class="active">30(U88-U90)</td>
            <td class="active"><?php echo $actual_data2[31][0] ?></td>
            <td class="active"><?php echo $actual_data2[31][1] ?></td>
            <td class="active"><?php echo $actual_data2[31][2] ?></td>
        </tr>
        <tr>        
            <td class="active">8(U22-U24)</td>
            <td class="active"><?php echo $actual_data2[7][0] ?></td>
            <td class="active"><?php echo $actual_data2[7][1] ?></td>
            <td class="active"><?php echo $actual_data2[7][2] ?></td>
            <td>&nbsp;</td>
            <td class="active">29(U85-U87)</td>
            <td class="active"><?php echo $actual_data2[28][0] ?></td>
            <td class="active"><?php echo $actual_data2[28][1] ?></td>
            <td class="active"><?php echo $actual_data2[28][2] ?></td>
        </tr>
        <tr>        
            <td class="active">9(U25-U27)</td>
            <td class="active"><?php echo $actual_data2[8][0] ?></td>
            <td class="active"><?php echo $actual_data2[8][1] ?></td>
            <td class="active"><?php echo $actual_data2[8][2] ?></td>
            <td>&nbsp;</td>
            <td class="active">28(U82-U84)</td>
            <td class="active"><?php echo $actual_data2[27][0] ?></td>
            <td class="active"><?php echo $actual_data2[27][1] ?></td>
            <td class="active"><?php echo $actual_data2[27][2] ?></td>
        </tr>
        <tr>        
            <td class="active">10(U28-U30)</td>
            <td class="active"><?php echo $actual_data2[9][0] ?></td>
            <td class="active"><?php echo $actual_data2[9][1] ?></td>
            <td class="active"><?php echo $actual_data2[9][2] ?></td>
            <td>&nbsp;</td>
            <td class="active">27(U79-U81)</td>
            <td class="active"><?php echo $actual_data2[26][0] ?></td>
            <td class="active"><?php echo $actual_data2[26][1] ?></td>
            <td class="active"><?php echo $actual_data2[26][2] ?></td>
        </tr>
        <tr>        
            <td class="active">11(U31-U33)</td>
            <td class="active"><?php echo $actual_data2[10][0] ?></td>
            <td class="active"><?php echo $actual_data2[10][1] ?></td>
            <td class="active"><?php echo $actual_data2[10][2] ?></td>
            <td>&nbsp;</td>
            <td class="active">26(U76-U78)</td>
            <td class="active"><?php echo $actual_data2[25][0] ?></td>
            <td class="active"><?php echo $actual_data2[25][1] ?></td>
            <td class="active"><?php echo $actual_data2[25][2] ?></td>
        </tr>
        <tr>        
            <td class="active">12(U34-U36)</td>
            <td class="active"><?php echo $actual_data2[11][0] ?></td>
            <td class="active"><?php echo $actual_data2[11][1] ?></td>
            <td class="active"><?php echo $actual_data2[11][2] ?></td>
            <td>&nbsp;</td>
            <td class="active">25(U73-U75)</td>
            <td class="active"><?php echo $actual_data2[24][0] ?></td>
            <td class="active"><?php echo $actual_data2[24][1] ?></td>
            <td class="active"><?php echo $actual_data2[24][2] ?></td>
        </tr>
        <tr>        
            <td class="active">13(U37-U39)</td>
            <td class="active"><?php echo $actual_data2[12][0] ?></td>
            <td class="active"><?php echo $actual_data2[12][1] ?></td>
            <td class="active"><?php echo $actual_data2[12][2] ?></td>
            <td>&nbsp;</td>
            <td class="active">24(U70-U72)</td>
            <td class="active"><?php echo $actual_data2[23][0] ?></td>
            <td class="active"><?php echo $actual_data2[23][1] ?></td>
            <td class="active"><?php echo $actual_data2[23][2] ?></td>
        </tr>
        <tr>        
            <td class="active">14(U40-U42)</td>
            <td class="active"><?php echo $actual_data2[13][0] ?></td>
            <td class="active"><?php echo $actual_data2[13][1] ?></td>
            <td class="active"><?php echo $actual_data2[13][2] ?></td>
            <td>&nbsp;</td>
            <td class="active">23(U67-U69)</td>
            <td class="active"><?php echo $actual_data2[22][0] ?></td>
            <td class="active"><?php echo $actual_data2[22][1] ?></td>
            <td class="active"><?php echo $actual_data2[22][2] ?></td>
        </tr>
        <tr>        
            <td class="active">15(U43-U45)</td>
            <td class="active"><?php echo $actual_data2[14][0] ?></td>
            <td class="active"><?php echo $actual_data2[14][1] ?></td>
            <td class="active"><?php echo $actual_data2[14][2] ?></td>
            <td>&nbsp;</td>
            <td class="active">22(U64-U66)</td>
            <td class="active"><?php echo $actual_data2[21][0] ?></td>
            <td class="active"><?php echo $actual_data2[21][1] ?></td>
            <td class="active"><?php echo $actual_data2[21][2] ?></td>
        </tr>
        <tr>        
            <td class="active">16(U46-U48)</td>
            <td class="active"><?php echo $actual_data2[15][0] ?></td>
            <td class="active"><?php echo $actual_data2[15][1] ?></td>
            <td class="active"><?php echo $actual_data2[15][2] ?></td>
            <td>&nbsp;</td>
            <td class="active">21(U61-U63)</td>
            <td class="active"><?php echo $actual_data2[20][0] ?></td>
            <td class="active"><?php echo $actual_data2[20][1] ?></td>
            <td class="active"><?php echo $actual_data2[20][2] ?></td>
        </tr>
        <tr>        
            <td class="active">17(U49-U51)</td>
            <td class="active"><?php echo $actual_data2[16][0] ?></td>
            <td class="active"><?php echo $actual_data2[16][1] ?></td>
            <td class="active"><?php echo $actual_data2[16][2] ?></td>
            <td>&nbsp;</td>
            <td class="active">20(U58-U60)</td>
            <td class="active"><?php echo $actual_data2[19][0] ?></td>
            <td class="active"><?php echo $actual_data2[19][1] ?></td>
            <td class="active"><?php echo $actual_data2[19][2] ?></td>
        </tr>
        <tr>        
            <td class="active">18(U52-U54)</td>
            <td class="active"><?php echo $actual_data2[17][0] ?></td>
            <td class="active"><?php echo $actual_data2[17][1] ?></td>
            <td class="active"><?php echo $actual_data2[17][2] ?></td>
            <td>&nbsp;</td>
            <td class="active">19(U55-U57)</td>
            <td class="active"><?php echo $actual_data2[18][0] ?></td>
            <td class="active"><?php echo $actual_data2[18][1] ?></td>
            <td class="active"><?php echo $actual_data2[18][2] ?></td>
        </tr>
    </table>
    </div>
    <div id="chain3" class="chain-data invisible">
        <h4>板平均温度: <?php echo explode(' ', $last3)[20] ?>℃</h4>
        <h4>芯片平均温度： <?php echo explode (' ', $last3)[18] ?>℃</h4>
        <table class="table table-bordered table-hover table-responsive">
        <tr>
            <th class="active">芯片级数</th>
            <th class="active"> 电压|温度|yield值|硬件错误数</th>
            <th class="active"> 电压|温度|yield值|硬件错误数</th>
            <th class="active"> 电压|温度|yield值|硬件错误数</th>
            <th></th>
            <th class="active">芯片级数</th>
            <th class="active"> 电压|温度|yield值|硬件错误数</th>
            <th class="active"> 电压|温度|yield值|硬件错误数</th>
            <th class="active"> 电压|温度|yield值|硬件错误数</th>
        </tr>
        <tr>
            <td class="active">1(U1-U3)</td>
            <td class="active"><?php echo $actual_data3[0][0] ?></td>
            <td class="active"><?php echo $actual_data3[0][1] ?></td>
            <td class="active"><?php echo $actual_data3[0][2] ?></td>
            <td>&nbsp;</td>
            <td class="active">36(U106-U108)</td>
            <td class="active"><?php echo $actual_data3[35][0] ?></td>
            <td class="active"><?php echo $actual_data3[35][1] ?></td>
            <td class="active"><?php echo $actual_data3[35][2] ?></td>
        </tr>
        <tr>
            <td class="active">2(U4-U6)</td>
            <td class="active"><?php echo $actual_data3[1][0] ?></td>
            <td class="active"><?php echo $actual_data3[1][1] ?></td>
            <td class="active"><?php echo $actual_data3[1][2] ?></td>
            <td>&nbsp;</td>
            <td class="active">35(U103-U105)</td>
            <td class="active"><?php echo $actual_data3[34][0] ?></td>
            <td class="active"><?php echo $actual_data3[34][1] ?></td>
            <td class="active"><?php echo $actual_data3[34][2] ?></td>
        </tr>
        <tr>
            <td class="active">3(U7-U9)</td>
            <td class="active"><?php echo $actual_data3[2][0] ?></td>
            <td class="active"><?php echo $actual_data3[2][1] ?></td>
            <td class="active"><?php echo $actual_data3[2][2] ?></td>
            <td>&nbsp;</td>
            <td class="active">34(U100-U102)</td>
            <td class="active"><?php echo $actual_data3[33][0] ?></td>
            <td class="active"><?php echo $actual_data3[33][1] ?></td>
            <td class="active"><?php echo $actual_data3[33][2] ?></td>
        </tr>
        <tr>        
            <td class="active">4(U10-U12)</td>
            <td class="active"><?php echo $actual_data3[3][0] ?></td>
            <td class="active"><?php echo $actual_data3[3][1] ?></td>
            <td class="active"><?php echo $actual_data3[3][2] ?></td>
            <td>&nbsp;</td>
            <td class="active">33(U97-U99)</td>
            <td class="active"><?php echo $actual_data3[32][0] ?></td>
            <td class="active"><?php echo $actual_data3[32][1] ?></td>
            <td class="active"><?php echo $actual_data3[32][2] ?></td>
        </tr>
        <tr>        
            <td class="active">5(U13-U15)</td>
            <td class="active"><?php echo $actual_data3[4][0] ?></td>
            <td class="active"><?php echo $actual_data3[4][1] ?></td>
            <td class="active"><?php echo $actual_data3[4][2] ?></td>
            <td>&nbsp;</td>
            <td class="active">32(U94-U96)</td>
            <td class="active"><?php echo $actual_data3[31][0] ?></td>
            <td class="active"><?php echo $actual_data3[31][1] ?></td>
            <td class="active"><?php echo $actual_data3[31][2] ?></td>
        </tr>
        <tr>        
            <td class="active">6(U16-U18)</td>
            <td class="active"><?php echo $actual_data3[5][0] ?></td>
            <td class="active"><?php echo $actual_data3[5][1] ?></td>
            <td class="active"><?php echo $actual_data3[5][2] ?></td>
            <td>&nbsp;</td>
            <td class="active">31(U91-U93)</td>
            <td class="active"><?php echo $actual_data3[30][0] ?></td>
            <td class="active"><?php echo $actual_data3[30][1] ?></td>
            <td class="active"><?php echo $actual_data3[30][2] ?></td>
        </tr>
        <tr>        
            <td class="active">7(U19-U21)</td>
            <td class="active"><?php echo $actual_data3[6][0] ?></td>
            <td class="active"><?php echo $actual_data3[6][1] ?></td>
            <td class="active"><?php echo $actual_data3[6][2] ?></td>
            <td>&nbsp;</td>
            <td class="active">30(U88-U90)</td>
            <td class="active"><?php echo $actual_data3[31][0] ?></td>
            <td class="active"><?php echo $actual_data3[31][1] ?></td>
            <td class="active"><?php echo $actual_data3[31][2] ?></td>
        </tr>
        <tr>        
            <td class="active">8(U22-U24)</td>
            <td class="active"><?php echo $actual_data3[7][0] ?></td>
            <td class="active"><?php echo $actual_data3[7][1] ?></td>
            <td class="active"><?php echo $actual_data3[7][2] ?></td>
            <td>&nbsp;</td>
            <td class="active">29(U85-U87)</td>
            <td class="active"><?php echo $actual_data3[28][0] ?></td>
            <td class="active"><?php echo $actual_data3[28][1] ?></td>
            <td class="active"><?php echo $actual_data3[28][2] ?></td>
        </tr>
        <tr>        
            <td class="active">9(U25-U27)</td>
            <td class="active"><?php echo $actual_data3[8][0] ?></td>
            <td class="active"><?php echo $actual_data3[8][1] ?></td>
            <td class="active"><?php echo $actual_data3[8][2] ?></td>
            <td>&nbsp;</td>
            <td class="active">28(U82-U84)</td>
            <td class="active"><?php echo $actual_data3[27][0] ?></td>
            <td class="active"><?php echo $actual_data3[27][1] ?></td>
            <td class="active"><?php echo $actual_data3[27][2] ?></td>
        </tr>
        <tr>        
            <td class="active">10(U28-U30)</td>
            <td class="active"><?php echo $actual_data3[9][0] ?></td>
            <td class="active"><?php echo $actual_data3[9][1] ?></td>
            <td class="active"><?php echo $actual_data3[9][2] ?></td>
            <td>&nbsp;</td>
            <td class="active">27(U79-U81)</td>
            <td class="active"><?php echo $actual_data3[26][0] ?></td>
            <td class="active"><?php echo $actual_data3[26][1] ?></td>
            <td class="active"><?php echo $actual_data3[26][2] ?></td>
        </tr>
        <tr>        
            <td class="active">11(U31-U33)</td>
            <td class="active"><?php echo $actual_data3[10][0] ?></td>
            <td class="active"><?php echo $actual_data3[10][1] ?></td>
            <td class="active"><?php echo $actual_data3[10][2] ?></td>
            <td>&nbsp;</td>
            <td class="active">26(U76-U78)</td>
            <td class="active"><?php echo $actual_data3[25][0] ?></td>
            <td class="active"><?php echo $actual_data3[25][1] ?></td>
            <td class="active"><?php echo $actual_data3[25][2] ?></td>
        </tr>
        <tr>        
            <td class="active">12(U34-U36)</td>
            <td class="active"><?php echo $actual_data3[11][0] ?></td>
            <td class="active"><?php echo $actual_data3[11][1] ?></td>
            <td class="active"><?php echo $actual_data3[11][2] ?></td>
            <td>&nbsp;</td>
            <td class="active">25(U73-U75)</td>
            <td class="active"><?php echo $actual_data3[24][0] ?></td>
            <td class="active"><?php echo $actual_data3[24][1] ?></td>
            <td class="active"><?php echo $actual_data3[24][2] ?></td>
        </tr>
        <tr>        
            <td class="active">13(U37-U39)</td>
            <td class="active"><?php echo $actual_data3[12][0] ?></td>
            <td class="active"><?php echo $actual_data3[12][1] ?></td>
            <td class="active"><?php echo $actual_data3[12][2] ?></td>
            <td>&nbsp;</td>
            <td class="active">24(U70-U72)</td>
            <td class="active"><?php echo $actual_data3[23][0] ?></td>
            <td class="active"><?php echo $actual_data3[23][1] ?></td>
            <td class="active"><?php echo $actual_data3[23][2] ?></td>
        </tr>
        <tr>        
            <td class="active">14(U40-U42)</td>
            <td class="active"><?php echo $actual_data3[13][0] ?></td>
            <td class="active"><?php echo $actual_data3[13][1] ?></td>
            <td class="active"><?php echo $actual_data3[13][2] ?></td>
            <td>&nbsp;</td>
            <td class="active">23(U67-U69)</td>
            <td class="active"><?php echo $actual_data3[22][0] ?></td>
            <td class="active"><?php echo $actual_data3[22][1] ?></td>
            <td class="active"><?php echo $actual_data3[22][2] ?></td>
        </tr>
        <tr>        
            <td class="active">15(U43-U45)</td>
            <td class="active"><?php echo $actual_data3[14][0] ?></td>
            <td class="active"><?php echo $actual_data3[14][1] ?></td>
            <td class="active"><?php echo $actual_data3[14][2] ?></td>
            <td>&nbsp;</td>
            <td class="active">22(U64-U66)</td>
            <td class="active"><?php echo $actual_data3[21][0] ?></td>
            <td class="active"><?php echo $actual_data3[21][1] ?></td>
            <td class="active"><?php echo $actual_data3[21][2] ?></td>
        </tr>
        <tr>        
            <td class="active">16(U46-U48)</td>
            <td class="active"><?php echo $actual_data3[15][0] ?></td>
            <td class="active"><?php echo $actual_data3[15][1] ?></td>
            <td class="active"><?php echo $actual_data3[15][2] ?></td>
            <td>&nbsp;</td>
            <td class="active">21(U61-U63)</td>
            <td class="active"><?php echo $actual_data3[20][0] ?></td>
            <td class="active"><?php echo $actual_data3[20][1] ?></td>
            <td class="active"><?php echo $actual_data3[20][2] ?></td>
        </tr>
        <tr>        
            <td class="active">17(U49-U51)</td>
            <td class="active"><?php echo $actual_data3[16][0] ?></td>
            <td class="active"><?php echo $actual_data3[16][1] ?></td>
            <td class="active"><?php echo $actual_data3[16][2] ?></td>
            <td>&nbsp;</td>
            <td class="active">20(U58-U60)</td>
            <td class="active"><?php echo $actual_data3[19][0] ?></td>
            <td class="active"><?php echo $actual_data3[19][1] ?></td>
            <td class="active"><?php echo $actual_data3[19][2] ?></td>
        </tr>
        <tr>        
            <td class="active">18(U52-U54)</td>
            <td class="active"><?php echo $actual_data3[17][0] ?></td>
            <td class="active"><?php echo $actual_data3[17][1] ?></td>
            <td class="active"><?php echo $actual_data3[17][2] ?></td>
            <td>&nbsp;</td>
            <td class="active">19(U55-U57)</td>
            <td class="active"><?php echo $actual_data3[18][0] ?></td>
            <td class="active"><?php echo $actual_data3[18][1] ?></td>
            <td class="active"><?php echo $actual_data3[18][2] ?></td>
        </tr>
    </table>
    </div>
    <div id="chain4" class="chain-data invisible">
        <h4>板平均温度: <?php echo explode(' ', $last4)[20] ?>℃</h4>
        <h4>芯片平均温度： <?php echo explode (' ', $last4)[18] ?>℃</h4>
        <table class="table table-bordered table-hover table-responsive">
        <tr>
            <th class="active">芯片级数</th>
            <th class="active"> 电压|温度|yield值|硬件错误数</th>
            <th class="active"> 电压|温度|yield值|硬件错误数</th>
            <th class="active"> 电压|温度|yield值|硬件错误数</th>
            <th></th>
            <th class="active">芯片级数</th>
            <th class="active"> 电压|温度|yield值|硬件错误数</th>
            <th class="active"> 电压|温度|yield值|硬件错误数</th>
            <th class="active"> 电压|温度|yield值|硬件错误数</th>
        </tr>
        <tr>
            <td class="active">1(U1-U3)</td>
            <td class="active"><?php echo $actual_data4[0][0] ?></td>
            <td class="active"><?php echo $actual_data4[0][1] ?></td>
            <td class="active"><?php echo $actual_data4[0][2] ?></td>
            <td>&nbsp;</td>
            <td class="active">36(U106-U108)</td>
            <td class="active"><?php echo $actual_data4[35][0] ?></td>
            <td class="active"><?php echo $actual_data4[35][1] ?></td>
            <td class="active"><?php echo $actual_data4[35][2] ?></td>
        </tr>
        <tr>
            <td class="active">2(U4-U6)</td>
            <td class="active"><?php echo $actual_data4[1][0] ?></td>
            <td class="active"><?php echo $actual_data4[1][1] ?></td>
            <td class="active"><?php echo $actual_data4[1][2] ?></td>
            <td>&nbsp;</td>
            <td class="active">35(U103-U105)</td>
            <td class="active"><?php echo $actual_data4[34][0] ?></td>
            <td class="active"><?php echo $actual_data4[34][1] ?></td>
            <td class="active"><?php echo $actual_data4[34][2] ?></td>
        </tr>
        <tr>
            <td class="active">3(U7-U9)</td>
            <td class="active"><?php echo $actual_data4[2][0] ?></td>
            <td class="active"><?php echo $actual_data4[2][1] ?></td>
            <td class="active"><?php echo $actual_data4[2][2] ?></td>
            <td>&nbsp;</td>
            <td class="active">34(U100-U102)</td>
            <td class="active"><?php echo $actual_data4[33][0] ?></td>
            <td class="active"><?php echo $actual_data4[33][1] ?></td>
            <td class="active"><?php echo $actual_data4[33][2] ?></td>
        </tr>
        <tr>        
            <td class="active">4(U10-U12)</td>
            <td class="active"><?php echo $actual_data4[3][0] ?></td>
            <td class="active"><?php echo $actual_data4[3][1] ?></td>
            <td class="active"><?php echo $actual_data4[3][2] ?></td>
            <td>&nbsp;</td>
            <td class="active">33(U97-U99)</td>
            <td class="active"><?php echo $actual_data4[32][0] ?></td>
            <td class="active"><?php echo $actual_data4[32][1] ?></td>
            <td class="active"><?php echo $actual_data4[32][2] ?></td>
        </tr>
        <tr>        
            <td class="active">5(U13-U15)</td>
            <td class="active"><?php echo $actual_data4[4][0] ?></td>
            <td class="active"><?php echo $actual_data4[4][1] ?></td>
            <td class="active"><?php echo $actual_data4[4][2] ?></td>
            <td>&nbsp;</td>
            <td class="active">32(U94-U96)</td>
            <td class="active"><?php echo $actual_data4[31][0] ?></td>
            <td class="active"><?php echo $actual_data4[31][1] ?></td>
            <td class="active"><?php echo $actual_data4[31][2] ?></td>
        </tr>
        <tr>        
            <td class="active">6(U16-U18)</td>
            <td class="active"><?php echo $actual_data4[5][0] ?></td>
            <td class="active"><?php echo $actual_data4[5][1] ?></td>
            <td class="active"><?php echo $actual_data4[5][2] ?></td>
            <td>&nbsp;</td>
            <td class="active">31(U91-U93)</td>
            <td class="active"><?php echo $actual_data4[30][0] ?></td>
            <td class="active"><?php echo $actual_data4[30][1] ?></td>
            <td class="active"><?php echo $actual_data4[30][2] ?></td>
        </tr>
        <tr>        
            <td class="active">7(U19-U21)</td>
            <td class="active"><?php echo $actual_data4[6][0] ?></td>
            <td class="active"><?php echo $actual_data4[6][1] ?></td>
            <td class="active"><?php echo $actual_data4[6][2] ?></td>
            <td>&nbsp;</td>
            <td class="active">30(U88-U90)</td>
            <td class="active"><?php echo $actual_data4[31][0] ?></td>
            <td class="active"><?php echo $actual_data4[31][1] ?></td>
            <td class="active"><?php echo $actual_data4[31][2] ?></td>
        </tr>
        <tr>        
            <td class="active">8(U22-U24)</td>
            <td class="active"><?php echo $actual_data4[7][0] ?></td>
            <td class="active"><?php echo $actual_data4[7][1] ?></td>
            <td class="active"><?php echo $actual_data4[7][2] ?></td>
            <td>&nbsp;</td>
            <td class="active">29(U85-U87)</td>
            <td class="active"><?php echo $actual_data4[28][0] ?></td>
            <td class="active"><?php echo $actual_data4[28][1] ?></td>
            <td class="active"><?php echo $actual_data4[28][2] ?></td>
        </tr>
        <tr>        
            <td class="active">9(U25-U27)</td>
            <td class="active"><?php echo $actual_data4[8][0] ?></td>
            <td class="active"><?php echo $actual_data4[8][1] ?></td>
            <td class="active"><?php echo $actual_data4[8][2] ?></td>
            <td>&nbsp;</td>
            <td class="active">28(U82-U84)</td>
            <td class="active"><?php echo $actual_data4[27][0] ?></td>
            <td class="active"><?php echo $actual_data4[27][1] ?></td>
            <td class="active"><?php echo $actual_data4[27][2] ?></td>
        </tr>
        <tr>        
            <td class="active">10(U28-U30)</td>
            <td class="active"><?php echo $actual_data4[9][0] ?></td>
            <td class="active"><?php echo $actual_data4[9][1] ?></td>
            <td class="active"><?php echo $actual_data4[9][2] ?></td>
            <td>&nbsp;</td>
            <td class="active">27(U79-U81)</td>
            <td class="active"><?php echo $actual_data4[26][0] ?></td>
            <td class="active"><?php echo $actual_data4[26][1] ?></td>
            <td class="active"><?php echo $actual_data4[26][2] ?></td>
        </tr>
        <tr>        
            <td class="active">11(U31-U33)</td>
            <td class="active"><?php echo $actual_data4[10][0] ?></td>
            <td class="active"><?php echo $actual_data4[10][1] ?></td>
            <td class="active"><?php echo $actual_data4[10][2] ?></td>
            <td>&nbsp;</td>
            <td class="active">26(U76-U78)</td>
            <td class="active"><?php echo $actual_data4[25][0] ?></td>
            <td class="active"><?php echo $actual_data4[25][1] ?></td>
            <td class="active"><?php echo $actual_data4[25][2] ?></td>
        </tr>
        <tr>        
            <td class="active">12(U34-U36)</td>
            <td class="active"><?php echo $actual_data4[11][0] ?></td>
            <td class="active"><?php echo $actual_data4[11][1] ?></td>
            <td class="active"><?php echo $actual_data4[11][2] ?></td>
            <td>&nbsp;</td>
            <td class="active">25(U73-U75)</td>
            <td class="active"><?php echo $actual_data4[24][0] ?></td>
            <td class="active"><?php echo $actual_data4[24][1] ?></td>
            <td class="active"><?php echo $actual_data4[24][2] ?></td>
        </tr>
        <tr>        
            <td class="active">13(U37-U39)</td>
            <td class="active"><?php echo $actual_data4[12][0] ?></td>
            <td class="active"><?php echo $actual_data4[12][1] ?></td>
            <td class="active"><?php echo $actual_data4[12][2] ?></td>
            <td>&nbsp;</td>
            <td class="active">24(U70-U72)</td>
            <td class="active"><?php echo $actual_data4[23][0] ?></td>
            <td class="active"><?php echo $actual_data4[23][1] ?></td>
            <td class="active"><?php echo $actual_data4[23][2] ?></td>
        </tr>
        <tr>        
            <td class="active">14(U40-U42)</td>
            <td class="active"><?php echo $actual_data4[13][0] ?></td>
            <td class="active"><?php echo $actual_data4[13][1] ?></td>
            <td class="active"><?php echo $actual_data4[13][2] ?></td>
            <td>&nbsp;</td>
            <td class="active">23(U67-U69)</td>
            <td class="active"><?php echo $actual_data4[22][0] ?></td>
            <td class="active"><?php echo $actual_data4[22][1] ?></td>
            <td class="active"><?php echo $actual_data4[22][2] ?></td>
        </tr>
        <tr>        
            <td class="active">15(U43-U45)</td>
            <td class="active"><?php echo $actual_data4[14][0] ?></td>
            <td class="active"><?php echo $actual_data4[14][1] ?></td>
            <td class="active"><?php echo $actual_data4[14][2] ?></td>
            <td>&nbsp;</td>
            <td class="active">22(U64-U66)</td>
            <td class="active"><?php echo $actual_data4[21][0] ?></td>
            <td class="active"><?php echo $actual_data4[21][1] ?></td>
            <td class="active"><?php echo $actual_data4[21][2] ?></td>
        </tr>
        <tr>        
            <td class="active">16(U46-U48)</td>
            <td class="active"><?php echo $actual_data4[15][0] ?></td>
            <td class="active"><?php echo $actual_data4[15][1] ?></td>
            <td class="active"><?php echo $actual_data4[15][2] ?></td>
            <td>&nbsp;</td>
            <td class="active">21(U61-U63)</td>
            <td class="active"><?php echo $actual_data4[20][0] ?></td>
            <td class="active"><?php echo $actual_data4[20][1] ?></td>
            <td class="active"><?php echo $actual_data4[20][2] ?></td>
        </tr>
        <tr>        
            <td class="active">17(U49-U51)</td>
            <td class="active"><?php echo $actual_data4[16][0] ?></td>
            <td class="active"><?php echo $actual_data4[16][1] ?></td>
            <td class="active"><?php echo $actual_data4[16][2] ?></td>
            <td>&nbsp;</td>
            <td class="active">20(U58-U60)</td>
            <td class="active"><?php echo $actual_data4[19][0] ?></td>
            <td class="active"><?php echo $actual_data4[19][1] ?></td>
            <td class="active"><?php echo $actual_data4[19][2] ?></td>
        </tr>
        <tr>        
            <td class="active">18(U52-U54)</td>
            <td class="active"><?php echo $actual_data4[17][0] ?></td>
            <td class="active"><?php echo $actual_data4[17][1] ?></td>
            <td class="active"><?php echo $actual_data4[17][2] ?></td>
            <td>&nbsp;</td>
            <td class="active">19(U55-U57)</td>
            <td class="active"><?php echo $actual_data4[18][0] ?></td>
            <td class="active"><?php echo $actual_data4[18][1] ?></td>
            <td class="active"><?php echo $actual_data4[18][2] ?></td>
        </tr>
    </table>
    </div>
    <?php else: ?>
        <div id="chain1" class="chain-data visible">
        <h4>板平均温度: <?php echo explode(' ', $last1)[20] ?>℃</h4>
        <h4>芯片平均温度：<?php echo explode (' ', $last1)[18] ?>℃</h4>
        <table class="table table-bordered table-hover table-responsive">
             <tr>
                 <th class="active">芯片级数</th>
                 <th class="active"> 电压|温度|yield值|硬件错误数</th>
                 <th class="active"> 电压|温度|yield值|硬件错误数</th>
                 <th class="active"> 电压|温度|yield值|硬件错误数</th>
                 <th></th>
                 <th class="active">芯片级数</th>
                 <th class="active"> 电压|温度|yield值|硬件错误数</th>
                 <th class="active"> 电压|温度|yield值|硬件错误数</th>
                 <th class="active"> 电压|温度|yield值|硬件错误数</th>
            </tr>
 
             <tr>
                 <td class="active">1(U1-U3)</td>
                 <td class="active"><?php echo $actual_data1[0][2] ?></td>
                 <td class="active"><?php echo $actual_data1[0][1] ?></td>
                 <td class="active"><?php echo $actual_data1[0][0] ?></td>
                 <td>&nbsp;</td>
                 <td class="active">40(U118-U120)</td>
                 <td class="active"><?php echo $actual_data1[39][2] ?></td>
                 <td class="active"><?php echo $actual_data1[39][1] ?></td>
                 <td class="active"><?php echo $actual_data1[39][0] ?></td>
             </tr>
             <tr>
                 <td class="active">2(U4-U6)</td>
                 <td class="active"><?php echo $actual_data1[1][0] ?></td>
                 <td class="active"><?php echo $actual_data1[1][1] ?></td>
                 <td class="active"><?php echo $actual_data1[1][2] ?></td>
                 <td>&nbsp;</td>
                 <td class="active">39(U115-U117)</td>
                 <td class="active"><?php echo $actual_data1[38][0] ?></td>
                 <td class="active"><?php echo $actual_data1[38][1] ?></td>
                 <td class="active"><?php echo $actual_data1[38][2] ?></td>
             </tr>
             <tr>
                 <td class="active">3(U7-U9)</td>
                 <td class="active"><?php echo $actual_data1[2][2] ?></td>
                 <td class="active"><?php echo $actual_data1[2][1] ?></td>
                 <td class="active"><?php echo $actual_data1[2][0] ?></td>
                 <td>&nbsp;</td>
                 <td class="active">38(U112-U114)</td>
                 <td class="active"><?php echo $actual_data1[37][2] ?></td>
                 <td class="active"><?php echo $actual_data1[37][1] ?></td>
                 <td class="active"><?php echo $actual_data1[37][0] ?></td>
             </tr>
             <tr>        
                 <td class="active">4(U10-U12)</td>
                 <td class="active"><?php echo $actual_data1[3][0] ?></td>
                 <td class="active"><?php echo $actual_data1[3][1] ?></td>
                 <td class="active"><?php echo $actual_data1[3][2] ?></td>
                 <td>&nbsp;</td>
                 <td class="active">37(U109-U111)</td>
                 <td class="active"><?php echo $actual_data1[36][0] ?></td>
                 <td class="active"><?php echo $actual_data1[36][1] ?></td>
                 <td class="active"><?php echo $actual_data1[36][2] ?></td>
             </tr>
             <tr>        
                 <td class="active">5(U13-U15)</td>
                 <td class="active"><?php echo $actual_data1[4][2] ?></td>
                 <td class="active"><?php echo $actual_data1[4][1] ?></td>
                 <td class="active"><?php echo $actual_data1[4][0] ?></td>
                 <td>&nbsp;</td>
                 <td class="active">36(U106-U108)</td>
                 <td class="active"><?php echo $actual_data1[35][2] ?></td>
                 <td class="active"><?php echo $actual_data1[35][1] ?></td>
                 <td class="active"><?php echo $actual_data1[35][0] ?></td>
             </tr>
             <tr>        
                 <td class="active">6(U16-U18)</td>
                 <td class="active"><?php echo $actual_data1[5][0] ?></td>
                 <td class="active"><?php echo $actual_data1[5][1] ?></td>
                 <td class="active"><?php echo $actual_data1[5][2] ?></td>
                 <td>&nbsp;</td>
                 <td class="active">35(103-U105)</td>
                 <td class="active"><?php echo $actual_data1[34][0] ?></td>
                 <td class="active"><?php echo $actual_data1[34][1] ?></td>
                 <td class="active"><?php echo $actual_data1[34][2] ?></td>
             </tr>
             <tr>        
                 <td class="active">7(U19-U21)</td>
                 <td class="active"><?php echo $actual_data1[6][2] ?></td>
                 <td class="active"><?php echo $actual_data1[6][1] ?></td>
                 <td class="active"><?php echo $actual_data1[6][0] ?></td>
                 <td>&nbsp;</td>
                 <td class="active">34(U100-U102)</td>
                 <td class="active"><?php echo $actual_data1[33][2] ?></td>
                 <td class="active"><?php echo $actual_data1[33][1] ?></td>
                 <td class="active"><?php echo $actual_data1[33][0] ?></td>
             </tr>
             <tr>        
                 <td class="active">8(U22-U24)</td>
                 <td class="active"><?php echo $actual_data1[7][0] ?></td>
                 <td class="active"><?php echo $actual_data1[7][1] ?></td>
                 <td class="active"><?php echo $actual_data1[7][2] ?></td>
                 <td>&nbsp;</td>
                 <td class="active">33(U97-U99)</td>
                 <td class="active"><?php echo $actual_data1[32][0] ?></td>
                 <td class="active"><?php echo $actual_data1[32][1] ?></td>
                 <td class="active"><?php echo $actual_data1[32][2] ?></td>
             </tr>
             <tr>        
                 <td class="active">9(U25-U27)</td>
                 <td class="active"><?php echo $actual_data1[8][2] ?></td>
                 <td class="active"><?php echo $actual_data1[8][1] ?></td>
                 <td class="active"><?php echo $actual_data1[8][0] ?></td>
                 <td>&nbsp;</td>
                 <td class="active">32(U94-U96)</td>
                 <td class="active"><?php echo $actual_data1[31][2] ?></td>
                 <td class="active"><?php echo $actual_data1[31][1] ?></td>
                 <td class="active"><?php echo $actual_data1[31][0] ?></td>
             </tr>
             <tr>        
                 <td class="active">10(U28-U30)</td>
                 <td class="active"><?php echo $actual_data1[9][0] ?></td>
                 <td class="active"><?php echo $actual_data1[9][1] ?></td>
                 <td class="active"><?php echo $actual_data1[9][2] ?></td>
                 <td>&nbsp;</td>
                 <td class="active">31(U91-U93)</td>
                 <td class="active"><?php echo $actual_data1[30][0] ?></td>
                 <td class="active"><?php echo $actual_data1[30][1] ?></td>
                 <td class="active"><?php echo $actual_data1[30][2] ?></td>
             </tr>
             <tr>        
                 <td class="active">11(U31-U33)</td>
                 <td class="active"><?php echo $actual_data1[10][2] ?></td>
                 <td class="active"><?php echo $actual_data1[10][1] ?></td>
                 <td class="active"><?php echo $actual_data1[10][0] ?></td>
                 <td>&nbsp;</td>
                 <td class="active">30(U88-U90)</td>
                 <td class="active"><?php echo $actual_data1[29][2] ?></td>
                 <td class="active"><?php echo $actual_data1[29][1] ?></td>
                 <td class="active"><?php echo $actual_data1[29][0] ?></td>
             </tr>
             <tr>        
                 <td class="active">12(U34-U36)</td>
                 <td class="active"><?php echo $actual_data1[11][0] ?></td>
                 <td class="active"><?php echo $actual_data1[11][1] ?></td>
                 <td class="active"><?php echo $actual_data1[11][2] ?></td>
                 <td>&nbsp;</td>
                 <td class="active">29(U85-U87)</td>
                 <td class="active"><?php echo $actual_data1[28][0] ?></td>
                 <td class="active"><?php echo $actual_data1[28][1] ?></td>
                 <td class="active"><?php echo $actual_data1[28][2] ?></td>
             </tr>
             <tr>        
                 <td class="active">13(U37-U39)</td>
                 <td class="active"><?php echo $actual_data1[12][2] ?></td>
                 <td class="active"><?php echo $actual_data1[12][1] ?></td>
                 <td class="active"><?php echo $actual_data1[12][0] ?></td>
                 <td>&nbsp;</td>
                 <td class="active">28(U82-U84)</td>
                 <td class="active"><?php echo $actual_data1[27][2] ?></td>
                 <td class="active"><?php echo $actual_data1[27][1] ?></td>
                 <td class="active"><?php echo $actual_data1[27][0] ?></td>
             </tr>
             <tr>        
                 <td class="active">14(U40-U42)</td>
                 <td class="active"><?php echo $actual_data1[22][0] ?></td>
                 <td class="active"><?php echo $actual_data1[22][1] ?></td>
                 <td class="active"><?php echo $actual_data1[22][2] ?></td>
                 <td>&nbsp;</td>
                 <td class="active">27(U79-U81)</td>
                 <td class="active"><?php echo $actual_data1[26][0] ?></td>
                 <td class="active"><?php echo $actual_data1[26][1] ?></td>
                 <td class="active"><?php echo $actual_data1[26][2] ?></td>
             </tr>
             <tr>        
                 <td class="active">15(U43-U45)</td>
                 <td class="active"><?php echo $actual_data1[14][2] ?></td>
                 <td class="active"><?php echo $actual_data1[14][1] ?></td>
                 <td class="active"><?php echo $actual_data1[14][0] ?></td>
                 <td>&nbsp;</td>
                 <td class="active">26(U76-U78)</td>
                 <td class="active"><?php echo $actual_data1[25][2] ?></td>
                 <td class="active"><?php echo $actual_data1[25][1] ?></td>
                 <td class="active"><?php echo $actual_data1[25][0] ?></td>
             </tr>
             <tr>        
                 <td class="active">16(U46-U48)</td>
                 <td class="active"><?php echo $actual_data1[15][0] ?></td>
                 <td class="active"><?php echo $actual_data1[15][1] ?></td>
                 <td class="active"><?php echo $actual_data1[15][2] ?></td>
                 <td>&nbsp;</td>
                 <td class="active">25(U73-U75)</td>
                 <td class="active"><?php echo $actual_data1[24][0] ?></td>
                 <td class="active"><?php echo $actual_data1[24][1] ?></td>
                 <td class="active"><?php echo $actual_data1[24][2] ?></td>
             </tr>
             <tr>        
                 <td class="active">17(U49-U51)</td>
                 <td class="active"><?php echo $actual_data1[16][2] ?></td>
                 <td class="active"><?php echo $actual_data1[16][1] ?></td>
                 <td class="active"><?php echo $actual_data1[16][0] ?></td>
                 <td>&nbsp;</td>
                 <td class="active">24(U70-U72)</td>
                 <td class="active"><?php echo $actual_data1[23][2] ?></td>
                 <td class="active"><?php echo $actual_data1[23][1] ?></td>
                 <td class="active"><?php echo $actual_data1[23][0] ?></td>
             </tr>
             <tr>        
                 <td class="active">18(U52-U54)</td>
                 <td class="active"><?php echo $actual_data1[17][0] ?></td>
                 <td class="active"><?php echo $actual_data1[17][1] ?></td>
                 <td class="active"><?php echo $actual_data1[17][2] ?></td>
                 <td>&nbsp;</td>
                 <td class="active">23(U67-U69)</td>
                 <td class="active"><?php echo $actual_data1[22][0] ?></td>
                 <td class="active"><?php echo $actual_data1[22][1] ?></td>
                 <td class="active"><?php echo $actual_data1[22][2] ?></td>
             </tr>
             <tr>        
                 <td class="active">19(U55-U557)</td>
                 <td class="active"><?php echo $actual_data1[18][0] ?></td>
                 <td class="active"><?php echo $actual_data1[18][1] ?></td>
                 <td class="active"><?php echo $actual_data1[18][2] ?></td>
                 <td>&nbsp;</td>
                 <td class="active">22(U64-U66)</td>
                 <td class="active"><?php echo $actual_data1[21][0] ?></td>
                 <td class="active"><?php echo $actual_data1[21][1] ?></td>
                 <td class="active"><?php echo $actual_data1[21][2] ?></td>
             </tr>
             <tr>        
                 <td class="active">20(U58-U60)</td>
                 <td class="active"><?php echo $actual_data1[19][0] ?></td>
                 <td class="active"><?php echo $actual_data1[19][1] ?></td>
                 <td class="active"><?php echo $actual_data1[19][2] ?></td>
                 <td>&nbsp;</td>
                 <td class="active">21(U61-U62)</td>
                 <td class="active"><?php echo $actual_data1[20][0] ?></td>
                 <td class="active"><?php echo $actual_data1[20][1] ?></td>
                 <td class="active"><?php echo $actual_data1[20][2] ?></td>
             </tr>
     </table>
     </div>
     <div id="chain2" class="chain-data invisible">
        <h4>板平均温度: <?php echo explode(' ', $last2)[20] ?>℃</h4>
        <h4>芯片平均温度： <?php echo explode (' ', $last2)[18] ?>℃</h4>
        <table class="table table-bordered table-hover table-responsive">
             <tr>
             <th class="active">芯片级数</th>
                 <th class="active"> 电压|温度|yield值|硬件错误数</th>
                 <th class="active"> 电压|温度|yield值|硬件错误数</th>
                 <th class="active"> 电压|温度|yield值|硬件错误数</th>
                 <th></th>
                 <th class="active">芯片级数</th>
                 <th class="active"> 电压|温度|yield值|硬件错误数</th>
                 <th class="active"> 电压|温度|yield值|硬件错误数</th>
                 <th class="active"> 电压|温度|yield值|硬件错误数</th>
             </tr>
             <tr>
                 <td class="active">1(U1-U3)</td>
                 <td class="active"><?php echo $actual_data2[0][2] ?></td>
                 <td class="active"><?php echo $actual_data2[0][1] ?></td>
                 <td class="active"><?php echo $actual_data2[0][0] ?></td>
                 <td>&nbsp;</td>
                 <td class="active">40(U118-U120)</td>
                 <td class="active"><?php echo $actual_data2[39][2] ?></td>
                 <td class="active"><?php echo $actual_data2[39][1] ?></td>
                 <td class="active"><?php echo $actual_data2[39][0] ?></td>
             </tr>
             <tr>
                 <td class="active">2(U4-U6)</td>
                 <td class="active"><?php echo $actual_data2[1][0] ?></td>
                 <td class="active"><?php echo $actual_data2[1][1] ?></td>
                 <td class="active"><?php echo $actual_data2[1][2] ?></td>
                 <td>&nbsp;</td>
                 <td class="active">39(U115-U117)</td>
                 <td class="active"><?php echo $actual_data2[38][0] ?></td>
                 <td class="active"><?php echo $actual_data2[38][1] ?></td>
                 <td class="active"><?php echo $actual_data2[38][2] ?></td>
             </tr>
             <tr>
                 <td class="active">3(U7-U9)</td>
                 <td class="active"><?php echo $actual_data2[2][2] ?></td>
                 <td class="active"><?php echo $actual_data2[2][1] ?></td>
                 <td class="active"><?php echo $actual_data2[2][0] ?></td>
                 <td>&nbsp;</td>
                 <td class="active">38(U112-U114)</td>
                 <td class="active"><?php echo $actual_data2[37][2] ?></td>
                 <td class="active"><?php echo $actual_data2[37][1] ?></td>
                 <td class="active"><?php echo $actual_data2[37][0] ?></td>
             </tr>
             <tr>        
                 <td class="active">4(U10-U12)</td>
                 <td class="active"><?php echo $actual_data2[3][0] ?></td>
                 <td class="active"><?php echo $actual_data2[3][1] ?></td>
                 <td class="active"><?php echo $actual_data2[3][2] ?></td>
                 <td>&nbsp;</td>
                 <td class="active">37(U109-U111)</td>
                 <td class="active"><?php echo $actual_data2[36][0] ?></td>
                 <td class="active"><?php echo $actual_data2[36][1] ?></td>
                 <td class="active"><?php echo $actual_data2[36][2] ?></td>
             </tr>
             <tr>        
                 <td class="active">5(U13-U15)</td>
                 <td class="active"><?php echo $actual_data2[4][2] ?></td>
                 <td class="active"><?php echo $actual_data2[4][1] ?></td>
                 <td class="active"><?php echo $actual_data2[4][0] ?></td>
                 <td>&nbsp;</td>
                 <td class="active">36(U106-U108)</td>
                 <td class="active"><?php echo $actual_data2[35][2] ?></td>
                 <td class="active"><?php echo $actual_data2[35][1] ?></td>
                 <td class="active"><?php echo $actual_data2[35][0] ?></td>
             </tr>
             <tr>        
                 <td class="active">6(U16-U18)</td>
                 <td class="active"><?php echo $actual_data2[5][0] ?></td>
                 <td class="active"><?php echo $actual_data2[5][1] ?></td>
                 <td class="active"><?php echo $actual_data2[5][2] ?></td>
                 <td>&nbsp;</td>
                 <td class="active">35(103-U105)</td>
                 <td class="active"><?php echo $actual_data2[34][0] ?></td>
                 <td class="active"><?php echo $actual_data2[34][1] ?></td>
                 <td class="active"><?php echo $actual_data2[34][2] ?></td>
             </tr>
             <tr>        
                 <td class="active">7(U19-U21)</td>
                 <td class="active"><?php echo $actual_data2[6][2] ?></td>
                 <td class="active"><?php echo $actual_data2[6][1] ?></td>
                 <td class="active"><?php echo $actual_data2[6][0] ?></td>
                 <td>&nbsp;</td>
                 <td class="active">34(U100-U102)</td>
                 <td class="active"><?php echo $actual_data2[33][2] ?></td>
                 <td class="active"><?php echo $actual_data2[33][1] ?></td>
                 <td class="active"><?php echo $actual_data2[33][0] ?></td>
             </tr>
             <tr>        
                 <td class="active">8(U22-U24)</td>
                 <td class="active"><?php echo $actual_data2[7][0] ?></td>
                 <td class="active"><?php echo $actual_data2[7][1] ?></td>
                 <td class="active"><?php echo $actual_data2[7][2] ?></td>
                 <td>&nbsp;</td>
                 <td class="active">33(U97-U99)</td>
                 <td class="active"><?php echo $actual_data2[32][0] ?></td>
                 <td class="active"><?php echo $actual_data2[32][1] ?></td>
                 <td class="active"><?php echo $actual_data2[32][2] ?></td>
             </tr>
             <tr>        
                 <td class="active">9(U25-U27)</td>
                 <td class="active"><?php echo $actual_data2[8][2] ?></td>
                 <td class="active"><?php echo $actual_data2[8][1] ?></td>
                 <td class="active"><?php echo $actual_data2[8][0] ?></td>
                 <td>&nbsp;</td>
                 <td class="active">32(U94-U96)</td>
                 <td class="active"><?php echo $actual_data2[31][2] ?></td>
                 <td class="active"><?php echo $actual_data2[31][1] ?></td>
                 <td class="active"><?php echo $actual_data2[31][0] ?></td>
             </tr>
             <tr>        
                 <td class="active">10(U28-U30)</td>
                 <td class="active"><?php echo $actual_data2[9][0] ?></td>
                 <td class="active"><?php echo $actual_data2[9][1] ?></td>
                 <td class="active"><?php echo $actual_data2[9][2] ?></td>
                 <td>&nbsp;</td>
                 <td class="active">31(U91-U93)</td>
                 <td class="active"><?php echo $actual_data2[30][0] ?></td>
                 <td class="active"><?php echo $actual_data2[30][1] ?></td>
                 <td class="active"><?php echo $actual_data2[30][2] ?></td>
             </tr>
             <tr>        
                 <td class="active">11(U31-U33)</td>
                 <td class="active"><?php echo $actual_data2[10][2] ?></td>
                 <td class="active"><?php echo $actual_data2[10][1] ?></td>
                 <td class="active"><?php echo $actual_data2[10][0] ?></td>
                 <td>&nbsp;</td>
                 <td class="active">30(U88-U90)</td>
                 <td class="active"><?php echo $actual_data2[29][2] ?></td>
                 <td class="active"><?php echo $actual_data2[29][1] ?></td>
                 <td class="active"><?php echo $actual_data2[29][0] ?></td>
             </tr>
             <tr>        
                 <td class="active">12(U34-U36)</td>
                 <td class="active"><?php echo $actual_data2[11][0] ?></td>
                 <td class="active"><?php echo $actual_data2[11][1] ?></td>
                 <td class="active"><?php echo $actual_data2[11][2] ?></td>
                 <td>&nbsp;</td>
                 <td class="active">29(U85-U87)</td>
                 <td class="active"><?php echo $actual_data2[28][0] ?></td>
                 <td class="active"><?php echo $actual_data2[28][1] ?></td>
                 <td class="active"><?php echo $actual_data2[28][2] ?></td>
             </tr>
             <tr>        
                 <td class="active">13(U37-U39)</td>
                 <td class="active"><?php echo $actual_data2[12][2] ?></td>
                 <td class="active"><?php echo $actual_data2[12][1] ?></td>
                 <td class="active"><?php echo $actual_data2[12][0] ?></td>
                 <td>&nbsp;</td>
                 <td class="active">28(U82-U84)</td>
                 <td class="active"><?php echo $actual_data2[27][2] ?></td>
                 <td class="active"><?php echo $actual_data2[27][1] ?></td>
                 <td class="active"><?php echo $actual_data2[27][0] ?></td>
             </tr>
             <tr>        
                 <td class="active">14(U40-U42)</td>
                 <td class="active"><?php echo $actual_data2[22][0] ?></td>
                 <td class="active"><?php echo $actual_data2[22][1] ?></td>
                 <td class="active"><?php echo $actual_data2[22][2] ?></td>
                 <td>&nbsp;</td>
                 <td class="active">27(U79-U81)</td>
                 <td class="active"><?php echo $actual_data2[26][0] ?></td>
                 <td class="active"><?php echo $actual_data2[26][1] ?></td>
                 <td class="active"><?php echo $actual_data2[26][2] ?></td>
             </tr>
             <tr>        
                 <td class="active">15(U43-U45)</td>
                 <td class="active"><?php echo $actual_data2[14][2] ?></td>
                 <td class="active"><?php echo $actual_data2[14][1] ?></td>
                 <td class="active"><?php echo $actual_data2[14][0] ?></td>
                 <td>&nbsp;</td>
                 <td class="active">26(U76-U78)</td>
                 <td class="active"><?php echo $actual_data2[25][2] ?></td>
                 <td class="active"><?php echo $actual_data2[25][1] ?></td>
                 <td class="active"><?php echo $actual_data2[25][0] ?></td>
             </tr>
             <tr>        
                 <td class="active">16(U46-U48)</td>
                 <td class="active"><?php echo $actual_data2[15][0] ?></td>
                 <td class="active"><?php echo $actual_data2[15][1] ?></td>
                 <td class="active"><?php echo $actual_data2[15][2] ?></td>
                 <td>&nbsp;</td>
                 <td class="active">25(U73-U75)</td>
                 <td class="active"><?php echo $actual_data2[24][0] ?></td>
                 <td class="active"><?php echo $actual_data2[24][1] ?></td>
                 <td class="active"><?php echo $actual_data2[24][2] ?></td>
             </tr>
             <tr>        
                 <td class="active">17(U49-U51)</td>
                 <td class="active"><?php echo $actual_data2[16][2] ?></td>
                 <td class="active"><?php echo $actual_data2[16][1] ?></td>
                 <td class="active"><?php echo $actual_data2[16][0] ?></td>
                 <td>&nbsp;</td>
                 <td class="active">24(U70-U72)</td>
                 <td class="active"><?php echo $actual_data2[23][2] ?></td>
                 <td class="active"><?php echo $actual_data2[23][1] ?></td>
                 <td class="active"><?php echo $actual_data2[23][0] ?></td>
             </tr>
             <tr>        
                 <td class="active">18(U52-U54)</td>
                 <td class="active"><?php echo $actual_data2[17][0] ?></td>
                 <td class="active"><?php echo $actual_data2[17][1] ?></td>
                 <td class="active"><?php echo $actual_data2[17][2] ?></td>
                 <td>&nbsp;</td>
                 <td class="active">23(U67-U69)</td>
                 <td class="active"><?php echo $actual_data2[22][0] ?></td>
                 <td class="active"><?php echo $actual_data2[22][1] ?></td>
                 <td class="active"><?php echo $actual_data2[22][2] ?></td>
             </tr>
             <tr>        
                 <td class="active">19(U55-U557)</td>
                 <td class="active"><?php echo $actual_data2[18][0] ?></td>
                 <td class="active"><?php echo $actual_data2[18][1] ?></td>
                 <td class="active"><?php echo $actual_data2[18][2] ?></td>
                 <td>&nbsp;</td>
                 <td class="active">22(U64-U66)</td>
                 <td class="active"><?php echo $actual_data2[21][0] ?></td>
                 <td class="active"><?php echo $actual_data2[21][1] ?></td>
                 <td class="active"><?php echo $actual_data2[21][2] ?></td>
             </tr>
             <tr>        
                 <td class="active">20(U58-U60)</td>
                 <td class="active"><?php echo $actual_data2[19][0] ?></td>
                 <td class="active"><?php echo $actual_data2[19][1] ?></td>
                 <td class="active"><?php echo $actual_data2[19][2] ?></td>
                 <td>&nbsp;</td>
                 <td class="active">21(U61-U62)</td>
                 <td class="active"><?php echo $actual_data2[20][0] ?></td>
                 <td class="active"><?php echo $actual_data2[20][1] ?></td>
                 <td class="active"><?php echo $actual_data2[20][2] ?></td>
             </tr>
     </table>
     </div>
     <div id="chain3" class="chain-data invisible">
        <h4>板平均温度: <?php echo explode(' ', $last3)[20] ?>℃</h4>
        <h4>芯片平均温度： <?php echo explode (' ', $last3)[18] ?>℃</h4>
        <table class="table table-bordered table-hover table-responsive">
             <tr>
             <th class="active">芯片级数</th>
                 <th class="active"> 电压|温度|yield值|硬件错误数</th>
                 <th class="active"> 电压|温度|yield值|硬件错误数</th>
                 <th class="active"> 电压|温度|yield值|硬件错误数</th>
                 <th></th>
                 <th class="active">芯片级数</th>
                 <th class="active"> 电压|温度|yield值|硬件错误数</th>
                 <th class="active"> 电压|温度|yield值|硬件错误数</th>
                 <th class="active"> 电压|温度|yield值|硬件错误数</th>
             </tr>
 
             <tr>
                 <td class="active">1(U1-U3)</td>
                 <td class="active"><?php echo $actual_data3[0][2] ?></td>
                 <td class="active"><?php echo $actual_data3[0][1] ?></td>
                 <td class="active"><?php echo $actual_data3[0][0] ?></td>
                 <td>&nbsp;</td>
                 <td class="active">40(U118-U120)</td>
                 <td class="active"><?php echo $actual_data3[39][2] ?></td>
                 <td class="active"><?php echo $actual_data3[39][1] ?></td>
                 <td class="active"><?php echo $actual_data3[39][0] ?></td>
             </tr>
             <tr>
                 <td class="active">2(U4-U6)</td>
                 <td class="active"><?php echo $actual_data3[1][0] ?></td>
                 <td class="active"><?php echo $actual_data3[1][1] ?></td>
                 <td class="active"><?php echo $actual_data3[1][2] ?></td>
                 <td>&nbsp;</td>
                 <td class="active">39(U115-U117)</td>
                 <td class="active"><?php echo $actual_data3[38][0] ?></td>
                 <td class="active"><?php echo $actual_data3[38][1] ?></td>
                 <td class="active"><?php echo $actual_data3[38][2] ?></td>
             </tr>
             <tr>
                 <td class="active">3(U7-U9)</td>
                 <td class="active"><?php echo $actual_data3[2][2] ?></td>
                 <td class="active"><?php echo $actual_data3[2][1] ?></td>
                 <td class="active"><?php echo $actual_data3[2][0] ?></td>
                 <td>&nbsp;</td>
                 <td class="active">38(U112-U114)</td>
                 <td class="active"><?php echo $actual_data3[37][2] ?></td>
                 <td class="active"><?php echo $actual_data3[37][1] ?></td>
                 <td class="active"><?php echo $actual_data3[37][0] ?></td>
             </tr>
             <tr>        
                 <td class="active">4(U10-U12)</td>
                 <td class="active"><?php echo $actual_data3[3][0] ?></td>
                 <td class="active"><?php echo $actual_data3[3][1] ?></td>
                 <td class="active"><?php echo $actual_data3[3][2] ?></td>
                 <td>&nbsp;</td>
                 <td class="active">37(U109-U111)</td>
                 <td class="active"><?php echo $actual_data3[36][0] ?></td>
                 <td class="active"><?php echo $actual_data3[36][1] ?></td>
                 <td class="active"><?php echo $actual_data3[36][2] ?></td>
             </tr>
             <tr>        
                 <td class="active">5(U13-U15)</td>
                 <td class="active"><?php echo $actual_data3[4][2] ?></td>
                 <td class="active"><?php echo $actual_data3[4][1] ?></td>
                 <td class="active"><?php echo $actual_data3[4][0] ?></td>
                 <td>&nbsp;</td>
                 <td class="active">36(U106-U108)</td>
                 <td class="active"><?php echo $actual_data3[35][2] ?></td>
                 <td class="active"><?php echo $actual_data3[35][1] ?></td>
                 <td class="active"><?php echo $actual_data3[35][0] ?></td>
             </tr>
             <tr>        
                 <td class="active">6(U16-U18)</td>
                 <td class="active"><?php echo $actual_data3[5][0] ?></td>
                 <td class="active"><?php echo $actual_data3[5][1] ?></td>
                 <td class="active"><?php echo $actual_data3[5][2] ?></td>
                 <td>&nbsp;</td>
                 <td class="active">35(103-U105)</td>
                 <td class="active"><?php echo $actual_data3[34][0] ?></td>
                 <td class="active"><?php echo $actual_data3[34][1] ?></td>
                 <td class="active"><?php echo $actual_data3[34][2] ?></td>
             </tr>
             <tr>        
                 <td class="active">7(U19-U21)</td>
                 <td class="active"><?php echo $actual_data3[6][2] ?></td>
                 <td class="active"><?php echo $actual_data3[6][1] ?></td>
                 <td class="active"><?php echo $actual_data3[6][0] ?></td>
                 <td>&nbsp;</td>
                 <td class="active">34(U100-U102)</td>
                 <td class="active"><?php echo $actual_data3[33][2] ?></td>
                 <td class="active"><?php echo $actual_data3[33][1] ?></td>
                 <td class="active"><?php echo $actual_data3[33][0] ?></td>
             </tr>
             <tr>        
                 <td class="active">8(U22-U24)</td>
                 <td class="active"><?php echo $actual_data3[7][0] ?></td>
                 <td class="active"><?php echo $actual_data3[7][1] ?></td>
                 <td class="active"><?php echo $actual_data3[7][2] ?></td>
                 <td>&nbsp;</td>
                 <td class="active">33(U97-U99)</td>
                 <td class="active"><?php echo $actual_data3[32][0] ?></td>
                 <td class="active"><?php echo $actual_data3[32][1] ?></td>
                 <td class="active"><?php echo $actual_data3[32][2] ?></td>
             </tr>
             <tr>        
                 <td class="active">9(U25-U27)</td>
                 <td class="active"><?php echo $actual_data3[8][2] ?></td>
                 <td class="active"><?php echo $actual_data3[8][1] ?></td>
                 <td class="active"><?php echo $actual_data3[8][0] ?></td>
                 <td>&nbsp;</td>
                 <td class="active">32(U94-U96)</td>
                 <td class="active"><?php echo $actual_data3[31][2] ?></td>
                 <td class="active"><?php echo $actual_data3[31][1] ?></td>
                 <td class="active"><?php echo $actual_data3[31][0] ?></td>
             </tr>
             <tr>        
                 <td class="active">10(U28-U30)</td>
                 <td class="active"><?php echo $actual_data3[9][0] ?></td>
                 <td class="active"><?php echo $actual_data3[9][1] ?></td>
                 <td class="active"><?php echo $actual_data3[9][2] ?></td>
                 <td>&nbsp;</td>
                 <td class="active">31(U91-U93)</td>
                 <td class="active"><?php echo $actual_data3[30][0] ?></td>
                 <td class="active"><?php echo $actual_data3[30][1] ?></td>
                 <td class="active"><?php echo $actual_data3[30][2] ?></td>
             </tr>
             <tr>        
                 <td class="active">11(U31-U33)</td>
                 <td class="active"><?php echo $actual_data3[10][2] ?></td>
                 <td class="active"><?php echo $actual_data3[10][1] ?></td>
                 <td class="active"><?php echo $actual_data3[10][0] ?></td>
                 <td>&nbsp;</td>
                 <td class="active">30(U88-U90)</td>
                 <td class="active"><?php echo $actual_data3[29][2] ?></td>
                 <td class="active"><?php echo $actual_data3[29][1] ?></td>
                 <td class="active"><?php echo $actual_data3[29][0] ?></td>
             </tr>
             <tr>        
                 <td class="active">12(U34-U36)</td>
                 <td class="active"><?php echo $actual_data3[11][0] ?></td>
                 <td class="active"><?php echo $actual_data3[11][1] ?></td>
                 <td class="active"><?php echo $actual_data3[11][2] ?></td>
                 <td>&nbsp;</td>
                 <td class="active">29(U85-U87)</td>
                 <td class="active"><?php echo $actual_data3[28][0] ?></td>
                 <td class="active"><?php echo $actual_data3[28][1] ?></td>
                 <td class="active"><?php echo $actual_data3[28][2] ?></td>
             </tr>
             <tr>        
                 <td class="active">13(U37-U39)</td>
                 <td class="active"><?php echo $actual_data3[12][2] ?></td>
                 <td class="active"><?php echo $actual_data3[12][1] ?></td>
                 <td class="active"><?php echo $actual_data3[12][0] ?></td>
                 <td>&nbsp;</td>
                 <td class="active">28(U82-U84)</td>
                 <td class="active"><?php echo $actual_data3[27][2] ?></td>
                 <td class="active"><?php echo $actual_data3[27][1] ?></td>
                 <td class="active"><?php echo $actual_data3[27][0] ?></td>
             </tr>
             <tr>        
                 <td class="active">14(U40-U42)</td>
                 <td class="active"><?php echo $actual_data3[22][0] ?></td>
                 <td class="active"><?php echo $actual_data3[22][1] ?></td>
                 <td class="active"><?php echo $actual_data3[22][2] ?></td>
                 <td>&nbsp;</td>
                 <td class="active">27(U79-U81)</td>
                 <td class="active"><?php echo $actual_data3[26][0] ?></td>
                 <td class="active"><?php echo $actual_data3[26][1] ?></td>
                 <td class="active"><?php echo $actual_data3[26][2] ?></td>
             </tr>
             <tr>        
                 <td class="active">15(U43-U45)</td>
                 <td class="active"><?php echo $actual_data3[14][2] ?></td>
                 <td class="active"><?php echo $actual_data3[14][1] ?></td>
                 <td class="active"><?php echo $actual_data3[14][0] ?></td>
                 <td>&nbsp;</td>
                 <td class="active">26(U76-U78)</td>
                 <td class="active"><?php echo $actual_data3[25][2] ?></td>
                 <td class="active"><?php echo $actual_data3[25][1] ?></td>
                 <td class="active"><?php echo $actual_data3[25][0] ?></td>
             </tr>
             <tr>        
                 <td class="active">16(U46-U48)</td>
                 <td class="active"><?php echo $actual_data3[15][0] ?></td>
                 <td class="active"><?php echo $actual_data3[15][1] ?></td>
                 <td class="active"><?php echo $actual_data3[15][2] ?></td>
                 <td>&nbsp;</td>
                 <td class="active">25(U73-U75)</td>
                 <td class="active"><?php echo $actual_data3[24][0] ?></td>
                 <td class="active"><?php echo $actual_data3[24][1] ?></td>
                 <td class="active"><?php echo $actual_data3[24][2] ?></td>
             </tr>
             <tr>        
                 <td class="active">17(U49-U51)</td>
                 <td class="active"><?php echo $actual_data3[16][2] ?></td>
                 <td class="active"><?php echo $actual_data3[16][1] ?></td>
                 <td class="active"><?php echo $actual_data3[16][0] ?></td>
                 <td>&nbsp;</td>
                 <td class="active">24(U70-U72)</td>
                 <td class="active"><?php echo $actual_data3[23][2] ?></td>
                 <td class="active"><?php echo $actual_data3[23][1] ?></td>
                 <td class="active"><?php echo $actual_data3[23][0] ?></td>
             </tr>
             <tr>        
                 <td class="active">18(U52-U54)</td>
                 <td class="active"><?php echo $actual_data3[17][0] ?></td>
                 <td class="active"><?php echo $actual_data3[17][1] ?></td>
                 <td class="active"><?php echo $actual_data3[17][2] ?></td>
                 <td>&nbsp;</td>
                 <td class="active">23(U67-U69)</td>
                 <td class="active"><?php echo $actual_data3[22][0] ?></td>
                 <td class="active"><?php echo $actual_data3[22][1] ?></td>
                 <td class="active"><?php echo $actual_data3[22][2] ?></td>
             </tr>
             <tr>        
                 <td class="active">19(U55-U557)</td>
                 <td class="active"><?php echo $actual_data3[18][0] ?></td>
                 <td class="active"><?php echo $actual_data3[18][1] ?></td>
                 <td class="active"><?php echo $actual_data3[18][2] ?></td>
                 <td>&nbsp;</td>
                 <td class="active">22(U64-U66)</td>
                 <td class="active"><?php echo $actual_data3[21][0] ?></td>
                 <td class="active"><?php echo $actual_data3[21][1] ?></td>
                 <td class="active"><?php echo $actual_data3[21][2] ?></td>
             </tr>
             <tr>        
                 <td class="active">20(U58-U60)</td>
                 <td class="active"><?php echo $actual_data3[19][0] ?></td>
                 <td class="active"><?php echo $actual_data3[19][1] ?></td>
                 <td class="active"><?php echo $actual_data3[19][2] ?></td>
                 <td>&nbsp;</td>
                 <td class="active">21(U61-U62)</td>
                 <td class="active"><?php echo $actual_data3[20][0] ?></td>
                 <td class="active"><?php echo $actual_data3[20][1] ?></td>
                 <td class="active"><?php echo $actual_data3[20][2] ?></td>
             </tr>
     </table>
     </div>
     <?php endif;?>
    <script type="text/javascript">
        $(document).ready(function() {
        $("#btn-navigator").find('button').click(function() {
            if(!$(this).hasClass('active')) {
                $(this).siblings('.btn').removeClass('btn-primary active');
                $(this).removeClass('btn-default').addClass('btn-primary active');
                $ref_data = $(this).data('ref');
                $('div.chain-data').removeClass("visible").addClass('invisible');
                $('#'+$ref_data).removeClass('invisible').addClass('visible');
            }
        });
    });
    </script>

  </body>
</html>