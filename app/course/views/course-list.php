<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Courses</li>
    </ol>
</nav>
<div class="row" style="max-width: 1500px ! important ;">
    <div class="col-8">
        <h2 class="mt-3">
            Courses List
        </h2>
    </div>
    <div class="col-4 mt-3">
        <a class="btn btn-secondary float-right" href="index.php?section=course&action=form&school_id=<?= $_GET["school_id"]; ?>">
            <i class="fa fa-plus"></i>&nbsp;Add</a>
    </div>  
</div>

<hr class="divider"></hr>

<div style="max-width: 1500px ! important ;">

    <input name="actual_page" id="actual-page" type="hidden" value="<?= $actual_page ?>">

    <div class="card mb-3">

        <div id="list-area" class=" col-md-12"></div>

    </div>
</div>
</div>

<script>

    function load_list(page)
    {
        console.log(page);
        $.ajax({
            type: 'GET',
            url: 'router.php',
            data: '&section=course&action=listing_data&page='+page,
            //dataType: "json",
            beforeSend: function () {
                $('#list-area').html('<div class="text-center" style="min-height: 100px; margin: auto;"><div class="spinner-border  text-primary" style="width: 4rem; height: 4rem;" role="status"><span class="sr-only">Loading...</span></div></div>');
            },
            success: function (datar) {
                $('#list-area').html(datar);
            },
            error: function (errorThrown, status, error) {
                console.log(status + error + errorThrown.responseText);
            }
        })
    }
    load_list();
</script>
