<?php
if (!file_exists('../videos/configuration.php')) {
    if (!file_exists('../install/index.php')) {
        die("No Configuration and no Installation");
    }
    header("Location: install/index.php");
}

require_once '../videos/configuration.php';
require_once $global['systemRootPath'] . 'objects/video.php';
require_once $global['systemRootPath'] . 'objects/category.php';

$category = Category::getAllCategories();
?>
<!DOCTYPE html>
<html>
    <head>
        <script>
            var webSiteRootURL = '<?php echo $global['webSiteRootURL']; ?>';
        </script>

        <link href="<?php echo $global['webSiteRootURL']; ?>js/webui-popover/jquery.webui-popover.min.css" rel="stylesheet" type="text/css"/>
        <link href="<?php echo $global['webSiteRootURL']; ?>plugin/YouPHPFlix/view/js/flickty/flickity.min.css" rel="stylesheet" type="text/css"/>
        <?php
        include $global['systemRootPath'] . 'view/include/head.php';
        ?>
        
        <title>YouPHPFlix</title>
    </head>
    <body>
        <?php
        include $global['systemRootPath'].'view/include/navbar.php';
        ?>
        <div class="container-fluid">   

            <?php
            foreach ($category as $cat) {
                $_GET['catName'] = $cat['clean_name'];
                //$_POST['rowCount'] = 18;
                //$_POST['current'] = 1;
                $videos = Video::getAllVideos();
                if(empty($videos)){
                    continue;
                }
                ?>
                <div class="row">
                    <h3>
                        <i class="<?php echo $cat['iconClass']; ?>"></i> <?php echo $cat['name']; ?> <span class="badge"><?php echo count($videos); ?></span>
                    </h3>
                    <div class="carousel">
                        <?php
                        foreach ($videos as $value) {
                            $images = Video::getImageFromFilename($value['filename'], $value['type']);

                            $imgGif = $images->thumbsGif;
                            $img = $images->thumbsJpg;
                            $poster = $images->poster;
                            ?>
                            <div class="carousel-cell tile " >
                                <div class="slide thumbsImage" videos_id="<?php echo $value['id']; ?>" poster="<?php echo $poster; ?>" cat="<?php echo $cat['clean_name']; ?>" video="<?php echo $value['clean_title']; ?>" iframe="<?php echo $global['webSiteRootURL']; ?>videoEmbeded/<?php echo $value['clean_title']; ?>">
                                    <div class="tile__media ">
                                        <img alt="<?php echo $value['title']; ?>" class="tile__img thumbsJPG ing img-responsive carousel-cell-image"  data-flickity-lazyload="<?php echo $img; ?>" />
                                        <?php
                                        if (!empty($imgGif)) {
                                            ?>
                                            <img style="position: absolute; top: 0; display: none;" alt="<?php echo $value['title']; ?>" id="tile__img thumbsGIF<?php echo $value['id']; ?>" class="thumbsGIF img-responsive img carousel-cell-image"  data-flickity-lazyload="<?php echo $imgGif; ?>"/>
                                        <?php } ?>
                                    </div>
                                    <div class="tile__details">
                                        <div class="tile__title">
                                            <?php echo $value['title']; ?>
                                        </div>
                                    </div>
                                    <div class="videoDescription">
                                        <?php echo $value['description']; ?>
                                    </div>
                                </div>
                                <div class="arrow-down" style="display: none;"></div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    <div class="poster list-group-item" style="display: none;">
                        <div class="posterDetails ">
                            <h2 class="infoTitle">                        
                                Title
                            </h2>
                            <div class="infoText">                        
                                Text
                            </div>
                            <div class="footerBtn" style="display: none;">                             
                                <button class="btn btn-danger playBtn"><i class="fa fa-play"></i> Play</button>
                                <button class="btn btn-primary myList"><i class="fa fa-plus"></i> My List</button>
                            </div>

                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
        
        <div class="webui-popover-content" id="popover">
            <?php
            if (User::isLogged()) {
                ?>
                <form role="form">
                    <div class="form-group">
                        <input class="form-control" id="searchinput" type="search" placeholder="Search..." />
                    </div>
                    <div id="searchlist" class="list-group">

                    </div>
                </form>
                <div >
                    <hr>
                    <div class="form-group">
                        <input id="playListName" class="form-control" placeholder="<?php echo __("Create a New Play List"); ?>"  >
                    </div>
                    <div class="form-group">
                        <?php echo __("Make it public"); ?>
                        <div class="material-switch pull-right">
                            <input id="publicPlayList" name="publicPlayList" type="checkbox" checked="checked"/>
                            <label for="publicPlayList" class="label-success"></label>
                        </div>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-success btn-block" id="addPlayList" ><?php echo __("Create a New Play List"); ?></button>
                    </div>
                </div>
                <?php
            } else {
                ?>
                <h5>Want to watch this again later?</h5>

                Sign in to add this video to a playlist.

                <a href="<?php echo $global['webSiteRootURL']; ?>user" class="btn btn-primary">
                    <span class="glyphicon glyphicon-log-in"></span>
                    <?php echo __("Login"); ?>
                </a>
                <?php
            }
            ?>
        </div>        
        <?php
        include 'include/footer.php';
        ?>
        
        <script src="<?php echo $global['webSiteRootURL']; ?>js/bootstrap-list-filter/bootstrap-list-filter.min.js" type="text/javascript"></script>
        
        <script src="<?php echo $global['webSiteRootURL']; ?>plugin/YouPHPFlix/view/js/flickty/flickity.pkgd.min.js" type="text/javascript"></script>
        <script src="<?php echo $global['webSiteRootURL']; ?>js/webui-popover/jquery.webui-popover.min.js" type="text/javascript"></script>
        <script src="<?php echo $global['webSiteRootURL']; ?>plugin/YouPHPFlix/view/js/script.js" type="text/javascript"></script>
        <script>
            $(function () {



            });
        </script>
    </body>
</html>