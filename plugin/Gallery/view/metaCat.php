 <?php function metaCatGen ($name, $sortId) { ?>   
                        <div class="clear clearfix">
						<h3 class="galleryTitle">
							<i class="glyphicon glyphicon-list-alt"></i>
                            <?php if (empty($_GET["sortByNameOrder"])) {
                                $_GET["sortByNameOrder"] = "ASC";
                            }
                        
                            if ($obj->sortReverseable) {
                                $info = createOrderInfo("sortByNameOrder", "zyx", "abc", $orderString);
                                echo __("Sort by name (" . $info[2] . ")") . " (Page " . $_GET['page'] . ") <a href='" . $info[0] . "' >" . $info[1] . "</a>";
                            } else {
                                echo __("Sort by name (abc)");
                            }
                            ?>
                        </h3>
						<div class="row"><?php
                        $countCols = 0;
                        unset($_POST['sort']);
                        $_POST['sort']['title'] = $_GET['sortByNameOrder'];
                        $_POST['current'] = $_GET['page'];
                        $_POST['rowCount'] = $obj->SortByNameRowCount;
                        $videos = Video::getAllVideos();
                        foreach ($videos as $value) {
                            $img_portrait = ($value['rotation'] === "90" || $value['rotation'] === "270") ? "img-portrait" : "";
                            $name = User::getNameIdentificationById($value['users_id']);
                            // make a row each 6 cols
                            if ($countCols % 6 === 0) {
                                echo '</div><div class="row aligned-row ">';
                            }
                            
                            $countCols ++;
                            ?>
                                <div class="col-lg-2 col-md-4 col-sm-4 col-xs-6 galleryVideo thumbsImage fixPadding">
								<a href="<?php echo $global['webSiteRootURL']; ?>cat/<?php echo $value['clean_category']; ?>/video/<?php echo $value['clean_title']; ?>"title="<?php echo $value['title']; ?>">
                                <?php
                                    $images = Video::getImageFromFilename($value['filename'], $value['type']);
                                    $imgGif = $images->thumbsGif;
                                    $poster = $images->thumbsJpg;
                                ?>
                                    <div class="aspectRatio16_9">
										<img src="<?php echo $images->thumbsJpgSmall; ?>" data-src="<?php echo $poster; ?>" alt="<?php echo $value['title']; ?>" class="thumbsJPG img img-responsive <?php echo $img_portrait; ?>  rotate<?php echo $value['rotation']; ?>" id="thumbsJPG<?php echo $value['id']; ?>" />
                                <?php if (! empty($imgGif)) { ?>
                                            <img src="<?php echo $global['webSiteRootURL']; ?>img/loading-gif.png" data-src="<?php echo $imgGif; ?>" style="position: absolute; top: 0; display: none;" alt="<?php echo $value['title']; ?>" id="thumbsGIF<?php echo $value['id']; ?>" class="thumbsGIF img-responsive <?php echo $img_portrait; ?>  rotate<?php echo $value['rotation']; ?>" height="130" />
                                <?php } ?>
                                    </div>
                                    <span class="duration"><?php echo Video::getCleanDuration($value['duration']); ?></span>
								</a> 
                                <a class="h6" href="<?php echo $global['webSiteRootURL']; ?>video/<?php echo $value['clean_title']; ?>" title="<?php echo $value['title']; ?>">
									<h2><?php echo $value['title']; ?></h2>
								</a>

								<div class="text-muted galeryDetails">
									<div>
                                    <?php
                                    $value['tags'] = Video::getTags($value['id']);
                                    foreach ($value['tags'] as $value2) {
                                        if ($value2->label === __("Group")) {
                                    ?>
                                    <span class="label label-<?php echo $value2->type; ?>"><?php echo $value2->text; ?></span>
                                    <?php
                                        }
                                    } ?>
                                    </div>
									<div>
										<i class="fa fa-eye"></i>
                                        <span itemprop="interactionCount">
                                            <?php echo number_format($value['views_count'], 0); ?> <?php echo __("Views"); ?>
                                        </span>
									</div>
                                <?php if(empty($_GET['catName'])) { ?>
                                    <div>
                                        <a class="label label-default" href="<?php echo $global['webSiteRootURL']; ?>cat/<?php echo $value['clean_category']; ?>/">
                                            <?php echo $value['category']; ?>
                                        </a>
									</div>
                                    <?php } ?>
									<div>
										<i class="fa fa-clock-o"></i>
                                        <?php echo humanTiming(strtotime($value['videoCreation'])), " ", __('ago'); ?>
                                    </div>
									<div>
										<i class="fa fa-user"></i>
                                        <a class="text-muted" href="<?php echo $global['webSiteRootURL']; ?>channel/<?php echo $value['users_id']; ?>/">
                                            <?php echo $name; ?>
                                        </a>
                                        <?php if ((! empty($value['description'])) && ($obj->Description)) { ?>
                                        <button type="button" data-trigger="focus" class="btn btn-xs" style="background-color: inherit; color: inherit;" data-toggle="popover" data-placement="top" data-html="true" title="<?php echo $value['title']; ?>" data-content="<div> <?php echo str_replace('"', '&quot;', nl2br(textToLink($value['description']))); ?> </div>" >Description</button>
                                        <?php } ?>
                                    </div>
                                    <?php if (Video::canEdit($value['id'])) { ?>
                                <div>
									<a href="<?php echo $global['webSiteRootURL']; ?>mvideos?video_id=<?php echo $value['id']; ?>" class="text-primary">
                                        <i class="fa fa-edit"></i> <?php echo __("Edit Video"); ?>
                                    </a>
								</div>
                                    <?php } ?>
                                    
                        <?php if ($config->getAllow_download()) { 
                                $ext = ".mp4";
                                if($value['type']=="audio"){
                                    if(file_exists($global['systemRootPath']."videos/".$value['filename'].".ogg")){
                                        $ext = ".ogg";
                                    } else if(file_exists($global['systemRootPath']."videos/".$value['filename'].".mp3")){
                                        $ext = ".mp3";
                                    }
                                } ?>
                            <div><a class="label label-default " role="button" href="<?php echo $global['webSiteRootURL'] . "videos/" . $value['filename'].$ext; ?>" download="<?php echo $value['title'] . $ext; ?>"><?php echo __("Download"); ?></a></div>
                        <?php } ?>

                                </div>
						</div>
                        <?php } ?>
                    </div>
					<div class="row">
						<ul class="pages">
						</ul>
					</div>
				</div>
<?php } ?>