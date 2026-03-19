<table width="100%">
<?php
$count = 0;
if (isset($dirs)) {
	for ($i=0;$i<sizeof($dirs);$i++) {
		$count++;
		if ($count == 1) echo "<tr>\n";
		echo "<td align='center'><a class='dir' href='?type=" . $type . "&dir=" . $requested_dir . $dirs[$i] . "'><img src='" . $folder_large_image . "' width='" . $dir_width . "' border='0px'></a><br><a href='#' onClick='delete_folder(\"" . $dirs[$i] . "\")'><img border=0 src='" . $delete_image . "'></a> " . $dirs[$i] . "</td>\n";
		if ($count == $pics_per_row || $i == (sizeof($dirs)-1)) {
			$count = 0;
			echo "</tr>\n";
		}
	}
}else {
	echo "</tr>\n";
}

$count = 0;
if (isset($files)) {
	for ($i=0;$i<sizeof($files);$i++) {
		$count++;
		if ($count == 1) echo "<tr>\n";
		echo "<td align='center'><a class='file' href='#' onClick='fileSelected(\"" . $requested_dir . $files[$i] . "\");'><img src='" . $dir . "/" . $files[$i] . "' width='" . $file_width . "' border='0px'></a><br><a href='#' onClick='delete_file(\"" . $files[$i] . "\")'><img border=0 src='" . $delete_image . "'></a> " . $files[$i] . "</td>\n";
		if ($count == $pics_per_row || $i == (sizeof($files)-1)) {
			$count = 0;
			echo "</tr>\n";
		}
	}
}else {
	echo "</tr>\n";
}
?>
</table>