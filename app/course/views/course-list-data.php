<div class="table-responsive table-body">
    <table class="table table-hover small mt-3">
        <thead class="thead-light">
            <tr class="bg-info">
                <th scope="col" width="35" height="34" align="center" valign="middle">Id</th>
                <th scope="col" align="center" valign="middle" bgcolor="#DAE0FC" class="texto_form">Nombre</th>
                <th scope="col" width="220" align="center" bgcolor="#DAE0FC"></th>
            </tr>
        </thead>
        <?php
        foreach ($list as $course) {
            ?>
            <tr>
                <td height="40" align="center" valign="middle"><?= $course->id; ?></td>
                <td valign="top"><?= $course->title; ?></td>
                <td align="center"> 
                    <div class="btn-group btn-group-sm">
                        <button type="button" 
                                class="btn btn-light dropdown-toggle dropdown-toggle-split"  
                                data-toggle="dropdown">
                            Opciones
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" 
                               href="index.php?section=course&action=form&course_id=<?= $course->id ?>">
                                <i class="fa fa-edit mr-1"></i>Update</a>
                            <a class="dropdown-item text-danger" href="index.php?section=course&action=delete&course_id=<?= $course->id ?>">
                                <i class="fa fa-trash-alt mr-2"></i>Delete</a>

                        </div>
                    </div>
                </td>
            </tr>
        <?php }
        ?>
    </table>
</div>
<div>
    <?= $_SESSION["pagination_nav"] ?>
</div>