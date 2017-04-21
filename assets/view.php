 <html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style> 
        html,body{margin: 0; height: 100%;}
        pre{margin:0px; font-family:'Microsoft YaHei'} 
        ul{ padding-left:20px;   }
        a{ text-decoration: none; color: inherit;}  
        .table tr{ background:#F5F5F5; }
        .table tr+tr{background:#fff} 
        .table td{border:1px solid #c0c0c0; vertical-align: top;}
        .table td{padding:8px; white-space:pre} 
        .table { border-collapse: collapse; border-spacing: 0;margin:10px 2px;  } 
     
        .left{ height: 100%; overflow: auto; float: left; width: 250px; line-height: 32px; padding-left:10px;}
        .left li{ white-space: nowrap;}
        .right{height: 100%; overflow: auto; padding: 0 10px;   } 
        .right:after{ content:''; height:500px; display:block;}
    </style>
    <title><?=$title?></title>
</head>
<body >   
    <div class="left" > 
        <?php $tfn=function($tree)use(&$tfn){
        echo "<ul>"; 
        foreach ($tree as $key=>$value) {
            echo "<li>";
            if(is_array($value)){
                echo $key;
                $tfn($value);  
            } 
            else {
                echo "<a href='#$value' title='$key'>$key</a>";
            }
            echo "</li>";
        }
        echo "</ul>";           
        };$tfn($tree);?> 
    </div>
    <div class="right" >
        <?php foreach($apis as $api){ ?>
        <div class="bb">
            <a style="display:block;height:1px;overflow:hidden;" name="<?=join($api['name'],'-')?>">*</a>
            <h2><?=end($api['name'])?></h2>
            <?php if(isset($api['desc'])){?>
            <h3><?=$api['desc']?></h3>
            <?php } ?>
             <?php if(isset($api['url'])){?>
            <fieldset>
                <legend>请求：</legend>
                <pre><?=$api['url']??''?></pre>
            </fieldset> 
            <?php } ?>
            <?php if(isset($api['req'])){ ?>
            <table class="table">
                <tr><td width="120">请求字段</td> <td width="100">参考值</td> <td >说明</td></tr>
                <?php foreach($api['req'] as $key=>$req){?>
                <tr><td width="120"><?=$key?></td><td><?=$req[0]??''?></td><td><?=$req[1]??''?></td></tr>
                <?php } ?>
            </table> 
            <?php } ?>
            <?php foreach($api['success']??[] as $success){?>
            <fieldset>
                <legend>响应成功：<?=$success[0]?></legend>
                <pre><?=$success[1]?></pre>
            </fieldset>
            <?php } ?>
            <?php foreach($api['error']??[] as $error){?>
            <fieldset>
                <legend>响应失败：<?=$error[0]?></legend>
                <pre><?=$error[1]?></pre>
            </fieldset>
            <?php } ?> 
            <?php if(isset($api['res'])){ ?>
            <table class="table">
                <tr><td width="120">响应字段</td> <td width="100">参考值</td> <td >说明</td></tr>
                <?php foreach($api['res']  as $key=>$res){?>
                <tr><td width="120"><?=$key?></td><td><?=$res[0]??''?></td><td><?=$res[1]??''?></td></tr>
                <?php } ?>
            </table> 
            <?php } ?>
        </div>
        <?php } ?> 
    </div>
</body>
</html>