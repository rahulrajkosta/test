<?php
    use \koolreport\widgets\google\ScatterChart;

    $height_weight_smokers = array(
        array("height","weight","smokers"),
        array(170,82,123),
        array(180,91,45),
        array(150,60,14),
        array(152,55,55),
        array(168,65,223),
        array(178,67,55),
        array(185,46,223),
        array(166,77,55),
        array(153,50,77),
        array(166,44,155),
    )
?>
<div class="report-content">
    <div class="text-center">
        <h1>ScatterChart</h1>
        <p class="lead">
            This example shows how to draw beautiful ScatterChart
        </p>
    </div>
    <div style="margin-bottom:50px;">
    <?php
    ScatterChart::create(array(
        "title"=>"Height vs Weight, Smokers",
        "dataSource"=>$height_weight_smokers,
    ));
    ?>
    </div>
</div>
