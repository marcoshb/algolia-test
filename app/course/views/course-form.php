<?php
$Security = new \Core\Security();
?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php?section=course&action=listing">Courses</a></li>
        <li class="breadcrumb-item">Course details</li>
    </ol>
</nav>
<h2 class="mt-4"> <?= $Course == true ? "Edit" : "Add"; ?> course </h2>
<hr class="divider"></hr>

<div class="row">

    <div class="col-md-9">
        <form method="post" enctype="multipart/form-data" name="FCourse" id="FCourse" data-toggle="validator" onsubmit="save_course(); return false">

            <input type="hidden" name="course_id" id="course_id" value="<?= $Course->id ?>" />

            <div class="card">
                <div class="card-header">
                    <div class="form-group mb-0 pb-0">
                        <input name="title" 
                               id="title" 
                               type="text" 
                               value="<?= $Security->escString($Course->title) ?>" 
                               class="form-control" 
                               placeholder="Title" 
                               required/>
                    </div>
                </div>

                <div class="card-body" >
                    <div class="form-group">
                        <label for="description" class=" text-muted">
                            <b>Description  </b>
                        </label>
                        <textarea 
                            name="description" 
                            id="description" 
                            class="tinymceBox" 
                            cols="80" rows="10"><?= trim($Security->escString($Course->description)) ?></textarea>
                        <div id="CusField"></div>
                    </div>
                    <div class="form-group">
                        <label for="link" class=" text-muted">
                            <b>Link </b>
                        </label>
                        <input 
                            name="link" 
                            id="link" 
                            class="form-control form-control-sm" 
                            value="<?= trim($Security->escString($Course->link)); ?>">
                    </div>

                </div>
            </div>

        </form>

    </div>

    <div class="col-md-3  ">
        <form method="post" enctype="multipart/form-data" name="FCourseOthes" id="FCourseOthes" onsubmit="save_course(); return false">
            <div class="card mb-3">
                <div class="card-header p-2">
                    <i class='far fa-image'></i> Imagen
                </div>
                <div class="card-body">
                    <?php
                    if ($Course->image) {
                        $image_src = $Course->image;
                    } else {
                        $image_src = IMAGES . "/image_placeholder.png";
                    }
                    ?>
                    <img src="<?= $image_src ?>" alt="" class="img-fluid m-2" id="image-main">
                    <div class="form-group">
                        <label for="image">
                            <b>Url </b>
                        </label>
                        <input 
                            name="image" 
                            id="image" 
                            class="form-control form-control-sm" 
                            value="<?= trim($Security->escString($Course->image)); ?>">
                    </div>
                </div>


            </div>
            <div class="card mb-3">
                <div class="card-header p-2">
                    <i class='fa fa-info-circle'></i>&nbsp; Información Adicional

                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="FNCategory"><b>Institute</label>
                        <input type="text" 
                               id="institute" 
                               name="institute" 
                               value="<?= trim($Security->escString($Course->institute)) ?>"
                               class="form-control form-control-sm">

                    </div>

                    <div class="form-group">
                        <label for="FNCategory"><b>Categories</label>
                        <?php
                        $CourseCategory = new Course\CourseCategoryModel();
                        $CourseCategory->course_id = $Course->id;
                        $list_course_category = $CourseCategory->find_all_by_property("course_id");
                        ?>
                        <select name="categories[]" 
                                id="categories" 
                                size="8" 
                                multiple="multiple" 
                                class="form-control form-control-sm">
                                    <?php
                                    $Category = new Course\CategoryModel();
                                    $category_list = $Category->find_all();
                                    foreach ($category_list as $item) :
                                        $key_exist = array_search($item->id, array_column($list_course_category, 'category_id'));
                                        ?>
                                <option  
                                    value="<?= $Security->escString($item->id); ?>" 
                                    <?php if (!is_null($key_exist) && $key_exist !== false) echo "selected"; ?> >
                                    <?= $Security->escString($item->name) ?>
                                </option>
                                <?php
                            endforeach;
                            ?>
                        </select>
                        <span class="small text-danger"> 
                            * <b>Multiple selection:</b> 
                            <br>Hold Ctrl (PC) or Command (Mac) key down and select.
                        </span>
                    </div>

                    <div class="row">
                        <div class="col-md-6 ">
                            <button id="save-post" type="submit" class="btn btn-dark" >Guardar</button>
                        </div>
                    </div>

                </div>
            </div>


        </form>
    </div>
</div>
</div>
<script type="text/javascript" src="<?= LIBRARY ?>/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
            tinymce.init({
                selector: ".tinymceBox",
                toolbar: "insertfile undo redo |  styleselect | bold italic |  blockquote | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | print preview media fullpage | forecolor backcolor emoticons |  |  link image | code ",
                plugins: "link, searchreplace, code, image, paste, lists,preview,wordcount",
                advlist_bullet_styles: "square",
                paste_auto_cleanup_on_paste: true,
                paste_remove_styles: true,
                paste_remove_styles_if_webkit: true,
                paste_strip_class_attributes: true,
                extended_valid_elements: 'script[language|type|src|charset|async]',
                convert_urls: false,
                force_p_newlines: false,
                forced_root_block: 'p',
                width: '99%',
                height: 350
            });

            function save_course()
            {

                if ($('#course_id').val() > 0 || $('#title').val() != "")
                {
                    tinyMCE.triggerSave();
                    $.ajax({
                        type: 'POST',
                        url: 'router.php',
                        data: $('#FCourse,#FCourseOthes').serialize() + '&section=course&action=save',
                        //dataType : "json",

                        beforeSend: function () {
                            $('#save-post').html('<i class="fa fa-spinner fa-spin"></i> Guardando');
                        },
                        success: function (datar) {
                            if ($('#course_id').val() == "")
                            {
                                window.location.replace("index.php?section=course&action=form&course_id=" + datar);
                            }
                             $('#image-main').attr('src',$('#image').val());
                            $('#save-post').html('Guardar');
                            console.log(datar);

                        },
                        error: function (errorThrown, status, error) {
                            alert("Error: No se pudo guardar la información");
                            console.log(status + error + errorThrown.responseText);
                        }
                    })

                }
            }
</script>

