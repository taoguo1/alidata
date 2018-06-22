<form method='post' action="<?=$formUrl?>">
</form>

<script src="<?=$jqueryUrl?>"></script>
<script src="<?=$getSignUrl?>"></script>

<script>
    $(function(){
        var
            formDom = $("form"),
            preFormUrl = "<?=$preFormUrl?>",
            data = {
                'userId' : "<?=$userId?>",
                'appId' : "<?=$appId?>"
            };
        getSign(preFormUrl, data, formDom);
    });
</script>