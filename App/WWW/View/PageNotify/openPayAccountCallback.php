<script type="text/javascript" src="<?=$apiJsUrl?>"></script>
<script>
    setTimeout(function()
    {
        api.execScript(
            {
                name:api.winName,
                script:"paySuccess('<?php echo 2?>')"
            });
    },200);
</script>