<html>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<head>
<title>Note , V 0.1</title>
<link rel="stylesheet" type="text/css" media="screen" href="./style.css" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
</head>
<body>
<div class=wrapper>
<span>
<?php echo date("M jS, l");?>
</span>
<div>
<form method="POST" action="index.php"> 
    <div><textarea name="msg" rows="4"></textarea></div>
    <div class=btn><input name="Btn" type="submit" value="Submit"></div>
</form>
</div>

<?php
// Set default time zone to tehran.
date_default_timezone_set("Asia/Tehran"); 

$filename = "./posts.txt";

// Add a unique deli. for saving format
$deli = "`";

file_exists($filename) or file_put_contents($filename, time().$deli."--Start--");
$original_posts = file_get_contents($filename);

if (isset($_POST["msg"])) 
{
    // Remove Spaces.
    $msg = trim($_POST["msg"]);
    ($msg=='') and die('Empty message.');
    
    $msg = preg_replace("/\bhttp:\/\/(\w+)+.*\b/",'<a href="$0">$0</a>',$msg);
    
    $now = time();
    
    $fpPosts = fopen('./posts.txt', 'r');
    $fpTemp = fopen('./temp', 'w');
    
    fwrite($fpTemp, $now.$deli.$msg."\r\n");
    while($sr = fgets($fpPosts))
    {
        fputs($fpTemp, $sr);
    }
    
    fclose($fpPosts);
    fclose($fpTemp);
    
    unlink('./posts.txt');
    rename('temp', 'posts.txt');
}

// Open posts file and read line by line, send modified result to output.
$fpPosts = fopen('./posts.txt', 'r');
while(!feof($fpPosts))
{
    $line = fgets($fpPosts, 4096);
    $result = explode($deli, $line);
    
    $post_month = date("M", (int)$result[0]);
    $post_day = date("d", (int)$result[0]);
    $current_month = date("M");
    $current_day = date("d");
    
    if($current_month===$post_month)
    {
        if($current_day===$post_day)
        {
            $time = date("H:i", (int)$result[0]);
        }
        else
        {
            $time = date("M d\r\nH:i\r\nD", (int)$result[0]);
        }
    }
    else
    {
        $time = date("M d\r\nH:i\r\nD", (int)$result[0]);
    }
    
    $posts = "<div class=post><div class=time>$time</div><div class=msg>".$result[1]."</div></div>";
    echo nl2br($posts);
}
fclose($fpPosts);


?>
</div>
</body>
</html>
