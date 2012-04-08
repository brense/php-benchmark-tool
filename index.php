<?php
$time = array();
$mem = array();
include('Benchmark.php');
$benchmark = new Benchmark();
if(!empty($_POST)){
	ob_start();
	foreach($_POST['code'] as $code){
		for($n=0;$n<$_POST['iterations'];$n++){
			$benchmark->start();
			eval($code);
			$benchmark->finish();
		}
		$time[] = $benchmark->get_time();
		$mem[] = $benchmark->get_memory();
		$benchmark->flush();
	}
	ob_end_clean();
} ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>PHP Benchmark</title>
<style type="text/css">
* { margin:0; padding:0; }
body { font:90% Arial, Helvetica, sans-serif; padding:2em; }
form textarea, form input { font-family:"Courier New", Courier, monospace; }
.block { float:left; }
.block textarea { width:90%; clear:both; }
.block label { clear:both; }
p { margin:0 0 1em 0; }
.block > p { margin:0; }
.block:first-child { clear:left; }
form { float:left; clear:both; margin:1em 0; width:100%; }
.clear { clear:both; margin:1em 0; }
</style>
</head>
<body>

<?php if(!empty($time) && !empty($mem)){
	echo '<h2>Results</h2><p>Note that the eval function is used to execute the codeblocks, the eval function itself is pretty slow and also uses a lot of memory.</p>';
	$count = 100 / count($time);
	foreach($time as $i => $val){
		echo '<div class="block" style="width:' . $count . '%;"><h3>codeblock ' . ($i+1) . '</h3><p>execution time: ' . $val . '</p><p>memory usage: ' . $mem[$i] . '</p></div>';
	}
}
if(empty($_GET['blocks'])){$_GET['blocks'] = 2;}
?>

<form action="?blocks=<?php echo $_GET['blocks']; ?>" method="post">
<div class="clear"><label>Iterations:</label><input type="text" name="iterations" value="<?php if(!empty($_POST['iterations'])){echo $_POST['iterations'];} else {echo '1000';} ?>" /></div>
<?php
for($n=0;$n<$_GET['blocks'];$n++){
	echo '<div class="block" style="width:' . (100/$_GET['blocks']) . '%;"><label>Codeblock ' . ($n+1) . ':</label><textarea name="code[' . $n . ']" cols="30" rows="10">'; if(!empty($_POST['code'][$n])){echo $_POST['code'][$n];} echo '</textarea></div>';
}
?>
<input type="submit" value="benchmark" />
</form>

</body>
</html>