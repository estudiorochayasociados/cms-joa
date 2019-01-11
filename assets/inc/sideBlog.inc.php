<?php
$funcionesSide= new Clases\PublicFunction();
$novedadesSide = new Clases\Novedades();
$novedadesSideData = $novedadesSide->listWithOps('','','6');
$imagenesSide= new Clases\Imagenes();
?>

<div id="sns_left" class="col-md-3 visible-lg visible-md">
    <div class="wrap-in">
        <div class="block block-latestblog-v3" id="sns_latestblog_19454288391442904929">
            <div class="block-title">
                <strong>Últimas novedades</strong>
            </div>
            <div class="block-content">
                <div class="list-blog">
                    <div class="item-post clearfix">
                        <?php
                        foreach ($novedadesSideData as $novSide) {
                            $imagenesSide->set("cod",$novSide['cod']);
                            $imgSide=$imagenesSide->view();
                            $fechaSide = explode("-", $novSide['fecha']);
                            ?>
                            <div class="item-child">
                                <div class="item-img">
                                    <a class="post-img" title="<?= ucfirst($novSide['titulo']); ?>" href="<?php echo URL . '/blog/' . $funcionesSide->normalizar_link($novSide['titulo']) . "/" . $novSide['id'] ?>">
                                        <img alt="" src="<?= URL. '/' . $imgSide['ruta'] ?>">
                                    </a>
                                </div>
                                <div class="item-content">
                                    <div class="post-title">
                                        <a title="<?= ucfirst($novSide['titulo']); ?>" href="<?php echo URL . '/blog/' . $funcionesSide->normalizar_link($novSide['titulo']) . "/" . $novSide['id'] ?>" > <?= substr(ucfirst($novSide['titulo']),0,20) ?>... </a>
                                    </div>
                                    <div class="date">
                                        <i class="fa fa-calendar-o"></i>
                                        <span class="day-month"><?php echo $fechaSide[2] . "/" . $fechaSide[1] . "/" . $fechaSide[0] ?></span>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
