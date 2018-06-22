<script type="text/javascript" src="<?=$apiJsUrl?>"></script>
<script>
    setTimeout(function()
    {
        api.execScript(
            {
                name:api.winName,
                script:"paySuccess('<?php echo 0?>')"
            });
    },200);
</script>